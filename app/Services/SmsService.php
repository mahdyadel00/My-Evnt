<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

/**
 * Service class for sending SMS messages
 */
class SmsService
{
    public function __construct(
        private readonly ?WaapiWhatsAppService $waapiWhatsAppService = null
    ) {
    }

    private function waapi(): WaapiWhatsAppService
    {
        return $this->waapiWhatsAppService ?? new WaapiWhatsAppService();
    }

    /**
     * Send SMS invitation for an event
     *
     * @param Event $event
     * @param string $phoneNumber
     * @return array
     */
    public function sendEventInvitation(Event $event, string $phoneNumber): array
    {
        // Priority 1: Try Twilio WhatsApp if configured
        $twilioSid = config('services.twilio.sid');
        $twilioToken = config('services.twilio.token');
        $twilioWhatsAppFrom = config('services.twilio.whatsapp_from');
        
        if ($twilioSid && $twilioToken && $twilioWhatsAppFrom) {
            Log::info("Sending via Twilio WhatsApp (primary method)");
            $twilioResult = $this->sendViaTwilio($event, $phoneNumber);
            
            if ($twilioResult['success']) {
                return $twilioResult;
            }
            
            // If Twilio failed, log and try WhatsApp Egypt as fallback
            Log::warning("Twilio WhatsApp failed, trying WhatsApp Egypt as fallback", [
                'error' => $twilioResult['message'] ?? 'Unknown error'
            ]);
        }
        
        // Priority 2: Try WhatsApp Egypt API (for Egyptian numbers)
        $whatsappAppKey = config('services.whatsapp_egypt.app_key');
        $whatsappAuthKey = config('services.whatsapp_egypt.auth_key');
        
        if ($whatsappAppKey && $whatsappAuthKey) {
            Log::info("Sending via WhatsApp Egypt API (fallback method)");
            $whatsappResult = $this->sendViaWhatsAppEgypt($event, $phoneNumber);
            
            if ($whatsappResult['success']) {
                return $whatsappResult;
            }
            
            // Both failed
            return [
                'success' => false,
                'message' => 'فشل الإرسال عبر كلا الطريقتين. Twilio: ' . ($twilioResult['message'] ?? 'غير مكوّن') . ' | WhatsApp Egypt: ' . ($whatsappResult['message'] ?? 'خطأ غير معروف')
            ];
        }
        
        // Neither configured
        return [
            'success' => false,
            'message' => 'لم يتم تكوين أي خدمة. يرجى إضافة بيانات Twilio WhatsApp (TWILIO_WHATSAPP_FROM) أو WhatsApp Egypt (WHATSAPP_EGYPT_APP_KEY) في ملف .env'
        ];
    }

