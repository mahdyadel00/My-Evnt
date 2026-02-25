@extends('Frontend.organization.account.inc.master')
@section('title', 'My Event')
@section('content')

    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px;">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="{{ route('home') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">My Event</a>
                </li>
            </ul>
            <h1>My Event</h1>
        </div>
    </div>
    @include('Frontend.organization.layouts._message')
    <!-- end breadcrumb  -->
    <!-- search part -->
    <div style="width: 100%; " class="row d-flex justify-content-between flex-wrap align-items-baseline">
        <div class="col-md-3 m-2 ">
            <select class="form-select p-2" id="inputGroupSelect01">
                <option disabled selected>Sorted by</option>
                <!--sorted by ascending and descending -->
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </div>
        <div class="col-md-3 m-2">
            <input class="form-control p-2" id="searchInput" placeholder="Search events..." autocomplete="off">
        </div>
    </div>
    <!-- start form-data -->
    <div class="form-data">
        <!-- start event details -->
        <div id="eventsContainer">
            @if(auth()->guard('company')->user()->events->count() > 0)
                @foreach(auth()->guard('company')->user()->events as $event)
                    <div class="row event-item" data-event-name="{{ strtolower($event->name) }}"
                        data-event-category="{{ strtolower($event->category?->name ?? '') }}">
                        <div class="card mb-3">
                            <div class="row g-0 p-2">
                                <!-- image poster -->
                                <div class="col-md-2">
                                    @foreach($event->media as $media)
                                        @if($media->name == 'poster')
                                            <a href="{{ asset('storage/' . $media->path) }}" data-lightbox="image-1">
                                                <img style="width: 250px;height: auto;" src="{{ asset('storage/' . $media->path) }}"
                                                    class="img-fluid rounded-1" alt="">
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- data event all  -->
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h4 style="font-weight: 700;">{{ $event->name }}</h4>
                                        <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                                            <i class='bx bxs-calendar-event p-2'></i>
                                            <p>
                                                @if($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date)
                                                    {{ \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d-m-Y') }}
                                                @else
                                                    No start date
                                                @endif
                                                <span class="p-2">
                                                    @if($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_time)
                                                        @php
                                                            try {
                                                                $startTime = $event->eventDates->first()->start_time;
                                                                $time = Carbon\Carbon::parse($startTime)->format('h:i A');
                                                                echo $time;
                                                            } catch (Exception $e) {
                                                                echo $event->eventDates->first()->start_time;
                                                            }
                                                        @endphp
                                                    @else
                                                        No start time
                                                    @endif
                                                </span>
                                            </p>
                                            <p> ,
                                                @if($event->eventDates->isNotEmpty() && $event->eventDates->first()->end_date)
                                                    {{ \Carbon\Carbon::parse($event->eventDates->first()->end_date)->format('d-m-Y') }}
                                                @else
                                                    No end date
                                                @endif
                                                <span class="p-2">
                                                    @if($event->eventDates->isNotEmpty() && $event->eventDates->first()->end_time)
                                                        @php
                                                            try {
                                                                $endTime = $event->eventDates->first()->end_time;
                                                                $time = Carbon\Carbon::parse($endTime)->format('h:i A');
                                                                echo $time;
                                                            } catch (Exception $e) {
                                                                echo $event->eventDates->first()->end_time;
                                                            }
                                                        @endphp
                                                    @else
                                                        No end time
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- details event actions  -->
                                    <div class="row">
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Total Price</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">${{ number_format($event->tickets()->sum('price'), 2) }}</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Available</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ number_format($event->tickets()->sum('quantity')) }}</span>
                                        </div>
                                        @php
                                            $soldTickets = $event->orders()->where('payment_status', 'paid')->sum('quantity') ?? 0;
                                            $eventEndDate = $event->eventDates->isNotEmpty() ? $event->eventDates->first()->end_date : null;
                                            $isEventEnded = $eventEndDate ? \Carbon\Carbon::parse($eventEndDate)->isPast() : false;
                                        @endphp

                                        @if($soldTickets > 0 || $isEventEnded)
                                            <div class="col-md-4 col-6 text-center">
                                                <p class="card-text">Sold</p>
                                                <span
                                                    style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ number_format($soldTickets) }}</span>
                                            </div>
                                        @else
                                            <div class="col-md-4 col-6 text-center">
                                                <p class="card-text">Status</p>
                                                <span
                                                    style="padding: 5px 10px;border-radius: 5px;background-color: #28a745; color: white;">Active</span>
                                            </div>
                                        @endif
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Views</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ number_format($event->view_count ?? 0) }}</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Income</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">${{ number_format($event->orders()->where('payment_status', 'paid')->sum('total') ?? 0, 2) }}</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">In Cart</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ number_format($event->orders()->where('payment_status', 'pending')->sum('quantity') ?? 0) }}</span>
                                        </div>
                                        {{-- <div class="col-md-4 col-6 text-center mt-2 mb-2">--}}
                                            {{-- <p class="card-text">Scans</p>--}}
                                            {{-- <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>--}}
                                            {{-- </div>--}}
                                    </div>
                                    <div class="d-flex justify-content-end m-2">
                                        <a href="{{ route('organization.event_details', $event->id) }}"
                                            class="btn btn-outline-website m-2">View Details</a>
                                        <button type="button" class="btn btn-danger m-2 archive-btn"
                                            data-event-uuid="{{ $event->uuid }}" data-event-name="{{ $event->name }}">
                                            Archived
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning" role="alert" id="noEventsAlert">
                    No event found
                </div>
            @endif
        </div>

        <!-- No search results message -->
        <div class="alert alert-info" role="alert" id="noSearchResults" style="display: none;">
            <i class='bx bx-search-alt me-2'></i>
            No events found matching your search criteria. Try different keywords.
        </div>
        <!-- end event details -->
        <!-- pagination -->
        <div aria-label="Page navigation example">
            <ul class="pagination">
                @if(auth()->guard('company')->user()->events->count() > 0)
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    @if(auth()->guard('company')->user()->events->count() > 0)
                        <li class="page-item">
                            <a class="page-link" href="{{ $events->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif
                    {{-- @for($i = 1; $i <= auth()->guard('company')->user()->events->lastPage(); $i++)--}}
                        {{-- <li class="page-item">--}}
                            {{-- <a class="page-link" href="{{ $events->url($i) }}">{{ $i }}</a>--}}
                            {{-- </li>--}}
                        {{-- @endfor--}}
                        @if(auth()->guard('company')->user()->events->count() > 0)
                            <li class="page-item">
                                <a class="page-link" href="{{ auth()->user()->company->events->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @endif
                @endif
            </ul>
        </div>
    </div>
    <!-- end form-data -->
