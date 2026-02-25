<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\EventDate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Constructor with permission middleware
     */
    public function __construct()
    {
        $this->middleware('permission:view orders', ['only' => ['index', 'show']]);
        $this->middleware('permission:create orders', ['only' => ['create', 'store']]);
        $this->middleware('permission:update orders', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete orders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of orders with search and filtering
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        
        // Get paginated orders
        $orders = Order::with(['user.media', 'event', 'ticket', 'eventDate'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Get statistics from all orders (not just paginated)
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'checked' => Order::where('status', 'checked')->count(),
            'exited' => Order::where('status', 'exited')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total' => Order::count(),
        ];
        
        return view('backend.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        try {
            $users = User::where('is_active', true)->select('id', 'first_name', 'last_name', 'email')->get();
            $events = Event::where('is_active', true)->with('eventDates', 'tickets')->get();
            
            return view('backend.orders.create', compact('users', 'events'));
        } catch (\Exception $e) {
            Log::error('Orders create error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the order creation form');
        }
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'event_id' => 'required|exists:events,id',
                'ticket_id' => 'required|exists:tickets,id',
                'event_date_id' => 'required|exists:event_dates,id',
                'quantity' => 'required|integer|min:1|max:10',
                'payment_method' => 'required|in:cash,credit_card,bank_transfer,paypal',
                'status' => 'required|in:pending,checked,exited,cancelled',
                'payment_status' => 'required|in:pending,completed,failed,refunded',
                'notes' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();

            // Calculate total amount
            $ticket = Ticket::findOrFail($validatedData['ticket_id']);
            $total = $ticket->price * $validatedData['quantity'];

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad((string)(Order::count() + 1), 6, '0', STR_PAD_LEFT);

            $order = Order::create([
                'user_id' => $validatedData['user_id'],
                'event_id' => $validatedData['event_id'],
                'ticket_id' => $validatedData['ticket_id'],
                'event_date_id' => $validatedData['event_date_id'],
                'order_number' => $orderNumber,
                'quantity' => $validatedData['quantity'],
                'total' => $total,
                'payment_amount' => $total,
                'payment_currency' => 'EGP', // أو من إعدادات النظام
                'payment_method' => $validatedData['payment_method'],
                'status' => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'payment_reference' => 'ADMIN-' . time(),
                'payment_response' => json_encode([
                    'created_by' => 'admin',
                    'created_at' => now(),
                    'notes' => $validatedData['notes'] ?? null
                ]),
            ]);

            DB::commit();

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order created successfully - Order Number: ' . $orderNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Orders store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while creating the order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(string $id)
    {
        try {
            $order = Order::with(['user', 'event', 'ticket', 'eventDate.event'])
                          ->findOrFail($id);

            return view('backend.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Orders show error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the order details');
        }
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(string $id)
    {
        try {
            $order = Order::with(['user', 'event', 'ticket', 'eventDate'])->findOrFail($id);
            $users = User::where('is_active', true)->select('id', 'first_name', 'last_name', 'email')->get();
            $events = Event::where('is_active', true)->with('eventDates', 'tickets')->get();

            return view('backend.orders.edit', compact('order', 'users', 'events'));
        } catch (\Exception $e) {
            Log::error('Orders edit error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the order edit form');
        }
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, string $id)
    {
        try {
            $order = Order::findOrFail($id);

            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1|max:10',
                'status' => 'required|in:pending,checked,exited,cancelled',
                'payment_status' => 'required|in:pending,completed,failed,refunded',
                'payment_method' => 'required|in:cash,credit_card,bank_transfer,paypal',
                'notes' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();

            // Recalculate total if quantity changed
            if ($order->quantity !== $validatedData['quantity']) {
                $ticket = $order->ticket;
                $newTotal = $ticket->price * $validatedData['quantity'];
                $validatedData['total'] = $newTotal;
                $validatedData['payment_amount'] = $newTotal;
            }

            // Update payment response with notes
            $currentResponse = $order->payment_response;
            if (is_string($currentResponse)) {
                $paymentResponse = json_decode($currentResponse, true) ?? [];
            } elseif (is_array($currentResponse)) {
                $paymentResponse = $currentResponse;
            } else {
                $paymentResponse = [];
            }
            $paymentResponse['updated_by'] = 'admin';
            $paymentResponse['updated_at'] = now();
            $paymentResponse['notes'] = $validatedData['notes'] ?? null;
            $validatedData['payment_response'] = json_encode($paymentResponse);

            unset($validatedData['notes']); // Remove notes from direct update

            $order->update($validatedData);

            DB::commit();

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Orders update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::findOrFail($id);
            
            // Check if order can be deleted
            if ($order->status === 'exited' && $order->payment_status === 'completed') {
                return back()->with('error', 'Cannot delete a completed and paid order');
            }

            DB::beginTransaction();
            $order->delete();
            DB::commit();

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Orders destroy error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the order');
        }
    }

    /**
     * Export orders to Excel/CSV
     */
    public function export(Request $request)
    {
        try {
            // This will be applied later with the export library
                return back()->with('info', 'Export feature is under development');
        } catch (\Exception $e) {
            Log::error('Orders export error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while exporting the data');
        }
    }
}
