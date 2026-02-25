<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'date_id' => 'required|exists:event_dates,id',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'event_id' => $request->event_id,
            'ticket_id' => $request->ticket_id,
            'event_date_id' => $request->date_id,
            'status' => 'pending',
        ]);

        return new SuccessResource([
            'message' => 'Order created successfully',
            'order_id' => $order->order_number,
        ]);
    } // end of createOrder method

    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())->paginate(config('app.pagination'));
        return new SuccessResource($orders);
    } // end of myOrders method

    public function myOrder($order_number)
    {
        
        $order = Order::where('user_id', auth()->id())
            ->where('order_number', $order_number)
            ->first();
        if (!$order) {
            return new ErrorResource('Order not found');
        }
        return new SuccessResource($order);
    } // end of myOrder method
}
