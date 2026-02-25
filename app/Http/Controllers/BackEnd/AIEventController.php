<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\Event;

class AIEventController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function processMCP(Request $request)
    {
        try {
            $description = $request->input('description');

            // Create prompt for AI
            $prompt = "Analyze the following event description and extract the following information in JSON format:
            - Event name (name)
            - Event description (description)
            - Location (location)
            - Organizer (organized_by)
            - Format (format) (1 for online events, 0 for offline events)
            - Meeting link (link_meeting) (if online)
            - Start date (start_date) (in YYYY-MM-DD format)
            - End date (end_date) (in YYYY-MM-DD format)
            - Start time (start_time) (in HH:MM format)
            - End time (end_time) (in HH:MM format)
            - Tickets (tickets) as an array of objects containing:
              * Ticket type (type)
              * Price (price)
              * Quantity (quantity)

            Description: {$description}

            Return only the JSON object, nothing else.";

            $response = $this->openAIService->createCompletion($prompt);

            // Parse OpenAI response
            $aiResponse = $response['choices'][0]['text'];

            // Find JSON in response
            preg_match('/\{.*\}/s', $aiResponse, $matches);
            if (empty($matches)) {
                throw new \Exception('No JSON data found in response');
            }

            $data = json_decode($matches[0], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON parsing error: ' . json_last_error_msg());
            }

            // Process format value
            if (isset($data['format'])) {
                if (strtolower($data['format']) === 'online' || $data['format'] === '1') {
                    $data['format'] = '1';
                } else {
                    $data['format'] = '0';
                }
            }

            // Process dates and times
            if (isset($data['start_date'])) {
                $data['start_date'] = date('Y-m-d', strtotime($data['start_date']));
            }
            if (isset($data['end_date'])) {
                $data['end_date'] = date('Y-m-d', strtotime($data['end_date']));
            }
            if (isset($data['start_time'])) {
                $data['start_time'] = date('H:i', strtotime($data['start_time']));
            }
            if (isset($data['end_time'])) {
                $data['end_time'] = date('H:i', strtotime($data['end_time']));
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing description: ' . $e->getMessage()
            ], 500);
        }
    }
}
