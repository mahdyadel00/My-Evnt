<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\FomrServayHRRequest;
use App\Models\Event;
use App\Models\FromServayHR;
use App\Models\Order;
use App\Models\User;
use App\Models\TicketQr;
use App\Models\Setting;
use App\Models\Ticket;
use App\Mail\SurveyMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormServayHRController extends Controller
{

    public function checkoutSurveyHR($id)
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

            return view('Frontend.events.checkout_servay_hr', compact('event', 'setting'));
        } catch (\Exception $e) {
            Log::error('Error in FormServayController@checkoutSurveyHR', [
                'event_id' => $id,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('home')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function checkoutSurveyHRPost(FomrServayHRRequest $request)
    {
        try {
            DB::beginTransaction();
            
            // Get event with company media
            $event = Event::with(['company.media'])->find($request->event_id);
            
            // Handle file upload if exists
            $startupFilePath = null;
            if ($request->hasFile('startup_file')) {
                $file = $request->file('startup_file');
                $startupFilePath = $file->store('startup_files', 'public');
            }

            // Check mentorship track limit if mentorship track is selected
            // Use lockForUpdate to prevent race conditions
            if ($request->mentorship_track) {
                $maxPerTrack = 12; // Match the value in getMentorshipTrackCounts
                
                // Lock the rows to prevent concurrent inserts
                $currentCount = FromServayHR::where('event_id', $request->event_id)
                    ->where('mentorship_track', $request->mentorship_track)
                    ->lockForUpdate()
                    ->count();
                
                if ($currentCount >= $maxPerTrack) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'The limit has been reached for this selection',
                        'title' => 'Limit Reached'
                    ], 400);
                }
            }

            // Create or get user for email purposes
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                    'phone'             => $request->phone,
                    'type'              => $event->name,
                ]
            );

            // Save survey data to FromServayHR table
            $survey = FromServayHR::create([
                'event_id'          => $request->event_id,
                'first_name'        => $request->first_name,
                'last_name'         => $request->last_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'job_title'         => $request->job_title,
                'organization'      => $request->organization,
                'ticket_type'       => $request->ticket_type,
                'attendee_type'     => $request->attendee_type,
                'mentorship_track'  => $request->mentorship_track,
                'startup_file'      => $startupFilePath,
                'status'            => 'approved',
            ]);
            
            // Get existing ticket from event if available (tickets table is for display only, we don't create new ones)
            $ticket = $event->tickets()->first();

            // Create order for email purposes (QR code generation)
            // Use existing ticket if available, otherwise null (ticket_id is nullable)
            $order = Order::create([
                'user_id'           => $user->id,
                'event_id'          => $request->event_id,
                'event_date_id'     => $event->eventDates->first()->id ?? null,
                'ticket_id'         => $ticket->id ?? null,
                'total_price'       => 0,
                'quantity'          => 1,
                'status'            => 'checked',
                'order_number'      => rand(10000, 99999),
                'payment_id'        => 'Free',
            ]);
            
            // Generate QR code image file for the order (stored in public disk)
            try {
                $qrDirectory = 'qrcodes';
                Storage::disk('public')->makeDirectory($qrDirectory);

                $qrFileName = 'qr_' . $order->order_number . '.png';
                $qrFullPath = Storage::disk('public')->path($qrDirectory . '/' . $qrFileName);

                QrCode::format('png')
                    ->size(150)
                    ->margin(1)
                    ->generate((string)$order->order_number, $qrFullPath);
            } catch (\Throwable $e) {
                Log::error('Failed to generate QR code image for order', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]);
            }

            // Send email
            Mail::to($request->email)->send(new SurveyMail($event, $user, $order, $survey));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in FormServayHRController@checkoutSurveyHRPost', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);

            // Return response based on request type
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ]);
            }

            return redirect()->back();
        }

        // Success message in Arabic
        $successMessage = 'Your booking request has been submitted successfully! We will contact you soon to confirm the appointment.';

        session()->flash('alert', [
            'type' => 'success',
            'message' => $successMessage
        ]);

        // Return response based on request type
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'title' => 'Success!',
                'redirect_url' => route('home') // Redirect to home page to check email
            ]);
        }

        return redirect()->route('home')->with('success', $successMessage);
    }

    /**
     * Get mentorship track counts for an event
     *
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMentorshipTrackCounts($eventId)
    {
        try {
            $event = Event::findOrFail($eventId);
            
            // Get counts for each mentorship track
            $counts = [
                'agricultural_biotech' => FromServayHR::where('event_id', $eventId)
                    ->where('mentorship_track', 'agricultural_biotech')
                    ->count(),
                'food_packaging_processing' => FromServayHR::where('event_id', $eventId)
                    ->where('mentorship_track', 'food_packaging_processing')
                    ->count(),
                'medicinal_aromatic_plants' => FromServayHR::where('event_id', $eventId)
                    ->where('mentorship_track', 'medicinal_aromatic_plants')
                    ->count(),
            ];
            
            // Maximum allowed per track (must match checkoutSurveyHRPost)
            $maxPerTrack = 12;
            
            // Determine which tracks are full
            $isFull = [
                'agricultural_biotech' => $counts['agricultural_biotech'] >= $maxPerTrack,
                'food_packaging_processing' => $counts['food_packaging_processing'] >= $maxPerTrack,
                'medicinal_aromatic_plants' => $counts['medicinal_aromatic_plants'] >= $maxPerTrack,
            ];
            
            return response()->json([
                'success' => true,
                'counts' => $counts,
                'isFull' => $isFull,
                'maxPerTrack' => $maxPerTrack,
                'remaining' => [
                    'agricultural_biotech' => max(0, $maxPerTrack - $counts['agricultural_biotech']),
                    'food_packaging_processing' => max(0, $maxPerTrack - $counts['food_packaging_processing']),
                    'medicinal_aromatic_plants' => max(0, $maxPerTrack - $counts['medicinal_aromatic_plants']),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in FormServayHRController@getMentorshipTrackCounts', [
                'event_id' => $eventId,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching track counts'
            ], 500);
        }
    }
}

