@extends('Frontend.organization.events.inc.master')
@section('title', 'Create Event - Tickets Info')
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
                <i class='bx bx-ticket me-2'></i>Create New Event
            </h1>
        </div>
    </div>

    <!-- Messages -->
    @include('Frontend.organization.layouts._message')
        <!-- start form-data -->
    <div class="form-data">
        <div class="head">
            <h3><i class='bx bx-ticket me-2'></i>Tickets Info</h3>
            <p>Step 3 of 5</p>
        </div>

        <!-- form step 3 -->
        <form class="row g-3 needs-validation" action="{{ route('event.store.setup3') }}" method="POST" novalidate>
            @csrf

            <!-- Info Notice -->
            <div class="col-12">
                <div class="notice-info-place">
                    <i class='bx bx-info-circle me-2'></i>
                    <strong>Ticket Types Guide:</strong><br>
                    • Create one ticket type for universal event access<br>
                    • Create multiple ticket types for varied opportunities based on price and features<br>
                    • You can always modify ticket details later
                </div>
            </div>

            <!-- Tickets Container -->
            <div class="col-12">
                <div id="tickets-container">
                    <!-- First Ticket (Default) -->
                    <div class="ticket-group border rounded p-3 mb-3" data-ticket-index="0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class='bx bx-ticket me-1'></i>Ticket #<span class="ticket-number">1</span>
                            </h5>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-ticket" style="display: none;">
                                <i class='bx bx-trash me-1'></i>Remove
                            </button>
                        </div>

                        <div class="row">
                            <!-- Ticket Type -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class='bx bx-tag me-1'></i>Ticket Type *
                                </label>
                                <input class="form-control @error('ticket_type.0') is-invalid @enderror"
                                       type="text"
                                       placeholder="e.g., General Admission, VIP, Early Bird"
                                       name="ticket_type[]"
                                       value="{{ old('ticket_type.0') }}"
                                       required>
                                @error('ticket_type.0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">
                                        <i class='bx bx-bulb me-1'></i>Enter a descriptive name for this ticket type
                                    </div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class='bx bx-dollar me-1'></i>Price *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control @error('price.0') is-invalid @enderror"
                                           type="number"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           name="price[]"
                                           value="{{ old('price.0') }}"
                                           required>
                                </div>
                                @error('price.0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">
                                        <i class='bx bx-bulb me-1'></i>Set to 0 for free tickets
                                    </div>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class='bx bx-package me-1'></i>Quantity *
                                </label>
                                <input class="form-control @error('quantity.0') is-invalid @enderror"
                                       type="number"
                                       min="1"
                                       placeholder="100"
                                       name="quantity[]"
                                       value="{{ old('quantity.0') }}"
                                       required>
                                @error('quantity.0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">
                                        <i class='bx bx-bulb me-1'></i>Number of tickets available
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Ticket Button -->
            <div class="col-12">
                <button type="button" id="add_ticket" class="btn btn-outline-primary">
                    <i class='bx bx-plus me-1'></i>Add Another Ticket Type
                </button>
                <small class="text-muted ms-3">
                    <i class='bx bx-info-circle me-1'></i>You can create multiple ticket types with different prices
                </small>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                <a href="{{ route('create_event_setup2') }}" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">
                    <i class='bx bx-arrow-back me-1'></i>Back
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">
                    Next <i class='bx bx-arrow-to-right ms-1'></i>
                </button>
            </div>
        </form>
    </div>
    <!-- end form-data -->
@endsection
@push('inc_events_js')
    <script>
        $(document).ready(function () {
            let ticketIndex = 1; // Start from 1 since we have ticket 0 already
            const maxTickets = 10; // Maximum number of ticket types allowed

            // Add new ticket type
            $('#add_ticket').click(function () {
                if ($('.ticket-group').length >= maxTickets) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maximum Limit Reached',
                        text: `Maximum ${maxTickets} ticket types allowed.`,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0d6efd'
                    });
                    return;
                }

                const newTicketHtml = `
                    <div class="ticket-group border rounded p-3 mb-3" data-ticket-index="${ticketIndex}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class='bx bx-ticket me-1'></i>Ticket #<span class="ticket-number">${ticketIndex + 1}</span>
                            </h5>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-ticket">
                                <i class='bx bx-trash me-1'></i>Remove
                            </button>
                        </div>

                        <div class="row">
                            <!-- Ticket Type -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class='bx bx-tag me-1'></i>Ticket Type *
                                </label>
                                <input class="form-control"
                                       type="text"
                                       placeholder="e.g., General Admission, VIP, Early Bird"
                                       name="ticket_type[]"
                                       required>
                                <div class="form-text">
                                    <i class='bx bx-bulb me-1'></i>Enter a descriptive name for this ticket type
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class='bx bx-dollar me-1'></i>Price *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control"
                                           type="number"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           name="price[]"
                                           required>
                                </div>
                                <div class="form-text">
                                    <i class='bx bx-bulb me-1'></i>Set to 0 for free tickets
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class='bx bx-package me-1'></i>Quantity *
                                </label>
                                <input class="form-control"
                                       type="number"
                                       min="1"
                                       placeholder="100"
                                       name="quantity[]"
                                       required>
                                <div class="form-text">
                                    <i class='bx bx-bulb me-1'></i>Number of tickets available
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#tickets-container').append(newTicketHtml);
                ticketIndex++;

                // Update remove button visibility
                updateRemoveButtons();

                // Add animation to new ticket
                $('.ticket-group').last().hide().fadeIn(300);

                // Scroll to new ticket
                $('html, body').animate({
                    scrollTop: $('.ticket-group').last().offset().top - 100
                }, 300);
            });

            // Remove ticket type
            $(document).on('click', '.remove-ticket', function () {
                const ticketGroup = $(this).closest('.ticket-group');

                // Confirm deletion with SweetAlert
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to remove this ticket type?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ticketGroup.fadeOut(300, function() {
                            $(this).remove();
                            updateTicketNumbers();
                            updateRemoveButtons();
                        });

                        // Show success message
                        Swal.fire({
                            title: 'Removed!',
                            text: 'Ticket type has been removed.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

            // Update ticket numbers after removal
            function updateTicketNumbers() {
                $('.ticket-group').each(function(index) {
                    $(this).find('.ticket-number').text(index + 1);
                    $(this).attr('data-ticket-index', index);
                });
            }

            // Update remove button visibility (hide for first ticket if it's the only one)
            function updateRemoveButtons() {
                const ticketGroups = $('.ticket-group');
                if (ticketGroups.length <= 1) {
                    ticketGroups.find('.remove-ticket').hide();
                } else {
                    ticketGroups.find('.remove-ticket').show();
                }
            }

            // Form validation
            const form = document.querySelector('.needs-validation');
            if (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Focus on first invalid field
                        const firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.focus();

                            // Scroll to invalid field
                            $('html, body').animate({
                                scrollTop: $(firstInvalid).offset().top - 100
                            }, 300);
                        }
                    }

                    form.classList.add('was-validated');
                }, false);
            }

            // Add loading state to submit button
            $('form').on('submit', function () {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.prop('disabled', true)
                         .html('<i class="bx bx-loader-alt bx-spin me-1"></i>Processing...');

                // Re-enable after 5 seconds as fallback
                setTimeout(function () {
                    submitBtn.prop('disabled', false).html(originalText);
                }, 5000);
            });

            // Auto-calculate total revenue preview
            $(document).on('input', 'input[name="price[]"], input[name="quantity[]"]', function() {
                calculateTotalRevenue();
            });

            function calculateTotalRevenue() {
                let totalRevenue = 0;
                $('.ticket-group').each(function() {
                    const price = parseFloat($(this).find('input[name="price[]"]').val()) || 0;
                    const quantity = parseInt($(this).find('input[name="quantity[]"]').val()) || 0;
                    totalRevenue += price * quantity;
                });

                // Update or create revenue display
                let revenueDisplay = $('#revenue-preview');
                if (revenueDisplay.length === 0) {
                    $('#add_ticket').parent().append(`
                        <div id="revenue-preview" class="mt-3 p-3 bg-light rounded">
                            <strong><i class='bx bx-calculator me-1'></i>Potential Revenue: $<span id="revenue-amount">0.00</span></strong>
                            <small class="text-muted d-block">This is calculated if all tickets are sold</small>
                        </div>
                    `);
                    revenueDisplay = $('#revenue-preview');
                }

                $('#revenue-amount').text(totalRevenue.toFixed(2));
            }

            // Initialize
            updateRemoveButtons();
        });
    </script>

    <style>
        .ticket-group {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .ticket-group:hover {
            background-color: #e9ecef;
        }

        .notice-info-place {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
            margin: 0;
        }

        .form-text {
            font-size: 0.875em;
            color: #6c757d;
        }

        .invalid-feedback {
            display: block;
        }

        /* Loading animation */
        .bx-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Revenue preview styling */
        #revenue-preview {
            border-left: 4px solid #28a745;
        }
    </style>
@endpush