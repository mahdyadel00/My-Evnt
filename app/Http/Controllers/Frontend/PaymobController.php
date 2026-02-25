<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Order\StoreOrderRequest;
use App\Mail\OrderMail;
use App\Models\Event;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Ticket;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PaymobController extends Controller
{
    public function credit(StoreOrderRequest $request, OrderService $orderService)
    {
        try {
            // Normalize payment_method on the request for downstream usage
            $request->merge(['payment_method' => $request->payment_method]);

            $paymentResult = $orderService->createOrder($request);
            $paymentUrl    = $paymentResult['url'] ?? null;

            // Detect free / zero-amount orders (e.g. free events or fully discounted)
            $totalAmount = (float) $request->input('total_price', 0);

            if ($totalAmount <= 0 || $paymentUrl === null) {
                // No online payment needed – go directly to ticket confirmation
                return redirect()->route('ticket_confirmation', ['event_id' => $request->event_id]);
            }

            // Paid orders – redirect to payment gateway / InstaPay QR URL
            return redirect()->to($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Payment creation error', [
                'message'      => $e->getMessage(),
                'trace'        => $e->getTraceAsString(),
                'request_data' => $request->except(['password', '_token']),
            ]);
            
            $errorMessage = $e->getMessage();
            
            // Provide user-friendly error messages
            if (str_contains($errorMessage, 'email')) {
                $errorMessage = 'Please provide a valid email address.';
            } elseif (str_contains($errorMessage, 'payment key')) {
                $errorMessage = 'An error occurred while creating the payment link. Please try again or contact the technical support.';
            } elseif (str_contains($errorMessage, 'payment order')) {
                $errorMessage = 'An error occurred while creating the payment order. Please try again.';
            }
            
            session()->flash('error', $errorMessage);
            return back()->withInput();
        }
    }

    public function callback(Request $request)
    {
        Log::info('PayMob Callback Data: ', $request->all());

        try {
            $success = $request->input('success');
            $metadata = $request->input('obj.metadata') ?? [];
            $context = $metadata['context'] ?? null;
            $merchantOrderId = $request->order;

            // Handle sponsor payments
            if ($context === 'sponsor') {
                $eventId = $metadata['event_id'] ?? null;
                $event = Event::find($eventId);

                if (!$event) {
                    Log::error('Event not found for sponsor payment: ' . $eventId);
                    return response()->json(['error' => 'Event not found'], 404);
                }

                $event->update(['ad_fee_status' => $success === 'true' ? 'paid' : 'failed']);

                if ($success === 'true') {
                    session()->flash('success', 'Ad fee payment completed successfully');
                    return redirect()->route('organization.events.index');
                } else {
                    session()->flash('error', 'Payment failed');
                    return back();
                }
            }

            // Handle ticket payments
            if (!$merchantOrderId) {
                Log::error('Merchant Order ID not found in callback');
                return response()->json(['error' => 'Merchant Order ID not found'], 400);
            }

            $order = Order::where('order_number', $merchantOrderId)->first();
            if (!$order) {
                Log::error('Order not found with order_number: ' . $merchantOrderId);
                return response()->json(['error' => 'Order not found'], 404);
            }

            DB::beginTransaction();

            // Update order status based on payment success
            $orderStatus = $success === 'true' ? 'completed' : 'cancelled';
            $order->update(['status' => $orderStatus]);

            if ($success === 'true') {
                // Send confirmation email
                try {
                    if ($order->user && $order->user->email) {
                        Mail::to($order->user->email)->send(new OrderMail($order));
                    }
                } catch (\Exception $e) {
                    Log::error('Email sending failed: ' . $e->getMessage());
                    // Don't fail the whole process if email fails
                }

                DB::commit();

                // Redirect to ticket confirmation
                return redirect()->route('ticket_confirmation', ['event_id' => $order->event_id])
                    ->with('success', 'Payment completed successfully! Your ticket is ready.');
            } else {
                DB::commit();
                session()->flash('error', 'Payment failed. Please check your card details and try again.');
                return redirect()->route('checkout_user', ['event_date_id' => $order->event_date_id]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback processing error: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while processing your payment.');
            return back();
        }
    }

    public function ticketConfirmation($event_id)
    {
        try {
            $event = Event::with(['tickets', 'city', 'eventDates'])->find($event_id);

            if (!$event) {
                session()->flash('error', 'Event not found');
                return redirect()->route('home');
            }

            // Get the latest completed order for this event
            $order = Order::with(['user', 'tickets'])
                ->where('event_id', $event_id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            $setting = Setting::first();

            return view('Frontend.events.ticket_confirmation', compact('order', 'event', 'setting'));

        } catch (\Exception $e) {
            Log::error('Ticket confirmation error: ' . $e->getMessage());
            session()->flash('error', 'Unable to load ticket confirmation.');
            return redirect()->route('home');
        }
    }
}
