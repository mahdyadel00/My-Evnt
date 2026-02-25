<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Event\StoreEventRequest;
use App\Http\Requests\Backend\Event\UpdateEventRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Event;
use App\Models\EventCategory;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    protected $eventRepository;

    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Paginated results
        $allEvents = Event::with(['eventDates', 'category', 'city', 'currency', 'company', 'media', 'tickets'])
            ->get();

        $newEvents = Event::with(['eventDates', 'category', 'city', 'currency', 'company', 'media', 'tickets'])
            ->new()
            ->get();

        $upcomingEvents = Event::with(['eventDates', 'category', 'city', 'currency', 'company', 'media', 'tickets'])
            ->upcoming()
            ->get();

        $pastEvents = Event::with(['eventDates', 'category', 'city', 'currency', 'company', 'media', 'tickets'])
            ->past()
            ->get();

        $weeklyEvents = Event::with(['eventDates', 'category', 'city', 'currency', 'company', 'media', 'tickets'])
            ->weekly()
            ->get();

        $monthlyEvents = Event::with(['eventDates', 'category', 'city', 'currency', 'company', 'media', 'tickets'])
            ->monthly()
            ->get();

        $eventCounts = [
            'all' => Event::latest()->count(),
            'new' => Event::new()->latest()->count(),
            'upcoming' => Event::upcoming()->latest()->count(),
            'past' => Event::past()->latest()->count(),
            'weekly' => Event::weekly()->latest()->count(),
            'monthly' => Event::monthly()->latest()->count(),
        ];

        return view('backend.events.index', compact(
            'allEvents',
            'newEvents',
            'upcomingEvents',
            'pastEvents',
            'weeklyEvents',
            'monthlyEvents',
            'eventCounts'
        ));
    }


    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $eventCategories = EventCategory::where('is_parent', true)->get();
        $cities = City::all();
        $currencies = Currency::all();
        $companies = Company::all();

        return view('backend.events.create', compact('eventCategories', 'cities', 'currencies', 'companies'));
    }

    /**
     * Store a newly created event in storage.
     *
     * @param StoreEventRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Store a newly created event in storage.
     *
     * @param StoreEventRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEventRequest $request)
    {
        try {
            DB::beginTransaction();

            // Prepare data for event creation
            $data = $request->validated();
            $data['facility'] = $request->has('facility') ? implode(',', $request->facility) : null;
            $data['is_active'] = true;

            // Create the event
            $event = Event::create($data);

            // Save media (poster and banner)
            if ($request->hasFile('poster') || $request->hasFile('banner')) {
                saveMedia($request, $event);
            }

            // Save tickets
            if ($request->has('ticket_type') && !empty(array_filter($request->ticket_type))) {
                foreach ($request->ticket_type as $index => $ticketType) {
                    if ($ticketType || $request->price[$index] || $request->quantity[$index]) {
                        $event->tickets()->create([
                            'ticket_type' => $ticketType ?: 'normal_price',
                            'price' => $request->price[$index] ?? 0,
                            'quantity' => $request->quantity[$index] ?? 0,
                            'qr_code' => rand(100000, 999999),
                        ]);
                    }
                }
            }

            // Save event dates (only save if at least one field has a value)
            if ($request->has('start_date') && is_array($request->start_date)) {
                foreach ($request->start_date as $index => $startDate) {
                    $endDate = $request->end_date[$index] ?? null;
                    $startTime = $request->start_time[$index] ?? null;
                    $endTime = $request->end_time[$index] ?? null;
                    
                    // Normalize time format to HH:MM:SS for database
                    if ($startTime && strlen($startTime) === 5) {
                        $startTime .= ':00';
                    }
                    if ($endTime && strlen($endTime) === 5) {
                        $endTime .= ':00';
                    }

                    // Only create if at least one date/time field has a value
                    if ($startDate || $endDate || $startTime || $endTime) {
                        $event->eventDates()->create([
                            'start_date' => $startDate ?: null,
                            'end_date' => $endDate,
                            'start_time' => $startTime ?: null,
                            'end_time' => $endTime ?: null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', __('Event created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()
                ->back()
                ->with('error', __('Error creating event: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show SMS invitation form for an event
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSmsForm(string $id)
    {
        try {
            $event = $this->getEventOrFail($id);
            return view('backend.events.send-sms', compact('event'));
        } catch (\Exception $e) {
            $this->logError('showSmsForm', $e);
            return redirect()->route('admin.events.index')->with('error', __('Event not found'));
        }
    }

    /**
     * Display the specified event.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        try {
            $event = Event::with(['eventDates', 'tickets', 'category', 'city', 'currency', 'company'])
                ->where('id', $id)
                ->firstOrFail();
            $event->increment('view_count');
            $event->load(['eventDates', 'tickets', 'category', 'city', 'currency', 'company']);
            return view('backend.events.show', compact('event'));
        } catch (\Exception $e) {
            $this->logError('show', $e);
            return redirect()->route('admin.events.index')->with('error', __('Event not found'));
        }
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        try {
            $event = Event::with(['eventDates', 'tickets', 'category', 'city', 'currency', 'company'])
                ->where('id', $id)
                ->firstOrFail();
            $event->load(['eventDates', 'tickets', 'category', 'city', 'currency', 'company']);
            $event->increment('view_count');
            $eventCategories = EventCategory::where('is_parent', true)->get();
            $cities = City::all();
            $currencies = Currency::all();
            $companies = Company::all();
            $eventSubCategories = EventCategory::where('parent_id', $event->category_id)->where('is_parent', false)->get();

            return view('backend.events.edit', compact('event', 'eventCategories', 'cities', 'currencies', 'companies', 'eventSubCategories'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->route('admin.events.index')->with('error', __('Event not found'));
        }
    }

    /**
     * Update the specified event in storage.
     *
     * @param UpdateEventRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEventRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $event = $this->getEventOrFail($id);
            $data = $request->validated();

            $data['facility'] = $request->has('facility') && !empty($request->facility) ? implode(',', $request->facility) : null;

            $event->update($data);

            if ($request->hasFile('poster') || $request->hasFile('banner') || $request->hasFile('exclusive_image')) {
                saveMedia($request, $event);
            }

            // تحديث التذاكر
            if ($request->has('ticket_type') && !empty(array_filter($request->ticket_type))) {
                $event->tickets()->delete(); // حذف التذاكر القديمة
                foreach ($request->ticket_type as $index => $ticketType) {
                    if ($ticketType || $request->price[$index] || $request->quantity[$index]) {
                        $event->tickets()->create([
                            'ticket_type' => $ticketType ?: 'normal_price',
                            'price' => $request->price[$index] ?? 0,
                            'quantity' => $request->quantity[$index] ?? 0,
                            'qr_code' => rand(100000, 999999),
                        ]);
                    }
                }
            }

            $event->eventDates()->delete(); // حذف التواريخ القديمة أولاً

            if ($request->has('start_date') && is_array($request->start_date)) {
                foreach ($request->start_date as $index => $startDate) {
                    $endDate = $request->end_date[$index] ?? null;
                    $startTime = $request->start_time[$index] ?? null;
                    $endTime = $request->end_time[$index] ?? null;
                    
                    // Normalize time format to HH:MM:SS for database
                    if ($startTime && strlen($startTime) === 5) {
                        $startTime .= ':00';
                    }
                    if ($endTime && strlen($endTime) === 5) {
                        $endTime .= ':00';
                    }

                    // Only create if at least one date/time field has a value
                    if ($startDate || $endDate || $startTime || $endTime) {
                        $event->eventDates()->create([
                            'start_date' => $startDate ?: null,
                            'end_date' => $endDate,
                            'start_time' => $startTime ?: null,
                            'end_time' => $endTime ?: null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', __('Event updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $this->logError('update', $e);
            return redirect()
                ->back()
                ->with('error', __('Error updating event: :message', ['message' => $e->getMessage()]));
        }
    }
    /**
     * Remove the specified event from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $event = $this->getEventOrFail($id);
            $event->delete();

            DB::commit();
            return redirect()->route('admin.events.index')->with('success', __('Event deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', __('Error deleting event'));
        }
    }

    /**
     * Get subcategories by category ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcategoriesByCategory(Request $request)
    {
        $subcategories = EventCategory::where('parent_id', $request->event_category_id)->where('is_parent', false)->get();

        return response()->json($subcategories);
    }

    /**
     * Export events to PDF.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function export(Request $request)
    {
        // Validate dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        // Get the events in the date range
        $events = Event::whereBetween('created_at', [$request->from, $request->to])->get();

        if ($events->isEmpty()) {
            return response()->json(['error' => 'No events found in the specified date range'], 404);
        }

        // Load the events into a view
        $pdf = Pdf::loadView('backend.events.export', compact('events'));

        // Return the PDF as a download response
        return $pdf->download('events.pdf');
    }

    /**
     * Upload gallery images for an event.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadGallery(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $event = $this->getEventOrFail($request->event_id);

            if ($request->hasFile('images')) {
                saveMedia($request, $event, 'images');
            }

            return response()->json(['message' => 'Images uploaded successfully!']);
        } catch (\Exception $e) {
            $this->logError('uploadGallery', $e);
            return response()->json(['error' => 'Error uploading images'], 500);
        }
    }

    /**
     * Helper method to fetch an event or throw exception.
     *
     * @param string $id
     * @return Event
     * @throws \Exception
     */
    private function getEventOrFail(string $id): Event
    {
        $event = Event::with(['eventDates', 'tickets', 'category', 'city', 'currency', 'company'])
            ->where('id', $id)
            ->first();

        if (!$event) {
            throw new \Exception('Event not found');
        }

        return $event;
    }

    /**
     * Helper method to log errors consistently.
     *
     * @param string $method
     * @param \Exception $e
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in EventController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }

    /**
     * Save tickets for an event.
     *
     * @param Event $event
     * @param Request $request
     */
    private function saveTickets(Event $event, Request $request)
    {
        for ($i = 0; $i < count($request->ticket_type); $i++) {
            $event->tickets()->create([
                'ticket_type' => $request->ticket_type[$i] ?? 'normal_price',
                'price' => $request->price[$i] ?? 0,
                'quantity' => $request->quantity[$i] ?? 0,
                'qr_code' => rand(100000, 999999),
            ]);
        }
    }

    /**
     * Save event dates for an event.
     *
     * @param Event $event
     * @param Request $request
     */
    private function saveEventDates(Event $event, Request $request)
    {
        foreach ($request->start_date as $key => $date) {
            $event->eventDates()->create([
                'start_date' => $date ?? null,
                'end_date' => $request->end_date[$key] ?? null,
                'start_time' => $request->start_time[$key] ?? null,
                'end_time' => $request->end_time[$key] ?? null,
            ]);
        }
    }

    /**
     * Toggle the active status of an event.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive(Request $request)
    {
        try {
            $event = Event::findOrFail($request->id);
            $event->is_active = !$event->is_active;
            $event->save();

            return response()->json([
                'success' => true,
                'is_active' => $event->is_active,
                'message' => $event->is_active ? 'Event activated successfully.' : 'Event deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating event status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the format of an event (online/offline).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFormat(Request $request)
    {
        try {
            $event = Event::findOrFail($request->id);
            $event->format = !$event->format;
            $event->save();

            return response()->json([
                'success' => true,
                'format' => $event->format,
                'message' => $event->format ? 'Event is now Online' : 'Event is now Offline',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating event format: ' . $e->getMessage()
            ], 500);
        }
    }

    public function soldOut()
    {
        $events = Event::whereHas('tickets', function ($query) {
            $query->where('quantity', '=', 0);
        })->with('tickets')->get();
        return view('backend.events.sold_out', compact('events'));
    }


    public function filter(Request $request)
    {
        $events = Event::with(['category', 'city', 'currency', 'media', 'tickets', 'eventDates', 'company'])
            ->whereBetween('created_at', [$request->from, $request->to])
            ->get();

        $html = view('backend.events.partials.events-rows', compact('events'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Send SMS invitation for an event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSmsInvitation(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'phone_number' => 'required|string'
            ]);

            $event = Event::findOrFail($request->event_id);
            $smsService = new SmsService();
            
            $result = $smsService->sendEventInvitation($event, $request->phone_number);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS sent successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to send SMS'
                ], 500);
            }
        } catch (\Exception $e) {
            $this->logError('sendSmsInvitation', $e);
            return response()->json([
                'success' => false,
                'message' => 'Error sending SMS: ' . $e->getMessage()
            ], 500);
        }
    }

}
