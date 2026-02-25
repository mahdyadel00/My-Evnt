<!DOCTYPE html>
<html>
<head>
    <title>Your Ticket Booking is Confirmed!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
        table {
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
<h1>Your Ticket Booking is Confirmed!</h1>
<p>Thank you for booking tickets for {{ $order->event->name }}. Your booking details are shown below for your reference:</p>

<table border="1">
    <tr>
        <th>Order Number</th>
        <td>{{ $order->order_number }}</td>
    </tr>
    <tr>
        <th>Event Date</th>
        <td>{{ $order->event?->start_date }}</td>
    </tr>
    
    <tr>
        <th>Quantity</th>
        <td>{{ $order->quantity ?? 1 }}</td>
    </tr>
    <tr>
        <th>Total Amount</th>
        <td>{{ $order->total }} {{ $order->event->currency?->code ?? 'EGP' }}</td>
    </tr>
    <tr>
        <th>Booking Date</th>
        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
    </tr>
    <tr>
        <th>Booking Status</th>
        <td>{{ ucfirst($order->status) }}</td>
    </tr>
    <tr>
        <th>View Ticket</th>
        <td>
            <a href="{{ route('ticket_confirmation', ['event_id' => $order->event_id]) }}" target="_blank">
                Click here to view your ticket
            </a>
        </td>
    </tr>
</table>

<p><strong>Important Information:</strong></p>
<ul style="text-align: left; display: inline-block;">
    <li>Please bring a printed copy of this email or show it on your mobile device at the entrance.</li>
    <li>Gates open at {{ $order->event->start_time }}</li>
    <li>For more information, please visit our website: {{ config('app.url') }}</li>
</ul>

<p>We look forward to seeing you there!</p>
</body>
</html>
