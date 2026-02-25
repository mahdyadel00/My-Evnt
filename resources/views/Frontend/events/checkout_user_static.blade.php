@extends('Frontend.layouts.master')
@section('title', 'Checkout')
@section('content')
@push('css')
    <!-- <link rel="stylesheet" href="{{ asset('Front/css/payment-methods.css') }}"> -->
@endpush
        <style>
        /* Enhanced Checkout Page Styles */
        .checkout-container {
            padding: 2rem 0;
        }

        .event-header-section {
            /* background: linear-gradient(135deg, #ed7326 0%, #f1683a 100%); */
            color: white;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 8px 25px rgba(237, 114, 38, 0.2);
        }

        .event-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            max-width:800px;
        }

        .event-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .info-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(237, 114, 38, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ed7326, #f1683a);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .info-card h5 {
            color: #ed7326;
            font-weight: 500;
            font-size: 1.1rem;
            margin-bottom: .7rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card h5 i {
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .info-card p {
            color: #666;
            margin: 0.5rem 0;
            font-size: 0.95rem;
        }

        .info-card a {
            color: #ed7326;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .info-card a:hover {
            color: #f1683a;
        }

        /* Enhanced Date and Time Display */
        .date-time-display {
            background: rgba(237, 114, 38, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid #ed7326;
        }

        .date-time-main {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .date-time-icon {
            background: #ed7326;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(237, 114, 38, 0.3);
        }

        .date-time-text {
            flex: 1;
        }

        .date-time-text .date {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .date-time-text .time {
            font-size: 0.95rem;
            color: #666;
        }

        .date-time-text .duration {
            font-size: 0.85rem;
            color: #888;
            font-style: italic;
        }

        /* Enhanced Form Section */
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .form-section h4 {
            color: #ed7326;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(237, 114, 38, 0.2);
        }

        .input-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-container i {
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            color: #ed7326;
            font-size: 1rem;
            z-index: 2;
            background-color: #fefefe;
        }

        .input-container input {
            padding: 12px 15px 12px 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-container input:focus {
            border-color: #ed7326;
            background: white;
            box-shadow: 0 0 0 3px rgba(237, 114, 38, 0.1);
            outline: none;
        }

        /* Enhanced Ticket Section */
        .ticket-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-top: 0;
          
        }

        .ticket-section h4 {
            color: #ed7326;
            font-weight: 600;
            margin-bottom: .5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(237, 114, 38, 0.2);
        }

        /* Disabled Payment Options */
        .payment-option-disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            filter: grayscale(100%);
            position: relative;
        }

        .payment-option-disabled input[type="radio"] {
            pointer-events: none !important;
        }

        .payment-option-disabled::after {
            content: 'غير متاح';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .payment-option-disabled:hover::after {
            opacity: 1;
        }

        /* SweetAlert Arabic Support */
        .swal-arabic {
            direction: rtl;
            text-align: right;
        }

        .swal-arabic .swal-title {
            direction: rtl;
            text-align: right;
        }

        .swal-arabic .swal-text {
            direction: rtl;
            text-align: right;
        }

        .swal-arabic .swal-button {
            direction: rtl;
        }

        /* Social Icons Styles */
        .share-links p {
            padding: 0;
            margin: 0;
        }

        .links {
            display: flex;
            flex-direction: row !important;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .social-icons a {
            text-decoration: none;
            color: gray;
            padding: 10px;
            font-size: 20px;
            transition: all 0.3s ease;
        }

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

        /* Responsive Design */
        @media screen and (max-width: 900px) {
            .social-icons a {
                padding: 7px;
            }

            .social-icons a i {
                font-size: 20px;
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .event-title {
                font-size: 1.5rem;
            }
            
            .info-cards-container {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }
            .ticket-section {
              width: 100%;
              margin: 0 auto;
              padding: 1rem;
            }
            /* .ticket-section {
                position: static;
                margin-top: 2rem;
            } */
            .form-section { 
              padding: 1rem;
            }
        }
    </style>
    <form action="{{ route('payment_checkout') }}" method="post">
        @csrf
        <!-- Enhanced Checkout Section -->
        <div class="checkout-container">
            <div class="container">
                <!-- Event Header Section -->
                <h1 class="event-title">{{ $event->name }}</h1>

                <div class="row">
                    <div class="col-lg-7">
                        <!-- Event Information Cards -->
                        <div class="info-cards-container">
                            <div class="info-card" style="display: {{ $event->format == 0 ? 'block' : 'none' }};">
                                <h5><i class="fa-thin fa-location-dot"></i> Location</h5>
                                <p><strong>{{ $event->city?->name }}</strong></p>
                                <p>
                                    <a href="https://www.google.com/maps/search/{{ $event->location }}" target="_blank">
                                    <i class="fa-thin fa-map-location-dot pe-1"></i> Go to Google Map
                                    </a>
                                </p>
                            </div>
                            
                            <div class="info-card">
                                <h5><i class="fa-thin fa-calendar-week"></i> Date & Time</h5>
                                <p><strong>{{ date('l, F j, Y', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_date : $event->start_date)) }}</strong></p>
                                <p>{{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_time ? \Carbon\Carbon::parse($event->eventDates->first()->start_time)->format('g:i A') : \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</p>
                            </div>
                        </div>
                        <!-- User Information Form Section -->
                        @if(!auth()->check())
                            <div class="form-section">
                                <h4><i class="fa-thin fa-user-plus"></i> Personal Information</h4>
                                @include('Frontend.layouts._message')
                                
                                <!-- Register Modal -->
                                <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content custom-modal-animation">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Create Account</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="login_form modal_login_form">
                                                    <form id="registerForm" action="#">
                                                        <div class="input_box">
                                                            <label for="email">Email</label>
                                                            <input type="email" id="email" placeholder="Enter email address" />
                                                        </div>
                                                        <div class="input_box">
                                                            <div class="password_title">
                                                                <label for="password">Password</label>
                                                            </div>
                                                            <input type="password" id="password" placeholder="Enter your password" />
                                                        </div>
                                                        <div class="input_box">
                                                            <div class="password_title">
                                                                <label for="password_confirmation">Confirm Password</label>
                                                            </div>
                                                            <input type="password" id="password_confirmation" placeholder="Confirm Password" />
                                                        </div>
                                                        <div class="input_box">
                                                            <div class="password_title">
                                                                <label for="Confirm-password">Phone Number</label>
                                                            </div>
                                                            <input type="number" placeholder="Enter phone number" id="phone" />
                                                        </div>
                                                        <p id="error-message" style="color: rgb(245, 33, 33); display: none;text-align: left;">
                                                            The password fields must match.
                                                        </p>
                                                        <div class="col-12">
                                                            <div class="form-check pb-2">
                                                                <input class="form-check-input" type="checkbox">
                                                                <label class="form-check-label">
                                                                    Agree to terms and conditions
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <button type="submit" id="register" class="btn btn-primary">Create Account</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Form -->
                                <div class="row" id="contact">
                                    <div class="col-md-12">
                                        <div class="input-container">
                                            <i class="fa-thin fa-user"></i>
                                            <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" required>
                                            @error('first_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-container">
                                            <i class="fa-thin fa-user"></i>
                                            <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" required>
                                            @error('last_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-container">
                                            <i class="fa-thin fa-envelope"></i>
                                            <input type="email" class="form-control" placeholder="Email Address" name="email" id="email" required>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-container">
                                            <i class="fa-thin fa-phone"></i>
                                            <input type="text" class="form-control" placeholder="Phone Number" name="phone" id="phone" required>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Card Photo -->
                                    <!-- <div class="col-md-12">
                                        <div class="input-container">
                                            <i class="fa-thin fa-image"></i>
                                            <input type="file" class="form-control" placeholder="Card Photo" name="card_photo" id="card_photo">
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        @else
                            <!-- Event Banner for logged in users -->
                            @foreach($event->media as $media)
                                @if($media->name == 'banner')
                                    <div class="section-cover">
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="Event Banner" class="img-fluid rounded">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->check() ? auth()->user()->id : '' }}">
                    <input type="hidden" name="date_id" value="{{ $event->eventDates->first()->id ?? '' }}">

                    <!-- Ticket Section -->
                    <div class="col-lg-5">
                        <div class="ticket-section">
                            <h4><i class="fa-thin fa-tickets"></i> Select Tickets</h4>
                            <div class="mt-3">
                                <!-- Ticket Types -->
                                <div id="ticket-container">
                                    @foreach($event->tickets as $ticket)
                                        <label class="ticket-box d-flex justify-content-between align-items-center" data-ticket-type="Regular">
                                            <input type="radio" name="ticket_id" value="{{ $ticket->id }}" class="ticket-radio" style="display: none;">
                                            <div>
                                                <div class="ticket-header">
                                                    <span class="ticket-name">{{ $ticket->ticket_type }}</span>
                                                </div>
                                                <div class="ticket-price">
                                                    @if($ticket->price == 0)
                                                        <span class="price">Free</span>
                                                    @else
                                                        <span class="price">
                                                        {{ $ticket->price }}
                                                        {{ $ticket->currency }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ticket-quantity">
                                                <div class="d-flex justify-content-between align-items-baseline mt-3 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="value-button decrement" style="display: {{ $ticket->price == 0 ? 'none' : '' }}">-</div>
                                                        <input type="number" name="quantity_{{ $ticket->id }}" class="mx-2 quantity" value="1" {{ $ticket->price == 0 ? 'readonly' : '' }}>
                                                        <div class="value-button increment" style="display: {{ $ticket->price == 0 ? 'none' : '' }}">+</div>
                                                    </div>
                                                </div>
                                                <a class="remove"><i class="fa-solid fa-trash"></i></a>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Free Event Notice -->
                                <!-- <div class="free-event-notice" id="free-event-notice" style="display: none;">
                                    <i class="fa-thin fa-gift"></i>
                                    <strong>Free Event!</strong> No payment required. You can complete your order directly.
                                </div> -->

                                <!-- Payment Method -->
                                <div class="payment-container" id="payment-methods-container" style="display: none;">
                                    <h4><i class="fa-thin fa-money-bill"></i> Payment Method</h4>
                                    <div class="payment-methods">
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="instapay" class="radio-input" checked>
                                            <div class="payment-content">
                                                <span class="payment-icon-text">InstaPay</span>
                                            </div>
                                        </label>
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="default" class="radio-input">
                                            <div class="payment-content">
                                                <img src="{{ asset('Front') }}/img/credit.png" alt="MasterCard" class="payment-icon">
                                            </div>
                                        </label>
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="value" class="radio-input">
                                            <div class="payment-content">
                                                <img src="{{ asset('Front') }}/img/valu.webp" alt="Valu" class="payment-icon">
                                            </div>
                                        </label>
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="souhoola" class="radio-input">
                                            <div class="payment-content">
                                                <img src="{{ asset('Front') }}/img/souhoola.png" alt="Souhoola" class="payment-icon">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Coupon Code -->
                               @if($event->coupons->isNotEmpty())
                                <div class="coupon-code">
                                    <h4><i class="fa-thin fa-ticket-alt"></i> Coupon Code</h4>
                                    <div class="input-container">
                                        <i class="fa-thin fa-ticket-alt"></i>
                                        <input type="text" name="coupon_code" id="coupon_code" placeholder="Enter coupon code">
                                        <div id="coupon-error" class="text-danger" style="display: none;"></div>
                                        <button type="button" id="applyCouponBtn" class="btn btn-primary">Apply</button>
                                    </div>
                                </div>
                               @endif

                                <!-- Total and Complete Order -->
                                <div class="p-2 d-flex justify-content-center flex-column mt-3">
                                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                                        <h5 class="fw-bold">Total</h5>
                                        <h5 class="fw-bold" id="totalPrice">0.00 {{ $event->currency?->code ?? 'EGP' }}</h5>
                                        <input type="hidden" name="total_price" id="total_price" value="0">
                                        <input type="hidden" name="quantity" id="selected_quantity" value="1">
                                    </div>
                                    <button type="submit" class="btn-ticket" id="completeOrderBtn">
                                        <i class="fa-thin fa-check-circle"></i> Complete Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    
                    // Auto-select InstaPay when payment methods are shown
                    if (show) {
                        const instaPayRadio = document.querySelector('input[name="payment_method"][value="instapay"]');
                        if (instaPayRadio) {
                            instaPayRadio.checked = true;
                            const instaPayLabel = instaPayRadio.closest('.payment-option');
                            if (instaPayLabel) {
                                instaPayLabel.classList.add('active');
                            }
                            toggleButtonState();
                        }
                    }
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
                option.addEventListener('click', function(e) {
                    // Remove active class from all payment options
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                    // Add active class to clicked option
                    this.classList.add('active');

                    // Check the related radio button
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                    }

                    toggleButtonState();
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

            // Add event listener for payment method changes (keep button state in sync)
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Sync active class with the checked radio
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                    const label = this.closest('.payment-option');
                    if (label) {
                        label.classList.add('active');
                    }

                    toggleButtonState();
                });
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
    <script>
        document.getElementById('applyCouponBtn').addEventListener('click', function() {
            const couponCode = document.getElementById('coupon_code').value;
            $.ajax({
                url: "{{ route('apply_coupon', $event->id) }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: { coupon_code: couponCode },
                success: function(response) {
                    //update total price
                    const totalPriceElement = document.getElementById('total_price');
                    totalPriceElement.value = response.totalPrice.toFixed(2);
                    //update total price display
                    const totalPriceDisplay = document.getElementById('totalPrice');
                    totalPriceDisplay.textContent = response.totalPrice + " EGP";
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>

@endpush