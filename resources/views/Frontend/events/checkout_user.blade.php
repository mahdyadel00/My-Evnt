@extends('Frontend.layouts.master')
@section('title', 'Checkout')
@section('content')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front/css/payment-methods.css') }}">
@endpush
    <style>
        .share-links p {
            padding: 0;
            margin: 0;
        }

        .links {
            display: flex;
            flex-direction: row !important;
            flex-wrap: wrap;
            /* justify-content:space-between; */
            align-items: flex-start;
        }

        /* .social-icons a {
            text-decoration: none;
            color: gray;
            padding: 10px;
            font-size: 20px;
            transition: all 0.3s ease;

        } */

        .social-icons a i {
            font-size: 25px;
            transition: all 0.3s ease;
            padding: 12px;
            border-radius: 50%;
            background-color: #fff;
            text-align: center;
            transition: .5s;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }

        .social-icons a i:hover {
            transform: translate(0, -15%);
        }

        .social-icons a .fa-facebook:hover {
            background-color: #3b5998;
            color: #fff;
        }

        .social-icons a .fa-x-twitter:hover {
            background-color: #111;
            color: #fff;
        }

        .social-icons a .fa-linkedin:hover {
            background-color: #007bb6;
            color: #fff;
        }

        .social-icons a .fa-whatsapp:hover {
            background-color: #24cc63;
            color: #fff;
        }

        .social-icons a .fa-instagram:hover {
            background-color: #E4405F;
            color: #fff;
        }

        .social-icons a .fa-copy:hover {
            background-color: #000;
            color: #fff;
        }

        @media screen and (max-width: 900px) {
            .social-icons a {
                padding: 7px;

            }

            .social-icons a i {
                font-size: 20px;
                padding: 10px;
            }
        }

        /* Ticket selection styling */
        .ticket-box {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #ddd;
        }

        .ticket-box:hover {
            border-color: #ff6b35;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 107, 53, 0.2);
        }

        .ticket-box.active {
            border-color: #ff6b35;
            background-color: #fff5f2;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .ticket-box.active::before {
            content: "✓";
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff6b35;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
    <form action="{{ route('payment_checkout') }}" method="post">
        @csrf
        <!-- start section All event -->
        <section class="details">
            <div class="container mt-3">
                <div class="row d-flex flex-wrap align-items-baseline">
                    <div class="col-md-7">
                        <!-- start part get ticket info -->
                        <div class="project-info-box">
                            <h4 class="pb-2">{{ $event->name }}</h4>
                            <div class="row">
                                <div class="col-lg-4  col-12">
                                    <div class="card-info-payment">
                                        <h5><i class="fas fa-map-marker-alt pe-1"></i> {{ $event->city?->name }}</h5>
                                        <!-- go to search in google map -->
                                        <p>
                                            <a href="https://www.google.com/maps/search/{{ $event->city?->name }}"
                                                target="_blank">
                                                {{ $event->location }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4  col-12">
                                    <div class="card-info-payment">
                                        <h5><i class="fas fa-calendar-alt pe-1"></i>
                                                    {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                                                    ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d-m-Y')
                                                    : (\Carbon\Carbon::parse($event->start_date)->format('d-m-Y') ?? 'N/A') }}
                                        </h5>
                                        <p>
                                            {{ date('l', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_date : '')) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="card-info-payment">
                                        <h5><i class="fas fa-clock pe-1"></i>
                                            {{ optional(\Carbon\Carbon::make($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_time ? $event->eventDates->first()->start_time : $event->start_time))->format('h:i A') }}
                                            {{ optional(\Carbon\Carbon::make($event->eventDates->isNotEmpty() && $event->eventDates->first()->end_date ? $event->eventDates->first()->end_date : $event->end_date))->format('h:i A') }}
                                        </h5>
                                        <p>
                                            @php
                                            $eventDate = $event->eventDates->first();

                                            if ($eventDate && isset($eventDate->start_time) && isset($eventDate->end_time)) {
                                                $start = \Carbon\Carbon::parse($eventDate->start_time);
                                                $end = \Carbon\Carbon::parse($eventDate->end_time);

                                                if ($end->lessThan($start)) {
                                                    $end->addDay();
                                                }

                                                $hours = $start->diffInHours($end);
                                                $minutes = $start->diffInMinutes($end) % 60;
                                            } else {
                                                $hours = 0;
                                                $minutes = 0;
                                            }
                                        @endphp

                                        {{ $hours }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }} Hours

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- section full data to payment  -->
                        @if(!auth()->check())
                            <div class="col-12">
                                <div class="project-info-box">
                                    <!-- modal register -->
                                    @include('Frontend.layouts._message')
                                    <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content custom-modal-animation">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"> Create Account </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="login_form modal_login_form">
                                                        <form id="registerForm" action="#">
                                                            <div class="input_box">
                                                                <label for="email">Email</label>
                                                                <input type="email" id="email"
                                                                    placeholder="Enter email address" />
                                                            </div>
                                                            <div class="input_box">
                                                                <div class="password_title">
                                                                    <label for="password">Password</label>
                                                                </div>
                                                                <input type="password" id="password"
                                                                    placeholder="Enter your password" />
                                                            </div>
                                                            <div class="input_box">
                                                                <div class="password_title">
                                                                    <label for="password_confirmation">Confirm Password</label>
                                                                </div>
                                                                <input type="password" id="password_confirmation"
                                                                    placeholder="Confirm Password" />
                                                            </div>
                                                            <div class="input_box">
                                                                <div class="password_title">
                                                                    <label for="Confirm-password">Phone Number </label>
                                                                </div>
                                                                <input type="number" placeholder="Enter phone number"
                                                                    id="phone" />
                                                            </div>
                                                            <p id="error-message"
                                                                style="color: rgb(245, 33, 33); display: none;text-align: left;">
                                                                The password fields must match .</p>
                                                            <div class="col-12">
                                                                <div class="form-check pb-2">
                                                                    <input class="form-check-input" type="checkbox">
                                                                    <label class="form-check-label">
                                                                        Agree to terms and conditions
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <button type="submit" id="register" class="btn btn-primary">Create
                                                                Account</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="contact">
                                        <div class="mb-3 input-container">
                                            <i class="fas fa-user"></i>
                                            <input style="padding-left: 45px;" type="text" class="form-control"
                                                placeholder="First Name" name="first_name" id="first_name" required>
                                            @error('first_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 input-container">
                                            <i class="fas fa-user"></i>
                                            <input style="padding-left: 45px;" type="text" class="form-control"
                                                placeholder="Last Name" name="last_name" id="last_name" required>
                                            @error('last_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 input-container">
                                            <i class="fas fa-envelope"></i>
                                            <input style="padding-left: 45px;" type="email" class="form-control"
                                                placeholder="Email Address" name="email" id="email" required>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 input-container">
                                            <i class="fas fa-phone"></i>
                                            <input style="padding-left: 45px;" type="text" class="form-control"
                                                placeholder="Phone Number " name="phone" id="phone" required>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach($event->media as $media)
                                @if($media->name == 'banner')
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="project-image"
                                        class="rounded img-details mb-4">
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->check() ? auth()->user()->id : '' }}">
                    <input type="hidden" name="date_id" value="{{ $event->eventDates->first()->id ?? '' }}">
                    <!-- start part get ticket -->
                    <div class="col-12 col-md-5 col-lg-5 box-ticket-info">
                        <aside class="project-info-box mt-0 ">
                            <h4>Tickets</h4>
                            <div class="mt-3">
                                <!-- ticket type -->
                                <div id="ticket-container">
                                    <!-- Ticket Box one -->
                                    @foreach($event->tickets as $ticket)
                                        <label class="ticket-box d-flex justify-content-between align-items-center"
                                            data-ticket-type="Regular">
                                            <input type="radio" name="ticket_id" value="{{ $ticket->id }}" class="ticket-radio" style="display: none;">
                                            <div>
                                                <div class="ticket-header">
                                                    <span class="ticket-name">{{ $ticket->ticket_type }}</span>
                                                </div>
                                                <div class="ticket-price">
                                                    @if($ticket->price == 0)
                                                        <span class="price">Free</span>
                                                    @else
                                                        <span class="price">{{ $ticket->price }} {{ $ticket->currency }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ticket-quantity">
                                                <div class="d-flex justify-content-between align-items-baseline mt-3 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="value-button decrement"
                                                            style="display: {{ $ticket->price == 0 ? 'none' : '' }}">-</div>
                                                        <input type="number" name="quantity_{{ $ticket->id }}" class="mx-2 quantity" value="1" {{ $ticket->price == 0 ? 'readonly' : '' }}>
                                                        <div class="value-button increment"
                                                            style="display: {{ $ticket->price == 0 ? 'none' : '' }}">+</div>
                                                    </div>
                                                </div>
                                                <a class="remove"><i class="fa-solid fa-trash"></i></a>
                                            </div>
                                        </label>
                                    @endforeach
                                    <!-- Ticket Box two -->
                                </div>
                                <!-- Free Event Notice -->
                                <div class="free-event-notice" id="free-event-notice" style="display: none;">
                                    <i class="fas fa-gift"></i>
                                    <strong>Free Event!</strong> No payment required. You can complete your order directly.
                                </div>

                                <!-- payment method -->
                                <div class="payment-container" id="payment-methods-container" style="display: none;">
                                    <h4>Payment Method</h4>
                                    <div class="payment-methods">
                                        <!-- Credit/Debit Card Option -->
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="default" class="radio-input">
                                            <div class="payment-content">
                                                <img src="{{ asset('Front') }}/img/credit.png" alt="MasterCard"
                                                    class="payment-icon">
                                            </div>
                                        </label>
                                        <!-- Valu Pay Installments Option -->
                                        <label class="payment-option ">
                                            <input type="radio" name="payment_method" value="value" class="radio-input">
                                            <div class="payment-content">
                                                <img src="{{ asset('Front') }}/img/valu.webp" alt="MasterCard"
                                                    class="payment-icon">
                                            </div>
                                        </label>
                                        <!-- souhoola Option -->
                                        <label class="payment-option ">
                                            <input type="radio" name="payment_method" value="souhoola" class="radio-input">
                                            <div class="payment-content">
                                                <img src="{{ asset('Front') }}/img/souhoola.png" alt="MasterCard"
                                                    class="payment-icon">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- payment method -->
                                <div class="p-2 d-flex justify-content-center flex-column mt-2">
                                    <!-- total price -->
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <p class="fw-bold"> Total</p>
                                        <p class="fw-bold" id="totalPrice">0.00 {{ $event->currency?->code ?? 'EGP' }}</p>
                                        <input type="hidden" name="total_price" id="total_price" value="0">
                                        <input type="hidden" name="quantity" id="selected_quantity" value="1">
                                    </div>
                                    <button type="submit" class="btn-ticket" id="completeOrderBtn">Complete Order</button>
                                </div>
                            </div>
                        </aside>
                    </div>
                    <!-- end part get ticket -->
                </div>
            </div>
        </section>
    </form>
    <!-- end section All events -->
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let totalPrice = 0;
            let selectedTickets = new Set();

            function updateTotalPrice() {
                totalPrice = 0;
                let selectedQuantity = 1;
                
                document.querySelectorAll('.ticket-box').forEach((ticketBox) => {
                    // Check if this ticket is selected (has active class)
                    if (ticketBox.classList.contains('active')) {
                        let priceElement = ticketBox.querySelector(".ticket-price .price");
                        if (priceElement) {
                            let priceText = priceElement.textContent.trim();
                            let ticketPrice = 0;
                            if(priceText.toLowerCase() === 'free') {
                                ticketPrice = 0;
                            } else {
                                ticketPrice = parseFloat(priceText) || 0;
                            }
                            const quantityElement = ticketBox.querySelector(".quantity");
                            const quantity = quantityElement ? (parseInt(quantityElement.value) || 1) : 1;
                            totalPrice += ticketPrice * quantity;
                            selectedQuantity = quantity; // Store the selected ticket's quantity
                        }
                    }
                });

                const totalPriceElement = document.getElementById('total_price');
                const totalPriceDisplay = document.getElementById('totalPrice');
                const selectedQuantityElement = document.getElementById('selected_quantity');
                
                if (totalPriceElement) {
                    totalPriceElement.value = totalPrice.toFixed(2);
                }
                
                if (totalPriceDisplay) {
                    const currency = totalPriceDisplay.textContent.split(' ')[1] || 'EGP';
                    totalPriceDisplay.textContent = `${totalPrice.toFixed(2)} ${currency}`;
                }
                
                if (selectedQuantityElement) {
                    selectedQuantityElement.value = selectedQuantity;
                }

                // Show/hide payment methods based on total price
                togglePaymentMethods(totalPrice > 0);
            }

            // Function to show/hide payment methods
            function togglePaymentMethods(show) {
                const paymentContainer = document.getElementById('payment-methods-container');
                const freeEventNotice = document.getElementById('free-event-notice');
                
                if (paymentContainer) {
                    paymentContainer.style.display = show ? 'block' : 'none';
                }
                
                if (freeEventNotice) {
                    freeEventNotice.style.display = show ? 'none' : 'block';
                }
            }

            // Initialize with zero total
            updateTotalPrice();

            // Remove active class from all tickets initially
            document.querySelectorAll(".ticket-box").forEach(ticket => ticket.classList.remove("active"));

            // Uncheck all radio buttons initially
            document.querySelectorAll('.ticket-radio').forEach(radio => radio.checked = false);

            // Set initial quantity to 1 for all tickets
            document.querySelectorAll('.quantity').forEach(input => {
                input.value = 1;
                input.min = 1;
            });

            // Ensure selected quantity is never null
            const selectedQuantityElement = document.getElementById('selected_quantity');
            if (selectedQuantityElement) {
                selectedQuantityElement.value = 1;
            }

            // Handle ticket selection (radio button behavior)
            document.querySelectorAll(".ticket-box").forEach((box) => {
                box.addEventListener("click", () => {
                    // Remove active class from all tickets
                    document.querySelectorAll(".ticket-box").forEach((b) => {
                        b.classList.remove("active");
                        selectedTickets.delete(b);
                    });
                    
                    // Add active class to clicked ticket
                    box.classList.add("active");
                    selectedTickets.add(box);
                    
                    // Check the radio button
                    const radio = box.querySelector('.ticket-radio');
                    if (radio) {
                        radio.checked = true;
                    }
                    
                    // Update the selected quantity field
                    const quantityElement = box.querySelector('.quantity');
                    const selectedQuantityElement = document.getElementById('selected_quantity');
                    if (quantityElement && selectedQuantityElement) {
                        selectedQuantityElement.value = quantityElement.value || 1;
                    }
                    
                    updateTotalPrice();
                    toggleButtonState(); // Update button state when ticket selection changes
                });
            });

            // Helper function to handle ticket selection and price update
            function handleTicketUpdate(ticketBox) {
                if (ticketBox && ticketBox.classList.contains('active')) {
                    updateTotalPrice();
                    toggleButtonState();
                }
                
                // Auto-select if this is the only ticket
                if (document.querySelectorAll('.ticket-box').length === 1) {
                    // Remove active class from all tickets first
                    document.querySelectorAll(".ticket-box").forEach((b) => {
                        b.classList.remove("active");
                        selectedTickets.delete(b);
                    });
                    
                    // Select the single ticket
                    ticketBox.classList.add('active');
                    selectedTickets.add(ticketBox);
                    
                    // Check the radio button
                    const radio = ticketBox.querySelector('.ticket-radio');
                    if (radio) {
                        radio.checked = true;
                    }
                    
                    // Update the selected quantity field
                    const quantityElement = ticketBox.querySelector('.quantity');
                    const selectedQuantityElement = document.getElementById('selected_quantity');
                    if (quantityElement && selectedQuantityElement) {
                        selectedQuantityElement.value = quantityElement.value || 1;
                    }
                    
                    updateTotalPrice();
                    toggleButtonState();
                }
            }

            // Handle quantity changes
            document.addEventListener('input', function (event) {
                if (event.target.classList.contains('quantity')) {
                    let input = event.target;
                    let value = parseInt(input.value) || 1;
                    input.value = value < 1 ? 1 : value;
                    
                    let ticketBox = input.closest('.ticket-box');
                    
                    // Update the selected quantity field if this ticket is selected
                    if (ticketBox && ticketBox.classList.contains('active')) {
                        const selectedQuantityElement = document.getElementById('selected_quantity');
                        if (selectedQuantityElement) {
                            selectedQuantityElement.value = input.value;
                        }
                    }
                    
                    handleTicketUpdate(ticketBox);
                }
            });

            // Prevent ticket selection when clicking on quantity input
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('quantity')) {
                    event.stopPropagation(); // Prevent ticket selection
                }
            });

            // Handle increment button
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('increment')) {
                    event.stopPropagation(); // Prevent ticket selection
                    let ticketBox = event.target.closest('.ticket-box');
                    let input = ticketBox.querySelector('.quantity');
                    let currentValue = parseInt(input.value) || 1;
                    input.value = currentValue + 1;
                    
                    // Update the selected quantity field if this ticket is selected
                    if (ticketBox && ticketBox.classList.contains('active')) {
                        const selectedQuantityElement = document.getElementById('selected_quantity');
                        if (selectedQuantityElement) {
                            selectedQuantityElement.value = input.value;
                        }
                    }
                    
                    handleTicketUpdate(ticketBox);
                }
            });

            // Handle decrement button
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('decrement')) {
                    event.stopPropagation(); // Prevent ticket selection
                    let ticketBox = event.target.closest('.ticket-box');
                    let input = ticketBox.querySelector('.quantity');
                    let currentValue = parseInt(input.value) || 1;
                    
                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                        
                        // Update the selected quantity field if this ticket is selected
                        if (ticketBox && ticketBox.classList.contains('active')) {
                            const selectedQuantityElement = document.getElementById('selected_quantity');
                            if (selectedQuantityElement) {
                                selectedQuantityElement.value = input.value;
                            }
                        }
                        
                        handleTicketUpdate(ticketBox);
                    }
                }
            });

            // Handle ticket radio button changes
            document.querySelectorAll('.ticket-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove active class from all tickets
                    document.querySelectorAll(".ticket-box").forEach((b) => {
                        b.classList.remove("active");
                        selectedTickets.delete(b);
                    });
                    
                    // Add active class to the ticket with checked radio
                    const ticketBox = this.closest('.ticket-box');
                    if (ticketBox) {
                        ticketBox.classList.add("active");
                        selectedTickets.add(ticketBox);
                        
                        // Update the selected quantity field
                        const quantityElement = ticketBox.querySelector('.quantity');
                        const selectedQuantityElement = document.getElementById('selected_quantity');
                        if (quantityElement && selectedQuantityElement) {
                            selectedQuantityElement.value = quantityElement.value || 1;
                        }
                    }
                    
                    updateTotalPrice();
                    toggleButtonState();
                });
            });

            // Handle payment method selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all payment options
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                    // Add active class to clicked option
                    this.classList.add('active');
                });
            });

            // Handle form validation and button state
            const inputs = document.querySelectorAll("#contact input");
            const submitButton = document.querySelector(".btn-ticket");

            function toggleButtonState() {
                const isEmpty = Array.from(inputs).some(input => input.value.trim() === "");
                const hasSelectedTicket = selectedTickets.size > 0;
                const hasPaymentMethod = document.querySelector('input[name="payment_method"]:checked') !== null;
                const isFreeEvent = totalPrice === 0;
                
                // For free events, payment method is not required
                const paymentMethodRequired = !isFreeEvent;
                
                if (isEmpty || !hasSelectedTicket || (paymentMethodRequired && !hasPaymentMethod)) {
                    submitButton.classList.remove("btn-ticket");
                    submitButton.classList.add("btn-disabled");
                    submitButton.disabled = true;
                    
                    let tooltipTitle = 'Please fill all required fields';
                    if (!hasSelectedTicket) {
                        tooltipTitle += ' and select a ticket';
                    } else if (paymentMethodRequired && !hasPaymentMethod) {
                        tooltipTitle += ' and select a payment method';
                    }
                    
                    //show tooltip
                    new bootstrap.Tooltip(submitButton, {
                        title: tooltipTitle,
                        placement: 'top'
                    });
                } else {
                    submitButton.classList.remove("btn-disabled");
                    submitButton.classList.add("btn-ticket");
                    submitButton.disabled = false;
                }
            }

            // Add event listeners for form validation
            inputs.forEach(input => {
                input.addEventListener("input", toggleButtonState);
            });

            // Add event listener for payment method changes
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', toggleButtonState);
            });

            // Initial button state
            toggleButtonState();
        });


        // Add tooltip to the complete order button
        const completeOrderBtn = document.getElementById('completeOrderBtn');
        if (completeOrderBtn) {
            new bootstrap.Tooltip(completeOrderBtn, {
                title: 'Please fill all required fields',
                placement: 'top'
            });
        }
    </script>

@endpush