    /**
     * Send via WhatsApp Egypt API
     *
     * @param Event $event
     * @param string $phoneNumber
     * @return array
     */
    private function sendViaWhatsAppEgypt(Event $event, string $phoneNumber): array
    {
        try {
            $apiUrl = config('services.whatsapp_egypt.api_url');
            $appKey = config('services.whatsapp_egypt.app_key');
            $authKey = config('services.whatsapp_egypt.auth_key');

            // If WhatsApp Egypt credentials are not configured, return clear error
            if (!$appKey || !$authKey || $appKey === '' || $authKey === '') {
                Log::warning("WhatsApp Egypt credentials missing", [
                    'app_key_exists' => !empty($appKey),
                    'auth_key_exists' => !empty($authKey)
                ]);
                return [
                    'success' => false, 
                    'message' => 'WhatsApp Egypt credentials not configured. Please add WHATSAPP_EGYPT_APP_KEY and WHATSAPP_EGYPT_AUTH_KEY to your .env file.'
                ];
            }

            // Build message content with QR link
            $message = $this->buildInvitationMessage($event, true);

            // Format phone number
            $to = PhoneNormalizer::toE164($phoneNumber);

            // Send text message first
            $postData = [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $to,
                'message' => $message
            ];
            
            Log::info("WhatsApp Egypt: Sending text message", ['to' => $to]);
            $response = $this->sendWhatsAppRequest($apiUrl, $postData);
            
            // Then send QR Code image if available
            $qrCodeImageUrl = $this->generateQrCodeImage($event);
            if (!empty($qrCodeImageUrl) && $response['success']) {
                Log::info("WhatsApp Egypt: Sending QR Code image separately", ['url' => $qrCodeImageUrl]);
                
                $imageData = [
                    'appkey' => $appKey,
                    'authkey' => $authKey,
                    'to' => $to,
                    'file' => $qrCodeImageUrl
                ];
                
                $imageResponse = $this->sendWhatsAppRequest($apiUrl, $imageData);
                Log::info("WhatsApp Egypt: QR Code image sent", ['success' => $imageResponse['success']]);
            }

            if ($response['success']) {
                return [
                    'success' => true,
                    'response' => $response['response'] ?? null,
                    'message' => 'Message sent successfully via WhatsApp'
                ];
            }

            // Log the failure for debugging
            Log::warning("WhatsApp Egypt API failed", [
                'error' => $response['message'] ?? 'Unknown error',
                'http_code' => $response['http_code'] ?? null,
                'response_data' => $response['response'] ?? null
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error("WhatsApp Egypt Error: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return [
                'success' => false,
                'message' => 'WhatsApp Egypt error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send via Twilio
     *
     * @param Event $event
     * @param string $phoneNumber
     * @return array
     */
    private function sendViaTwilio(Event $event, string $phoneNumber): array
    {
        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            
            // Try WhatsApp first, then SMS, then SIM as fallback
            $from = config('services.twilio.whatsapp_from') ?: (config('services.twilio.sms_from') ?: config('services.twilio.sim_from'));

            if (!$sid || !$token) {
                return [
                    'success' => false,
                    'message' => 'Twilio credentials are missing. Please check TWILIO_SID and TWILIO_AUTH_TOKEN in .env file.'
                ];
            }

            if (!$from) {
                return [
                    'success' => false,
                    'message' => 'Twilio "From" number is missing. Please set TWILIO_WHATSAPP_FROM (for WhatsApp) or TWILIO_SMS_FROM in .env file.'
                ];
            }
            
            // Check if using WhatsApp
            $isWhatsApp = str_starts_with($from, 'whatsapp:');

            $client = new Client($sid, $token);

            // Generate QR Code image
            $qrCodeImageUrl = $this->generateQrCodeImage($event);

            // Format phone number
            $to = $this->formatPhoneNumber($phoneNumber);
            
            // If using WhatsApp, add whatsapp: prefix to recipient number
            if ($isWhatsApp && !str_starts_with($to, 'whatsapp:')) {
                $to = 'whatsapp:' . $to;
            }

            // Build message content
            $message = $this->buildInvitationMessage($event, true);
            
            // Send text message first
            $textMessage = $client->messages->create($to, [
                "from" => $from,
                "body" => $message
            ]);
            
            Log::info("Twilio: Text message sent", ['sid' => $textMessage->sid]);
            
            // Then send QR Code image if available
            if (!empty($qrCodeImageUrl)) {
                Log::info("Twilio: Sending QR Code image separately", ['url' => $qrCodeImageUrl]);
                
                $imageMessage = $client->messages->create($to, [
                    "from" => $from,
                    "mediaUrl" => [$qrCodeImageUrl]
                ]);
                
                Log::info("Twilio: QR Code image sent", ['sid' => $imageMessage->sid]);
                $twilioMessage = $imageMessage; // Use image message for response
            } else {
                Log::warning("Twilio: QR Code image not generated");
                $twilioMessage = $textMessage; // Use text message for response
            }
            
            $messageType = $isWhatsApp ? 'WhatsApp' : 'SMS';

            return [
                'success' => true,
                'response' => $twilioMessage->sid,
                'message' => $messageType . ' message sent successfully via Twilio'
            ];
        } catch (RestException $e) {
            Log::error("Twilio Error: {$e->getMessage()} (Code: {$e->getCode()})");
            
            // Provide more helpful error messages
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Mismatch between the \'From\' number')) {
                $errorMessage = 'رقم TWILIO_WHATSAPP_FROM غير صحيح أو غير مفعّل. تحقق من Twilio Console.';
            } elseif (str_contains($errorMessage, 'Unable to create record')) {
                $errorMessage = 'فشل الإرسال. تحقق من إعدادات حساب Twilio.';
            } elseif (str_contains($errorMessage, 'not a valid WhatsApp')) {
                $errorMessage = 'المستقبل لم ينضم إلى Twilio WhatsApp Sandbox. يجب أن يرسل "join <code>" أولاً.';
            }
            
            return [
                'success' => false,
                'message' => $errorMessage
            ];
        } catch (\Exception $e) {
            Log::error("Twilio Service Error: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return [
                'success' => false,
                'message' => 'فشل الإرسال عبر Twilio: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send WhatsApp HTTP request
     *
     * @param string $url
     * @param array $postData
     * @return array
     */
    private function sendWhatsAppRequest(string $url, array $postData): array
    {
        $jsonData = json_encode($postData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            Log::error("WhatsApp cURL Error: $error", ['request_data' => $postData]);
            return ['success' => false, 'message' => "cURL Error: $error"];
        }

        if ($httpCode === 0) {
            Log::error("WhatsApp Connection Failed", ['request_data' => $postData]);
            return ['success' => false, 'message' => 'Connection failed to WhatsApp API'];
        }

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("WhatsApp JSON Error", [
                'json_error' => json_last_error_msg(),
                'raw_response' => $response
            ]);
            return ['success' => false, 'message' => 'Invalid JSON response from WhatsApp API'];
        }

        // Log full response for debugging
        Log::info("WhatsApp API Response", [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
            'raw_response' => substr($response, 0, 500) // First 500 chars
        ]);

        // Check for successful response
        if (
            $httpCode === 200 && $decodedResponse &&
            ((isset($decodedResponse['message_status']) && $decodedResponse['message_status'] === 'Success') ||
                (isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') ||
                (isset($decodedResponse['success']) && $decodedResponse['success'] === true) ||
                (isset($decodedResponse['message_id']) && !empty($decodedResponse['message_id'])))
        ) {
            $responseId = $decodedResponse['message_id'] ?? 
                         $decodedResponse['id'] ?? 
                         $decodedResponse['sid'] ?? 
                         'N/A';
            
            Log::info("WhatsApp API Success", [
                'response_id' => $responseId,
                'full_response' => $decodedResponse
            ]);
            
            return [
                'success' => true, 
                'response' => $responseId,
                'full_response' => $decodedResponse,
                'message' => 'Message sent successfully via WhatsApp Egypt API'
            ];
        }

        // Log the error for debugging
        Log::error("WhatsApp API Error", [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
            'raw_response' => $response
        ]);

        // Return detailed error message
        $errorMessage = 'WhatsApp API returned error';
        if (isset($decodedResponse['message'])) {
            $errorMessage = $decodedResponse['message'];
        } elseif (isset($decodedResponse['error'])) {
            $errorMessage = $decodedResponse['error'];
        }

        return [
            'success' => false, 
            'message' => $errorMessage . ' (HTTP: ' . $httpCode . ')',
            'response' => $decodedResponse, 
            'http_code' => $httpCode
        ];
    }

    /**
     * Build the invitation message content (without QR URL - image will be sent separately)
     *
     * @param Event $event
     * @return string
     */
    private function buildInvitationMessage(Event $event, bool $includeQrLink = false): string
    {
        // Fixed message as requested
        $message = "تدعوكم Red Star Films و Film Square لحضور العرض الخاص\n\n";
        $message .= "لفيلم \"لنا في الخيال… حب\"\n\n";
        $message .= "وذلك يوم الثلاثاء 19 نوفمبر في سينما أركان بلازا، الساعة 9 مساءً.\n\n";
        $message .= "ليلة مليانة خيال وحب… نشارككم فيها مشاهدة الفيلم مع أبطاله وصُنّاعه.\n\n";
        $message .= "يرجى تأكيد الحضور — Kindly confirm your attendance.";
        
        // Add QR link as fallback if image fails
        // if ($includeQrLink) {
        //     $qrCodeUrl = route('event.qrcode', ['event' => $event->uuid]);
        //     $message .= "\n\n" . $qrCodeUrl;
        // }

        return $message;
    }

    /**
     * Generate and save QR Code image
     *
     * @param Event $event
     * @return string|null Public URL to saved QR code image
     */
    private function generateQrCodeImage(Event $event): ?string
    {
        try {
            $qrCodeUrl = route('event.qrcode', ['event' => $event->uuid]);
            
            // Create directory if it doesn't exist
            $qrCodeDir = storage_path('app/public/qrcodes');
            if (!file_exists($qrCodeDir)) {
                mkdir($qrCodeDir, 0755, true);
            }
            
            // Generate unique filename
            $filename = 'qr_' . $event->uuid . '_' . time() . '.png';
            $filePath = $qrCodeDir . '/' . $filename;
            
            // Generate QR Code as PNG and save it
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(800)
                ->margin(10)
                ->color(0, 0, 0)
                ->backgroundColor(255, 255, 255)
                ->errorCorrection('H')
                ->generate($qrCodeUrl, $filePath);
            
            // Verify file was created
            if (!file_exists($filePath)) {
                Log::error("QR Code file was not created: {$filePath}");
                return null;
            }
            
            // Add text below QR Code
            $this->addTextToQrCode($filePath, 'لنا في الخيال… حب');
            
            // Return absolute URL for external access
            return url('storage/qrcodes/' . $filename);
        } catch (\Exception $e) {
            Log::error("Failed to generate QR Code image: {$e->getMessage()}", [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }

    /**
     * Add text below QR Code image
     *
     * @param string $imagePath
     * @param string $text
     * @return void
     */
    private function addTextToQrCode(string $imagePath, string $text): void
    {
        try {
            // Load the QR code image
            $qrImage = imagecreatefrompng($imagePath);
            if (!$qrImage) {
                return;
            }
            
            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);
            
            // Create new image with space for text below
            $textHeight = 80;
            $newHeight = $qrHeight + $textHeight;
            $newImage = imagecreatetruecolor($qrWidth, $newHeight);
            
            // Fill with white background
            $white = imagecolorallocate($newImage, 255, 255, 255);
            $black = imagecolorallocate($newImage, 0, 0, 0);
            imagefill($newImage, 0, 0, $white);
            
            // Copy QR code to new image
            imagecopy($newImage, $qrImage, 0, 0, 0, 0, $qrWidth, $qrHeight);
            
            // Add text
            $fontSize = 24;
            $fontPath = public_path('fonts/Arial.ttf'); // Path to Arabic font
            
            // If custom font doesn't exist, use built-in font
            if (file_exists($fontPath)) {
                // Calculate text position (centered)
                $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
                $textWidth = abs($bbox[4] - $bbox[0]);
                $x = ($qrWidth - $textWidth) / 2;
                $y = $qrHeight + 50;
                
                // Add text with TrueType font
                imagettftext($newImage, $fontSize, 0, $x, $y, $black, $fontPath, $text);
            } else {
                // Fallback to built-in font (won't display Arabic correctly, but won't break)
                $x = ($qrWidth - (strlen($text) * 8)) / 2;
                $y = $qrHeight + 30;
                imagestring($newImage, 5, $x, $y, $text, $black);
            }
            
            // Save the new image
            imagepng($newImage, $imagePath);
            
            // Free memory
            imagedestroy($qrImage);
            imagedestroy($newImage);
            
        } catch (\Exception $e) {
            Log::error("Failed to add text to QR Code: {$e->getMessage()}");
            // Don't throw error, just continue without text
        }
    }

    /**
     * Format phone number for Twilio
     *
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        return PhoneNormalizer::toE164($phoneNumber);
    }

    /**
     * Send custom message via WhatsApp or SMS
     *
     * @param string $phoneNumber
     * @param string $message
     * @param string $type 'whatsapp' or 'sms'
     * @return array
     */
    public function sendCustomMessage(string $phoneNumber, string $message, string $type = 'whatsapp'): array
    {
        if ($type === 'sms') {
            // For SMS: Use SMS Misr
            return $this->sendCustomViaSmsMisr($phoneNumber, $message);
        } else {
            // WhatsApp: WAAPI PRO first, then Twilio, then WhatsApp Egypt
            $waapiResult = null;

            if ($this->waapi()->isConfigured()) {
                $waapiResult = $this->waapi()->sendText($phoneNumber, $message);

                if ($waapiResult['success']) {
                    return $waapiResult;
                }

                Log::warning('WAAPI WhatsApp failed, trying Twilio fallback', [
                    'error' => $waapiResult['message'] ?? 'Unknown error',
                ]);
            }

            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioResult = null;
            
            if ($twilioSid && $twilioToken) {
                $twilioResult = $this->sendCustomViaTwilio($phoneNumber, $message, 'whatsapp');
                
                if ($twilioResult['success']) {
                    return $twilioResult;
                }
                
                Log::warning("Twilio WhatsApp failed, trying WhatsApp Egypt as fallback", [
                    'error' => $twilioResult['message'] ?? 'Unknown error'
                ]);
            }
            
            // Fallback: Try WhatsApp Egypt API (for Egyptian numbers)
            $whatsappAppKey = config('services.whatsapp_egypt.app_key');
            $whatsappAuthKey = config('services.whatsapp_egypt.auth_key');
            
            if ($whatsappAppKey && $whatsappAuthKey) {
                $whatsappResult = $this->sendCustomViaWhatsAppEgypt($phoneNumber, $message);
                
                if ($whatsappResult['success']) {
                    return $whatsappResult;
                }
                
                return [
                    'success' => false,
                    'message' => 'Sending failed. WAAPI: ' . ($waapiResult['message'] ?? 'Not configured') . ' | Twilio: ' . ($twilioResult['message'] ?? 'Not configured') . ' | WhatsApp Egypt: ' . ($whatsappResult['message'] ?? 'Unknown error')
                ];
            }

            if (is_array($waapiResult)) {
                return [
                    'success' => false,
                    'message' => 'WAAPI send failed: ' . ($waapiResult['message'] ?? 'Unknown error'),
                ];
            }

            if (is_array($twilioResult)) {
                return [
                    'success' => false,
                    'message' => 'Twilio send failed: ' . ($twilioResult['message'] ?? 'Unknown error'),
                ];
            }
            
            return [
                'success' => false,
                'message' => 'No WhatsApp service configured. Add WAAPI_AUTH_KEY or Twilio credentials in .env'
            ];
        }
    }

    /**
     * Send custom message via Twilio
     *
     * @param string $phoneNumber
     * @param string $message
     * @param string $type
     * @return array
     */
    private function sendCustomViaTwilio(string $phoneNumber, string $message, string $type = 'whatsapp'): array
    {
        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            
            if ($type === 'whatsapp') {
                $from = config('services.twilio.whatsapp_from');
            } else {
                $from = config('services.twilio.sms_from') ?: config('services.twilio.sim_from');
            }

            if (!$sid || !$token) {
                return [
                    'success' => false,
                    'message' => 'Twilio credentials are missing. Please check TWILIO_SID and TWILIO_AUTH_TOKEN in .env file.'
                ];
            }

            if (!$from) {
                return [
                    'success' => false,
                    'message' => 'Twilio "From" number is missing. Please set TWILIO_WHATSAPP_FROM or TWILIO_SMS_FROM in .env file.'
                ];
            }
            
            $isWhatsApp = str_starts_with($from, 'whatsapp:');
            $client = new Client($sid, $token);
            $to = $this->formatPhoneNumber($phoneNumber);
            
            if ($isWhatsApp && !str_starts_with($to, 'whatsapp:')) {
                $to = 'whatsapp:' . $to;
            }

            $textMessage = $client->messages->create($to, [
                "from" => $from,
                "body" => $message
            ]);
            
            Log::info("Twilio: Custom message sent", [
                'sid' => $textMessage->sid,
                'type' => $type
            ]);

            $messageType = $isWhatsApp ? 'WhatsApp' : 'SMS';

            return [
                'success' => true,
                'response' => $textMessage->sid,
                'message' => $messageType . ' message sent successfully via Twilio'
            ];
        } catch (RestException $e) {
            Log::error("Twilio Error: {$e->getMessage()} (Code: {$e->getCode()})");
            
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Mismatch between the \'From\' number')) {
                $errorMessage = 'TWILIO_WHATSAPP_FROM is incorrect or not enabled. Please check Twilio Console.';
            } elseif (str_contains($errorMessage, 'Unable to create record')) {
                $errorMessage = 'Sending failed. Please check Twilio account settings.';
            } elseif (str_contains($errorMessage, 'not a valid WhatsApp')) {
                $errorMessage = 'The recipient has not joined the Twilio WhatsApp Sandbox. Please send "join <code>" first.';
            }
            
            return [
                'success' => false,
                'message' => $errorMessage
            ];
        } catch (\Exception $e) {
            Log::error("Twilio Exception: {$e->getMessage()}", [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send custom message via WhatsApp Egypt API
     *
     * @param string $phoneNumber
     * @param string $message
     * @return array
     */
    private function sendCustomViaWhatsAppEgypt(string $phoneNumber, string $message): array
    {
        try {
            $apiUrl = config('services.whatsapp_egypt.api_url');
            $appKey = config('services.whatsapp_egypt.app_key');
            $authKey = config('services.whatsapp_egypt.auth_key');

            if (!$appKey || !$authKey || $appKey === '' || $authKey === '') {
                return [
                    'success' => false, 
                    'message' => 'WhatsApp Egypt credentials not configured. Please add WHATSAPP_EGYPT_APP_KEY and WHATSAPP_EGYPT_AUTH_KEY to your .env file.'
                ];
            }

            $to = PhoneNormalizer::toE164($phoneNumber);

            $postData = [
                'appkey' => $appKey,
                'authkey' => $authKey,
                'to' => $to,
                'message' => $message
            ];
            
            Log::info("WhatsApp Egypt: Sending custom message", ['to' => $to]);
            $response = $this->sendWhatsAppRequest($apiUrl, $postData);
            
            return $response;
        } catch (\Exception $e) {
            Log::error("WhatsApp Egypt Exception: {$e->getMessage()}", [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error sending message: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send custom message via SMS Misr (POST {base}/SMS per official API).
     *
     * @param string $phoneNumber
     * @param string $message
     * @return array{success: bool, message?: string, response?: mixed, full_response?: mixed, http_code?: int}
     */
    private function sendCustomViaSmsMisr(string $phoneNumber, string $message): array
    {
        try {
            $environment = (int) config('services.sms_misr.environment', 1);
            $language = (int) config('services.sms_misr.language', 2);
            $sender = trim((string) (config('services.sms_misr.sender') ?? ''));
            $token = trim((string) (config('services.sms_misr.token') ?? ''));
            $username = trim((string) (config('services.sms_misr.username') ?? ''));
            $password = trim((string) (config('services.sms_misr.password') ?? ''));
            $legacySenderToken = trim((string) (config('services.sms_misr.sender_token') ?? ''));

            $credentials = [];
            $isPlaceholderPassword = $password === '' || str_starts_with(strtoupper($password), 'YOUR_');

            if ($token !== '') {
                $credentials = ['environment' => $environment, 'token' => $token];
            } elseif ($username !== '' && !$isPlaceholderPassword) {
                $credentials = ['environment' => $environment, 'username' => $username, 'password' => $password];
            } elseif ($legacySenderToken !== '') {
                $credentials = [
                    'environment' => $environment,
                    'username' => $legacySenderToken,
                    'password' => $legacySenderToken,
                ];
                // Legacy SMS Misr mode expects sender token to be used as sender value as well.
                $sender = $legacySenderToken;
            } else {
                return [
                    'success' => false,
                    'message' => 'SMS Misr is not configured. Set SMS_MISR_TOKEN or SMS_MISR_USERNAME and SMS_MISR_PASSWORD (or legacy SMS_MISR_SENDER_TOKEN).',
                ];
            }

            if ($sender === '') {
                return [
                    'success' => false,
                    'message' => 'SMS Misr sender is missing. Set SMS_MISR_SENDER in .env to your approved sender name.',
                ];
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = substr($phoneNumber, 1);
            }
            if (!str_starts_with($phoneNumber, '2')) {
                $phoneNumber = '2' . $phoneNumber;
            }
            $to = $phoneNumber;

            $base = $this->smsMisrResolveApiBaseUrl();
            $endpoint = $base . '/SMS';

            $query = array_merge($credentials, [
                'sender' => $sender,
                'language' => $language,
                'message' => $message,
                'mobile' => $to,
            ]);

            $logQuery = $query;
            if (isset($logQuery['password'])) {
                $logQuery['password'] = '***';
            }
            if (isset($logQuery['token'])) {
                $logQuery['token'] = '***';
            }
            Log::info('SMS Misr: POST /SMS', ['endpoint' => $endpoint, 'query' => $logQuery]);

            $httpResponse = Http::timeout(30)
                ->acceptJson()
                ->withQueryParameters($query)
                ->post($endpoint);

            if ($httpResponse->status() === 404) {
                $httpResponse = Http::timeout(30)
                    ->acceptJson()
                    ->asForm()
                    ->post($endpoint, $query);
            }

            return $this->interpretSmsMisrHttpResponse($httpResponse);
        } catch (\Throwable $e) {
            Log::error("SMS Misr Exception: {$e->getMessage()}", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'Error sending message: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Normalize configured API root (strip legacy /webapi suffix).
     */
    private function smsMisrResolveApiBaseUrl(): string
    {
        $base = rtrim((string) config('services.sms_misr.api_url', 'https://smsmisr.com/api'), '/');
        if (str_ends_with($base, '/webapi')) {
            $base = rtrim(substr($base, 0, -strlen('/webapi')), '/');
        }

        return $base !== '' ? $base : 'https://smsmisr.com/api';
    }

    /**
     * @return array{success: bool, message?: string, response?: mixed, full_response?: mixed, http_code?: int}
     */
    private function interpretSmsMisrHttpResponse(Response $httpResponse): array
    {
        $httpCode = $httpResponse->status();
        $decodedResponse = $httpResponse->json();
        $rawBody = $httpResponse->body();

        if (!is_array($decodedResponse)) {
            Log::error('SMS Misr: non-JSON or invalid response', [
                'http_code' => $httpCode,
                'raw' => substr($rawBody, 0, 500),
            ]);

            return [
                'success' => false,
                'message' => 'Invalid JSON response from SMS Misr API (HTTP: ' . $httpCode . ')',
                'http_code' => $httpCode,
            ];
        }

        Log::info('SMS Misr API Response', [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
        ]);

        $code = $decodedResponse['code'] ?? $decodedResponse['Code'] ?? null;
        $status = strtolower((string) ($decodedResponse['status'] ?? $decodedResponse['Status'] ?? ''));
        $type = strtolower((string) ($decodedResponse['Type'] ?? $decodedResponse['type'] ?? ''));
        $successFlag = $decodedResponse['success'] ?? $decodedResponse['Success'] ?? null;
        $codeStr = $code === null ? '' : (string) $code;

        if (
            $httpCode === 200 &&
            (
                $codeStr === '1901'
                || $status === 'success'
                || $successFlag === true || $successFlag === 'true' || $successFlag === 1 || $successFlag === '1'
                || $type === 'success'
                || (isset($decodedResponse['message_id']) && !empty($decodedResponse['message_id']))
            )
        ) {
            $responseId = $decodedResponse['message_id']
                ?? $decodedResponse['id']
                ?? $decodedResponse['Code']
                ?? 'N/A';

            Log::info('SMS Misr API Success', [
                'response_id' => $responseId,
                'full_response' => $decodedResponse,
            ]);

            return [
                'success' => true,
                'response' => $responseId,
                'full_response' => $decodedResponse,
                'message' => 'SMS sent successfully via SMS Misr',
            ];
        }

        Log::error('SMS Misr API Error', [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
        ]);

        $errorMessage = 'SMS Misr API returned error';
        if (isset($decodedResponse['message'])) {
            $errorMessage = (string) $decodedResponse['message'];
        } elseif (isset($decodedResponse['Message'])) {
            $errorMessage = (string) $decodedResponse['Message'];
        } elseif (isset($decodedResponse['error'])) {
            $errorMessage = (string) $decodedResponse['error'];
        } elseif (isset($decodedResponse['Error'])) {
            $errorMessage = (string) $decodedResponse['Error'];
        } elseif (isset($decodedResponse['Msg'])) {
            $errorMessage = (string) $decodedResponse['Msg'];
        }

        if ($codeStr === '1903') {
            $errorMessage = 'SMS Misr rejected credentials (Code 1903). Verify API auth mode: use valid SMS_MISR_TOKEN or valid username/password from SMS Misr settings.';
        } elseif ($codeStr === '1904') {
            $errorMessage = 'SMS Misr rejected the request (Code 1904). Verify SMS_MISR_SENDER is an approved Sender ID on your account and that API credentials match the same account.';
        }

        if ($codeStr !== '') {
            $errorMessage .= ' (Code: ' . $codeStr . ')';
        }

        return [
            'success' => false,
            'message' => $errorMessage . ' (HTTP: ' . $httpCode . ')',
            'response' => $decodedResponse,
            'http_code' => $httpCode,
        ];
    }
}

