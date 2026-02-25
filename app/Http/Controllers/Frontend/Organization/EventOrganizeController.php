<?php

namespace App\Http\Controllers\Frontend\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Sponsor\StoreSponsorRequest;
use App\Http\Requests\Frontend\Event\{BankEventRequest,
    CachEventRequest,
    Setup2EventRequest,
    Setup3EventRequest,
    Setup4EventRequest,
    StoreEventRequest,
    UpdateEventRequest,
    UpdateSetup2EventRequest,
    UpdateSetup3EventRequest,
    UpdateSetup4EventRequest,
    UploadGallery};
use App\Models\{
    AdFee,
    City,
    Country,
    Currency,
    Event,
    EventCategory,
    Notification,
    PaymentMethod,
    Setting,
    Order,
    Company,
    FromServayHR,
    User,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Log,
};

class EventOrganizeController extends Controller
{

    public function getCities(Request $request)
    {

        $cities = Country::find($request->country_id)->cities;

        return response()->json($cities);
    }

    public function index()
    {
        $setting = Setting::first();
        $categories = EventCategory::where('parent_id', null)->get();
        $currencies = Currency::get();

        return view('Frontend.organization.events.create', compact('setting', 'categories', 'currencies'));
    }


    public function store(StoreEventRequest $request)
    {

        try {
            DB::beginTransaction();
            $event = Event::create($request->merge([
                'company_id' => auth()->guard('company')->user()->id,
            ])->all());

            if (count($request->files) > 0) {
                saveMedia($request, $event);
            }

            foreach ($request->start_date as $key => $date) {
                $event->eventDates()->create([
                    'start_date' => $date,
                    'end_date' => $request->end_date[$key],
                    'start_time' => $request->start_time[$key],
                    'end_time' => $request->end_time[$key],
                ]);
            }

            DB::commit();
            session()->flash('success', 'Event created successfully');
            return redirect()->route('create_event_setup2');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('error')->error('EventOrganizeController@store Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function setup2()
    {
        $countries = Country::get();

        return view('Frontend.organization.events.create_setup2', compact('countries'));
    }

    public function storeSetup2(Setup2EventRequest $request)
    {
        try {
            DB::beginTransaction();
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);
            $event->update($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Event setup 2 created successfully');
            return redirect()->route('create_event_setup3');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeSetup2 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function setup3()
    {
        $currencies = Currency::get();

        return view('Frontend.organization.events.create_setup3', compact('currencies'));
    }

    public function storeSetup3(Setup3EventRequest $request)
    {
        try {
            DB::beginTransaction();
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);
            $event->update($request->safe()->all());
            if ($event) {
                for ($i = 0; $i < count($request->ticket_type); $i++) {
                    $event->tickets()->create([
                        'ticket_type' => $request->ticket_type[$i],
                        'price' => $request->price[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                }
            }

            DB::commit();
            session()->flash('success', 'Event setup 3 created successfully');
            return redirect()->route('create_event_setup4', $event->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeSetup3 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function setup4($id)
    {
        $countries = Country::get();

        $payment_method = PaymentMethod::where('event_id', $id)->where('company_id', auth()->guard('company')->user()->id)->first();

        return view('Frontend.organization.events.create_setup4', compact('countries', 'payment_method'));
    }

    //store checkout
    public function storeCheckout(Request $request)
    {
        try {
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);
            $event->paymentMethods()->create($request->safe()->merge([
                'type' => $request->type,
                'event_id' => $event->id,
                'company_id' => auth()->guard('company')->user()->id,
            ])->all());

            session()->flash('success', 'Event setup 4 created successfully');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeCheckout Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function storeSetup4(Setup4EventRequest $request)
    {
        try {
            DB::beginTransaction();
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);
            $event->paymentMethods()->create($request->safe()->merge([
                'type' => $request->type,
                'event_id' => $event->id,
                'company_id' => auth()->guard('company')->user()->id,
            ])->all());

            DB::commit();
            session()->flash('success', 'Event setup 4 created successfully');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeSetup4 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function storeCache(CachEventRequest $request)
    {
        try {
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);
            $event->paymentMethods()->create($request->safe()->merge([
                'type' => $request->type,
                'event_id' => $event->id,
                'company_id' => auth()->guard('company')->user()->id,
            ])->all());

            session()->flash('success', 'Event setup 4 created successfully');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeCache Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function storeTransferBank(BankEventRequest $request)
    {
        try {
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);
            $event->paymentMethods()->create($request->safe()->merge([
                'type' => $request->type,
                'event_id' => $event->id,
                'company_id' => auth()->guard('company')->user()->id,
            ])->all());

            session()->flash('success', 'Event setup 4 created successfully');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeTransferBank Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function setup5()
    {
        $latest_event = auth()->guard('company')->user()->events->last();

        return view('Frontend.organization.events.create_setup5', compact('latest_event'));
    }

    public function myEvents()
    {
        $events = auth()->guard('company')->user()->events->sortByDesc('created_at');
            
        //createnotification for event
        Notification::create([
            'title'             => 'Event Created',
            'message'           => 'Your event has been created successfully',
            'company_id'        => auth()->guard('company')->user()->id,
            'status'            => 'sent',
        ]);

        return view('Frontend.organization.account.my_events', compact('events'));
    }

    public function orders(Request $request)
    {
        $company = auth()->guard('company')->user();
        
        // Get all event IDs for this company
        $eventIds = $company->events()->pluck('id')->toArray();
        
        // Debug: Log event IDs
        Log::info('Organization Orders - Event IDs', ['event_ids' => $eventIds, 'company_id' => $company->id]);
        
        // If no events, return empty result
        if (empty($eventIds)) {
            $orders = FromServayHR::whereRaw('1 = 0')->paginate(10); // Empty pagination
            $orderNumbersMap = [];
            $availableStatuses = [
                'Completed'     => 'Completed',
                'Pending'       => 'Pending',
                'Checked In'    => 'Checked In',
                'Cancelled'     => 'Cancelled',
            ];
            return view('Frontend.organization.account.orders', compact('orders', 'orderNumbersMap', 'availableStatuses'));
        }
        
        // Build query with filters
        $query = FromServayHR::whereIn('event_id', $eventIds)->with('event:id,name');
        
        // Debug: Log total count before filters
        $totalBeforeFilters = $query->count();
        Log::info('Organization Orders - Total before filters', ['count' => $totalBeforeFilters]);
        
        // Apply filters
        if ($request->filled('ticket_type')) {
            $query->where('ticket_type', $request->string('ticket_type'));
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }
        
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }
        
        // Get paginated results with query string for filters
        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // Debug: Log results count
        Log::info('Organization Orders - Results', [
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'count' => $orders->count()
        ]);
        
        // Get order numbers mapping (email => order_number) for display
        $orderNumbersMap = [];
        $orderEmails = $orders->pluck('email')->unique()->toArray();
        if (!empty($orderEmails)) {
            $userIds = User::whereIn('email', $orderEmails)->pluck('id')->toArray();
            if (!empty($userIds)) {
                $ordersData = Order::whereIn('event_id', $eventIds)
                    ->whereIn('user_id', $userIds)
                    ->select('user_id', 'order_number', 'event_id')
                    ->get();
                
                foreach ($ordersData as $orderData) {
                        $user = User::find($orderData->user_id);
                    if ($user) {
                        $orderNumbersMap[$user->email] = $orderData->order_number;
                    }
                }
            }
        }
        
        // Available statuses for filter dropdown
        $availableStatuses = [
            'Completed'     => 'Completed',
            'Pending'       => 'Pending',
            'Checked In'    => 'Checked In',
            'Cancelled'     => 'Cancelled',
        ];
        
        return view('Frontend.organization.account.orders', compact('orders', 'orderNumbersMap', 'availableStatuses'));
    }
    
    /**
     * Export orders to CSV
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function exportOrders(Request $request)
    {
        try {
            $company = auth()->guard('company')->user();
            $eventIds = $company->events()->pluck('id')->toArray();
            
            if (empty($eventIds)) {
                return redirect()->back()->with('error', 'No events found for export');
            }
            
            // Build query with same filters as orders page
            $query = FromServayHR::whereIn('event_id', $eventIds)->with('event:id,name');
            
            // Apply filters
            if ($request->filled('ticket_type')) {
                $query->where('ticket_type', $request->string('ticket_type'));
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->string('status'));
            }
            
            if ($request->filled('from')) {
                $query->whereDate('created_at', '>=', $request->date('from'));
            }
            
            if ($request->filled('to')) {
                $query->whereDate('created_at', '<=', $request->date('to'));
            }
            
            $orders = $query->orderBy('created_at', 'desc')->get();
            
            // Get order numbers mapping
            $orderNumbersMap = [];
            $orderEmails = $orders->pluck('email')->unique()->toArray();
            if (!empty($orderEmails)) {
                $userIds = User::whereIn('email', $orderEmails)->pluck('id')->toArray();
                if (!empty($userIds)) {
                    $ordersData = Order::whereIn('event_id', $eventIds)
                        ->whereIn('user_id', $userIds)
                        ->select('user_id', 'order_number', 'event_id')
                        ->get();
                    
                    foreach ($ordersData as $orderData) {
                        $user = User::find($orderData->user_id);
                        if ($user) {
                            $orderNumbersMap[$user->email] = $orderData->order_number;
                        }
                    }
                }
            }
            
            $fileName = 'orders_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];
            
            $callback = function () use ($orders, $orderNumbersMap) {
                $handle = fopen('php://output', 'wb');
                
                // Add BOM for UTF-8 Excel compatibility
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($handle, [
                    'ID',
                    'Event Name',
                    'First Name',
                    'Last Name',
                    'Email',
                    'Phone',
                    'Job Title',
                    'Organization',
                    'Ticket Type',
                    'Attendee Type',
                    'Mentorship Track',
                    'Status',
                    'Order Number',
                    'Created At',
                ]);
                
                // Data rows
                foreach ($orders as $order) {
                    // Determine ticket display
                    $ticketDisplay = 'N/A';
                    if ($order->ticket_type === 'attendee' && $order->attendee_type) {
                        $ticketDisplay = ucfirst($order->attendee_type);
                    }
                    if ($order->attendee_type === 'mentorship' && $order->mentorship_track) {
                        $ticketDisplay = $order->mentorship_track;
                    }
                    if ($order->ticket_type === 'startups') {
                        $ticketDisplay = 'Startups';
                    }
                    
                    fputcsv($handle, [
                        $order->id,
                        $order->event?->name ?? 'N/A',
                        $order->first_name ?? '',
                        $order->last_name ?? '',
                        $order->email ?? '',
                        $order->phone ?? '',
                        $order->job_title ?? '',
                        $order->organization ?? '',
                        $order->ticket_type ?? '',
                        $order->attendee_type ?? '',
                        $order->mentorship_track ?? '',
                        $order->status ?? 'Completed',
                        $orderNumbersMap[$order->email] ?? 'N/A',
                        $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '',
                    ]);
                }
                
                fclose($handle);
            };
            
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::channel('error')->error('EventOrganizeController@exportOrders Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Export failed. Please try again.');
        }
    }
    
    public function deleteOrder(Request $request, $id)
    {
        try {
            $company = auth()->guard('company')->user();
            $eventIds = $company->events()->pluck('id')->toArray();
            
            if (empty($eventIds)) {
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No events found'
                    ], 404);
                }
                session()->flash('error', 'No events found');
                return redirect()->back();
            }
            
            // Find the order in FromServayHR table
            $order = FromServayHR::whereIn('event_id', $eventIds)
                ->where('id', $id)
                ->first();
            
            if (!$order) {
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order not found'
                    ], 404);
                }
                session()->flash('error', 'Order not found');
                return redirect()->back();
            }
            
            DB::beginTransaction();
            
            // Delete startup file if exists
            if ($order->startup_file) {
                $filePath = storage_path('app/public/' . $order->startup_file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $order->delete();
            DB::commit();
            
            // Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully'
                ]);
            }
            
            session()->flash('success', 'Order deleted successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@deleteOrder Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting order: ' . $e->getMessage()
                ], 500);
            }
            
            session()->flash('error', 'Error deleting order');
            return redirect()->back();
        }
    }

    // Edit Evetn
    public function editEvent($id)
    {
        $event = Event::find($id);
        $setting = Setting::first();
        $categories = EventCategory::where('parent_id', null)->get();
        $currencies = Currency::get();
        $sub_categories = EventCategory::where('parent_id', $event->category_id)->get();

        return view('Frontend.organization.events.edit', compact('setting', 'categories', 'event', 'currencies', 'sub_categories'));
    }

    public function updateEvent(UpdateEventRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $event = Event::find($id);
            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }
            $event->update($request->safe()->merge([
                'company_id' => auth()->guard('company')->user()->id,
            ])->all());

            if ($request->hasFile('poster')) {
                saveMedia($request, $event);
            }

            //update event dates
            $event->eventDates()->delete();
            foreach ($request->start_date as $key => $date) {
                $event->eventDates()->create([
                    'start_date' => $date,
                    'end_date' => $request->end_date[$key],
                    'start_time' => $request->start_time[$key],
                    'end_time' => $request->end_time[$key],
                ]);
            }


            DB::commit();
            return redirect()->route('organization.edit_setup2', $event->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateEvent Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function editSetup2($id)
    {
        try {
            DB::beginTransaction();
            $event = Event::find($id);

            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }
            $cities = City::get();
            $countries = Country::get();

            DB::commit();
            return view('Frontend.organization.events.edit_setup2', compact('event', 'cities', 'countries'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateSetup2 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function updateSetup2(UpdateSetup2EventRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $event = Event::find($id);

            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }
            $event->update($request->safe()->all());

            DB::commit();
            return redirect()->route('organization.edit_setup3', $event->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateSetup2 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function editSetup3($id)
    {
        try {
            DB::beginTransaction();

            $event = Event::find($id);

            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }

            DB::commit();
            return view('Frontend.organization.events.edit_setup3', compact('event'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateSetup3 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function updateSetup3(UpdateSetup3EventRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $event = Event::find($id);
            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }

            //update event tickets
            $event->tickets()->delete();
            for ($i = 0; $i < count($request->ticket_type); $i++) {
                $event->tickets()->create([
                    'ticket_type' => $request->ticket_type[$i],
                    'price' => $request->price[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }
            $countries = Country::get();
            $cities = City::get();
            DB::commit();
            return view('Frontend.organization.events.edit_setup4', compact('event', 'countries', 'cities'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateSetup3 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function editSetup4($id)
    {
        try {
            DB::beginTransaction();

            $event = Event::find($id);

            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }
            $countries = Country::get();
            $payment_method = PaymentMethod::where('event_id', $id)->where('company_id', auth()->guard('company')->user()->id)->first();

            DB::commit();
            return view('Frontend.organization.events.edit_setup4', compact('event', 'countries', 'payment_method'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateSetup4 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function updateSetup4(UpdateSetup4EventRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $event = Event::where('id', $id)->first();
            if (!$event) {
                session()->flash('error', 'Event not found');
                return back();
            }
            $event->paymentMethods()->update($request->safe()->all());

            DB::commit();
            return redirect()->route('organization.edit_setup5', $event->id)->with('success', 'Event updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@updateSetup4 Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    public function editSetup5($id)
    {
        $event = Event::find($id);

        return view('Frontend.organization.events.edit_setup5', compact('event'));
    }

    public function subCategories(Request $request)
    {

        $sub_categories = EventCategory::where('parent_id', $request->category_id)->get();

        return response()->json($sub_categories);
    }

    public function search(Request $request)
    {
        $events = Event::where('name', 'like', '%' . $request->search . '%')->get();

        response()->json($events);
    }

    public function uploadGallery(Request $request , $id)
    {
        $event = Event::find($id);

        return view('Frontend.organization.account.upload_gallery', compact('event'));
    }

    public function storeGallery(UploadGallery $request, $id)
    {
        try {
            $event = Event::find($id);
            if ($request->hasFile('gallery')) {
                saveMedia($request, $event);
            }

            $gallery = $event->media->where('name', 'gallery');

            return response()->json(['success' => 'Gallery uploaded successfully' , 'gallery' => $gallery]);
        } catch (\Exception $e) {
            Log::channel('error')->error('EventOrganizeController@storeGallery Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return response()->json(['error' => 'Something went wrong']);
        }
    }

    public function deleteGallery(Request $request)
    {
        dd($request->all());
        try {
            DB::beginTransaction();
            $event = Event::find($request->event_id);

            $event->clearMediaCollection('gallery');
            $event->addMediaFromRequest('gallery')->toMediaCollection('gallery');

            DB::commit();
            return response()->json(['success' => 'Gallery uploaded successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@deleteGallery Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return response()->json(['error' => 'Something went wrong']);
        }
    }


}
