@extends('Frontend.layouts.master')
@section('title', 'Your Ticket')
@section('content')
<style>
    .ticket-section-page-new {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px 20px;
        width: 100%;
        margin: 0 auto;
    }

    .ticket-section-page-new .page-container-new {
        width: 100%;
        max-width: 400px;
    }

    .page-container-ticket-new {
        margin-bottom: 20px;
    }

    .ticket-details-new {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
    }

    .ticket-details-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }

    .ticket-details-new h2 {
        margin-top: 0;
        color: #4a4a4a;
        border-bottom: 2px solid #eaeaea;
        padding-bottom: 10px;
        font-weight: 600;
        font-size: 22px;
    }

    .ticket-details-new p {
        margin: 8px 0;
        font-size: 14px;
        color: #555;
        line-height: 1.4;
    }

    .ticket-details-new strong {
        color: #333;
        font-weight: 600;
    }

    .ticket-container-new {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        padding: 10px 20px;
        width: 100%;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .ticket-header-new {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        text-align: center;
        letter-spacing: 0.5px;
    }

    .qr-container-new {
        display: flex;
        justify-content: center;
        padding: 8px 0;
        width: 100%;
    }

    .qr-code-new {
        width: 150px;
        height: 150px;
        border: 1px solid #eaeaea;
        padding: 8px;
        background-color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        transition: transform 0.3s ease;
    }

    .qr-code-new:hover {
        transform: scale(1.03);
    }

    .ticket-info-new {
        padding: 0 5px;
    }

    .ticket-label-new {
        font-size: 12px;
        color: #777;
        margin-bottom: 3px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .ticket-value-new {
        font-size: 18px;
        font-weight: 600;
        margin: 3px 0 12px;
        color: #333;
        letter-spacing: 0.5px;
    }

    .event-name-new {
        font-size: 16px;
        font-weight: 700;
        margin: 4px 0;
        color: #444;
        line-height: 1.4;
    }

    .event-time-new {
        margin-top: 5px;
        font-size: 14px;
        color: #555;
    }

    .buttons-new {
        margin-top: 20px;
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .btn-new {
        padding: 10px 20px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 14px;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-home-new {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .btn-print-new {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
    }

    /* Print-specific styles */
    @media print {
        .ticket-section-page-new {
            padding: 0;
            background: none;
            display: block;
        }

        .page-container-new {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .buttons-new,
        .ticket-details-new {
            display: none;
        }

        .qr-code-new {
            box-shadow: none;
        }

        .ticket-container-new::before {
            display: none;
        }

        .ticket-header-new {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .ticket-value-new {
            font-size: 16px;
        }

        .event-name-new {
            font-size: 14px;
        }

        .event-time-new {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .ticket-container-new,
        .ticket-details-new {
            padding: 12px;
        }

        .buttons-new {
            flex-direction: column;
            gap: 8px;
        }

        .btn-new {
            width: 100%;
        }
    }
</style>

<!-- start ticket -->
<section class="ticket-section-page-new">
    <div class="page-container-new">
        <div class="page-container-ticket-new">
            <div class="ticket-details-new">
                <h2>Event Details</h2>
                <p><strong>Event:</strong> {{ $event->name }}</p>
                <p><strong>Date:</strong>
                    {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                        ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('D M d, Y')
                        : \Carbon\Carbon::parse($event->start_date)->format('D M d, Y') }}
                </p>
                <p><strong>Time:</strong>
                    {{ optional(\Carbon\Carbon::make($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_time ? $event->eventDates->first()->start_time : $event->start_time))->format('g:i A') }} -
                    {{ optional(\Carbon\Carbon::make($event->eventDates->isNotEmpty() && $event->eventDates->first()->end_time ? $event->eventDates->first()->end_time : $event->end_time))->format('g:i A') }}
                </p>
                @if(isset($order) && $order->tickets->isNotEmpty())
                    <p><strong>Ticket Type:</strong> {{ $order->tickets->first()->ticket_type ?? 'General' }}</p>
                    <p><strong>Ticket Code:</strong> {{ $order->tickets->first()->ticket_code ?? $order->id }}</p>
                @else
                    <p><strong>Ticket Type:</strong> {{ $event->tickets->first()->ticket_type ?? 'General' }}</p>
                    <p><strong>Ticket Code:</strong> {{ $event->tickets->first()->ticket_code ?? 'TKT' . rand(100000, 999999) }}</p>
                @endif
                @if($event->format == 'online')
                    <p><strong>Location:</strong> Online</p>
                @else
                    <p><strong>Location:</strong> {{ $event->location }}, {{ $event->area ?? $event->city?->name }}</p>
                @endif
                @if(isset($order))
                    <p><strong>Attendee:</strong> {{ $order->user?->user_name ?? $order->first_name . ' ' . $order->last_name }}</p>
                @endif
            </div>

            <div class="ticket-container-new">
                <div class="ticket-header-new">Your Ticket</div>

                <div class="qr-container-new">
                    @if(isset($order) && $order->tickets->isNotEmpty() && $order->tickets->first()->qr_code)
                        {!! QrCode::size(150)->generate($order->tickets->first()->qr_code) !!}
                    @elseif(isset($event->tickets[0]->qr_code))
                        {!! QrCode::size(150)->generate($event->tickets[0]->qr_code) !!}
                    @else
                        {!! QrCode::size(150)->generate('TICKET-' . ($order->id ?? rand(100000, 999999))) !!}
                    @endif
                </div>

                <div class="ticket-info-new">
                    <div class="ticket-label-new">TICKET CODE</div>
                    <h3 class="ticket-value-new">
                        @if(isset($order) && $order->tickets->isNotEmpty())
                            {{ $order->tickets->first()->ticket_code ?? $order->id }}
                        @else
                            {{ $event->tickets->first()->ticket_code ?? 'TKT' . rand(100000, 999999) }}
                        @endif
                    </h3>
                </div>

                <div class="ticket-info-new">
                    <div class="ticket-label-new">TICKET TYPE</div>
                    <h3 class="ticket-value-new">
                        @if(isset($order) && $order->tickets->isNotEmpty())
                            {{ $order->tickets->first()->ticket_type ?? 'General' }}
                        @else
                            {{ $event->tickets->first()->ticket_type ?? 'General' }}
                        @endif
                    </h3>
                </div>

                <div class="ticket-info-new">
                    <div class="ticket-label-new">EVENT</div>
                    <div class="event-name-new">{{ $event->name }}</div>
                    <div class="event-time-new">
                        {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                            ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('D M d, Y')
                            : \Carbon\Carbon::parse($event->start_date)->format('D M d, Y') }}
                        {{ optional(\Carbon\Carbon::make($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_time ? $event->eventDates->first()->start_time : $event->start_time))->format('g:i A') }} -
                        {{ optional(\Carbon\Carbon::make($event->eventDates->isNotEmpty() && $event->eventDates->first()->end_time ? $event->eventDates->first()->end_time : $event->end_time))->format('g:i A') }}
                    </div>
                </div>
            </div>

            <div class="buttons-new">
                <a href="{{ route('home') }}" class="btn-new btn-home-new">Return to Home</a>
                <button class="btn-new btn-print-new" id="printBtn">Print Ticket</button>
            </div>
        </div>
    </div>
</section>
<!-- end ticket -->

@push('js')
<script>
    document.getElementById("printBtn").addEventListener("click", function () {
        window.print();
    });
</script>
@endpush
@endsection