@endsection
@push('inc_js')
    <script>
        $(document).ready(function () {
            // Search functionality
            $('#searchInput').on('input', function () {
                var searchTerm = $(this).val().toLowerCase().trim();
                var eventItems = $('.event-item');
                var visibleCount = 0;

                if (searchTerm === '') {
                    // Show all events if search is empty
                    eventItems.show();
                    $('#noSearchResults').hide();
                    visibleCount = eventItems.length;
                } else {
                    // Filter events based on search term
                    eventItems.each(function () {
                        var eventName = $(this).data('event-name');
                        var eventCategory = $(this).data('event-category');

                        // Search in event name and category
                        if (eventName.includes(searchTerm) || eventCategory.includes(searchTerm)) {
                            $(this).show();
                            visibleCount++;
                        } else {
                            $(this).hide();
                        }
                    });
                }

                // Show/hide no results message
                if (visibleCount === 0 && searchTerm !== '') {
                    $('#noSearchResults').show();
                } else {
                    $('#noSearchResults').hide();
                }

                // Update pagination visibility
                if (visibleCount === 0) {
                    $('.pagination').hide();
                } else {
                    $('.pagination').show();
                }
            });

            // Clear search on escape key
            $('#searchInput').on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $(this).val('');
                    $(this).trigger('input');
                }
            });

            //sorted by status and date
            $('#inputGroupSelect01').change(function () {
                var value = $(this).val();
                var searchTerm = $('#searchInput').val();

                // Clear search when sorting
                if (searchTerm) {
                    $('#searchInput').val('');
                    $('.event-item').show();
                    $('#noSearchResults').hide();
                }

                $.ajax({
                    url: '{{ route('organization.sorted_events') }}',
                    type: 'GET',
                    data: { value: value },
                    success: function (data) {
                        // Note: This might need adjustment based on your backend response
                        location.reload(); // Simple reload for now
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to sort events. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            // Archive event functionality
            $(document).on('click', '.archive-btn', function () {
                var eventId = $(this).data('event-id');
                var eventName = $(this).data('event-name');
                var eventRow = $(this).closest('.event-item');
                var archiveBtn = $(this);

                Swal.fire({
                    title: 'Archive Event?',
                    text: 'Are you sure you want to archive "' + eventName + '"?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Archive it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        archiveBtn.prop('disabled', true).html('Archiving...');

                        // Make AJAX request
                        $.ajax({
                            url: '{{ route('organization.add_archived_event', '') }}/' + eventId,
                            type: 'GET',
                            success: function (response) {
                                Swal.fire({
                                    title: 'Archived!',
                                    text: 'Event archived successfully',
                                    icon: 'success',
                                    confirmButtonColor: '#28a745'
                                });

                                eventRow.fadeOut(500, function () {
                                    $(this).remove();

                                    // Check if no events left
                                    if ($('.event-item').length === 0) {
                                        $('#eventsContainer').html('<div class="alert alert-warning" role="alert">No event found</div>');
                                    }
                                });
                            },
                            error: function () {
                                archiveBtn.prop('disabled', false).html('Archived');
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to archive event. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        });
                    }
                });
            });

            // Add search icon and clear button
            var searchContainer = $('#searchInput').parent();
            searchContainer.addClass('position-relative');

            // Add search icon
            searchContainer.append('<i class="bx bx-search position-absolute" style="right: 35px; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>');

            // Add clear button
            searchContainer.append('<i class="bx bx-x position-absolute search-clear" style="right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; cursor: pointer; display: none;"></i>');

            // Show/hide clear button
            $('#searchInput').on('input', function () {
                if ($(this).val().length > 0) {
                    $('.search-clear').show();
                } else {
                    $('.search-clear').hide();
                }
            });

            // Clear search on click
            $('.search-clear').on('click', function () {
                $('#searchInput').val('').trigger('input');
                $(this).hide();
            });
        });
    </script>

    <style>
        /* Search input enhancements */
        #searchInput {
            padding-right: 70px !important;
            transition: all 0.3s ease;
        }

        #searchInput:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }

        .search-clear:hover {
            color: #dc3545 !important;
        }

        /* Event item animations */
        .event-item {
            transition: all 0.3s ease;
        }

        .event-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* No results styling */
        #noSearchResults {
            background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%);
            border: 1px solid #1976d2;
            color: #1976d2;
        }

        /* Button loading animation */
        .bx-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive search */
        @media (max-width: 768px) {
            .col-md-3.m-2 {
                margin: 10px 0 !important;
                width: 100%;
            }

            #searchInput {
                font-size: 16px;
                /* Prevent zoom on iOS */
            }
        }
    </style>
@endpush