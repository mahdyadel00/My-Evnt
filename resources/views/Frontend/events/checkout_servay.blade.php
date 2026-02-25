@extends('Frontend.layouts.master')
@section('title', 'Checkout Survey')

@push('css')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $sessionPricing = App\Models\FormServay::getSessionPricing();
    @endphp

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
            gap: 0;
            padding: 10px 0;
        }

        #session-types .col-md-6 {
            display: flex;
            margin-bottom: 25px;
        }

        .pricing-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border: 3px solid transparent;
            border-radius: 20px;
            padding: 10px 15px !important;
            margin-bottom: 15px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            min-height: 480px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF6B35 0%, #F7931E 50%, #FF6B35 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .pricing-card:hover {
            border-color: #FF6B35;
            box-shadow: 0 15px 35px rgba(255, 107, 53, 0.15);
            transform: translateY(-8px);
        }

        .pricing-card:hover::before {
            transform: scaleX(1);
        }

        .pricing-card.selected {
            border-color: #FF6B35;
            background: linear-gradient(145deg, #fff8f5 0%, #ffe8dc 100%);
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.2);
            transform: translateY(-5px);
        }

        .pricing-card.selected::before {
            transform: scaleX(1);
            height: 6px;
            background: linear-gradient(90deg, #FF6B35 0%, #E55A2B 50%, #FF6B35 100%);
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 0;
            position: relative;
        }

        .pricing-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .pricing-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #FF6B35, #F7931E);
            border-radius: 2px;
        }

        .pricing-price {
            margin: 10px 0;
            font-size: 1.5rem;
            font-weight: 800;
            color: #FF6B35;
            background: linear-gradient(135deg, #FF6B35, #E55A2B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .pricing-currency {
            font-size: 1.2rem;
            color: #7f8c8d;
            font-weight: 600;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 0 0 0 0;
        }

        .pricing-features li {
            padding: 12px 15px;
            margin: 8px 0;
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            border: 1px solid rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
        }

        /* .pricing-features li:hover {
                                                                                                                                                                                                                                                background: linear-gradient(145deg, #fff8f5 0%, #ffe8dc 100%);
                                                                                                                                                                                                                                                border-color: rgba(255, 107, 53, 0.2);
                                                                                                                                                                                                                                                transform: translateX(3px);
                                                                                                                                                                                                                                            } */

        .pricing-features li i {
            color: #FF6B35;
            margin-right: 12px;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
            background: rgba(255, 107, 53, 0.1);
            padding: 8px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pricing-features li strong {
            color: #2c3e50;
            font-weight: 700;
        }

        .pricing-features li {
            color: #34495e;
            font-weight: 500;
        }

        /* Age Group Selector */
        .age-group-selector {
            margin: 5px 0;
            padding: 20px;
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 1px solid rgba(255, 107, 53, 0.1);
        }

        .age-group-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .age-group-options {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .age-option {
            flex: 1;
            padding: 10px;
            border: 2px solid transparent;
            border-radius: 12px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .age-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .age-option:hover {
            border-color: #FF6B35;
            background: linear-gradient(145deg, #fff8f5 0%, #ffe8dc 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.15);
        }

        .age-option:hover::before {
            left: 100%;
        }

        .age-option.selected {
            border-color: #FF6B35;
            background: linear-gradient(145deg, #FF6B35 0%, #E55A2B 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .age-option .age-name {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .age-option .age-price {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0.9;
        }

        /* Address Selector - Same styling as Age Group */
        .address-selector {
            margin: 5px 0;
            padding: 20px;
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 1px solid rgba(255, 107, 53, 0.1);
        }

        .address-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .address-options {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .address-option {
            flex: 1;
            padding: 10px;
            border: 2px solid transparent;
            border-radius: 12px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .address-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .address-option:hover {
            border-color: #FF6B35;
            background: linear-gradient(145deg, #fff8f5 0%, #ffe8dc 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.15);
        }

        .address-option:hover::before {
            left: 100%;
        }

        .address-option.selected {
            border-color: #FF6B35;
            background: linear-gradient(145deg, #FF6B35 0%, #E55A2B 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .address-option .address-name {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .address-option .address-details {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            opacity: 0.9;
            line-height: 1.3;
        }

        .pricing-card.disabled {
            opacity: 0.6;
            pointer-events: none;
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

        @media screen and (max-width: 992px) {
            #session-types {
                flex-direction: column;
                gap: 20px;
                padding: 15px 0;
            }

            #session-types .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }

            .pricing-card {
                min-height: 420px;
                padding: 25px 20px;
            }
        }

        @media screen and (max-width: 768px) {
            #session-types {
                gap: 20px;
                padding: 10px 0;
            }

            .pricing-card {
                min-height: auto;
                padding: 25px 20px;
                margin-bottom: 20px;
            }

            .pricing-title {
                font-size: 1.5rem;
                letter-spacing: 0.5px;
            }

            .pricing-price {
                font-size: 2.2rem;
            }

            .age-group-selector {
                padding: 15px;
                margin: 20px 0;
            }

            .age-group-options {
                gap: 10px;
            }

            .age-option {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .age-option .age-name {
                font-size: 0.95rem;
            }

            .age-option .age-price {
                font-size: 0.85rem;
            }

            .pricing-features li {
                padding: 10px 12px;
                margin: 6px 0;
            }

            .pricing-features li i {
                width: 30px;
                height: 30px;
                font-size: 1rem;
            }
        }

        @media screen and (max-width: 576px) {
            .pricing-card {
                padding: 20px 15px;
                border-radius: 15px;
            }

            .pricing-title {
                font-size: 1.3rem;
                margin-bottom: 12px;
            }

            .pricing-title::after {
                width: 50px;
                height: 2px;
            }

            .pricing-price {
                font-size: 2rem;
            }

            .pricing-currency {
                font-size: 1rem;
            }

            .age-group-selector {
                padding: 12px;
                margin: 15px 0;
            }

            .age-group-title {
                font-size: 1rem;
                margin-bottom: 12px;
            }

            .age-group-options {
                flex-direction: column;
                gap: 8px;
            }

            .age-option {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            /* Address Selector Responsive */
            .address-selector {
                padding: 12px;
                margin: 15px 0;
            }

            .address-title {
                font-size: 1rem;
                margin-bottom: 12px;
            }

            .address-options {
                flex-direction: column;
                gap: 8px;
            }

            .address-option {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .pricing-features li {
                padding: 8px 10px;
                margin: 5px 0;
                border-radius: 8px;
            }

            .pricing-features li i {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
                margin-right: 10px;
            }
        }

        @media screen and (max-width: 480px) {
            .pricing-card {
                padding: 18px 12px;
            }

            .pricing-title {
                font-size: 1.2rem;
            }

            .pricing-price {
                font-size: 1.8rem;
            }

            .age-group-selector {
                padding: 10px;
            }

            .age-option {
                padding: 10px 12px;
                font-size: 0.85rem;
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
            padding: 10px !important;
            margin: 5px !important;
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
            max-height: 450px;
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
            font-size: 1.1rem;
        }

        .time-grid {
            padding: 25px;
        }

        .time-period {
            margin-bottom: 25px;
        }

        .time-period:last-child {
            margin-bottom: 0;
        }

        .period-label {
            font-size: 16px;
            font-weight: 700;
            color: #FF6B35;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .time-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
        }

        .time-option {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            min-height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .time-option:hover {
            background: #FFE8DC;
            border-color: #FF6B35;
            color: #FF6B35;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.2);
        }

        .time-option.selected {
            background: #FF6B35;
            border-color: #FF6B35;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            font-weight: 700;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .time-dropdown {
                max-height: 350px;
                border-radius: 15px;
                margin-top: 5px;
            }

            .time-grid {
                padding: 20px;
            }

            .time-options {
                grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
                gap: 8px;
            }

            .time-option {
                padding: 14px 10px;
                font-size: 12px;
                border-radius: 10px;
                font-weight: 600;
                min-height: 45px;
            }

            .period-label {
                font-size: 12px;
                margin-bottom: 10px;
            }

            .time-header {
                padding: 10px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .time-dropdown {
                max-height: 400px;
                left: -10px;
                right: -10px;
            }

            .time-options {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }

            .time-option {
                padding: 12px 8px;
                font-size: 11px;
                border-radius: 8px;
                min-height: 42px;
            }

            .time-grid {
                padding: 15px;
            }

            .period-label {
                font-size: 11px;
                margin-bottom: 8px;
            }
        }

        /* Additional touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .time-option {
                min-height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                font-weight: 600;
                padding: 15px 12px;
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
        }

        /* Additional Animations and Enhancements */
        .pricing-card {
            animation: cardFadeIn 0.6s ease-out forwards;
        }

        .pricing-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .pricing-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .pricing-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pricing-price {
            position: relative;
        }

        .pricing-price::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #FF6B35, #F7931E);
            transition: width 0.4s ease;
        }

        .pricing-card:hover .pricing-price::after,
        .pricing-card.selected .pricing-price::after {
            width: 100%;
        }

        /* Enhanced Section Header */
        .project-info-box h4 {
            position: relative;
            font-size: 2rem;
            font-weight: 800;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 35px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .project-info-box h4::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #FF6B35, #F7931E);
            border-radius: 2px;
        }

        .project-info-box h4::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #FF6B35, transparent);
        }

        /* Enhanced Quantity Selector */
        .quantity-selector {
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid rgba(255, 107, 53, 0.1);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .quantity-btn {
            background: linear-gradient(145deg, #FF6B35 0%, #E55A2B 100%);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .quantity-btn:hover {
            background: linear-gradient(145deg, #E55A2B 0%, #CC4E1F 100%);
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 107, 53, 0.4);
        }

        .quantity-display {
            font-size: 1.8rem;
            font-weight: 800;
            min-width: 60px;
            text-align: center;
            padding: 15px 20px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            border: 3px solid #FF6B35;
            color: #FF6B35;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Order Summary */
        .order-summary {
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
            border: 2px solid rgba(255, 107, 53, 0.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .order-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF6B35, #F7931E, #FF6B35);
        }

        .order-summary h5 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
            font-weight: 600;
            color: #34495e;
        }

        .summary-row:last-child {
            border-bottom: 3px solid #FF6B35;
            font-weight: 800;
            font-size: 1.2rem;
            color: #FF6B35;
            margin-top: 10px;
            padding-top: 15px;
        }

        /* Enhanced Button */
        .btn-ticket {
            background: linear-gradient(145deg, #FF6B35 0%, #E55A2B 100%);
            color: white;
            border: none;
            padding: 18px 35px;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            width: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-ticket::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-ticket:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-ticket:hover {
            background: linear-gradient(145deg, #E55A2B 0%, #CC4E1F 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
        }

        /* Page Loading Animation */
        @keyframes pageLoad {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .details {
            animation: pageLoad 0.8s ease-out;
        }

        /* Smooth Scroll Behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced Focus States */
        .pricing-card:focus-within {
            outline: 3px solid rgba(255, 107, 53, 0.3);
            outline-offset: 2px;
        }

        .age-option:focus {
            outline: 2px solid rgba(255, 107, 53, 0.5);
            outline-offset: 2px;
        }

        /* Loading State */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #FF6B35;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        /* Success Animation */
        @keyframes successPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .success-animation {
            animation: successPulse 0.6s ease-in-out;
        }

        @media (max-width: 768px) {

            .event-description,
            .event-details {
                padding: 5px;
                margin-bottom: 5px;
            }

            .mobile-details-res {
                flex-direction: column-reverse;
            }
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
                            <div class="col-lg-4 col-12">
                                <div class="card-info-payment">
                                    <h5><i class="fas fa-map-marker-alt pe-1"></i> Egypt</h5>
                                    <p>
                                        <a href="https://www.google.com/maps/search/{{ $event->city?->name }}"
                                            target="_blank">
                                            {{ $event->city?->name }}
                                        </a>
                                    </p>
                                </div>
                            </div>
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
                                        -
                                        {{ date('h:i A', strtotime($event->eventDates->isNotEmpty() ? $event->eventDates[0]->end_time : '')) }}
                                    </h5>
                                    <p>
                                        @php
                                            $start = \Carbon\Carbon::parse($event->eventDates->isNotEmpty() ? $event->eventDates[0]->start_time : '00:00:00');
                                            $end = \Carbon\Carbon::parse($event->eventDates->isNotEmpty() ? $event->eventDates[0]->end_time : '00:00:00');
                                            $hours = $end->diffInHours($start);
                                            $minutes = $end->diffInMinutes($start) - ($hours * 60);
                                        @endphp
                                        {{-- {{ $hours }}:{{ sprintf('%02d', $minutes) }} Hours --}}
                                        1:00 Hour
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Type Selection -->
                    <div class="col-12">
                        <div class="project-info-box">
                            <h4 class="mb-4">Choose Your Session Type</h4>
                            <div class="row" id="session-types">
                                @foreach($sessionPricing as $sessionType => $details)
                                    <div class="col-md-6 col-12">
                                        <div class="pricing-card" data-session="{{ $sessionType }}"
                                            data-price="{{ $details['price'] }}"
                                            data-age-groups="{{ json_encode($details['age_group']) }}">
                                            <div class="pricing-header">
                                                <div class="pricing-title">{{ $details['title'] }}</div>
                                                <div class="pricing-price" id="price-{{ $sessionType }}">
                                                    {{ $details['price'] }} <span class="pricing-currency">EGP</span>
                                                </div>
                                            </div>

                                            <!-- Age Group Selector -->
                                            @if(isset($details['age_group']) && count($details['age_group']) > 0)
                                                <div class="age-group-selector">
                                                    <div class="age-group-title">Choose Age Group:</div>
                                                    <div class="age-group-options">
                                                        @foreach($details['age_group'] as $ageGroup => $ageDetails)
                                                            <div class="age-option" data-session="{{ $sessionType }}"
                                                                data-age="{{ $ageGroup }}"
                                                                data-price="@if(is_array($ageDetails)){{ $ageDetails['price'] }}@else{{ $details['price'] }}@endif">
                                                                <span class="age-name">{{ $ageGroup }}</span>
                                                                <span class="age-price">
                                                                    @if(is_array($ageDetails))
                                                                        {{ $ageDetails['price'] }} EGP
                                                                    @else
                                                                        {{ $details['price'] }} EGP
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if(isset($details['addresses']) && count($details['addresses']) > 0)
                                                <div class="address-selector">
                                                    <div class="address-title">Choose Location:</div>
                                                    <div class="address-options">
                                                        @foreach($details['addresses'] as $addressKey => $addressDetails)
                                                            <div class="address-option" data-session="{{ $sessionType }}"
                                                                data-address="{{ $addressKey }}"
                                                                data-location="@if(is_array($addressDetails)){{ $addressDetails['name'] }}@else{{ $addressDetails }}@endif">
                                                                <span class="address-name">
                                                                    @if(is_array($addressDetails))
                                                                        {{ $addressDetails['name'] }}
                                                                    @else
                                                                        {{ $addressDetails }}
                                                                    @endif
                                                                </span>
                                                                @if(is_array($addressDetails) && isset($addressDetails['details']))
                                                                    <span class="address-details">{{ $addressDetails['details'] }}</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <ul class="pricing-features">
                                                @if(isset($details['time']) || isset($details['duration']))
                                                    <li><i class="fas fa-clock"></i>
                                                        <strong>{{ isset($details['time']) ? 'Time' : 'Duration' }}
                                                            :</strong> {{ $details['time'] ?? $details['duration'] }}
                                                    </li>
                                                @endif
                                                @if(isset($details['days']))
                                                    <li><i class="fas fa-calendar-days"></i>
                                                        <strong>Period:</strong> {{ $details['days'] }}
                                                    </li>
                                                @endif
                                                @if(isset($details['note']))
                                                    <li><i class="fas fa-info-circle"></i>
                                                        <strong>Note:</strong> {{ $details['note'] }}
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Quantity Selector -->
                            <div id="quantity-section" class="hidden">
                                <h5 class="mb-3">Select Quantity</h5>
                                <div class="quantity-selector">
                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <div class="quantity-display" id="quantity-display">1</div>
                                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Form -->
                    <div class="col-12" id="contact-form" style="display: none;">
                        <div class="project-info-box">
                            <h4 class="mb-4">Contact Information</h4>
                            @include('Frontend.layouts._message')

                            <form action="{{ route('checkout_survey_post') }}" method="post" id="checkout-form">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <input type="hidden" name="session_type" id="selected-session" value="">
                                <input type="hidden" name="age_group" id="selected-age-group" value="">
                                <input type="hidden" name="quantity" id="selected-quantity" value="1">
                                <input type="hidden" name="unit_price" id="selected-price" value="">
                                <input type="hidden" name="selected_address" id="selected-address" value="">
                                <input type="hidden" name="selected_location" id="selected-location" value="">
                                <input type="hidden" name="total_amount" id="total-amount" value="">
                                <input type="hidden" name="payment_method" id="payment_method" value="">
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
                                            <input type="text" class="form-control" placeholder="Phone Number" name="phone"
                                                id="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-container">
                                            <textarea class="form-control" rows="3"
                                                placeholder="Special Requirements or Notes (Optional)" name="notes"
                                                id="notes">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Date and Time Selection -->
                                    <div class="col-12">
                                        <div class="date-time-section">
                                            <h6><i class="fas fa-calendar-clock me-2"></i>Preferred Date & Time</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-container">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <input type="text" class="form-control" placeholder="Select Date"
                                                            name="date" id="date" value="{{ old('date') }}" required
                                                            readonly>
                                                        @error('date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-container">
                                                        <i class="fas fa-clock"></i>
                                                        <div class="time-picker-wrapper">
                                                            <input type="text" class="form-control time-input" name="time"
                                                                placeholder="Select Time" id="time" value="{{ old('time') }}" required readonly>
                                                            <div class="time-dropdown" id="time-dropdown">
                                                                <div class="time-grid">
                                                                    <!-- Available Times (11:00 AM - 5:00 PM) -->
                                                                    <div class="time-period">
                                                                        <div class="time-options">
                                                                            <button type="button" class="time-option" data-time="11:00 AM">11:00 AM</button>
                                                                            <button type="button" class="time-option" data-time="11:30 AM">11:30 AM</button>
                                                                            <button type="button" class="time-option" data-time="12:00 PM">12:00 PM</button>
                                                                            <button type="button" class="time-option" data-time="12:30 PM">12:30 PM</button>
                                                                            <button type="button" class="time-option" data-time="1:00 PM">1:00 PM</button>
                                                                            <button type="button" class="time-option" data-time="1:30 PM">1:30 PM</button>
                                                                            <button type="button" class="time-option" data-time="2:00 PM">2:00 PM</button>
                                                                            <button type="button" class="time-option" data-time="2:30 PM">2:30 PM</button>
                                                                            <button type="button" class="time-option" data-time="3:00 PM">3:00 PM</button>
                                                                            <button type="button" class="time-option" data-time="3:30 PM">3:30 PM</button>
                                                                            <button type="button" class="time-option" data-time="4:00 PM">4:00 PM</button>
                                                                            <button type="button" class="time-option" data-time="4:30 PM">4:30 PM</button>
                                                                            <button type="button" class="time-option" data-time="5:00 PM">5:00 PM</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('time')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!--select payment method [cash, credit card, value , suhoola] -->
                                                <div class="col-md-4">
                                                    <div class="input-container">
                                                        <i class="fas fa-credit-card"></i>
                                                        <select class="form-control" name="payment_method"
                                                            id="payment_method">
                                                            <option value="default">Credit Card</option>
                                                            <option value="value">Valu</option>
                                                            <option value="suhoola">Suhoola</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Order Summary -->
                                <div class="order-summary">
                                    <h5><i class="fas fa-ticket-alt me-2"></i>Order Summary</h5>
                                    <div class="summary-row">
                                        <span>Session Type:</span>
                                        <span id="summary-session">-</span>
                                    </div>
                                    <div class="summary-row" id="summary-age-row" style="display: none;">
                                        <span>Age Group:</span>
                                        <span id="summary-age-group">-</span>
                                    </div>
                                    <div class="summary-row" id="summary-address-row" style="display: none;">
                                        <span>Location:</span>
                                        <span id="summary-address">-</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Unit Price:</span>
                                        <span id="summary-unit-price">0 EGP</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Quantity:</span>
                                        <span id="summary-quantity">1</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Total Amount:</span>
                                        <span id="summary-total">0 EGP</span>
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
                        <h4>Event Survey</h4>
                        <div class="mt-3">
                            @foreach ($event->media as $media)
                                @if ($media->name == 'banner')
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="project-image" class="rounded mb-4">
                                @endif
                            @endforeach

                            <div class="event-description">
                                <h6 class="mb-3">About this Event</h6>
                                <p class="text-muted">{!! Str::limit($event->description, 200) !!}</p>
                            </div>

                            <div class="event-details mt-4">
                                <h6 class="mb-3">Event Details</h6>
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
                                        <strong>Format:</strong> {{ $event->format ? 'Online' : 'In-Person' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <script>
        // ===============================
        // GLOBAL VARIABLES
        // ===============================
        const sessionPricing = @json($sessionPricing);

        let selectedSession = '';
        let selectedAgeGroup = '';
        let selectedAddress = '';
        let selectedPrice = 0;
        let selectedPaymentMethod = '';
        let quantity = 1;

        // ===============================
        // FORM VALIDATION FUNCTIONS
        // ===============================
        function initializeFormValidation() {
            const requiredInputs = ['first_name', 'last_name', 'email', 'phone'];

            requiredInputs.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('blur', function () {
                        validateField(this);
                    });
                    input.addEventListener('input', function () {
                        if (this.classList.contains('is-invalid')) {
                            validateField(this);
                        }
                    });
                }
            });
        }

        function validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;

            if (!value) {
                field.classList.add('is-invalid');
                return false;
            }

            // Email validation
            if (fieldName === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('is-invalid');
                    return false;
                }
            }

            // Phone validation (Egyptian format)
            if (fieldName === 'phone') {
                const phoneRegex = /^01[0-2,5]{1}[0-9]{8}$/;
                if (!phoneRegex.test(value)) {
                    field.classList.add('is-invalid');
                    return false;
                }
            }

            // Name validation
            if (fieldName === 'first_name' || fieldName === 'last_name') {
                if (value.length < 2) {
                    field.classList.add('is-invalid');
                    return false;
                }
            }

            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            return true;
        }

        // ===============================
        // SESSION SELECTION FUNCTIONS
        // ===============================
        function initializeSessionSelection() {
            // Age group selection
            document.querySelectorAll('.age-option').forEach(option => {
                option.addEventListener('click', handleAgeGroupSelection);
            });

            // Address selection
            document.querySelectorAll('.address-option').forEach(option => {
                option.addEventListener('click', handleAddressSelection);
            });

            // Pricing card selection
            document.querySelectorAll('.pricing-card').forEach(card => {
                card.addEventListener('click', handlePricingCardSelection);
            });
        }

        function handleAgeGroupSelection(e) {
            e.stopPropagation();

            const sessionType = this.dataset.session;
            const ageGroup = this.dataset.age;
            const price = parseInt(this.dataset.price);

            // Remove selected class from all age options in this session
            document.querySelectorAll(`[data-session="${sessionType}"].age-option`).forEach(opt => {
                opt.classList.remove('selected');
            });

            // Add selected class to clicked option
            this.classList.add('selected');

            // Update selections
            selectedSession = sessionType;
            selectedAgeGroup = ageGroup;
            selectedPrice = price;

            updateSelectionUI(sessionType, price);
            updateHiddenInputs();
            showContactForm();
            updateOrderSummary();

            // Show success message
            const sessionTitle = sessionPricing[selectedSession]?.title || 'Session';
            showSuccessAlert('Great Choice!', `You've selected ${sessionTitle} for ${selectedAgeGroup || 'your session'}`);
        }

        function handleAddressSelection(e) {
            e.stopPropagation();

            const sessionType = this.dataset.session;
            const address = this.dataset.address;
            const location = this.dataset.location;

            // Remove selected class from all address options in this session
            document.querySelectorAll(`[data-session="${sessionType}"].address-option`).forEach(opt => {
                opt.classList.remove('selected');
            });

            // Add selected class to clicked option
            this.classList.add('selected');

            // Update selection
            selectedAddress = address;

            // Update hidden inputs
            document.getElementById('selected-address').value = selectedAddress;
            document.getElementById('selected-location').value = location;
            document.getElementById('payment_method').value = selectedPaymentMethod;

            updateOrderSummary();
            addSuccessAnimation(this);
        }

        function handlePricingCardSelection() {
            const ageGroups = this.querySelectorAll('.age-option');
            const addressOptions = this.querySelectorAll('.address-option');

            if (ageGroups.length > 0 || addressOptions.length > 0) {
                return; // User must select age group or address first
            }

            // For cards without age groups, handle selection normally
            document.querySelectorAll('.pricing-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            selectedSession = this.dataset.session;
            selectedPrice = parseInt(this.dataset.price);
            selectedAgeGroup = '';
            selectedPaymentMethod = 'cash';

            updateHiddenInputs();
            showContactForm();
            updateOrderSummary();
            addSuccessAnimation(this);

            const sessionTitle = sessionPricing[selectedSession]?.title || 'Session';
            showSuccessAlert('Great Choice!', `You've selected ${sessionTitle}`);
        }

        // ===============================
        // UI UPDATE FUNCTIONS
        // ===============================
        function updateSelectionUI(sessionType, price) {
            // Update the pricing card selection
            document.querySelectorAll('.pricing-card').forEach(c => c.classList.remove('selected'));
            const parentCard = document.querySelector(`[data-session="${sessionType}"]`).closest('.pricing-card');
            parentCard.classList.add('selected');

            // Update price display in card header
            const priceDisplay = document.getElementById(`price-${sessionType}`);
            if (priceDisplay) {
                priceDisplay.innerHTML = `${price} <span class="pricing-currency">EGP</span>`;
            }

            addSuccessAnimation(parentCard);
        }

        function updateHiddenInputs() {
            document.getElementById('selected-session').value = selectedSession;
            document.getElementById('selected-age-group').value = selectedAgeGroup;
            document.getElementById('selected-price').value = selectedPrice;
            document.getElementById('payment_method').value = selectedPaymentMethod;

            if (selectedAddress) {
                document.getElementById('selected-address').value = selectedAddress;
                document.getElementById('selected-location').value =
                    document.querySelector('.address-option.selected')?.dataset.location || '';
            }
        }

        function showContactForm() {
            document.getElementById('quantity-section').classList.remove('hidden');
            document.getElementById('contact-form').style.display = 'block';

            // Smooth scroll to contact form
            document.getElementById('contact-form').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function addSuccessAnimation(element) {
            element.classList.add('success-animation');
            setTimeout(() => {
                element.classList.remove('success-animation');
            }, 600);
        }

        // ===============================
        // QUANTITY MANAGEMENT
        // ===============================
        function increaseQuantity() {
            if (quantity < 10) {
                quantity++;
                updateQuantityDisplay();
            } else {
                showWarningAlert('Maximum Limit', 'You can only book up to 10 tickets at once');
            }
        }

        function decreaseQuantity() {
            if (quantity > 1) {
                quantity--;
                updateQuantityDisplay();
            } else {
                showWarningAlert('Minimum Limit', 'You must book at least 1 ticket');
            }
        }

        function updateQuantityDisplay() {
            document.getElementById('quantity-display').textContent = quantity;
            document.getElementById('selected-quantity').value = quantity;
            updateOrderSummary();

            const quantityDisplay = document.getElementById('quantity-display');
            addSuccessAnimation(quantityDisplay);
        }

        // ===============================
        // ORDER SUMMARY
        // ===============================
        function updateOrderSummary() {
            // تأكد أن selectedPrice رقم صحيح
            const unitPrice = parseFloat(selectedPrice) || 0;
            // تأكد أن quantity رقم صحيح
            const qty = parseInt(quantity) || 1;
            // حساب الإجمالي بدون أي خصومات أو كوبونات
            const total = unitPrice * qty;

            // تحديث ملخص الطلب
            document.getElementById('summary-unit-price').textContent = unitPrice + ' EGP';
            document.getElementById('summary-quantity').textContent = qty;
            document.getElementById('summary-total').textContent = total + ' EGP';
            document.getElementById('total-amount').value = total;

            // إظهار أو إخفاء صف العمر
            const ageRow = document.getElementById('summary-age-row');
            if (selectedAgeGroup) {
                ageRow.style.display = 'flex';
                document.getElementById('summary-age-group').textContent = selectedAgeGroup;
            } else {
                ageRow.style.display = 'none';
            }

            // إظهار أو إخفاء صف العنوان
            const addressRow = document.getElementById('summary-address-row');
            if (selectedAddress) {
                const selectedAddressElement = document.querySelector('.address-option.selected');
                const addressText = selectedAddressElement ?
                    selectedAddressElement.querySelector('.address-name').textContent : selectedAddress;
                addressRow.style.display = 'flex';
                document.getElementById('summary-address').textContent = addressText;
            } else {
                addressRow.style.display = 'none';
            }
        }

        // ===============================
        // FORM SUBMISSION
        // ===============================
        function initializeFormSubmission() {
            document.getElementById('checkout-form').addEventListener('submit', function (e) {
                e.preventDefault();

                if (!validateFormSubmission()) {
                    return false;
                }

                submitForm(this);
                return false;
            });
        }

        function validateFormSubmission() {
            // Check session selection
            if (!selectedSession) {
                showWarningAlert('Session Required', 'Please select a session type first');
                return false;
            }

            // Check age group requirement
            const sessionData = sessionPricing[selectedSession];
            if (sessionData?.age_group && Object.keys(sessionData.age_group).length > 0) {
                const hasAgeGroups = Object.values(sessionData.age_group).some(group => typeof group === 'object');
                if (hasAgeGroups && !selectedAgeGroup) {
                    showWarningAlert('Age Group Required', 'Please select an age group for your session');
                    return false;
                }
            }

            // Check address requirement
            if (sessionData?.addresses && Object.keys(sessionData.addresses).length > 0 && !selectedAddress) {
                showWarningAlert('Location Required', 'Please select a location for your session');
                return false;
            }

            // Validate required fields
            const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'date', 'time'];
            let isValid = true;

            // Check date and time specifically
            const selectedDate = document.getElementById('date').value;
            const selectedTime = document.getElementById('time').value;

            if (!selectedDate) {
                showWarningAlert('Date Required', 'Please select a preferred date');
                return false;
            }

            if (!selectedTime) {
                showWarningAlert('Time Required', 'Please select a preferred time');
                return false;
            }

            // Check other required fields
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                showWarningAlert('Missing Information', 'Please fill in all required fields including date and time');
                return false;
            }

            // Email validation
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showWarningAlert('Invalid Email', 'Please enter a valid email address');
                return false;
            }

            // Phone validation
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^01[0-2,5]{1}[0-9]{8}$/;
            if (!phoneRegex.test(phone)) {
                showWarningAlert('Invalid Phone Number', 'Please enter a valid Egyptian phone number (e.g., 01234567890)');
                return false;
            }

            return true;
        }

        function submitForm(form) {
            const submitBtn = form.querySelector('.btn-ticket');
            const originalText = submitBtn.innerHTML;

            // Update button state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            submitBtn.disabled = true;
            form.classList.add('loading');

            // Prepare form data
            const formData = new FormData(form);

            // Submit via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        handleSuccessfulSubmission(data, form);
                    } else {
                        showErrorAlert('Booking Failed', data.message || 'Unable to process your booking. Please check your information and try again.');
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    // Fallback to normal form submission
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    form.classList.remove('loading');
                });
        }

        function handleSuccessfulSubmission(data, form) {
            Swal.fire({
                title: data.title || 'شكراً! تم التسجيل بنجاح',
                html: `
                    <div style="padding: 10px;">
                        <p style="font-size: 16px; margin-bottom: 15px;">
                            ${data.message || 'تم إرسال طلب الحجز بنجاح. سنتواصل معك قريباً لتأكيد الموعد.'}
                        </p>
                        <p style="font-size: 14px; color: #667eea; margin-top: 15px; font-weight: 600;">
                            <i class="fa fa-envelope me-2"></i>
                            من فضلك، اذهب لمراجعة الإيميل الخاص بك
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'شكراً!',
                confirmButtonColor: '#28a745',
                customClass: {
                    popup: 'survey-success-popup'
                }
            }).then(() => {
                resetForm(form);
            });
        }

        function resetForm(form) {
            // Reset form fields
            form.reset();
            document.getElementById('selected-quantity').value = 1;
            quantity = 1;
            updateQuantityDisplay();

            // Reset selections
            selectedSession = '';
            selectedAgeGroup = '';
            selectedAddress = '';
            selectedPrice = 0;

            // Reset UI
            document.querySelectorAll('.pricing-card').forEach(c => c.classList.remove('selected'));
            document.querySelectorAll('.age-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelectorAll('.address-option').forEach(opt => opt.classList.remove('selected'));
            document.getElementById('quantity-section').classList.add('hidden');
            document.getElementById('contact-form').style.display = 'none';

            // Reset order summary
            updateOrderSummary();

            // Clear time and date selections
            document.getElementById('time').value = '';
            document.querySelectorAll('.time-option').forEach(opt => opt.classList.remove('selected'));

            if (window.flatpickrInstances?.date) {
                window.flatpickrInstances.date.clear();
            }
        }

        // ===============================
        // ALERT FUNCTIONS
        // ===============================
        function showSuccessAlert(title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#FF6B35'
            });
        }

        function showWarningAlert(title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#FF6B35'
            });
        }

        function showErrorAlert(title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'error',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#dc3545'
            });
        }

        // ===============================
        // INPUT FORMATTING
        // ===============================
        function initializeInputFormatting() {
            // Phone number formatting
            document.getElementById('phone').addEventListener('input', function () {
                let value = this.value.replace(/\D/g, ''); // Remove non-digits

                if (value.length > 0) {
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

                // Validate phone number in real-time
                if (this.value.length >= 11) {
                    validateField(this);
                }
            });

            // Email validation on blur
            document.getElementById('email').addEventListener('blur', function () {
                if (this.value.trim().length > 0) {
                    validateField(this);
                }
            });

            // Name field validation
            ['first_name', 'last_name'].forEach(fieldId => {
                document.getElementById(fieldId).addEventListener('blur', function () {
                    if (this.value.trim().length >= 2) {
                        validateField(this);
                    }
                });
            });
        }

        // ===============================
        // DATE/TIME PICKERS
        // ===============================
        function initializeDateTimePickers() {
            // Initialize Flatpickr
            window.flatpickrInstances = window.flatpickrInstances || {};
            window.flatpickrInstances.date = flatpickr("#date", {
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                        longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
                    },
                    months: {
                        shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
                    }
                },
                onChange: function (selectedDates, dateStr, instance) {
                    clearTimeSelection();

                    const dateInput = document.getElementById('date');
                    dateInput.classList.remove('is-invalid');
                    dateInput.classList.add('is-valid');
                }
            });

            // Initialize custom time picker
            initializeTimePicker();
        }

        function initializeTimePicker() {
            const timeInput = document.getElementById('time');
            const timeDropdown = document.getElementById('time-dropdown');
            const timeWrapper = document.querySelector('.time-picker-wrapper');
            const timeOptions = document.querySelectorAll('.time-option');

            // Toggle dropdown on input click
            timeInput.addEventListener('click', function (e) {
                e.stopPropagation();
                timeDropdown.classList.toggle('show');
                timeWrapper.classList.toggle('active');
            });

            // Handle time option selection
            timeOptions.forEach(option => {
                option.addEventListener('click', function () {
                    const selectedTime = this.getAttribute('data-time');

                    timeInput.value = selectedTime;

                    // Remove selected class from all options
                    timeOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    // Hide dropdown
                    timeDropdown.classList.remove('show');
                    timeWrapper.classList.remove('active');

                    // Remove validation error
                    timeInput.classList.remove('is-invalid');
                    timeInput.classList.add('is-valid');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!timeInput.contains(e.target) && !timeDropdown.contains(e.target)) {
                    timeDropdown.classList.remove('show');
                    timeWrapper.classList.remove('active');
                }
            });

            // Close dropdown on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    timeDropdown.classList.remove('show');
                    timeWrapper.classList.remove('active');
                }
            });
        }

        function clearTimeSelection() {
            document.getElementById('time').value = '';
            document.querySelectorAll('.time-option').forEach(option => {
                option.classList.remove('selected');
            });

            const timeDropdown = document.getElementById('time-dropdown');
            const timeWrapper = document.querySelector('.time-picker-wrapper');

            if (timeDropdown) timeDropdown.classList.remove('show');
            if (timeWrapper) timeWrapper.classList.remove('active');
        }

        // ===============================
        // INITIALIZATION
        // ===============================
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize all components
            initializeFormValidation();
            initializeSessionSelection();
            initializeFormSubmission();
            initializeInputFormatting();
            initializeDateTimePickers();

            // Restore old values if any
            @if(old('session_type'))
                const oldSessionType = '{{ old('session_type') }}';
                const oldAgeGroup = '{{ old('age_group') }}';
                const oldQuantity = {{ old('quantity', 1) }};

                if (oldSessionType && sessionPricing[oldSessionType]) {
                    if (oldAgeGroup) {
                        const ageOption = document.querySelector(`[data-session="${oldSessionType}"][data-age="${oldAgeGroup}"]`);
                        if (ageOption) ageOption.click();
                    } else {
                        const card = document.querySelector(`[data-session="${oldSessionType}"]`);
                        if (card) card.click();
                    }

                    quantity = oldQuantity;
                    updateQuantityDisplay();
                }
            @endif

                // Restore old time value
                @if(old('time'))
                    const oldTime = '{{ old('time') }}';
                    if (oldTime) {
                        document.getElementById('time').value = oldTime;
                        const matchingOption = document.querySelector(`[data-time="${oldTime}"]`);
                        if (matchingOption) matchingOption.classList.add('selected');
                    }
                @endif

            updateOrderSummary();
        });
    </script>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection