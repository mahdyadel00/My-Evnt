@extends('Frontend.organization.events.inc.master')
@section('title', 'Create Event - Venue Location')
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
                <i class='bx bx-map-pin me-2'></i>Create New Event
            </h1>
        </div>
    </div>

    <!-- Messages -->
    @include('Frontend.organization.layouts._message')

        <!-- start form-data -->
    <div class="form-data">
        <div class="head">
            <h3>Venue Location</h3>
            <p>Step 2 of 5</p>
        </div>
        <!-- form step 2 -->
        <form class="row g-3 needs-validation" action="{{ route('event.store.setup2') }}" method="post" novalidate>
            @csrf

            <!-- Country Selection -->
            <div class="col-12">
                <label for="country_id" class="form-label">
                    <i class='bx bx-world me-1'></i>Country *
                </label>
                <select class="form-select p-2 @error('country_id') is-invalid @enderror"
                        name="country_id" id="country_id" required>
                    <option value="" disabled selected>Choose your country...</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">
                        <i class='bx bx-info-circle me-1'></i>Select your country
                    </div>
                @enderror
            </div>

            <!-- City Selection -->
            <div class="col-12">
                <label for="city_id" class="form-label">
                    <i class='bx bx-buildings me-1'></i>City *
                </label>
                <select class="form-select p-2 @error('city_id') is-invalid @enderror"
                        name="city_id" id="city_id" required disabled>
                    <option value="" selected>First select a country...</option>
                </select>
                @error('city_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">
                        <i class='bx bx-info-circle me-1'></i>Select a country first to load available cities
                    </div>
                @enderror
            </div>

            <!-- Location -->
            <div class="col-12">
                <label for="location" class="form-label">
                    <i class='bx bx-current-location me-1'></i>Venue Name/Location
                </label>
                <input type="text"
                       class="form-control @error('location') is-invalid @enderror"
                       id="location"
                       name="location"
                       value="{{ old('location') }}"
                       placeholder="e.g., Grand Conference Hall, Downtown Center">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">
                        <i class='bx bx-bulb me-1'></i>Enter the venue or location name
                    </div>
                @enderror
            </div>

            <!-- Address -->
            <div class="col-12">
                <label for="address" class="form-label">
                    <i class='bx bx-map me-1'></i>Event Address
                </label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address"
                          name="address"
                          rows="3"
                          placeholder="Enter the complete address including street, building number, landmarks, etc.">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">
                        <i class='bx bx-bulb me-1'></i>Provide detailed address to help attendees find your event easily
                    </div>
                @enderror
            </div>

            <!-- Help Notice -->
            <div class="col-12">
                <p class="notice-info-place">
                    <i class='bx bx-help-circle me-1'></i>If you didn't find your country or city in the proposed list, please
                    <a href="{{ route('contacts') }}">contact support</a> to add the value you need.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                <a href="{{ route('create_event') }}" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">
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
            // Initialize form validation
            const form = document.querySelector('.needs-validation');

            // Country and City Selection Handler
            $('#country_id').on('change', function () {
                const countryId = $(this).val();
                const citySelect = $('#city_id');

                if (countryId) {
                    // Show loading state
                    citySelect.html('<option value="">Loading cities...</option>')
                              .prop('disabled', true)
                              .removeClass('is-invalid');

                    // Fetch cities via AJAX
                    $.ajax({
                        url: '{{ route('get_cities') }}',
                        method: 'GET',
                        data: { country_id: countryId },
                        dataType: 'json',
                        success: function (cities) {
                            citySelect.empty().prop('disabled', false);

                            if (cities.length > 0) {
                                citySelect.append('<option value="">Select a city...</option>');
                                $.each(cities, function (index, city) {
                                    citySelect.append(`<option value="${city.id}">${city.name}</option>`);
                                });
                            } else {
                                citySelect.append('<option value="">No cities available</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching cities:', error);
                            citySelect.html('<option value="">Error loading cities</option>')
                                     .prop('disabled', false);
                        }
                    });
                } else {
                    // Reset city selection
                    citySelect.html('<option value="">First select a country...</option>')
                             .prop('disabled', true);
                }
            });

            // Form Validation
            if (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Focus on first invalid field
                        const firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.focus();
                        }
                    }

                    form.classList.add('was-validated');
                }, false);
            }

            // Auto-resize textarea
            $('#address').on('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

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
        });
    </script>

        <style>
        /* Enhanced form styling while keeping original design */
        .form-select:focus,
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-text {
            font-size: 0.875em;
            color: #6c757d;
        }

        .notice-info-place {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
            margin: 0;
        }

        .invalid-feedback {
            display: block;
        }

        /* Loading animation for submit button */
        .bx-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush