@extends('Frontend.organization.events.inc.master')
@section('title', 'Create Event - Payment Setup')
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
                <i class='bx bx-credit-card me-2'></i>Create New Event
            </h1>
        </div>
    </div>

    <!-- Messages -->
    @include('Frontend.organization.layouts._message')

    <!-- start form-data -->
    <div class="form-data">
        <div class="head">
            <h3><i class='bx bx-credit-card me-2'></i>Payment Setup</h3>
            <p>Step 4 of 5</p>
        </div>

        <!-- Info Notice -->
        <div class="col-12 mb-4">
            <div class="notice-info-place">
                <i class='bx bx-info-circle me-2'></i>
                <strong>Payment Methods:</strong><br>
                • Choose how customers will pay for tickets<br>
                • You can set up multiple payment methods<br>
                • All information is securely encrypted
            </div>
        </div>

        <!-- Payment Method Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm responsive-card">
                    <div class="card-header bg-light">
                        <div class="p-2">
                            <!-- Payment method tabs -->
                            <ul class="nav nav-pills nav-fill mb-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active payment-tab"
                                       data-bs-toggle="pill"
                                       href="#pay-online"
                                       role="tab"
                                       data-method="pay_online">
                                        <i class='bx bx-credit-card me-2'></i>
                                        <span class="d-none d-md-inline">Pay Online</span>
                                        <span class="d-md-none">Online</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link payment-tab"
                                       data-bs-toggle="pill"
                                       href="#office"
                                       role="tab"
                                       data-method="cache">
                                        <i class='bx bx-store me-2'></i>
                                        <span class="d-none d-md-inline">Ticket Office</span>
                                        <span class="d-md-none">Office</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link payment-tab"
                                       data-bs-toggle="pill"
                                       href="#net-banking"
                                       role="tab"
                                       data-method="bank_transfer">
                                        <i class='bx bx-bank me-2'></i>
                                        <span class="d-none d-md-inline">Bank Transfer</span>
                                        <span class="d-md-none">Bank</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Payment method content -->
                    <div class="card-body tab-content">
                        <!-- Pay Online Tab -->
                        @if($payment_method ? $payment_method->type != 'pay_online' : true)
                        <div id="pay-online" class="tab-pane fade show active pt-3">
                            <div class="payment-method-header mb-4">
                                <h5><i class='bx bx-credit-card me-2'></i>Online Payment Setup</h5>
                                <p class="text-muted">Configure credit/debit card payment processing</p>
                            </div>

                            <form class="row g-3 needs-validation payment-form"
                                  method="post"
                                  action="{{ route('event.store.setup4') }}"
                                  novalidate>
                                @csrf
                                <input type="hidden" name="type" value="pay_online">

                                <!-- Card Owner Name -->
                                <div class="col-md-6 col-12">
                                    <label class="form-label">
                                        <i class='bx bx-user me-1'></i>Card Owner Name *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('card_name') is-invalid @enderror"
                                           placeholder="Enter cardholder's full name"
                                           name="card_name"
                                           value="{{ old('card_name') }}"
                                           required>
                                    @error('card_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>Full name as it appears on the card
                                        </div>
                                    @enderror
                                </div>

                                <!-- Card Number -->
                                <div class="col-md-6 col-12">
                                    <label class="form-label">
                                        <i class='bx bx-credit-card me-1'></i>Card Number *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('card_number') is-invalid @enderror"
                                           placeholder="1234 5678 9012 3456"
                                           maxlength="19"
                                           name="card_number"
                                           value="{{ old('card_number') }}"
                                           id="card_number"
                                           required>
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>16-digit card number
                                        </div>
                                    @enderror
                                </div>

                                <!-- Expiry Date -->
                                <div class="col-md-6 col-12">
                                    <label class="form-label">
                                        <i class='bx bx-calendar me-1'></i>Expiration Date *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('card_expiry') is-invalid @enderror"
                                           placeholder="MM/YY"
                                           maxlength="5"
                                           name="card_expiry"
                                           value="{{ old('card_expiry') }}"
                                           id="card_expiry"
                                           required>
                                    @error('card_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>MM/YY format
                                        </div>
                                    @enderror
                                </div>

                                <!-- CVV -->
                                <div class="col-md-6 col-12">
                                    <label class="form-label">
                                        <i class='bx bx-shield me-1'></i>CVV *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('card_cvc') is-invalid @enderror"
                                           placeholder="123"
                                           maxlength="4"
                                           name="card_cvc"
                                           value="{{ old('card_cvc') }}"
                                           id="card_cvc"
                                           required>
                                    @error('card_cvc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>3-4 digit security code
                                        </div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-check me-1'></i>Save Payment Method
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Ticket Office Tab -->
                        @if($payment_method ? $payment_method->type != 'cache' : true)
                        <div id="office" class="tab-pane fade pt-3">
                            <div class="payment-method-header mb-4">
                                <h5><i class='bx bx-store me-2'></i>Ticket Office Setup</h5>
                                <p class="text-muted">Configure physical ticket selling location</p>
                            </div>

                            <form class="row g-3 needs-validation payment-form"
                                  method="post"
                                  action="{{ route('event.store.cache') }}"
                                  novalidate>
                                @csrf
                                <input type="hidden" name="type" value="cache">

                                <!-- Address -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-map me-1'></i>Ticket Office Address *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('address') is-invalid @enderror"
                                           placeholder="Enter complete address"
                                           name="address"
                                           value="{{ old('address') }}"
                                           required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>Full address where customers can buy tickets
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-envelope me-1'></i>Contact Email *
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="office@example.com"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>Email for customer inquiries
                                        </div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-phone me-1'></i>Contact Phone *
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="+1 (555) 123-4567"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>Phone number for customer support
                                        </div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-check me-1'></i>Save Office Details
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Bank Transfer Tab -->
                        @if($payment_method ? $payment_method->type != 'bank_transfer' : true)
                        <div id="net-banking" class="tab-pane fade pt-3">
                            <div class="payment-method-header mb-4">
                                <h5><i class='bx bx-bank me-2'></i>Bank Transfer Setup</h5>
                                <p class="text-muted">Configure bank account for direct transfers</p>
                            </div>

                            <form class="row g-3 needs-validation payment-form"
                                  method="post"
                                  action="{{ route('event.store.transfer_bank') }}"
                                  novalidate>
                                @csrf
                                <input type="hidden" name="type" value="bank_transfer">

                                <!-- Bank Name -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-bank me-1'></i>Bank Name *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('bank_name') is-invalid @enderror"
                                           placeholder="Enter bank name"
                                           name="bank_name"
                                           value="{{ old('bank_name') }}"
                                           required>
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-envelope me-1'></i>Contact Email *
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="bank@example.com"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-phone me-1'></i>Phone Number *
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="+1 (555) 123-4567"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Branch -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-building me-1'></i>Branch *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('branch') is-invalid @enderror"
                                           placeholder="Enter branch name"
                                           name="branch"
                                           value="{{ old('branch') }}"
                                           required>
                                    @error('branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-world me-1'></i>Country *
                                    </label>
                                    <select class="form-select @error('country_id') is-invalid @enderror"
                                            name="country_id"
                                            id="country_select"
                                            required>
                                        <option value="" disabled selected>Choose Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"
                                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-map-pin me-1'></i>City *
                                    </label>
                                    <select class="form-select @error('city_id') is-invalid @enderror"
                                            name="city_id"
                                            id="city_select"
                                            required>
                                        <option value="" disabled selected>Choose City</option>
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-map me-1'></i>Postal Code *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('postal_code') is-invalid @enderror"
                                           placeholder="Enter postal code"
                                           name="postal_code"
                                           value="{{ old('postal_code') }}"
                                           required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- IBAN -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-credit-card me-1'></i>IBAN *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('iban') is-invalid @enderror"
                                           placeholder="Enter IBAN number"
                                           name="iban"
                                           value="{{ old('iban') }}"
                                           id="iban_input"
                                           required>
                                    @error('iban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">
                                            <i class='bx bx-bulb me-1'></i>International Bank Account Number
                                        </div>
                                    @enderror
                                </div>

                                <!-- Account Holder Name -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-user me-1'></i>Account Holder Name *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('account_name') is-invalid @enderror"
                                           placeholder="Enter account holder name"
                                           name="account_name"
                                           value="{{ old('account_name') }}"
                                           required>
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Account Number -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-hash me-1'></i>Account Number *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('account_number') is-invalid @enderror"
                                           placeholder="Enter account number"
                                           name="account_number"
                                           value="{{ old('account_number') }}"
                                           required>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-check me-1'></i>Save Bank Details
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                    <a href="{{ route('create_event_setup3') }}" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">
                        <i class='bx bx-arrow-back me-1'></i>Back
                    </a>
                    <a href="{{ route('create_event_sponsor') }}" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">
                        Next <i class='bx bx-arrow-to-right ms-1'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end form-data -->
@endsection

@push('inc_events_js')
<script>
$(document).ready(function () {
    // Mobile responsive adjustments
    function adjustForMobile() {
        if (window.innerWidth <= 768) {
            // Adjust form layout for mobile
            $('.payment-form .row').addClass('mobile-form');

            // Make tabs more touch-friendly
            $('.nav-pills .nav-link').css('min-height', '44px');

            // Ensure proper spacing
            $('.col-md-6').addClass('mb-3');
        }
    }

    // Run on load and resize
    adjustForMobile();
    $(window).resize(adjustForMobile);

    // Country/City functionality
    $('#country_select').on('change', function () {
        const countryId = $(this).val();
        const citySelect = $('#city_select');

        if (countryId) {
            // Show loading state
            citySelect.html('<option disabled selected>Loading cities...</option>');
            citySelect.prop('disabled', true);

            $.ajax({
                url: '{{ route('get_cities') }}/',
                data: { country_id: countryId },
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    citySelect.empty();
                    citySelect.append('<option value="" disabled selected>Choose City</option>');

                    if (data.length > 0) {
                        $.each(data, function (key, value) {
                            citySelect.append(`<option value="${value.id}">${value.name}</option>`);
                        });
                    } else {
                        citySelect.append('<option disabled>No cities available</option>');
                    }

                    citySelect.prop('disabled', false);
                },
                error: function () {
                    citySelect.html('<option disabled selected>Error loading cities</option>');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load cities. Please try again.',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            });
        } else {
            citySelect.html('<option value="" disabled selected>Choose City</option>');
            citySelect.prop('disabled', false);
        }
    });

    // Card number formatting
    $('#card_number').on('input', function () {
        let value = $(this).val().replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        if (formattedValue !== $(this).val()) {
            $(this).val(formattedValue);
        }
    });

    // Expiry date formatting
    $('#card_expiry').on('input', function () {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        $(this).val(value);
    });

    // CVV validation
    $('#card_cvc').on('input', function () {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
    });

    // IBAN formatting
    $('#iban_input').on('input', function () {
        let value = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        $(this).val(formattedValue);
    });

    // Form validation
    $('.payment-form').each(function() {
        const form = this;
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
            } else {
                // Show loading state
                const submitBtn = $(form).find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.prop('disabled', true)
                         .html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving...');

                // Re-enable after 10 seconds as fallback
                setTimeout(function () {
                    submitBtn.prop('disabled', false).html(originalText);
                }, 10000);
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Tab switching with validation check
    $('.payment-tab').on('click', function() {
        const method = $(this).data('method');
        console.log('Switching to payment method:', method);
    });

    // Auto-save draft functionality (optional)
    let saveTimeout;
    $('.payment-form input, .payment-form select').on('input change', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(function() {
            // Could implement auto-save draft here
            console.log('Auto-saving draft...');
        }, 2000);
    });
});
</script>

<style>
.payment-method-header {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 15px;
}

.payment-method-header h5 {
    color: #0d6efd;
    margin-bottom: 5px;
}

.notice-info-place {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #0d6efd;
    margin: 0;
}

.nav-pills .nav-link {
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
    background-color: #e9ecef;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
}

.card {
    border-radius: 10px;
}

.responsive-card {
    overflow-x: auto;
}

/* Ensure proper spacing on all devices */
.form-data {
    padding: 0 15px;
}

/* Better mobile navigation */
.head-title {
    padding: 0 15px;
}

/* Improve tab visibility on mobile */
.nav-pills .nav-item {
    flex: 1;
}

/* Mobile form adjustments */
.mobile-form .col-md-6 {
    flex: 0 0 100%;
    max-width: 100%;
}

/* Touch-friendly elements */
@media (hover: none) and (pointer: coarse) {
    .nav-pills .nav-link {
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn {
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control, .form-select {
        min-height: 48px;
    }
}

/* Prevent horizontal scroll */
body {
    overflow-x: hidden;
}

.container-fluid {
    padding-left: 15px;
    padding-right: 15px;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.invalid-feedback {
    display: block;
}

.form-text {
    font-size: 0.875em;
    color: #6c757d;
}

/* Loading animation */
.bx-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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

    .notice-info-place {
        padding: 12px;
        font-size: 0.9rem;
    }

    .nav-pills .nav-link {
        padding: 10px 8px;
        font-size: 0.85rem;
        margin: 0 1px;
    }

    .nav-pills .nav-link i {
        font-size: 1rem;
    }

    .payment-method-header h5 {
        font-size: 1.1rem;
    }

    .payment-method-header p {
        font-size: 0.85rem;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .form-control, .form-select {
        padding: 10px 12px;
        font-size: 0.9rem;
    }

    .form-text {
        font-size: 0.8rem;
    }

    .btn {
        padding: 10px 20px !important;
        font-size: 0.9rem;
    }

    .card-body {
        padding: 1rem;
    }

    .col-md-6 {
        margin-bottom: 1rem;
    }

    /* Stack form fields vertically on mobile */
    .row .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

@media (max-width: 576px) {
    .head-title h1 {
        font-size: 1.3rem;
    }

    .breadcrumb {
        font-size: 0.8rem;
        flex-wrap: wrap;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        padding: 0 0.3rem;
    }

    .form-data {
        padding: 0;
    }

    .form-data .head {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .form-data .head h3 {
        font-size: 1.2rem;
    }

    .notice-info-place {
        padding: 10px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .card {
        border-radius: 8px;
        margin: 0 -15px;
    }

    .card-header {
        padding: 0.75rem;
    }

    .nav-pills {
        flex-direction: column;
        gap: 5px;
    }

    .nav-pills .nav-link {
        padding: 12px;
        text-align: center;
        border-radius: 6px;
        margin: 0;
    }

    .nav-pills .nav-link span {
        display: inline !important;
    }

    .d-md-none {
        display: none !important;
    }

    .d-none.d-md-inline {
        display: inline !important;
    }

    .payment-method-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .payment-method-header h5 {
        font-size: 1rem;
    }

    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }

    .form-control, .form-select {
        padding: 12px;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    .form-text {
        font-size: 0.75rem;
        margin-top: 0.2rem;
    }

    .invalid-feedback {
        font-size: 0.75rem;
    }

    .btn {
        padding: 12px 24px !important;
        font-size: 0.85rem;
        width: 100%;
        margin-bottom: 1rem;
    }

    .d-flex.justify-content-end {
        justify-content: center !important;
    }

    .d-flex.justify-content-center {
        flex-direction: column;
        gap: 10px;
    }

    .d-flex.justify-content-center .btn {
        margin: 0 !important;
    }

    /* Input group improvements */
    .input-group-text {
        padding: 12px;
        font-size: 0.85rem;
    }

    /* Tab content spacing */
    .tab-pane {
        padding-top: 1rem !important;
    }

    /* Form spacing */
    .row.g-3 {
        --bs-gutter-x: 0.75rem;
        --bs-gutter-y: 0.75rem;
    }
}

/* Extra small devices */
@media (max-width: 375px) {
    .head-title h1 {
        font-size: 1.1rem;
    }

    .breadcrumb {
        font-size: 0.75rem;
    }

    .form-data .head h3 {
        font-size: 1.1rem;
    }

    .notice-info-place {
        font-size: 0.8rem;
        padding: 8px;
    }

    .nav-pills .nav-link {
        padding: 10px 8px;
        font-size: 0.8rem;
    }

    .form-label {
        font-size: 0.8rem;
    }

    .form-control, .form-select {
        padding: 10px;
        font-size: 0.8rem;
    }

    .btn {
        padding: 10px 20px !important;
        font-size: 0.8rem;
    }

    .card-body {
        padding: 0.75rem;
    }
}

/* Landscape mobile orientation */
@media (max-width: 768px) and (orientation: landscape) {
    .nav-pills {
        flex-direction: row;
    }

    .nav-pills .nav-link {
        padding: 8px 12px;
        font-size: 0.8rem;
    }

    .d-flex.justify-content-center {
        flex-direction: row;
        gap: 10px;
    }

    .d-flex.justify-content-center .btn {
        width: auto;
        margin: 0 5px !important;
    }
}
</style>
@endpush
