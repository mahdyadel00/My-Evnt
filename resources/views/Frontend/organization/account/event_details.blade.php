@extends('Frontend.organization.account.inc.master')
@section('title', 'Event Details')
@section('content')
    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px; ">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="{{ route('home') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">My Events </a>
                </li>
            </ul>
            <h1>Event Details</h1>
        </div>
        <div class="d-flex justify-content-end">
            <a style="margin: 0 5px;" href="{{ route('organization.add_archived_event', $event->id) }}" class="btn btn-outline-danger">Archived</a>
        </div>
    </div>
    @include('Frontend.organization.layouts._message')
    <!-- end breadcrumb  -->
    <!-- start form-data -->
    <div class="form-data">
        <div class="row mt-2 mb-3">
            <div class=" col-md-3 col-12 mt-2">
                <div class="card">
                    <div class="card-body card-res">
                        <h5 style="font-size: 15px;" class="card-title">Format</h5>
                        <p style="font-size: 15px;" class="card-text">{{ $event->format == 1 ? 'Online' : 'Offline' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 15px;" class="card-title">Sales</h5>
                        <p style="font-size: 15px;" class="card-text">{{ $event->orders->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 15px;" class="card-title">Total Income</h5>
                        <p style="font-size: 15px;" class="card-text">{{ $event->orders->sum('total_price') }} {{ $event->currency?->code }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 15px;" class="card-title">Views</h5>
                        <p style="font-size: 15px;" class="card-text">{{ $event->view_count }}</p>
                    </div>
                </div>
            </div>

        </div>
        <!-- start event details -->
        <div class="row">
            <div class="card mb-3">
                <div class="row g-0 p-2 details-event-info">
                    <!-- details event image  -->
                    <div class="col-md-3">
                        @foreach($event->media as $media)
                            @if($media->name == 'poster')
                                <a href="{{ asset('storage/'.$media->path) }}" data-lightbox="image-1">
                                    <img style="width: 100%; height: 350px; object-fit: cover;" src="{{ asset('storage/'.$media->path) }}" class="img-fluid rounded-1" alt="">
                                </a>
                            @endif
                        @endforeach
                        <div class="mt-3 mb-2">
                            <h4 style="font-weight: bold;">Banner</h4>
                            @foreach($event->media as $media)
                                @if($media->name == 'banner')
                                    <a href="{{ asset('storage/'.$media->path) }}" data-lightbox="image-1">
                                        <img style="width: 100%; height: 200px; object-fit: cover;" src="{{ asset('storage/'.$media->path) }}" class="img-fluid rounded-1" alt="">
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <!-- details event info  -->
                    <div class="col-md-9">
                        <div class="card-body">
                            <span class="pb-3" style="color: #777;">Event info
                                <a href="{{ route('organization.edit_event' , $event->id) }}" class="p-2">
                                    <i class='bx bxs-edit-alt'></i>
                                </a>
                            </span>
                            <h4 style="font-weight: 700;" class="pt-3">{{ $event->name }}</h4>
                            <div style="color: #777;" class="d-flex justify-content-start align-items-baseline pt-2">
                                <i class='bx bxs-calendar-event p-1'></i>
                                <p>{{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                                    ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d-m-Y')
                                    : (\Carbon\Carbon::parse($event->start_date)->format('d-m-Y') ?? 'N/A') }}
                                    <span class="p-2">
                                        {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                                        ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('H:i')
                                        : (\Carbon\Carbon::parse($event->start_date)->format('H:i') ?? 'N/A') }}
                                    </span>
                                </p>
                            </div>
                            <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                                <i class='bx bxs-location-plus p-1'></i>
                                <p>{{ $event->city?->name }}, {{ $event->city?->country?->name }}</p>
                                <a href="{{ route('organization.edit_setup2' , $event->id) }}" class="p-2">
                                    <i class='bx bxs-edit-alt'></i>
                                </a>
                            </div>
                            <div class="p-2" style="color: #777;">
                                <span>Location</span>
                                <p class="pt-2">
                                    <a href="{{ $event->location }}" target="_blank">
                                        Go to location
                                    </a>
                                </p>
                                <p>Event Type: <span style="font-weight: bold;">{{ $event->category?->name }}</span></p>
                            </div>
                        </div>
                        <!-- details ticket info  -->
                        <div class="row details-ticket-info pt-3 pb-1 rounded-2" style="border: 1px solid #ddd; width: 95%; margin: 20px auto;">
                                <span class="pb-3" style="color: #777;">Ticket info
                                    <a href="{{ route('organization.edit_setup3' , $event->id) }}" class="p-2">
                                        <i class='bx bxs-edit-alt'></i>
                                    </a>
                                </span>
                                @foreach($event->tickets as $ticket)
                                    <div class="col-md-4 col-12 text-start mb-4">
                                        <p class="card-text">Ticket Name </p>
                                        <span style="padding: 5px 10px; border-radius: 5px; background-color: #cccccc54;">{{ $ticket->ticket_type }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mt-2 mb-2">
                                        <p class="card-text">Price</p>
                                        <span style="padding: 5px 10px; border-radius: 5px; background-color: #cccccc54;">{{ $ticket->price }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mt-2 mb-2">
                                        <p class="card-text">Available</p>
                                        <span style="padding: 5px 10px; border-radius: 5px; background-color: #cccccc54;">{{ $ticket->quantity }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mt-2 mb-2">
                                        <p class="card-text">Sold</p>
                                        <span style="padding: 5px 10px; border-radius: 5px; background-color: #cccccc54;">{{ $ticket->orders->count() }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mt-2 mb-2">
                                        <p class="card-text">In Cart </p>
                                        <span style="padding: 5px 10px; border-radius: 5px; background-color: #cccccc54;">0</span>
                                    </div>
                                    <hr style="color: gray;width: 90%;margin: 10px auto;">
                                @endforeach
                                <div class="col-12 mt-4">
                            <span>Description</span>
                            <p class="pt-2">{!! $event->description !!}</p>
                        </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end event details -->
        <div class="d-flex justify-content-end flex-wrap ">
            <a href="{{ route('upload_gallery' , $event->id) }}" class="btn btn-success m-1"> Upload Gallery </a>
            <a href="{{ route('organization.edit_event' , $event->id) }}" class="btn btn-primary m-1"> Edit Event </a>
        </div>
    </div>
    <!-- end form-data -->
@endsection
