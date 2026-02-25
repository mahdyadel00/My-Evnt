<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

/**
 * Service class for sending SMS messages
 */
class SmsService
{
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
            $to = '+2' . preg_replace('/[^0-9]/', '', $phoneNumber);

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
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If it doesn't start with country code, add +2 for Egypt
        if (!str_starts_with($phoneNumber, '2')) {
            $phoneNumber = '+2' . $phoneNumber;
        } else {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
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
            // For WhatsApp: Use Twilio
            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            
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
                    'message' => 'Sending failed via both methods. Twilio: ' . ($twilioResult['message'] ?? 'Not configured') . ' | WhatsApp Egypt: ' . ($whatsappResult['message'] ?? 'Unknown error')
                ];
            }
            
            return [
                'success' => false,
                'message' => 'No service configured. Please add Twilio or WhatsApp Egypt credentials in .env file'
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

            $to = '+2' . preg_replace('/[^0-9]/', '', $phoneNumber);

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
     * Send custom message via SMS Misr
     *
     * @param string $phoneNumber
     * @param string $message
     * @return array
     */
    private function sendCustomViaSmsMisr(string $phoneNumber, string $message): array
    {
        try {
            $senderToken = config('services.sms_misr.sender_token');
            $apiUrl = config('services.sms_misr.api_url', 'https://smsmisr.com/api/webapi');

            if (!$senderToken || $senderToken === '') {
                return [
                    'success' => false,
                    'message' => 'SMS Misr sender token not configured. Please add SMS_MISR_SENDER_TOKEN to your .env file.'
                ];
            }

            // Format phone number (remove + and keep only digits)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Remove leading 0 if exists, then add country code if needed
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = substr($phoneNumber, 1);
            }
            
            // If it doesn't start with country code, add 2 for Egypt
            if (!str_starts_with($phoneNumber, '2')) {
                $phoneNumber = '2' . $phoneNumber;
            }

            // SMS Misr API expects phone number without +
            $to = $phoneNumber;

            // SMS Misr API format - using sender token as username/password or in URL
            $postData = [
                'username' => $senderToken,
                'password' => $senderToken,
                'language' => 2, // 1 for English, 2 for Arabic
                'message' => $message,
                'mobile' => $to,
                'sender' => $senderToken
            ];

            Log::info("SMS Misr: Sending SMS", [
                'url' => $apiUrl,
                'to' => $to,
                'message_length' => strlen($message),
                'post_data' => array_merge($postData, ['password' => '***']) // Hide password in log
            ]);

            $response = $this->sendSmsMisrRequest($apiUrl, $postData);

            return $response;
        } catch (\Exception $e) {
            Log::error("SMS Misr Exception: {$e->getMessage()}", [
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
     * Send SMS Misr HTTP request
     *
     * @param string $url
     * @param array $postData
     * @return array
     */
    private function sendSmsMisrRequest(string $url, array $postData): array
    {
        $senderToken = $postData['sender'] ?? $postData['username'] ?? '';
        
        // Try different URL formats and request methods
        $urlsToTry = [
            $url, // Original URL
            'https://smsmisr.com/api/SendSMS', // Alternative URL 1
            'https://smsmisr.com/api/v2/SendSMS', // Alternative URL 2
            'https://smsmisr.com/api/webapi', // Alternative URL 3
        ];

        foreach ($urlsToTry as $tryUrl) {
            // Try 1: Form-data POST
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $tryUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
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

            if ($httpCode === 200) {
                Log::info("SMS Misr: Success with form-data", ['url' => $tryUrl]);
                break; // Success, exit loop
            }

            if ($httpCode !== 404) {
                Log::warning("SMS Misr: HTTP {$httpCode} with form-data", ['url' => $tryUrl]);
                break; // Not 404, might be different error, try to process
            }

            // Try 2: JSON POST
            Log::info("SMS Misr: Trying JSON format", ['url' => $tryUrl]);
            $jsonData = json_encode($postData);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $tryUrl);
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

            if ($httpCode === 200) {
                Log::info("SMS Misr: Success with JSON", ['url' => $tryUrl]);
                break; // Success, exit loop
            }

            // Try 3: GET with query string
            if ($httpCode === 404) {
                Log::info("SMS Misr: Trying GET method", ['url' => $tryUrl]);
                $queryString = http_build_query($postData);
                $getUrl = $tryUrl . '?' . $queryString;
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $getUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
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

                if ($httpCode === 200) {
                    Log::info("SMS Misr: Success with GET", ['url' => $getUrl]);
                    break; // Success, exit loop
                }
            }
        }

        if ($error) {
            Log::error("SMS Misr cURL Error: $error", ['request_data' => $postData]);
            return ['success' => false, 'message' => "cURL Error: $error"];
        }

        if ($httpCode === 0) {
            Log::error("SMS Misr Connection Failed", ['request_data' => $postData]);
            return ['success' => false, 'message' => 'Connection failed to SMS Misr API'];
        }

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("SMS Misr JSON Error", [
                'json_error' => json_last_error_msg(),
                'raw_response' => $response
            ]);
            return ['success' => false, 'message' => 'Invalid JSON response from SMS Misr API'];
        }

        // Log full response for debugging
        Log::info("SMS Misr API Response", [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
            'raw_response' => substr($response, 0, 500)
        ]);

        // Check for successful response
        // SMS Misr typically returns success in different formats
        if (
            $httpCode === 200 && $decodedResponse &&
            (
                (isset($decodedResponse['code']) && $decodedResponse['code'] === '1901') ||
                (isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') ||
                (isset($decodedResponse['success']) && $decodedResponse['success'] === true) ||
                (isset($decodedResponse['Type']) && $decodedResponse['Type'] === 'success') ||
                (isset($decodedResponse['message_id']) && !empty($decodedResponse['message_id']))
            )
        ) {
            $responseId = $decodedResponse['message_id'] ?? 
                         $decodedResponse['id'] ?? 
                         $decodedResponse['Code'] ?? 
                         'N/A';

            Log::info("SMS Misr API Success", [
                'response_id' => $responseId,
                'full_response' => $decodedResponse
            ]);

            return [
                'success' => true,
                'response' => $responseId,
                'full_response' => $decodedResponse,
                'message' => 'SMS sent successfully via SMS Misr'
            ];
        }

        // Log the error for debugging
        Log::error("SMS Misr API Error", [
            'http_code' => $httpCode,
            'response' => $decodedResponse,
            'raw_response' => $response
        ]);

        // Return detailed error message
        $errorMessage = 'SMS Misr API returned error';
        if (isset($decodedResponse['message'])) {
            $errorMessage = $decodedResponse['message'];
        } elseif (isset($decodedResponse['error'])) {
            $errorMessage = $decodedResponse['error'];
        } elseif (isset($decodedResponse['Msg'])) {
            $errorMessage = $decodedResponse['Msg'];
        }

        return [
            'success' => false,
            'message' => $errorMessage . ' (HTTP: ' . $httpCode . ')',
            'response' => $decodedResponse,
            'http_code' => $httpCode
        ];
    }
}

