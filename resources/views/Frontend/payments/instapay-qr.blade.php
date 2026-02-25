@extends('Frontend.layouts.master')

@section('title', 'InstaPay Payment')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="h3 mb-4">Pay with InstaPay</h1>

                <p class="mb-2">
                    Total Amount:
                    <strong>{{ number_format($order->payment_amount ?? $order->total, 2) }} {{ $setting->currency }}</strong>
                </p>
                <p class="mb-4">
                    Order Number:
                    <strong>{{ $order->order_number ?? $order->id }}</strong>
                </p>

                <div class="bg-white d-inline-block p-4 rounded shadow-sm">
                    {!! $qrSvg !!}
                </div>

                <p class="mt-4 text-muted" style="font-size: 0.9rem;">
                    Open InstaPay app or your bank app, scan the QR code, then confirm the payment with the amount and order number shown above.
                </p>
            </div>
        </div>
    </div>
@endsection


