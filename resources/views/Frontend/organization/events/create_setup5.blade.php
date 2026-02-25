@extends('Frontend.organization.events.inc.master')
@section('title', 'Create Event - Review & Publish')
@section('content')
    <!-- Breadcrumb Section -->
    <div class="head-title mb-4">
        <div class="left">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class='bx bx-home-alt me-1'></i>Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('organization.events.my_events') }}" class="text-decoration-none">My Events</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Event</li>
                </ol>
            </nav>
            <h1 class="h2 fw-bold text-primary">
                <i class='bx bx-check-circle me-2'></i>Create New Event
            </h1>
        </div>
    </div>

    <!-- Messages -->
    @include('Frontend.organization.layouts._message')

    <!-- start form-data -->
    <div class="form-data">
        <!-- head -->
        <div class="head">
            <h3><i class='bx bx-check-circle me-2'></i>Review & Publish</h3>
            <p>Final Step - Review your event details</p>
        </div>

        <!-- Success Notice -->
        <div class="col-12 mb-4">
            <div class="notice-success-place">
                <i class='bx bx-check-circle me-2'></i>
                <strong>Event Created Successfully!</strong><br>
                • Review all the details below<br>
                • Make sure everything looks correct<br>
                • Click "Publish Event" to make it live<br>
                • You can always edit these details later
            </div>
        </div>

        <!-- Event Preview Section -->
        <div class="row g-4">
            <!-- Event Poster & Basic Info -->
            <div class="col-lg-8">
                <div class="card event-preview-card h-100">
                    <div class="card-header text-white" style="background: linear-gradient(45deg, #1976d2, #2196f3);">
                        <h5 class="mb-0">
                            <i class='bx bx-image me-2'></i>Event Preview
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <div class="poster-container">
                                    @foreach($latest_event->media as $media)
                                        @if($media->name == 'poster')
                                            <img src="{{ asset('storage/' . $media->path) }}" class="img-fluid poster-image"
                                                alt="{{ $latest_event->name }} Poster">
                                        @endif
                                    @endforeach
                                    <div class="poster-overlay">
                                        <div class="category-badge">
                                            {{ $latest_event->category?->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="event-details p-4">
                                    <h2 class="event-title text-primary mb-3">{{ $latest_event->name }}</h2>

                                    <div class="event-description mb-4">
                                        <h6 class="text-muted mb-2">Description:</h6>
                                        <div class="description-content">
                                            {!! Str::limit($latest_event->description, 200) !!}
                                        </div>
                                    </div>

                                    <div class="event-meta">
                                        <div class="meta-item mb-3">
                                            <i class='bx bx-calendar text-primary me-2'></i>
                                            <span class="meta-label">Date & Time:</span>
                                            <span class="meta-value">
                                                @if($latest_event->eventDates->first())
                                                    {{ \Carbon\Carbon::parse($latest_event->eventDates->first()->start_time)->format('M d, Y - h:i A') }}
                                                @else
                                                    Date to be announced
                                                @endif
                                            </span>
                                        </div>

                                        <div class="meta-item mb-3">
                                            <i class='bx bx-map text-primary me-2'></i>
                                            <span class="meta-label">Location:</span>
                                            <span class="meta-value">
                                                @if($latest_event->location)
                                                    <a href="{{ $latest_event->location }}" target="_blank"
                                                        class="text-decoration-none">
                                                        <i class='bx bx-link-external me-1'></i>View Location
                                                    </a>
                                                @else
                                                    Location to be announced
                                                @endif
                                            </span>
                                        </div>

                                        <div class="meta-item mb-3">
                                            <i class='bx bx-building text-primary me-2'></i>
                                            <span class="meta-label">Organizer:</span>
                                            <span class="meta-value">{{ $latest_event->company?->company_name }}</span>
                                        </div>

                                        <div class="meta-item">
                                            <i class='bx bx-category text-primary me-2'></i>
                                            <span class="meta-label">Category:</span>
                                            <span class="meta-value">{{ $latest_event->eventCategory?->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Statistics -->
            <div class="col-lg-4">
                <div class="card stats-card h-100">
                    <div class="card-header text-white" style="background: linear-gradient(45deg, #4caf50, #66bb6a);">
                        <h5 class="mb-0">
                            <i class='bx bx-bar-chart me-2'></i>Event Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class='bx bx-dollar-circle'></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-value">${{ number_format($latest_event->tickets->sum('price'), 2) }}
                                    </h4>
                                    <p class="stat-label">Total Revenue Potential</p>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class='bx bx-ticket'></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-value">{{ number_format($latest_event->tickets->sum('quantity')) }}</h4>
                                    <p class="stat-label">Total Tickets Available</p>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class='bx bx-category-alt'></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-value">{{ $latest_event->tickets->count() }}</h4>
                                    <p class="stat-label">Ticket Types</p>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class='bx bx-trophy'></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-value">
                                        @if($latest_event->adFee)
                                            {{ $latest_event->adFee->name }}
                                        @else
                                            No Sponsorship
                                        @endif
                                    </h4>
                                    <p class="stat-label">Sponsorship Plan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card tickets-card">
                    <div class="card-header text-white" style="background: linear-gradient(45deg, #1976d2, #4caf50);">
                        <h5 class="mb-0">
                            <i class='bx bx-ticket me-2'></i>Ticket Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($latest_event->tickets->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th><i class='bx bx-tag me-1'></i>Ticket Type</th>
                                                            <th><i class='bx bx-dollar me-1'></i>Price</th>
                                                            <th><i class='bx bx-package me-1'></i>Quantity</th>
                                                            <th><i class='bx bx-calculator me-1'></i>Total Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($latest_event->tickets as $ticket)
                                                            <tr>
                                                                <td>
                                                                    <strong>{{ $ticket->type }}</strong>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-success">${{ number_format($ticket->price, 2) }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-primary">{{ number_format($ticket->quantity) }}</span>
                                                                </td>
                                                                <td>
                                                                    <strong
                                                                        class="text-success">${{ number_format($ticket->price * $ticket->quantity, 2) }}</strong>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <th colspan="3">Total Potential Revenue:</th>
                                                            <th class="text-success">
                                                                ${{ number_format($latest_event->tickets->sum(function ($ticket) {
                                return $ticket->price * $ticket->quantity;
                            }), 2) }}
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class='bx bx-info-circle me-2'></i>
                                No tickets have been created for this event yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons mt-5 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                        <a href="{{ route('create_event_sponsor') }}" class="btn btn-outline-primary">
                            <i class='bx bx-arrow-back me-1'></i>Back to Previous Step
                        </a>

                        <button type="button" class="btn btn-primary" id="preview-btn">
                            <i class='bx bx-show me-1'></i>Preview Event
                        </button>

                        <a href="{{ route('organization.events.my_events') }}" class="btn btn-success" id="publish-btn">
                            <i class='bx bx-check-circle me-1'></i>Publish Event
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="border: 2px solid #1976d2;">
                        <div class="card-header text-white" style="background: linear-gradient(45deg, #1976d2, #2196f3);">
                            <h6 class="mb-0">
                                <i class='bx bx-edit me-2'></i>Need to make changes?
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">You can edit any part of your event before publishing:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('create_event') }}" class="btn btn-outline-primary btn-sm">
                                    <i class='bx bx-edit me-1'></i>Edit Basic Info
                                </a>
                                <a href="{{ route('create_event_setup2') }}" class="btn btn-outline-success btn-sm">
                                    <i class='bx bx-map me-1'></i>Edit Location
                                </a>
                                <a href="{{ route('create_event_setup3') }}" class="btn btn-outline-primary btn-sm">
                                    <i class='bx bx-ticket me-1'></i>Edit Tickets
                                </a>
                                <a href="{{ route('create_event_sponsor') }}" class="btn btn-outline-success btn-sm">
                                    <i class='bx bx-trophy me-1'></i>Edit Sponsorship
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end form-data -->
@endsection

@push('inc_events_js')
    <script>
        $(document).ready(function () {
            // Preview event functionality
            $('#preview-btn').on('click', function () {
                Swal.fire({
                    title: 'Event Preview',
                    html: `
                                                                                                                            <div class="text-start">
                                                                                                                                <h5>{{ $latest_event->name }}</h5>
                                                                                                                                <p><strong>Category:</strong> {{ $latest_event->category?->name }}</p>
                                                                                                                                <p><strong>Organizer:</strong> {{ $latest_event->company?->company_name }}</p>
                                                                                                                                <p><strong>Tickets:</strong> {{ $latest_event->tickets->count() }} types available</p>
                                                                                                                                <p><strong>Total Capacity:</strong> {{ $latest_event->tickets->sum('quantity') }} attendees</p>
                                                                                                                            </div>
                                                                                                                        `,
                    icon: 'info',
                    confirmButtonText: 'Close Preview',
                    confirmButtonColor: '#0d6efd',
                    width: '500px'
                });
            });

            // Publish event with confirmation
            $('#publish-btn').on('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Publish Event?',
                    text: 'Are you ready to make your event live and visible to the public?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Publish Event!',
                    cancelButtonText: 'Not Yet'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show success message
                        Swal.fire({
                            title: 'Event Published!',
                            text: 'Your event is now live and visible to the public.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to my events
                            window.location.href = "{{ route('organization.events.my_events') }}";
                        });
                    }
                });
            });

            // Add smooth scrolling for better UX
            $('a[href^="#"]').on('click', function (event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });

            // Add loading animation to buttons
            $('.btn').on('click', function () {
                if (!$(this).hasClass('no-loading')) {
                    const btn = $(this);
                    const originalText = btn.html();

                    btn.prop('disabled', true)
                        .html('<i class="bx bx-loader-alt bx-spin me-1"></i>Loading...');

                    setTimeout(function () {
                        btn.prop('disabled', false).html(originalText);
                    }, 3000);
                }
            });

            // Animate statistics on scroll
            function animateStats() {
                $('.stat-value').each(function () {
                    const $this = $(this);
                    const countTo = $this.text().replace(/[^0-9]/g, '');

                    if (countTo) {
                        $({ countNum: 0 }).animate({
                            countNum: countTo
                        }, {
                            duration: 2000,
                            easing: 'linear',
                            step: function () {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function () {
                                $this.text(this.countNum);
                            }
                        });
                    }
                });
            }

            // Trigger animation when stats come into view
            $(window).on('scroll', function () {
                const statsCard = $('.stats-card');
                if (statsCard.length) {
                    const statsTop = statsCard.offset().top;
                    const statsBottom = statsTop + statsCard.outerHeight();
                    const viewportTop = $(window).scrollTop();
                    const viewportBottom = viewportTop + $(window).height();

                    if (statsBottom > viewportTop && statsTop < viewportBottom) {
                        animateStats();
                        $(window).off('scroll'); // Run only once
                    }
                }
            });
        });
    </script>

    <style>
        .notice-success-place {
            background: linear-gradient(135deg, #e3f2fd 0%, #e8f5e8 100%);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin: 0;
            color: #1565c0;
        }

        .event-preview-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .poster-container {
            position: relative;
            height: 100%;
            min-height: 300px;
        }

        .poster-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .poster-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.7) 100%);
        }

        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(45deg, #1976d2, #2196f3);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .event-details {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .event-title {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .description-content {
            background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%);
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #1976d2;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .meta-item {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .meta-label {
            font-weight: 600;
            margin-right: 8px;
            min-width: 80px;
        }

        .meta-value {
            color: #1976d2;
        }

        .stats-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .stats-grid {
            display: grid;
            gap: 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: linear-gradient(135deg, #e8f5e8 0%, #e3f2fd 100%);
            border-radius: 10px;
            border-left: 4px solid #4caf50;
        }

        .stat-icon {
            font-size: 2rem;
            color: #1976d2;
            margin-right: 15px;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4caf50;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #1976d2;
            margin: 0;
        }

        .tickets-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(135deg, rgba(25, 118, 210, 0.05) 0%, rgba(76, 175, 80, 0.05) 100%);
        }

        .action-buttons .btn {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3);
        }

        .quick-actions .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        /* Loading animation */
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

        /* Responsive improvements */
        @media (max-width: 768px) {
            .head-title h1 {
                font-size: 1.5rem;
            }

            .breadcrumb {
                font-size: 0.85rem;
            }

            .form-data .head h3 {
                font-size: 1.3rem;
            }

            .notice-success-place {
                padding: 12px;
                font-size: 0.9rem;
            }

            .event-title {
                font-size: 1.4rem;
            }

            .poster-container {
                min-height: 200px;
            }

            .meta-item {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 15px;
            }

            .meta-label {
                margin-bottom: 5px;
                min-width: auto;
            }

            .stats-grid {
                gap: 15px;
            }

            .stat-item {
                padding: 12px;
            }

            .stat-icon {
                font-size: 1.5rem;
                margin-right: 10px;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .action-buttons .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .quick-actions .btn-sm {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        @media (max-width: 576px) {
            .event-preview-card .row.g-0 {
                flex-direction: column;
            }

            .poster-container {
                min-height: 250px;
            }

            .event-details {
                padding: 20px !important;
            }

            .table-responsive {
                font-size: 0.85rem;
            }

            .stat-item {
                flex-direction: column;
                text-align: center;
            }

            .stat-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }

        /* Touch-friendly elements */
        @media (hover: none) and (pointer: coarse) {
            .btn {
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .stat-item {
                min-height: 80px;
            }
        }
    </style>
@endpush