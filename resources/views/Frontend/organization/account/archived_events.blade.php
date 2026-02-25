@extends('Frontend.organization.account.inc.master')
@section('title', 'Archived Events')
@section('content')
    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px; ">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="#">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">Archived Events </a>
                </li>
            </ul>
            <h1>Archived Events </h1>
        </div>

    </div>
    <!-- end breadcrumb  -->
    <!-- search part -->
    <div style="width: 100%; " class="row d-flex justify-content-between flex-wrap align-items-baseline">
        <div class="col-md-3 m-2 ">
            <select class="form-select p-2" id="inputGroupSelect01">
                <option disabled selected>Sorted by</option>
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
                <option value="reset">Reset Order</option>
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
            @if($events->count() > 0)
                @foreach($events as $event)
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
                                <div class="col-md-10 col-12">
                                    <div class="card-body">
                                        <h4 style="font-weight: 700;">{{ $event->name }}</h4>
                                        <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                                            <i class='bx bxs-calendar-event p-2'></i>
                                            <p>{{ \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d M Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($event->eventDates->first()->end_date)->format('d M Y') }}
                                                <span class="p-2"> </span>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- details event actions  -->
                                    <div class="row">
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Price</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ $event->tickets->first()->price }}$</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Available</p>
                                            <span style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center">
                                            <p class="card-text">Sold</p>
                                            <span style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Views</p>
                                            <span style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Income</p>
                                            <span style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">In Cart</p>
                                            <span style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>

                                    </div>
                                    <div class="d-flex justify-content-end ">
                                        <a href="{{ route('organization.event_details', $event->id) }}"
                                            class="btn btn-outline-website m-2">View Details</a>
                                        <button type="button" class="btn btn-success m-2 unarchive-btn"
                                            data-event-uuid="{{ $event->uuid }}" data-event-name="{{ $event->name }}">
                                            Unarchive
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning" role="alert" id="noEventsAlert">
                    No Archived Events
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
            {{-- <ul class="pagination">--}}
                {{-- @if(auth()->guard('company')->check() ? auth()->guard('company')->user()->events->count() == 0 :
                true)--}}
                {{-- <li class="page-item">--}}
                    {{-- <a class="page-link" href="#" aria-label="Previous">--}}
                        {{-- <span aria-hidden="true">&laquo;</span>--}}
                        {{-- </a>--}}
                    {{-- </li>--}}
                {{-- <li class="page-item">--}}
                    {{-- <a class="page-link" href="#">1</a>--}}
                    {{-- </li>--}}
                {{-- <li class="page-item">--}}
                    {{-- <a class="page-link" href="#" aria-label="Next">--}}
                        {{-- <span aria-hidden="true">&raquo;</span>--}}
                        {{-- </a>--}}
                    {{-- </li>--}}
                {{-- @else--}}
                {{-- @if(auth()->guard('company')->user()->events->count() > 0)--}}
                {{-- <li class="page-item">--}}
                    {{-- <a class="page-link" href="{{ auth()->guard('company')->user()->events->previousPageUrl() }}" --}}
                        {{-- aria-label="Previous">--}}
                        {{-- <span aria-hidden="true">&laquo;</span>--}}
                        {{-- </a>--}}
                    {{-- </li>--}}
                {{-- @endif--}}
                {{-- @for($i = 1; $i <= auth()->guard('company')->user()->events->lastPage(); $i++)--}}
                    {{-- <li class="page-item">--}}
                        {{-- <a class="page-link" href="{{ auth()->guard('company')->user()->events->url($i) }}">{{ $i
                            }}</a>--}}
                        {{-- </li>--}}
                    {{-- @endfor--}}
                    {{-- @if(auth()->guard('company')->user()->events->count() > 0)--}}
                    {{-- <li class="page-item">--}}
                        {{-- <a class="page-link" href="{{ auth()->guard('company')->user()->events->nextPageUrl() }}" --}}
                            {{-- aria-label="Next">--}}
                            {{-- <span aria-hidden="true">&raquo;</span>--}}
                            {{-- </a>--}}
                        {{-- </li>--}}
                    {{-- @endif--}}
                    {{-- @endif--}}
                    {{-- </ul>--}}
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
            });

            // Clear search on escape key
            $('#searchInput').on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $(this).val('');
                    $(this).trigger('input');
                }
            });

            // Unarchive event functionality
            $(document).on('click', '.unarchive-btn', function () {
                var eventId = $(this).data('event-id');
                var eventName = $(this).data('event-name');
                var eventRow = $(this).closest('.event-item');
                var unarchiveBtn = $(this);

                Swal.fire({
                    title: 'Unarchive Event?',
                    text: 'Are you sure you want to unarchive "' + eventName + '"?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Unarchive it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        unarchiveBtn.prop('disabled', true).html('Unarchiving...');

                        // Make AJAX request
                        $.ajax({
                            url: '{{ route('organization.unarchive_event', '') }}/' + eventId,
                            type: 'GET',
                            success: function (response) {
                                Swal.fire({
                                    title: 'Unarchived!',
                                    text: 'Event unarchived successfully',
                                    icon: 'success',
                                    confirmButtonColor: '#28a745'
                                });

                                eventRow.fadeOut(500, function () {
                                    $(this).remove();

                                    // Check if no events left
                                    if ($('.event-item').length === 0) {
                                        $('#eventsContainer').html('<div class="alert alert-warning" role="alert">No Archived Events</div>');
                                    }
                                });
                            },
                            error: function () {
                                unarchiveBtn.prop('disabled', false).html('Unarchive');
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to unarchive event. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        });
                    }
                });
            });

            // Store original order
            var originalOrder = $('.event-item').get();

            // Sort functionality
            $('#inputGroupSelect01').change(function () {
                var value = $(this).val();
                var searchTerm = $('#searchInput').val();
                var eventsContainer = $('#eventsContainer');
                var eventItems = $('.event-item').get();

                // Clear search when sorting
                if (searchTerm) {
                    $('#searchInput').val('');
                    $('.event-item').show();
                    $('#noSearchResults').hide();
                }

                if (value === 'asc' || value === 'desc') {
                    // Sort events by name
                    eventItems.sort(function (a, b) {
                        var nameA = $(a).data('event-name');
                        var nameB = $(b).data('event-name');

                        if (value === 'asc') {
                            return nameA.localeCompare(nameB);
                        } else {
                            return nameB.localeCompare(nameA);
                        }
                    });

                    // Clear container and append sorted items
                    eventsContainer.empty();
                    $.each(eventItems, function (index, item) {
                        eventsContainer.append(item);
                    });

                    // Show success message
                    Swal.fire({
                        title: 'Sorted!',
                        text: 'Events sorted ' + (value === 'asc' ? 'Ascending' : 'Descending') + ' successfully',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else if (value === 'reset') {
                    // Reset to original order
                    eventsContainer.empty();
                    $.each(originalOrder, function (index, item) {
                        eventsContainer.append(item);
                    });

                    // Reset dropdown
                    $(this).prop('selectedIndex', 0);

                    // Show reset message
                    Swal.fire({
                        title: 'Reset!',
                        text: 'Events order reset to original',
                        icon: 'info',
                        confirmButtonColor: '#17a2b8',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
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

        /* Sort dropdown styling */
        #inputGroupSelect01 {
            transition: all 0.3s ease;
        }

        #inputGroupSelect01:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
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

            #inputGroupSelect01 {
                font-size: 16px;
            }
        }
    </style>
@endpush