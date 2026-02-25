@extends('Frontend.layouts.master')
@section('title', $event->name)

@push('css')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <style>
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

        /* Pricing Styles */
        #session-types {
            display: flex;
            align-items: stretch;
        }

        #session-types .col-md-4 {
            display: flex;
        }

        .pricing-card {
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            min-height: 200px;
        }

        .pricing-card:hover {
            border-color: #FF6B35;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.1);
        }

        .pricing-card.selected {
            border-color: #FF6B35;
            background: linear-gradient(135deg, #fff8f5 0%, #ffe8dc 100%);
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .pricing-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .pricing-price {
            font-size: 2rem;
            font-weight: bold;
            color: #FF6B35;
        }

        .pricing-currency {
            font-size: 1rem;
            color: #666;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }

        .pricing-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li i {
            color: #28a745;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .quantity-btn {
            background: #FF6B35;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #E55A2B;
            transform: scale(1.1);
        }

        .quantity-display {
            font-size: 1.5rem;
            font-weight: bold;
            min-width: 50px;
            text-align: center;
            padding: 10px 15px;
            background: white;
            border-radius: 5px;
            border: 2px solid #dee2e6;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid #FF6B35;
        }

        .order-summary h5 {
            color: #FF6B35;
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: 2px solid #FF6B35;
            font-weight: bold;
            font-size: 1.1rem;
            color: #FF6B35;
        }

        .hidden {
            display: none !important;
        }

        @media screen and (max-width: 900px) {
            .social-icons a {
                padding: 7px;
            }

            .social-icons a i {
                font-size: 16px;
                padding: 10px;
            }

            .pricing-card {
                margin-bottom: 15px;
            }

            .quantity-selector {
                gap: 10px;
            }
        }

        @media screen and (max-width: 768px) {
            #session-types {
                flex-direction: column;
            }

            #session-types .col-md-4 {
                width: 100%;
                margin-bottom: 15px;
            }

            .pricing-card {
                min-height: auto;
            }
        }

        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .btn-ticket {
            background: linear-gradient(135deg, #FF6B35 0%, #E55A2B 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-ticket:hover {
            background: linear-gradient(135deg, #E55A2B 0%, #CC4E1F 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .input-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-container i {
            position: absolute;
            /* left: 15px; */
            top: 50%;
            transform: translateY(-50%);
            color: #FF6B35;
            z-index: 3;
            font-size: 1.1rem;
            /* width: 16px; */
            text-align: center;
        }

        .input-container:has(textarea) i {
            top: 20px;
            transform: none;
        }

        .input-container .form-control {
            padding-left: 45px !important;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
            /* height: 50px; */
        }

        .input-container textarea.form-control {
            height: auto;
            min-height: 100px;
            padding-top: 15px;
            padding-left: 0px !important;
        }

        .input-container .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }

        .input-container .form-control::placeholder {
            color: #999;
            font-size: 0.95rem;
        }

        /* Fix icon alignment for different screen sizes */
        @media (max-width: 768px) {
            .input-container i {
                /* left: 12px; */
                font-size: 1rem;
            }

            .input-container .form-control {
                padding-left: 40px !important;
                /* height: 45px; */
            }
        }

        /* Add better visual feedback for form validation */
        .input-container .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .input-container .form-control.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* Improve button animation */
        .btn-ticket {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-ticket:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        /* Loading state for submit button */
        .btn-ticket:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Date and Time picker styling */
        .input-container input[readonly] {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .input-container input[readonly]:focus {
            background-color: #fff;
        }

        /* Flatpickr custom styles */
        .flatpickr-calendar {
            font-family: inherit;
        }

        .flatpickr-time .flatpickr-am-pm {
            background: #FF6B35;
            color: white;
            border-radius: 3px;
            font-weight: bold;
        }

        /* Date/Time section styling */
        .date-time-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #FF6B35;
        }

        .date-time-section h6 {
            color: #FF6B35;
            margin-bottom: 15px;
        }

        .event-description,
        .event-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .event-details .text-primary {
            color: #FF6B35 !important;
        }

        /* Additional hover effects for better UX */
        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .alert-success {
            border-color: #FF6B35;
            background-color: #fff8f5;
            color: #CC4E1F;
        }

        .alert-success .border-success {
            border-color: #FF6B35 !important;
        }

        /* Card info payment icons */
        .card-info-payment h5 i {
            color: #FF6B35;
        }

        /* Project info box improvements */
        .project-info-box {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .project-info-box h4 {
            color: #333;
            border-bottom: 2px solid #FF6B35;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Custom Time Picker Styles */
        .time-picker-wrapper {
            position: relative;
            width: 100%;
        }

        .time-input {
            cursor: pointer;
            background-color: #fff;
            position: relative;
            padding-right: 45px !important;
        }

        .time-picker-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #FF6B35;
            font-size: 1.1rem;
            pointer-events: none;
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .time-picker-wrapper.active::after {
            transform: translateY(-50%) rotate(180deg);
        }

        .time-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #FF6B35;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }

        .time-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .time-header {
            background: linear-gradient(135deg, #FF6B35 0%, #E55A2B 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .time-grid {
            padding: 20px;
        }

        .time-period {
            margin-bottom: 20px;
        }

        .time-period:last-child {
            margin-bottom: 0;
        }

        .period-label {
            font-size: 14px;
            font-weight: 600;
            color: #FF6B35;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .time-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: 8px;
        }

        .time-option {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .time-option:hover {
            background: #FFE8DC;
            border-color: #FF6B35;
            color: #FF6B35;
            transform: translateY(-2px);
        }

        .time-option.selected {
            background: #FF6B35;
            border-color: #FF6B35;
            color: white;
            transform: translateY(-2px);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .time-dropdown {
                max-height: 300px;
                border-radius: 15px;
                margin-top: 5px;
            }

            .time-grid {
                padding: 15px;
            }

            .time-options {
                grid-template-columns: repeat(auto-fit, minmax(85px, 1fr));
                gap: 8px;
            }

            .time-option {
                padding: 12px 8px;
                font-size: 13px;
                border-radius: 10px;
                font-weight: 600;
            }

            .period-label {
                font-size: 13px;
                margin-bottom: 10px;
            }

            .time-header {
                padding: 12px 15px;
                font-size: 15px;
            }
        }

        @media (max-width: 576px) {
            .time-dropdown {
                max-height: 350px;
                left: -10px;
                right: -10px;
            }

            .time-options {
                grid-template-columns: repeat(3, 1fr);
                gap: 6px;
            }

            .time-option {
                padding: 10px 6px;
                font-size: 12px;
                border-radius: 8px;
            }

            .time-grid {
                padding: 12px;
            }

            .period-label {
                font-size: 12px;
                margin-bottom: 8px;
            }
        }

        /* Additional touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .time-option {
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Custom SweetAlert styles */
        .survey-success-popup {
            border-radius: 15px !important;
        }

        .survey-success-popup .swal2-title {
            color: #28a745 !important;
            font-size: 1.8rem !important;
            font-weight: bold !important;
        }

        .survey-success-popup .swal2-content {
            font-size: 1.1rem !important;
            color: #333 !important;
            line-height: 1.6 !important;
        }

        .survey-success-popup .swal2-confirm {
            border-radius: 8px !important;
            font-weight: bold !important;
            padding: 12px 30px !important;
        }

        @media screen and (max-width: 700px) {

            .box-ticket-info,
            .event-info-data {
                position: relative;
                bottom: 0;
                padding: 0;
                margin: 0;
                z-index: 9;
            }

            .mobile-details-res {
                flex-direction: column-reverse;
            }

            .event-description,
            .event-details {
                padding: 5px;
                margin-bottom: 5px;
            }
        }

        /* Mentorship Track & Attendee Type Select Styles */
        #mentorship_track option:disabled {
            color: #999 !important;
            background-color: #f5f5f5 !important;
            cursor: not-allowed !important;
            opacity: 0.5 !important;
            pointer-events: none !important;
        }
        
        #mentorship_track option.disabled-option {
            color: #999 !important;
            background-color: #f5f5f5 !important;
            cursor: not-allowed !important;
            opacity: 0.5 !important;
        }
        
        #mentorship_track,
        #attendee_type {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
        }

        #mentorship_track:focus,
        #attendee_type:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }
    </style>

    <!-- start section All event -->
    <section class="details">
        <div class="container mt-3">
            <div class="row d-flex flex-wrap align-items-baseline mobile-details-res">
                <div class="col-md-7">
                    <!-- start part get ticket info -->
                    <div class="project-info-box">
                        <h4 class="pb-2">{{ $event->name }}</h4>
                        <div class="row">
                            @if($event->format == 0)
                                <div class="col-lg-4 col-12">
                                    <div class="card-info-payment">
                                        <h5><i class="fas fa-map-marker-alt pe-1"></i> Location</h5>
                                        <p>
                                            <a href="https://www.google.com/maps/search/{{ $event->location }}" target="_blank">
                                                {{ $event->location }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-4 col-12">
                                <div class="card-info-payment">
                                    <h5><i class="fas fa-calendar-alt pe-1"></i>
                                        {{ date('d M Y', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_date : '')) }}
                                    </h5>
                                    <p>
                                        {{ date('l', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_date : '')) }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="card-info-payment">
                                    <h5><i class="fas fa-clock pe-1"></i>
                                        {{ date('h:i A', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_time : '')) }}
                                        <!-- -
                                                    {{ date('h:i A', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->end_time : '')) }} -->
                                    </h5>
                                    <p>
                                        @php
                                            $start = \Carbon\Carbon::parse($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_time : '00:00:00');
                                            $end = \Carbon\Carbon::parse($event->eventDates->isNotEmpty() ? $event->eventDates[0]->end_time : '00:00:00');
                                            $hours = $end->diffInHours($start);
                                            $minutes = $end->diffInMinutes($start) - ($hours * 60);
                                        @endphp
                                        {{ $hours }}:{{ sprintf('%02d', $minutes) }} Hours
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Contact Information Form -->
                    <div class="col-12" id="contact-form">
                        <div class="project-info-box">
                            <h4 class="mb-4">Contact Information</h4>
                            @include('Frontend.layouts._message')

                            <form action="{{ route('checkout_survey_hr_post') }}" method="post" id="checkout-form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <div class="row" id="contact">
                                    <div class="col-md-6">
                                        <div class="input-container">
                                            <i class="fas fa-user"></i>
                                            <input type="text" class="form-control" placeholder="First Name"
                                                name="first_name" id="first_name" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-container">
                                            <i class="fas fa-user"></i>
                                            <input type="text" class="form-control" placeholder="Last Name" name="last_name"
                                                id="last_name" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-container">
                                            <i class="fas fa-envelope"></i>
                                            <input type="email" class="form-control" placeholder="Email Address"
                                                name="email" id="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-container">
                                            <i class="fas fa-phone"></i>
                                            <input type="text" class="form-control"
                                                placeholder="Phone Number (e.g., 01012345678 or +201012345678)" name="phone"
                                                id="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-container">
                                            <i class="fas fa-briefcase"></i>
                                            <input type="text" class="form-control" placeholder="Job Title" name="job_title"
                                                id="job_title" value="{{ old('job_title') }}">
                                            @error('job_title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-container">
                                            <i class="fas fa-building"></i>
                                            <input type="text" class="form-control" placeholder="Organization"
                                                name="organization" id="organization" value="{{ old('organization') }}">
                                            @error('organization')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- Ticket Type --}}
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">Ticket Type</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ticket_type"
                                                        id="ticket_type_attendee" value="attendee" {{ old('ticket_type', 'attendee') === 'attendee' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ticket_type_attendee">
                                                        Attendee Ticket
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ticket_type"
                                                        id="ticket_type_startups" value="startups" {{ old('ticket_type') === 'startups' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ticket_type_startups">
                                                        Startups
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('ticket_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Attendee option (visible only when Attendee Ticket is selected) --}}
                                <div class="row mt-3 d-none" id="attendee-options">
                                    <div class="col-12">
                                        <label for="attendee_type" class="form-label fw-semibold">
                                            Attendee Type
                                        </label>
                                        <select name="attendee_type" id="attendee_type" class="form-control">
                                            <option value="">Select attendee type</option>
                                            <option value="attendee" {{ old('attendee_type') === 'attendee' ? 'selected' : '' }}>
                                                Attendee
                                            </option>
                                            <option value="mentorship" {{ old('attendee_type') === 'mentorship' ? 'selected' : '' }}>
                                                Mentorship
                                            </option>
                                        </select>
                                        @error('attendee_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Mentorship tracks (visible when Mentorship is selected from Attendee Type) --}}
                                <div class="row mt-3 d-none" id="mentorship-tracks-options">
                                    <div class="col-12">
                                        <label for="mentorship_track" class="form-label fw-semibold">
                                            Mentorship Track
                                        </label>
                                        <select name="mentorship_track" id="mentorship_track" class="form-control">
                                            <option value="">Select mentorship track</option>
                                            <!-- <option value="agricultural_biotech" data-original-text="Mentorship (Agricultural Biotech)" {{ old('mentorship_track') === 'agricultural_biotech' ? 'selected' : '' }}>
                                                Mentorship (Agricultural Biotech)
                                            </option> -->
                                            <option value="food_packaging_processing" data-original-text="Mentorship (Food Packaging/Processing)" {{ old('mentorship_track') === 'food_packaging_processing' ? 'selected' : '' }}>
                                                Mentorship (Food Packaging/Processing)
                                            </option>
                                            <option value="medicinal_aromatic_plants" data-original-text="Mentorship (Medicinal & Aromatic Plants)" {{ old('mentorship_track') === 'medicinal_aromatic_plants' ? 'selected' : '' }}>
                                                Mentorship (Medicinal &amp; Aromatic Plants)
                                            </option>
                                        </select>
                                        @error('mentorship_track')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Startups upload (visible only when Startups is selected) --}}
                                <div class="row mt-3 d-none" id="startup-upload">
                                    <div class="col-12">
                                        <p class="text-muted">
                                            This ticket is for early-stage agriculture startups and researchers with a Proof
                                            of Concept (PoC), prototype, who want to pitch their innovations and connect
                                            with investors to explore commercialization opportunities and to get access to
                                            Aria ventures fund and support
                                        </p>
                                        <label for="startup_file" class="form-label fw-semibold">
                                            Upload your Pitch Deck (PDF, PPT, DOC)
                                        </label>
                                        <input type="file" name="startup_file" id="startup_file" class="form-control"
                                            accept=".pdf,.ppt,.pptx,.doc,.docx">
                                        @error('startup_file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="order-summary">
                                    <h5><i class="fas fa-ticket-alt me-2"></i>Order Summary</h5>
                                    <div class="summary-row">
                                        <span>Ticket Price:</span>
                                        <span id="summary-ticket-price">Free</span>
                                    </div>
                                </div>

                                <div class="p-2 d-flex justify-content-center flex-column mt-4">
                                    <button type="submit" class="btn-ticket">
                                        <i class="fas fa-credit-card me-2"></i>Complete Order
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Event Details Sidebar -->
                <div class="col-12 col-md-5 col-lg-5 box-ticket-info">
                    <aside class="project-info-box mt-0">
                        <h4>{{ $event->category?->name }}</h4>
                        <div class="mt-3">
                            @foreach ($event->media as $media)
                                @if ($media->name == 'banner')
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="project-image" class="rounded mb-4">
                                @endif
                            @endforeach

                            <div class="event-description">
                                <h6 class="mb-3 fw-semibold">About this Event</h6>
                                <p class="text-muted">{!! Str::limit($event->summary ?? $event->description, 200) !!}</p>
                            </div>

                            <div class=" event-details mt-2">
                                <h6 class="mb-3 fw-semibold">Event Details</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-users me-2" style="color: #FF6B35;"></i>
                                        <strong>Organizer:</strong> {{ $event->company->company_name ?? 'Event Organizer' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-tag me-2" style="color: #FF6B35;"></i>
                                        <strong>Category:</strong> {{ $event->category->name ?? 'General' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-globe me-2" style="color: #FF6B35;"></i>
                                        <strong>Format:</strong> {{ $event->format ? 'Online' : 'Offline' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                    <!-- start map in desktop -->
                    @if($event->format == 0)
                        <div class=" map-card">
                            <div class="map-header">
                                <i class="fas fa-map-marker-alt"></i>
                                <h5>Location</h5>
                            </div>
                            <div class="map-desktop" id="map-desktop">
                                @if($event->latitude && $event->longitude)
                                    <a href="https://www.google.com/maps?q={{ urlencode($event->location) }}+{{ $event->latitude }},{{ $event->longitude }}"
                                        target="_blank" rel="noopener noreferrer">
                                        <iframe
                                            src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_api_key') }}&q={{ $event->latitude }},{{ $event->longitude }}"
                                            width="100%" height="300" style="border:0; pointer-events: none;" allowfullscreen=""
                                            loading="lazy">
                                        </iframe>
                                    </a>
                                @else
                                    <div class="alert alert-warning">
                                        Location information is not available for this event.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <!-- end map in desktop -->
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Tooltip Styles -->
    <style>
        .input-container {
            position: relative;
        }

        .tooltip-content {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #ed7326 0%, #ff8c42 100%);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 10px 25px rgba(237, 115, 38, 0.3);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .tooltip-content.show {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        .tooltip-content::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 20px;
            width: 12px;
            height: 12px;
            background: linear-gradient(135deg, #ed7326 0%, #ff8c42 100%);
            transform: rotate(45deg);
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .tooltip-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: white;
            font-size: 14px;
        }

        .tooltip-header i {
            font-size: 16px;
            color: #ffe066 !important;
        }

        .tooltip-body {
            color: rgba(255, 255, 255, 0.9);
        }

        .format-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #ffe066;
            font-size: 12px;
        }

        .format-options {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 12px;
        }

        .format-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
        }

        .format-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(4px);
        }

        .supported-networks {
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-container input:focus+.tooltip-content {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .tooltip-content {
                position: fixed;
                top: auto;
                bottom: 20px;
                left: 20px;
                right: 20px;
                z-index: 9999;
            }

            .tooltip-content::before {
                display: none;
            }
        }

        /* Enhanced input focus effects */
        .input-container input:focus {
            border-color: #ed7326;
            box-shadow: 0 0 0 3px rgba(237, 115, 38, 0.1);
            outline: none;
        }

        /* Animation for tooltip appearance */
        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .tooltip-content.show {
            animation: tooltipFadeIn 0.3s ease-out;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE') }}&libraries=places&callback=initMap"
        async defer></script>
        <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE&libraries=places&callback=initMap"
        async defer></script>

    <script>
        function initMap() {
            if (typeof google !== 'object' || typeof google.maps !== 'object') {
                console.error("❌ Google Maps API not loaded correctly.");
                return;
            }

            var eventLocation = {
                lat: parseFloat("{{ $event->latitude }}"),
                lng: parseFloat("{{ $event->longitude }}")
            };

            if (isNaN(eventLocation.lat) || isNaN(eventLocation.lng)) {
                console.error("❌ Invalid latitude or longitude values.");
                eventLocation = { lat: 30.0444, lng: 31.2357 };
            }

            var mapOptions = {
                zoom: 15,
                center: eventLocation,
                mapTypeControl: false,
                streetViewControl: false
            };

            var mapDesktop = new google.maps.Map(document.getElementById('map-desktop'), mapOptions);

            var infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px; text-align: center;">
                        <h6 style="margin: 0; color: #ed7326; font-weight: bold;">{{ $event->name }}</h6>
                        <p style="margin: 5px 0; color: #666;">{{ $event->location }}</p>
                        <p style="margin: 0; color: #888; font-size: 12px;">{{ $event->city?->name }}, {{ $event->city?->country?->name }}</p>
                    </div>
                `
            });

            var marker = new google.maps.Marker({
                position: eventLocation,
                map: mapDesktop,
                title: "{{ $event->name }} - {{ $event->location }}",
                animation: google.maps.Animation.DROP,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    scaledSize: new google.maps.Size(40, 40)
                }
            });

            marker.addListener('mouseover', function () {
                infoWindow.open(mapDesktop, marker);
            });

            marker.addListener('mouseout', function () {
                infoWindow.close();
            });

            marker.addListener('click', function () {
                var url = `https://www.google.com/maps?q={{ urlencode($event->location) }}+{{ $event->latitude }},{{ $event->longitude }}`;
                window.open(url, '_blank');
            });

            var mapMobileElement = document.getElementById('map-mobile');
            if (mapMobileElement) {
                var mapMobile = new google.maps.Map(mapMobileElement, mapOptions);

                var markerMobile = new google.maps.Marker({
                    position: eventLocation,
                    map: mapMobile,
                    title: "{{ $event->name }} - {{ $event->location }}",
                    animation: google.maps.Animation.DROP,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                        scaledSize: new google.maps.Size(40, 40)
                    }
                });

                markerMobile.addListener('click', function () {
                    infoWindow.open(mapMobile, markerMobile);
                });

                markerMobile.addListener('click', function () {
                    var url = `https://www.google.com/maps?q={{ urlencode($event->location) }}+{{ $event->latitude }},{{ $event->longitude }}`;
                    window.open(url, '_blank');
                });

                mapMobile.setOptions({
                    draggable: false,
                    zoomControl: false,
                    scrollwheel: false,
                    disableDoubleClickZoom: true
                });
            }

            mapDesktop.setOptions({
                draggable: true,
                zoomControl: true,
                scrollwheel: true,
                disableDoubleClickZoom: false
            });
        }
    </script>
    <script>
        function initMap() {
            // التأكد من تحميل Google Maps API بشكل صحيح
            if (typeof google !== 'object' || typeof google.maps !== 'object') {
                console.error("❌ Google Maps API not loaded correctly.");
                return;
            }

            // إحداثيات الحدث من البيانات
            var eventLocation = {
                lat: parseFloat("{{ $event->latitude }} "),
                lng: parseFloat("{{ $event->longitude }}")
            };

            // التأكد من أن الإحداثيات صالحة
            if (isNaN(eventLocation.lat) || isNaN(eventLocation.lng)) {
                console.error("❌ Invalid latitude or longitude values.");
                eventLocation = { lat: 30.0444, lng: 31.2357 }; // القيمة الافتراضية (القاهرة)
            }

            // إعداد الخيارات المشتركة للخريطة
            var mapOptions = {
                zoom: 15,
                center: eventLocation,
                mapTypeControl: false, // إزالة خيارات نوع الخريطة
                streetViewControl: false // تعطيل Street View
            };

            // إنشاء الخريطة للـ Desktop
            var mapDesktop = new google.maps.Map(document.getElementById('map-desktop'), mapOptions);

            // إنشاء InfoWindow لعرض معلومات المكان
            var infoWindow = new google.maps.InfoWindow({
                content: `
                            <div style="padding: 10px; text-align: center;">
                                <h6 style="margin: 0; color: #ed7326; font-weight: bold;">{{ $event->name }}</h6>
                                <p style="margin: 5px 0; color: #666;">{{ $event->location }}</p>
                                <p style="margin: 0; color: #888; font-size: 12px;">{{ $event->city?->name }}, {{ $event->city?->country?->name }}</p>
                            </div>
                        `
            });

            // إنشاء نقطة (Marker) للحدث على الخريطة للـ Desktop
            var marker = new google.maps.Marker({
                position: eventLocation,
                map: mapDesktop,
                title: "{{ $event->name }} - {{ $event->location }}",
                animation: google.maps.Animation.DROP,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    scaledSize: new google.maps.Size(40, 40)
                }
            });

            // إضافة event listeners للـ marker
            marker.addListener('mouseover', function () {
                infoWindow.open(mapDesktop, marker);
            });

            marker.addListener('mouseout', function () {
                infoWindow.close();
            });

            marker.addListener('click', function () {
                var url = `https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}`;
                window.open(url, '_blank');
            });

            var mapMobileElement = document.getElementById('map-mobile');
            if (mapMobileElement) {
                var mapMobile = new google.maps.Map(mapMobileElement, mapOptions);

                var markerMobile = new google.maps.Marker({
                    position: eventLocation,
                    map: mapMobile,
                    title: "{{ $event->name }} - {{ $event->location }}",
                    animation: google.maps.Animation.DROP,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                        scaledSize: new google.maps.Size(40, 40)
                    }
                        < script >



                        // Session selection
                        document.querySelectorAll('.pricing-card').forEach(card => {
                            card.addEventListener('click', function () {
                                // Remove selected class from all cards
                                document.querySelectorAll('.pricing-card').forEach(c => c.classList.remove('selected'));

                                // Add selected class to clicked card
                                this.classList.add('selected');


                                // Show quantity selector and contact form
                                // document.getElementById('quantity-section').classList.remove('hidden');
                                document.getElementById('contact-form').style.display = 'block';

                                // Update order summary
                                updateOrderSummary();

                                // Smooth scroll to contact form
                                document.getElementById('contact-form').scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });

                                markerMobile.addListener('click', function () {
                                    infoWindow.open(mapMobile, markerMobile);
                                });

                                markerMobile.addListener('click', function () {
                                    var url = `https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}`;
                                    window.open(url, '_blank');
                                });

                                mapMobile.setOptions({
                                    draggable: false,
                                    zoomControl: false,
                                    scrollwheel: false,
                                    disableDoubleClickZoom: true
                                });
                            }

                    mapDesktop.setOptions({
                                draggable: true,
                                zoomControl: true,
                                scrollwheel: true,
                                disableDoubleClickZoom: false
                            });
                        });






                    document.addEventListener('DOMContentLoaded', function () {
                        if (document.querySelector('.pricing-card')) {
                            let unitPrice = parseFloat(document.querySelector('.pricing-card').getAttribute('data-price'));
                            document.getElementById('session_type').value = document.querySelector('.pricing-card').getAttribute('data-session-type');

                            // Quantity selector logic
                            const qtyInput = document.getElementById('quantity');
                            const decreaseBtn = document.getElementById('decrease-qty');
                            const increaseBtn = document.getElementById('increase-qty');
                            const summaryQty = document.getElementById('summary-quantity');
                            let unitPrice = 0; // You may want to dynamically set this from session selection

                            decreaseBtn.addEventListener('click', function () {
                                let qty = parseInt(qtyInput.value);
                                if (qty > 1) {
                                    qty--;
                                    qtyInput.value = qty;
                                    summaryQty.textContent = qty;

                                    updateOrderSummary();
                                }

                                // Ensure SweetAlert is set up correctly
                                @if(session('alert'))
                                    Swal.fire({
                                        icon: '{{ session('alert.type') }}',
                                        title: '{{ session('alert.type') === 'success' ? 'Success!' : 'Error!' }}',
                                        text: '{{ session('alert.message') }}',
                                    });
                                @endif
                                                                                                                                                                                                                                                                                        });

                            let unitPrice = 0; // You may want to dynamically set this from session selection

                            function updateOrderSummary() {
                                // Display ticket price or "Free" if price is 0
                                const ticketPriceElement = document.getElementById('summary-ticket-price');
                                if (unitPrice === 0) {
                                    ticketPriceElement.textContent = 'Free';
                                } else {
                                    ticketPriceElement.textContent = unitPrice + ' EGP';
                                }
                            }

                            // Call updateOrderSummary initially
                            updateOrderSummary();

                            // Form validation and submission
                            document.getElementById('checkout-form').addEventListener('submit', function (e) {
                                e.preventDefault(); // Prevent default form submission

                                const requiredFields = ['first_name', 'last_name', 'email', 'phone'];
                                let isValid = true;

                                requiredFields.forEach(field => {
                                    const input = document.getElementById(field);
                                    if (!input.value.trim()) {
                                        isValid = false;
                                        input.classList.add('is-invalid');
                                    } else {
                                        input.classList.remove('is-invalid');
                                    }
                                });

                                // Ticket type validation
                                const selectedTicketType = document.querySelector('input[name="ticket_type"]:checked');
                                const attendeeType = document.getElementById('attendee_type');
                                const mentorshipTrack = document.getElementById('mentorship_track');
                                const startupFileInput = document.getElementById('startup_file');

                                if (!selectedTicketType) {
                                    isValid = false;
                                } else if (selectedTicketType.value === 'attendee') {
                                    if (!attendeeType || !attendeeType.value) {
                                        isValid = false;
                                        if (attendeeType) {
                                            attendeeType.style.borderColor = '#dc3545';
                                        }
                                    } else {
                                        if (attendeeType) {
                                            attendeeType.style.borderColor = '';
                                        }
                                        // If attendee type is mentorship, check mentorship track
                                        if (attendeeType.value === 'mentorship') {
                                            if (!mentorshipTrack || !mentorshipTrack.value) {
                                                isValid = false;
                                                if (mentorshipTrack) {
                                                    mentorshipTrack.style.borderColor = '#dc3545';
                                                }
                                            } else {
                                                if (mentorshipTrack) {
                                                    mentorshipTrack.style.borderColor = '';
                                                }
                                            }
                                        }
                                    }
                                } else if (selectedTicketType.value === 'startups') {
                                    if (!startupFileInput || !startupFileInput.files || startupFileInput.files.length === 0) {
                                        isValid = false;
                                        if (startupFileInput) {
                                            startupFileInput.style.borderColor = '#dc3545';
                                        }
                                    } else {
                                        if (startupFileInput) {
                                            startupFileInput.style.borderColor = '';
                                        }
                                    }
                                }

                                if (!isValid) {
                                    Swal.fire({
                                        title: 'Missing Information',
                                        text: 'Please fill in all required fields and ticket details',
                                        icon: 'warning',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#FF6B35'
                                    });
                                    return false;
                                }

                                // Email validation
                                const email = document.getElementById('email').value;
                                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                if (!emailRegex.test(email)) {
                                    Swal.fire({
                                        title: 'Invalid Email',
                                        text: 'Please enter a valid email address',
                                        icon: 'warning',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#FF6B35'
                                    });
                                    return false;
                                }

                                // Check mentorship track limit before submitting
                                const attendeeType = document.getElementById('attendee_type');
                                const mentorshipTrackSelect = document.getElementById('mentorship_track');
                                if (attendeeType && attendeeType.value === 'mentorship' && mentorshipTrackSelect && mentorshipTrackSelect.value) {
                                    const selectedTrack = mentorshipTrackSelect.value;
                                    if (trackIsFull[selectedTrack]) {
                                        Swal.fire({
                                            title: 'Limit Reached',
                                            text: 'The limit has been reached for this selection',
                                            icon: 'warning',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: '#FF6B35'
                                        });
                                        return false;
                                    }
                                }

                                // Submit form via AJAX
                                const submitBtn = this.querySelector('.btn-ticket');
                                const originalText = submitBtn.innerHTML;
                                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                                submitBtn.disabled = true;

                                // Create FormData and clean it based on user selections
                                const formData = new FormData(this);
                                
                                // Get selected ticket type
                                const selectedTicketType = document.querySelector('input[name="ticket_type"]:checked');
                                
                                // Remove fields that shouldn't be sent based on ticket type
                                if (selectedTicketType && selectedTicketType.value === 'attendee') {
                                    // If attendee type is not mentorship, remove mentorship_track
                                    if (attendeeType && attendeeType.value !== 'mentorship') {
                                        formData.delete('mentorship_track');
                                    }
                                    // Remove startup_file if ticket type is attendee
                                    formData.delete('startup_file');
                                } else if (selectedTicketType && selectedTicketType.value === 'startups') {
                                    // Remove attendee-related fields if ticket type is startups
                                    formData.delete('attendee_type');
                                    formData.delete('mentorship_track');
                                }

                                fetch(this.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json'
                                    }
                                })
                                    .then(async response => {
                                        // Parse JSON response regardless of status
                                        let data;
                                        try {
                                            data = await response.json();
                                        } catch (e) {
                                            // If JSON parsing fails, create default error
                                            throw {
                                                message: 'An error occurred, please try again.',
                                                title: 'Error!',
                                                data: null
                                            };
                                        }
                                        
                                        // Check if response is ok
                                        if (!response.ok) {
                                            // Use the error data from response
                                            throw {
                                                message: data.message || 'An error occurred, please try again.',
                                                title: data.title || 'Error!',
                                                data: data,
                                                response: response
                                            };
                                        }
                                        
                                        return data;
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire({
                                                title: data.title || 'Thank you! Your registration was successful',
                                                html: `
                                                    <div style="padding: 10px;">
                                                        <p style="font-size: 16px; margin-bottom: 15px;">
                                                            ${data.message || 'Your registration was successful'}
                                                        </p>
                                                        <p style="font-size: 14px; color: #667eea; margin-top: 15px; font-weight: 600;">
                                                            <i class="fa fa-envelope me-2"></i>
                                                            Please check your email
                                                        </p>
                                                    </div>
                                                `,
                                                icon: 'success',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#28a745'
                                            }).then(async () => {
                                                // Refresh track counts BEFORE resetting form
                                                if (attendeeTypeSelect && attendeeTypeSelect.value === 'mentorship') {
                                                    await updateMentorshipTrackLimits();
                                                    // Reset form fields after updating counts
                                                    document.getElementById('checkout-form').reset();
                                                    // Reset attendee type selection to show mentorship again
                                                    if (attendeeTypeSelect) {
                                                        attendeeTypeSelect.value = 'mentorship';
                                                        updateAttendeeTypeUI();
                                                    }
                                                } else {
                                                // Reset form fields
                                                document.getElementById('checkout-form').reset();
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                title: data.title || 'Error!',
                                                text: data.message || 'An error occurred, please try again.',
                                                icon: 'error',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#dc3545'
                                            }).then(() => {
                                                // Refresh track counts if mentorship track error
                                                if (attendeeType && attendeeType.value === 'mentorship') {
                                                    updateMentorshipTrackLimits();
                                                }
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        console.error('Error data:', error.data);
                                        
                                        // Check if error has title and message from API
                                        // First check error.data, then error itself
                                        const errorData = error.data || error;
                                        const errorTitle = errorData.title || error.title || 'Error!';
                                        const errorMessage = errorData.message || error.message || 'An error occurred, please try again.';
                                        
                                        console.log('Showing error modal with title:', errorTitle, 'message:', errorMessage);
                                        
                                        Swal.fire({
                                            title: errorTitle,
                                            text: errorMessage,
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: '#dc3545'
                                        }).then(() => {
                                            // Refresh track counts on error
                                            if (attendeeType && attendeeType.value === 'mentorship') {
                                                updateMentorshipTrackLimits();
                                            }
                                        });
                                    })
                                    .finally(() => {
                                        submitBtn.innerHTML = originalText;
                                        submitBtn.disabled = false;
                                    });

                                return false;
                            });

                            // Real-time email validation
                            document.getElementById('email').addEventListener('blur', function () {
                                const email = this.value;
                                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                                if (email && !emailRegex.test(email)) {
                                    this.classList.add('is-invalid');
                                } else {
                                    this.classList.remove('is-invalid');
                                }
                            });

                            // Phone number formatting (Egyptian format)
                            document.getElementById('phone').addEventListener('input', function () {
                                let value = this.value.replace(/\D/g, ''); // Remove non-digits

                                if (value.length > 0) {
                                    // Handle country code removal for Egypt (+2 or 002)
                                    if (value.startsWith('002')) {
                                        value = value.substring(3);
                                    } else if (value.startsWith('2') && value.length > 11) {
                                        value = value.substring(1);
                                    }

                                    // Ensure it starts with 01 and has valid second digit
                                    if (value.length <= 11) {
                                        if (value.startsWith('01')) {
                                            this.value = value;
                                        } else if (value.startsWith('1') && value.length <= 10) {
                                            this.value = '0' + value;
                                        } else {
                                            this.value = value;
                                        }
                                    } else {
                                        this.value = value.substring(0, 11);
                                    }
                                }
                            });

                            // Phone number validation (Egyptian format)
                            document.getElementById('phone').addEventListener('blur', function () {
                                const phone = this.value.replace(/\D/g, ''); // Remove non-digits
                                const validPrefixes = ['010', '011', '012', '015', '016', '017', '018', '019'];

                                if (phone && phone.length === 11) {
                                    const prefix = phone.substring(0, 3);
                                    if (!validPrefixes.includes(prefix)) {
                                        this.classList.add('is-invalid');
                                        this.setAttribute('title', 'Please enter a valid Egyptian mobile number (010, 011, 012, 015, 016, 017, 018, 019)');
                                    } else {
                                        this.classList.remove('is-invalid');
                                        this.removeAttribute('title');
                                    }
                                } else if (phone && phone.length !== 11) {
                                    this.classList.add('is-invalid');
                                    this.setAttribute('title', 'Egyptian mobile number must be 11 digits');
                                } else {
                                    this.classList.remove('is-invalid');
                                    this.removeAttribute('title');
                                }
                            });

                            // Ticket type behaviour (show/hide attendee options & startup upload)
                            const ticketTypeRadios = document.querySelectorAll('input[name="ticket_type"]');
                            const attendeeOptions = document.getElementById('attendee-options');
                            const startupUpload = document.getElementById('startup-upload');

                            function updateTicketTypeUI() {
                                if (!ticketTypeRadios.length) {
                                    return;
                                }

                                const selected = document.querySelector('input[name="ticket_type"]:checked');

                                if (!selected) {
                                    attendeeOptions?.classList.add('d-none');
                                    startupUpload?.classList.add('d-none');
                                    return;
                                }

                                if (selected.value === 'attendee') {
                                    attendeeOptions?.classList.remove('d-none');
                                    startupUpload?.classList.add('d-none');
                                } else if (selected.value === 'startups') {
                                    attendeeOptions?.classList.add('d-none');
                                    startupUpload?.classList.remove('d-none');
                                } else {
                                    attendeeOptions?.classList.add('d-none');
                                    startupUpload?.classList.add('d-none');
                                }
                            }

                            ticketTypeRadios.forEach(radio => {
                                radio.addEventListener('change', updateTicketTypeUI);
                            });

                            // Initialise on page load (respecting old() values)
                            updateTicketTypeUI();

                            // Attendee Type change handler (show/hide mentorship tracks)
                            const attendeeTypeSelect = document.getElementById('attendee_type');
                            const mentorshipTracksOptions = document.getElementById('mentorship-tracks-options');

                            function updateAttendeeTypeUI() {
                                if (!attendeeTypeSelect) {
                                    return;
                                }

                                const selectedValue = attendeeTypeSelect.value;

                                if (selectedValue === 'mentorship') {
                                    mentorshipTracksOptions?.classList.remove('d-none');
                                } else {
                                    mentorshipTracksOptions?.classList.add('d-none');
                                }
                            }

                            if (attendeeTypeSelect) {
                                attendeeTypeSelect.addEventListener('change', updateAttendeeTypeUI);
                                // Initialise on page load
                                updateAttendeeTypeUI();
                            }

                            // Mentorship Track Counts and Limits
                            const mentorshipTrackSelect = document.getElementById('mentorship_track');
                            const eventId = {{ $event->id }};
                            
                            if (!mentorshipTrackSelect) {
                                console.error('Mentorship track select element not found!');
                            } else {
                                console.log('Mentorship track select element found:', mentorshipTrackSelect);
                            }
                            
                            let trackCounts = {
                                agricultural_biotech: 0,
                                food_packaging_processing: 0,
                                medicinal_aromatic_plants: 0
                            };
                            let trackIsFull = {
                                agricultural_biotech: false,
                                food_packaging_processing: false,
                                medicinal_aromatic_plants: false
                            };
                            const maxPerTrack = 12;
                            
                            console.log('Initialized mentorship track limits with maxPerTrack:', maxPerTrack);

                            // Function to fetch track counts and update UI
                            async function updateMentorshipTrackLimits() {
                                try {
                                    console.log('Fetching track counts for event:', eventId);
                                    const response = await fetch(`/api/mentorship-track-counts/${eventId}`);
                                    
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }
                                    
                                    const data = await response.json();
                                    console.log('Track counts data:', data);
                                    
                                    if (data.success) {
                                        trackCounts = data.counts;
                                        trackIsFull = data.isFull;
                                        
                                        console.log('Track counts:', trackCounts);
                                        console.log('Track isFull:', trackIsFull);
                                        
                                        // Update select options
                                        if (mentorshipTrackSelect) {
                                            const options = mentorshipTrackSelect.querySelectorAll('option');
                                            console.log('Found options:', options.length);
                                            
                                            options.forEach(option => {
                                                const value = option.value;
                                                if (value && value !== '') {
                                                    const trackKey = value;
                                                    const isFull = trackIsFull[trackKey] || false;
                                                    const count = trackCounts[trackKey] || 0;
                                                    const remaining = maxPerTrack - count;
                                                    
                                                    console.log(`Track: ${trackKey}, Count: ${count}, IsFull: ${isFull}, Max: ${maxPerTrack}`);
                                                    
                                                    // Get original text from data attribute or use current text
                                                    let originalText = option.getAttribute('data-original-text');
                                                    if (!originalText) {
                                                        // If no data attribute, extract original text (remove any existing count info)
                                                        originalText = option.textContent.replace(/\s*\(.*?\)\s*$/, '').trim();
                                                        option.setAttribute('data-original-text', originalText);
                                                    }
                                                    
                                                    // Disable if full - CRITICAL: This prevents selection
                                                    // Check if count >= maxPerTrack (not just isFull from API)
                                                    const isActuallyFull = count >= maxPerTrack;
                                                    const shouldDisable = isActuallyFull || isFull;
                                                    
                                                    console.log(`Track ${trackKey}: isActuallyFull=${isActuallyFull}, isFull=${isFull}, shouldDisable=${shouldDisable}`);
                                                    
                                                    // Force disable
                                                    option.disabled = shouldDisable;
                                                    
                                                    // Update option text to show count/remaining
                                                    if (shouldDisable) {
                                                        option.textContent = `${originalText} (Full - ${count}/${maxPerTrack})`;
                                                        option.style.color = '#999';
                                                        option.style.cursor = 'not-allowed';
                                                        option.style.opacity = '0.5';
                                                        option.style.backgroundColor = '#f5f5f5';
                                                        // Add class for additional styling
                                                        option.classList.add('disabled-option');
                                                        // Set attribute for additional validation
                                                        option.setAttribute('data-full', 'true');
                                                        console.log(`Disabled option: ${trackKey}`);
                                                    } else {
                                                        option.textContent = `${originalText} (${remaining} remaining)`;
                                                        option.style.color = '';
                                                        option.style.cursor = '';
                                                        option.style.opacity = '';
                                                        option.style.backgroundColor = '';
                                                        option.classList.remove('disabled-option');
                                                        option.removeAttribute('data-full');
                                                        option.disabled = false; // Make sure it's enabled
                                                    }
                                                }
                                            });
                                            
                                            // If current selection is full, clear it immediately
                                            const currentValue = mentorshipTrackSelect.value;
                                            if (currentValue) {
                                                const currentCount = trackCounts[currentValue] || 0;
                                                const currentIsFull = trackIsFull[currentValue] || false;
                                                
                                                if (currentCount >= maxPerTrack || currentIsFull) {
                                                    mentorshipTrackSelect.value = '';
                                                    Swal.fire({
                                                        title: 'Track Full',
                                                        text: 'The limit has been reached for this selection',
                                                        icon: 'warning',
                                                        confirmButtonText: 'OK',
                                                        confirmButtonColor: '#FF6B35'
                                                    });
                                                }
                                            }
                                        } else {
                                            console.error('mentorshipTrackSelect element not found!');
                                        }
                                    } else {
                                        console.error('API returned error:', data.message);
                                    }
                                } catch (error) {
                                    console.error('Error fetching track counts:', error);
                                    // Don't show error to user, just log it
                                }
                            }

                            // Check track limits when mentorship track is selected
                            if (mentorshipTrackSelect) {
                                mentorshipTrackSelect.addEventListener('change', function() {
                                    const selectedValue = this.value;
                                    
                                    if (!selectedValue || selectedValue === '') {
                                        return;
                                    }
                                    
                                    // Check if selected option is disabled
                                    const selectedOption = this.querySelector(`option[value="${selectedValue}"]`);
                                    if (selectedOption) {
                                        // Check disabled attribute
                                        if (selectedOption.disabled) {
                                            this.value = '';
                                            Swal.fire({
                                                title: 'Track Full',
                                                text: 'This mentorship track is full. Please select another track.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#FF6B35'
                                            });
                                            return;
                                        }
                                        
                                        // Check data-full attribute
                                        if (selectedOption.getAttribute('data-full') === 'true') {
                                            this.value = '';
                                            Swal.fire({
                                                title: 'Track Full',
                                                text: 'This mentorship track is full. Please select another track.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#FF6B35'
                                            });
                                            return;
                                        }
                                        
                                        // Check count directly from trackCounts
                                        const count = trackCounts[selectedValue] || 0;
                                        if (count >= maxPerTrack) {
                                            this.value = '';
                                            Swal.fire({
                                                title: 'Track Full',
                                                text: 'This mentorship track is full. Please select another track.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#FF6B35'
                                            });
                                            return;
                                        }
                                    }
                                    
                                    // Double check with trackIsFull object
                                    if (trackIsFull[selectedValue]) {
                                        this.value = '';
                                        Swal.fire({
                                            title: 'Limit Reached',
                                            text: 'The limit has been reached for this selection',
                                            icon: 'warning',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: '#FF6B35'
                                        });
                                    }
                                });
                                
                                // Prevent selection of disabled options via mouse/keyboard
                                mentorshipTrackSelect.addEventListener('mousedown', function(e) {
                                    const option = e.target;
                                    if (option.tagName === 'OPTION' && (option.disabled || option.getAttribute('data-full') === 'true')) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        return false;
                                    }
                                });
                                
                                // Also prevent on click
                                mentorshipTrackSelect.addEventListener('click', function(e) {
                                    const option = e.target;
                                    if (option.tagName === 'OPTION' && (option.disabled || option.getAttribute('data-full') === 'true')) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        return false;
                                    }
                                });
                            }

                            // Load track counts on page load and when mentorship section is shown
                            if (attendeeTypeSelect) {
                                attendeeTypeSelect.addEventListener('change', function() {
                                    if (this.value === 'mentorship') {
                                        // Show mentorship tracks section
                                        const mentorshipTracksOptions = document.getElementById('mentorship-tracks-options');
                                        if (mentorshipTracksOptions) {
                                            mentorshipTracksOptions.classList.remove('d-none');
                                        }
                                        // Update track limits
                                        updateMentorshipTrackLimits();
                                    } else {
                                        // Hide mentorship tracks section
                                        const mentorshipTracksOptions = document.getElementById('mentorship-tracks-options');
                                        if (mentorshipTracksOptions) {
                                            mentorshipTracksOptions.classList.add('d-none');
                                        }
                                        // Clear selection
                                        if (mentorshipTrackSelect) {
                                            mentorshipTrackSelect.value = '';
                                        }
                                    }
                                });
                                
                                // Also load on initial page load if mentorship is already selected
                                if (attendeeTypeSelect.value === 'mentorship') {
                                    updateMentorshipTrackLimits();
                                }
                            }
                            
                            // Also update when mentorship track dropdown is opened
                            if (mentorshipTrackSelect) {
                                // Update on focus (when user clicks on dropdown)
                                mentorshipTrackSelect.addEventListener('focus', function() {
                                    console.log('Mentorship track dropdown focused - updating limits');
                                    updateMentorshipTrackLimits();
                                });
                                
                                // Update on click (when user clicks on dropdown)
                                mentorshipTrackSelect.addEventListener('click', function() {
                                    console.log('Mentorship track dropdown clicked - updating limits');
                                    updateMentorshipTrackLimits();
                                });
                                
                                // Update on mousedown (before dropdown opens)
                                mentorshipTrackSelect.addEventListener('mousedown', function(e) {
                                    // Only update if clicking on the select itself, not on an option
                                    if (e.target === this) {
                                        console.log('Mentorship track dropdown mousedown - updating limits');
                                        updateMentorshipTrackLimits();
                                    }
                                });
                                
                                // Update when dropdown opens (for better browser compatibility)
                                mentorshipTrackSelect.addEventListener('focusin', function() {
                                    console.log('Mentorship track dropdown focusin - updating limits');
                                    updateMentorshipTrackLimits();
                                });
                            }

                            // Refresh counts after form submission (in case of validation errors)
                            const form = document.getElementById('checkout-form');
                            if (form) {
                                form.addEventListener('submit', async function(e) {
                                    // Check if mentorship track is selected and if it's full
                                    if (attendeeTypeSelect && attendeeTypeSelect.value === 'mentorship') {
                                        const selectedTrack = mentorshipTrackSelect?.value;
                                        
                                        // Refresh track counts before submitting to ensure accuracy
                                        await updateMentorshipTrackLimits();
                                        
                                        if (selectedTrack && trackIsFull[selectedTrack]) {
                                            e.preventDefault();
                                            Swal.fire({
                                                title: 'Track Full',
                                                text: 'This mentorship track is full. Please select another track.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#FF6B35'
                                            });
                                            
                                            // Clear selection if full
                                            if (mentorshipTrackSelect) {
                                                mentorshipTrackSelect.value = '';
                                            }
                                            
                                            return false;
                                        }
                                        
                                        // Double check: if option is disabled, prevent submission
                                        if (selectedTrack) {
                                            const selectedOption = mentorshipTrackSelect.querySelector(`option[value="${selectedTrack}"]`);
                                            if (selectedOption && selectedOption.disabled) {
                                                e.preventDefault();
                                                Swal.fire({
                                                    title: 'Track Full',
                                                    text: 'This mentorship track is full. Please select another track.',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK',
                                                    confirmButtonColor: '#FF6B35'
                                                });
                                                
                                                mentorshipTrackSelect.value = '';
                                                return false;
                                            }
                                        }
                                    }
                                });
                            }


                            // Tooltip functionality
                            const tooltips = document.querySelectorAll('[data-tooltip]');

                            tooltips.forEach(container => {
                                const input = container.querySelector('input');
                                const tooltip = container.querySelector('.tooltip-content');

                                if (input && tooltip) {
                                    // Show tooltip on focus
                                    input.addEventListener('focus', function () {
                                        // Hide other tooltips first
                                        document.querySelectorAll('.tooltip-content.show').forEach(t => {
                                            t.classList.remove('show');
                                        });

                                        tooltip.classList.add('show');
                                    });

                                    // Hide tooltip on blur (with delay to allow interaction)
                                    input.addEventListener('blur', function () {
                                        setTimeout(() => {
                                            if (!tooltip.matches(':hover') && !input.matches(':focus')) {
                                                tooltip.classList.remove('show');
                                            }
                                        }, 150);
                                    });

                                    // Keep tooltip visible when hovering over it
                                    tooltip.addEventListener('mouseenter', function () {
                                        tooltip.classList.add('show');
                                    });

                                    // Hide tooltip when leaving it
                                    tooltip.addEventListener('mouseleave', function () {
                                        if (!input.matches(':focus')) {
                                            tooltip.classList.remove('show');
                                        }
                                    });

                                    // Allow clicking on format examples to copy to input
                                    const formatItems = tooltip.querySelectorAll('.format-item');
                                    formatItems.forEach(item => {
                                        item.addEventListener('click', function () {
                                            input.value = item.textContent;
                                            input.focus();

                                            // Add visual feedback
                                            item.style.background = 'rgba(255, 255, 255, 0.3)';
                                            setTimeout(() => {
                                                item.style.background = 'rgba(255, 255, 255, 0.1)';
                                            }, 200);
                                        });

                                        // Add cursor pointer
                                        item.style.cursor = 'pointer';
                                    });
                                }
                            });

                            // Hide tooltips when clicking outside
                            document.addEventListener('click', function (e) {
                                if (!e.target.closest('.input-container')) {
                                    document.querySelectorAll('.tooltip-content.show').forEach(tooltip => {
                                        tooltip.classList.remove('show');
                                    });
                                }
                            });

                            // Hide tooltips on escape key
                            document.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    document.querySelectorAll('.tooltip-content.show').forEach(tooltip => {
                                        tooltip.classList.remove('show');
                                    });
                                }
                            });
    </script>

    {{-- Lightweight script to handle ticket type UI in case previous script fails --}}
    <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const ticketTypeRadios = document.querySelectorAll('input[name="ticket_type"]');
                                    const attendeeOptions = document.getElementById('attendee-options');
                                    const startupUpload = document.getElementById('startup-upload');
                                    const attendeeTypeSelect = document.getElementById('attendee_type');
                                    const mentorshipTracksOptions = document.getElementById('mentorship-tracks-options');

                                    function updateTicketTypeUI() {
                                        if (!ticketTypeRadios.length) {
                                            return;
                                        }

                                        const selected = document.querySelector('input[name="ticket_type"]:checked');

                                        if (!selected) {
                                            if (attendeeOptions) attendeeOptions.classList.add('d-none');
                                            if (startupUpload) startupUpload.classList.add('d-none');
                                            return;
                                        }

                                        if (selected.value === 'attendee') {
                                            if (attendeeOptions) attendeeOptions.classList.remove('d-none');
                                            if (startupUpload) startupUpload.classList.add('d-none');
                                        } else if (selected.value === 'startups') {
                                            if (attendeeOptions) attendeeOptions.classList.add('d-none');
                                            if (startupUpload) startupUpload.classList.remove('d-none');
                                        } else {
                                            if (attendeeOptions) attendeeOptions.classList.add('d-none');
                                            if (startupUpload) startupUpload.classList.add('d-none');
                                        }
                                    }

                                    function updateAttendeeTypeUI() {
                                        if (!attendeeTypeSelect) {
                                            return;
                                        }

                                        const selectedValue = attendeeTypeSelect.value;

                                        if (selectedValue === 'mentorship') {
                                            if (mentorshipTracksOptions) mentorshipTracksOptions.classList.remove('d-none');
                                        } else {
                                            if (mentorshipTracksOptions) mentorshipTracksOptions.classList.add('d-none');
                                        }
                                    }

                                    ticketTypeRadios.forEach(radio => {
                                        radio.addEventListener('change', updateTicketTypeUI);
                                    });

                                    if (attendeeTypeSelect) {
                                        attendeeTypeSelect.addEventListener('change', updateAttendeeTypeUI);
                                    }

                                    updateTicketTypeUI();
                                    updateAttendeeTypeUI();
                                });
    </script>

    {{-- AJAX submit to prevent page reload and show success popup --}}
    <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const form = document.getElementById('checkout-form');
                                    if (!form) {
                                        return;
                                    }

                                    form.addEventListener('submit', function (e) {
                                        e.preventDefault();

                                        // Use browser native validation
                                        if (!form.reportValidity()) {
                                            return;
                                        }

                                        // Additional validation for ticket type
                                        const selectedTicketType = document.querySelector('input[name="ticket_type"]:checked');
                                        const attendeeType = document.getElementById('attendee_type');
                                        const mentorshipTrack = document.getElementById('mentorship_track');

                                        if (!selectedTicketType) {
                                            Swal.fire({
                                                title: 'Missing Information',
                                                text: 'Please select a ticket type',
                                                icon: 'warning',
                                                confirmButtonText: 'OK',
                                                confirmButtonColor: '#FF6B35'
                                            });
                                            return;
                                        }

                                        if (selectedTicketType.value === 'attendee') {
                                            if (!attendeeType || !attendeeType.value) {
                                                Swal.fire({
                                                    title: 'Missing Information',
                                                    text: 'Please select an attendee type',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK',
                                                    confirmButtonColor: '#FF6B35'
                                                });
                                                return;
                                            }
                                            // If attendee type is mentorship, check mentorship track
                                            if (attendeeType.value === 'mentorship') {
                                                if (!mentorshipTrack || !mentorshipTrack.value) {
                                                    Swal.fire({
                                                        title: 'Missing Information',
                                                        text: 'Please select a mentorship track',
                                                        icon: 'warning',
                                                        confirmButtonText: 'OK',
                                                        confirmButtonColor: '#FF6B35'
                                                    });
                                                    return;
                                                }
                                            }
                                        } else if (selectedTicketType.value === 'startups') {
                                            const startupFileInput = document.getElementById('startup_file');
                                            if (!startupFileInput || !startupFileInput.files || startupFileInput.files.length === 0) {
                                                Swal.fire({
                                                    title: 'Missing Information',
                                                    text: 'Please upload your pitch deck file',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK',
                                                    confirmButtonColor: '#FF6B35'
                                                });
                                                return;
                                            }
                                        }

                                        const submitBtn = form.querySelector('.btn-ticket');
                                        const originalText = submitBtn ? submitBtn.innerHTML : '';

                                        if (submitBtn) {
                                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                                            submitBtn.disabled = true;
                                        }

                                        // Create FormData and clean it based on user selections
                                        const formData = new FormData(form);
                                        
                                        // Remove fields that shouldn't be sent based on ticket type
                                        if (selectedTicketType.value === 'attendee') {
                                            // If attendee type is not mentorship, remove mentorship_track
                                            if (attendeeType && attendeeType.value !== 'mentorship') {
                                                formData.delete('mentorship_track');
                                            }
                                            // Remove startup_file if ticket type is attendee
                                            formData.delete('startup_file');
                                        } else if (selectedTicketType.value === 'startups') {
                                            // Remove attendee-related fields if ticket type is startups
                                            formData.delete('attendee_type');
                                            formData.delete('mentorship_track');
                                        }

                                        fetch(form.action, {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                            }
                                        })
                                            .then(function (response) {
                                                if (response.ok) {
                                                    Swal.fire({
                                                        title: 'Successfully',
                                                        text: 'Please check your email',
                                                        icon: 'success',
                                                        confirmButtonText: 'OK',
                                                        confirmButtonColor: '#28a745'
                                                    }).then(function () {
                                                        form.reset();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        title: 'Error!',
                                                        text: 'The limit has been reached for this selection.',
                                                        icon: 'error',
                                                        confirmButtonText: 'OK',
                                                        confirmButtonColor: '#dc3545'
                                                    });
                                                }
                                            })
                                            .catch(function () {
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'An error occurred, please try again.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK',
                                                    confirmButtonColor: '#dc3545'
                                                });
                                            })
                                            .finally(function () {
                                                if (submitBtn) {
                                                    submitBtn.innerHTML = originalText;
                                                    submitBtn.disabled = false;
                                                }
                                            });
                                    });
                            });
    </script>
@endsection