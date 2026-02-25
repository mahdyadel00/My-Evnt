<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\FomrServayRequest;
use App\Models\Event;
use App\Models\FormServay;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WebinarMail;

class FormServayController extends Controller
{

    public function checkoutSurvey($id)
    {
        try {
            // Get the event directly by ID
            $event = Event::whereHas('eventDates', function ($query) use ($id) {
                $query->where('id', $id);
            })->first();
            if (!$event) {
                Log::warning('Event not found', ['event_id' => $id]);
                return redirect()->route('home')->with('error', 'Event not found');
            }

            $setting = Setting::first();

            if (!$setting) {
                Log::warning('Settings not found');
                $setting = new Setting(); // Create empty settings object to prevent errors
            }

            return view('Frontend.events.survey', compact('event', 'setting'));
        } catch (\Exception $e) {
            Log::error('Error in FormServayController@checkoutSurvey', [
                'event_id' => $id,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('home')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function checkoutSurveyPost(FomrServayRequest $request)
    {
        try {
            DB::beginTransaction();
            // Save data to database
            $formSurvey = FormServay::create($request->safe()->all());
            

            Mail::to($request->email)->send(new WebinarMail($formSurvey));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in FormServayController@checkoutSurveyPost', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

        // Success message
        $successMessage = 'Your booking request has been submitted successfully! We will contact you soon to confirm the appointment.';

        // Return response based on request type
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'title' => 'Success!'
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Send WhatsApp message with customer details
     */
    private function sendWhatsAppMessage($formSurvey)
    {

        try {
            $url = 'https://whats.algypt.net/api/create-message';
            $appkey = '423befa8-ba7e-4d9d-992f-87ca5c9c3b6d';
            $authkey = 'P4sxhcbrWsX4llg6R8bm0WYNKDbBjCyJd4SNpTPMtDocKGEyZx';

            // Format phone number - Send to the specified admin number
            $phone = '+2' . $formSurvey->phone;

            // Prepare message content with customer details
            $event = Event::find($formSurvey->event_id);
            $messageContent = "📋 Your booking for the workshop '{$event->name}' has been successfully received!\n\n";
            $messageContent .= "We’ll be reaching out to you shortly to confirm your spot and share any needed information.\n\n";
            $messageContent .= "Thanks for choosing MyEvnt — we’re looking forward to seeing you there!\n\n";

            if ($event) {
                $messageContent .= "🎯 Event: {$event->name}\n";
            }

            $workshopDate = $event->eventDates->first()->start_date;
            $messageContent .= "📅 Workshop Date: {$workshopDate}\n";

            // Try simple direct message first
            $postData = [
                'appkey' => $appkey,
                'authkey' => $authkey,
                'to' => $phone,
                'message' => $messageContent
            ];

            $response = $this->sendWhatsAppRequest($postData);
            if ($response['success']) {
                return $response;
            }

            // Try with template approach as fallback
            $postData = [
                'appkey' => $appkey,
                'authkey' => $authkey,
                'to' => $phone,
                'template_id' => '04fcfb5c-0d21-4d77-9766-eef41d9cd01f',
                'variables' => ['otp' => $messageContent]
            ];

            $response = $this->sendWhatsAppRequest($postData);

            if ($response['success']) {
                return $response;
            }


            return ['success' => false, 'message' => 'All WhatsApp attempts failed', 'response' => $response];

        } catch (\Exception $e) {
            Log::error('Exception in sendWhatsAppMessage', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'survey_id' => $formSurvey->id ?? 'unknown'
            ]);

            return ['success' => false, 'message' => 'WhatsApp error: ' . $e->getMessage()];
        }
    }

    /**
     * Send WhatsApp HTTP request
     */
    private function sendWhatsAppRequest($postData)
    {
        $url = 'https://whats.algypt.net/api/create-message';
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
            Log::error("WhatsApp JSON Decode Error", [
                'json_error' => json_last_error_msg(),
                'raw_response' => $response
            ]);
            return ['success' => false, 'message' => 'Invalid JSON response from WhatsApp API'];
        }

        // Check for successful response
        if (
            $httpCode === 200 && $decodedResponse &&
            ((isset($decodedResponse['message_status']) && $decodedResponse['message_status'] === 'Success') ||
                (isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') ||
                (isset($decodedResponse['success']) && $decodedResponse['success'] === true))
        ) {
            return ['success' => true, 'response' => $decodedResponse];
        }

        return ['success' => false, 'message' => 'WhatsApp API returned error', 'response' => $decodedResponse, 'http_code' => $httpCode];
    }
}
