<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Models\Order;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\FirebaseService;
use App\Models\Notification;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\SuccessResource;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function checkoutOrder(Request $request, OrderService $orderService)
    {
        $request->validate([
            'payment_method'                => 'required|in:default,value,souhoola',
            'ticket_id'                     => 'required|exists:tickets,id',
            'event_id'                      => 'required|exists:events,id',
            'quantity'                      => 'required|integer|min:1',
            'total_price'                   => 'required|numeric|min:0',
            'date_id'                       => ['required', Rule::exists('event_dates', 'id')->where('event_id', $request->event_id)],
        ]);

        try {
            $paymentUrl = $orderService->createOrder($request);

            $user = auth()->user();
            $firebaseToken = $user->fcm_token;

            if ($firebaseToken) {
                $title = 'Order Confirmation';
                $message = 'Your order has been successfully created';
                $firebaseService = new FirebaseService();
                $firebaseService->sendNotificationToUser($firebaseToken, $title, $message);

                Log::info('Firebase notification sent successfully.');
            }

            return new SuccessResource([
                'message'               => 'Order created successfully',
                'payment_url'           => $paymentUrl,
            ]);
        } catch (\Exception $e) {
            return new ErrorResource([
                'message'               => $e->getMessage(),
                'line'                  => $e->getLine(),
                'file'                  => $e->getFile(),
            ]);
        }
    }

    public function updateOrderStatus(Request $request)
    {
        try {
            Log::info('PayMob API Callback Data: ', $request->all());

            $paymentSuccess = filter_var($request->input('success'), FILTER_VALIDATE_BOOLEAN);
            $merchantOrderId = $request->input('order');

            if (!$merchantOrderId) {
                return response()->json(['error' => 'Merchant Order ID not found'], 400);
            }

            $order = Order::where('order_number', $merchantOrderId)->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $newStatus = $paymentSuccess ? 'checked' : 'cancelled';

            if (!in_array($newStatus, ['pending', 'checked', 'exited', 'cancelled'])) {
                return response()->json(['error' => 'Invalid status update'], 400);
            }

            $order->update(['status' => $newStatus]);

            if ($newStatus === 'checked') {
                try {
                    Mail::to($order->user->email)->send(new OrderMail($order));
                } catch (\Exception $e) {
                    Log::error("Failed to send email for order {$order->id}: " . $e->getMessage());
                }
            }

            return response()->json([
                'message' => $paymentSuccess ? 'Payment successful, order updated' : 'Payment failed, order cancelled',
                'order_status' => $newStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in updateOrderStatus: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
