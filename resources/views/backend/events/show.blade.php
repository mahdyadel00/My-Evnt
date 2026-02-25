@extends('backend.partials.master')
@section('title', 'Show Event')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('content')
    <!-- Content -->
    <div class="container-xxl flegrow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <nav aria-label="breadcrumx-b">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.events.index') }}">All Events</a>
                    </li>
                    <li class="breadcrumb-item active">Show Event</li>
                </ol>
            </nav>
        </nav>
        <!-- Users List Table -->
        <div class="content-body">
            <!-- app e-commerce details start -->
            <section class="app-ecommerce-details">
                <div class="card">
                    <!-- Product Details starts -->
                    <div class="card-body">
                        <div class="row my-2">
                            <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    @foreach ($event->media as $media)
                                        @if ($media->name == 'poster')
                                            <img src="{{ asset('storage/' . $media->path) }}" class="img-fluid product-img"
                                                alt="Event image">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 ">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0">{{ $event->name }}</h4>
                                    <a href="{{ route('admin.events.send-sms-form', $event->id) }}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-message-2 ti-xs me-1"></i>
                                        Send SMS Invitation
                                    </a>
                                </div>
                                <span class="card-text item-company">Organized By : <a
                                        style="color:#ed7226c0">{{ $event->organized_by }}</a></span><br />
                                <span class="card-text item-company">Uploaded By : <a
                                        style="color:#ed7226c0">{{ $event->upload_by }}</a></span>
                                <div class="row mt-3">
                                    <p class="card-text col-md-6 col-12">Category : <span
                                            class="text-success">{{ $event->category->name }}</span></p>
                                    <p class="card-text col-md-6 col-12">Sub Category : <span
                                            class="text-success">{{ $event->category->child->isNotEmpty() ? $event->category->child->first()->name : 'No Sub Category' }}</span>
                                    </p>
                                    <p class="card-text col-md-6 col-12">City : <span
                                            class="text-success">{{ $event->city?->name }}</span></p>
                                    <p class="card-text col-md-6 col-12">Currency : <span
                                            class="text-success">{{ $event->currency?->code }}</span></p>
                                    <!--go to location in google map by clicking on the location name-->
                                    <p class="card-text col-md-6 col-12">Location : <span class="text-success"><a
                                                href="https://www.google.com/maps/search/{{ $event->location }}"
                                                target="_blank">{{ $event->city?->name }}</a></span></p>
                                    <p class="card-text col-md-6 col-12">External Link : <span class="text-success">
                                            <a href="{{ $event->external_link }}"
                                                target="_blank">{{ $event->external_link }}</a>
                                        </span></p>
                                    <p class="card-text col-md-6 col-12">Start Date : <span
                                            class="text-success">{{ $event->eventDates[0]->start_date }}</span></p>
                                    <p class="card-text col-md-6 col-12">End Date : <span
                                            class="text-success">{{ $event->eventDates[0]->end_date }}</span></p>
                                    <p class="card-text col-md-6 col-12">Start Time : <span
                                            class="text-success">{{ $event->eventDates[0]->start_time }}</span></p>
                                    <p class="card-text col-md-6 col-12">End Time : <span
                                            class="text-success">{{ $event->eventDates[0]->end_time }}</span></p>
                                    <p class="card-text col-md-6 col-12">Normal Price : <span
                                            class="text-success">{{ $event->tickets[0]->price }}</span></p>
                                    <p class="card-text col-md-6 col-12">format : <span
                                            class="text-success">{{ $event->format == 1 ? 'Online' : 'Offline' }}</span>
                                    </p>
                                    <p class="card-text col-md-6 col-12">Link Meeting : <span class="text-success">
                                            <a href="{{ $event->link_meeting }}"
                                                target="_blank">{{ $event->link_meeting }}</a>
                                        </span></p>
                                    <p class="card-text col-md-6 col-12">Summary : <span
                                            class="text-success">{!! $event->summary !!}</span></p>
                                    <p class="card-text col-md-6 col-12">Area : <span
                                            class="text-success">{{ $event->area ?? 'N/A' }}</span></p>
                                        <p class="card-text col-md-6 col-12">Description : <span
                                            class="text-success">{!! $event->description !!}</span></p>
                                    <p class="card-text col-md-6 col-12">Cancellation Policy : <span
                                            class="text-success">{!! $event->cancellation_policy !!}</span></p <!-- add
                                        table for ticket -->
                                    <p class="card-text col-md-6 col-12">Is Exclusive : <span
                                            class="text-success">{{ $event->is_exclusive ? 'Yes' : 'No' }}</span></p>
                                    <div class="col-12">
                                        <h4>Tickets</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Ticket Type</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Qr Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($event->tickets as $ticket)
                                                        <tr>
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td>{{ $ticket->ticket_type }}</td>
                                                            <td>{{ $ticket->price }}</td>
                                                            <td>{{ $ticket->quantity }}</td>
                                                            <td>
                                                                <!--qr code for the event use this package simplesoftwareio/simple-qrcode-->
                                                                {!! QrCode::size(100)->generate($ticket->qr_code ?? 'No Qr Code') !!}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Product Details ends -->
                    </div>
            </section>
            <!-- app e-commerce details end -->
        </div><!-- / Content -->
@endsection

    @section('js')
        <!-- Page JS -->
        <script src="{{ asset('backend') }}/assets/js/app-user-list.js"></script>

        <script>
            $(document).ready(function () {
                var table = $('#example2').DataTable({
                    lengthChange: true,
                    buttons: ['copy', 'excel', 'pdf', 'print']
                });
                table.buttons().container()
                    .appendTo('#example2_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endsection
