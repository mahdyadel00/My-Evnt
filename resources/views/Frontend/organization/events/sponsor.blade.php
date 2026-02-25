@extends('Frontend.organization.events.inc.master')
@section('title', 'Create Event - Sponsorship')
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
                <i class='bx bx-trophy me-2'></i>Create New Event
            </h1>
        </div>
    </div>

    <!-- Messages -->
    @include('Frontend.organization.layouts._message')

    <!-- start form-data sponserd -->
    <div class="form-data">
        <div class="head">
            <h3><i class='bx bx-trophy me-2'></i>Choose Sponsorship</h3>
            <p>Step 5 of 5</p>
        </div>

        <!-- Info Notice -->
        <div class="col-12 mb-4">
            <div class="notice-info-place">
                <i class='bx bx-info-circle me-2'></i>
                <strong>Sponsorship Benefits:</strong><br>
                • Increase your event visibility and reach<br>
                • Get featured in our promotional campaigns<br>
                • Access to premium marketing tools<br>
                • You can skip this step and add sponsorship later
            </div>
        </div>

        <div class="container-fluid px-0">
            <form action="{{ route('event.store.sponsor') }}" method="post" class="needs-validation" novalidate>
                @csrf

                <!-- Sponsorship Plans -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4" id="sponsorship-plans">
                    @forelse($ad_fees as $index => $ad_fee)
                        <div class="col">
                            <div class="card h-100 sponsor-card {{ $index === 0 ? 'recommended' : '' }}" data-plan-id="{{ $ad_fee->id }}">
                                @if($index === 0)
                                    <div class="recommended-badge">
                                        <i class='bx bx-star'></i> Recommended
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <div class="sponsor-header mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input sponsor-radio"
                                                   type="radio"
                                                   name="ad_fee_id"
                                                   id="sponsor_{{ $ad_fee->id }}"
                                                   value="{{ $ad_fee->id }}"
                                                   {{ $index === 0 ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="sponsor_{{ $ad_fee->id }}">
                                                <h5 class="card-title mb-0 text-primary">
                                                    <i class='bx bx-crown me-1'></i>{{ $ad_fee->name }}
                                                </h5>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="sponsor-description mb-3">
                                        <p class="card-text text-muted">{{ $ad_fee->description }}</p>
                                    </div>

                                    <div class="sponsor-features mb-3 flex-grow-1">
                                        @php
                                            $features = [
                                                'Enhanced visibility',
                                                'Priority listing',
                                                'Marketing support',
                                                'Analytics dashboard'
                                            ];
                                        @endphp
                                        <ul class="feature-list">
                                            @foreach(array_slice($features, 0, $index + 2) as $feature)
                                                <li><i class='bx bx-check text-success me-1'></i>{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="sponsor-pricing mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="duration">
                                                <small class="text-muted">
                                                    <i class='bx bx-time me-1'></i>{{ $ad_fee->duration }}
                                                </small>
                                            </div>
                                            <div class="price">
                                                <strong class="h5 text-primary mb-0">
                                                    ${{ number_format($ad_fee->price, 2) }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class='bx bx-info-circle me-2'></i>
                                No sponsorship plans available at the moment.
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Error Display -->
                @error('ad_fee_id')
                    <div class="alert alert-danger mb-4">
                        <i class='bx bx-error me-2'></i>{{ $message }}
                    </div>
                @enderror

                <!-- Selected Plan Summary -->
                <div id="selected-plan-summary" class="card border-primary mb-4" style="display: none;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class='bx bx-check-circle me-2'></i>Selected Sponsorship Plan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 id="summary-name" class="text-primary"></h6>
                                <p id="summary-description" class="text-muted mb-0"></p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div id="summary-duration" class="text-muted small"></div>
                                <div id="summary-price" class="h5 text-primary mb-0"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                    <a href="{{ route('create_event_setup3') }}" class="btn btn-secondary me-3">
                        <i class='bx bx-arrow-back me-1'></i>Back
                    </a>
                    <a href="{{ route('create_event_setup5') }}" class="btn btn-outline-secondary me-3">
                        <i class='bx bx-skip-next me-1'></i>Skip for Now
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class='bx bx-check me-1'></i>Complete Setup
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- end form-data sponserd -->
@endsection

@push('inc_events_js')
<script>
$(document).ready(function () {
    // Initialize
    updateSelectedPlan();

    // Handle sponsor card selection
    $('.sponsor-card').on('click', function() {
        const radioInput = $(this).find('.sponsor-radio');

        // Remove active class from all cards
        $('.sponsor-card').removeClass('active');

        // Add active class to clicked card
        $(this).addClass('active');

        // Check the radio button
        radioInput.prop('checked', true);

        // Update summary
        updateSelectedPlan();

        // Add selection animation
        $(this).addClass('selected-animation');
        setTimeout(() => {
            $(this).removeClass('selected-animation');
        }, 300);
    });

    // Handle radio button change
    $('.sponsor-radio').on('change', function() {
        if ($(this).is(':checked')) {
            // Remove active class from all cards
            $('.sponsor-card').removeClass('active');

            // Add active class to parent card
            $(this).closest('.sponsor-card').addClass('active');

            // Update summary
            updateSelectedPlan();
        }
    });

    // Update selected plan summary
    function updateSelectedPlan() {
        const selectedRadio = $('.sponsor-radio:checked');

        if (selectedRadio.length > 0) {
            const card = selectedRadio.closest('.sponsor-card');
            const planId = card.data('plan-id');
            const name = card.find('.card-title').text().trim();
            const description = card.find('.card-text').text().trim();
            const duration = card.find('.duration small').text().trim();
            const price = card.find('.price strong').text().trim();

            // Update summary content
            $('#summary-name').text(name);
            $('#summary-description').text(description);
            $('#summary-duration').text(duration);
            $('#summary-price').text(price);

            // Show summary
            $('#selected-plan-summary').slideDown(300);

            // Update submit button
            $('#submit-btn').html('<i class="bx bx-check me-1"></i>Complete Setup with ' + name);
        } else {
            $('#selected-plan-summary').slideUp(300);
            $('#submit-btn').html('<i class="bx bx-check me-1"></i>Complete Setup');
        }
    }

    // Form validation
    const form = document.querySelector('.needs-validation');
    if (form) {
        form.addEventListener('submit', function (event) {
            const selectedPlan = $('.sponsor-radio:checked');

            if (selectedPlan.length === 0) {
                event.preventDefault();
                event.stopPropagation();

                Swal.fire({
                    icon: 'warning',
                    title: 'No Plan Selected',
                    text: 'Please select a sponsorship plan or skip this step.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0d6efd'
                });

                return false;
            }

            // Show loading state
            const submitBtn = $('#submit-btn');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true)
                     .html('<i class="bx bx-loader-alt bx-spin me-1"></i>Processing...');

            // Re-enable after 10 seconds as fallback
            setTimeout(function () {
                submitBtn.prop('disabled', false).html(originalText);
            }, 10000);

            form.classList.add('was-validated');
        }, false);
    }

    // Add hover effects
    $('.sponsor-card').hover(
        function() {
            if (!$(this).hasClass('active')) {
                $(this).addClass('hover-effect');
            }
        },
        function() {
            $(this).removeClass('hover-effect');
        }
    );

    // Initialize first card as selected if none selected
    if ($('.sponsor-radio:checked').length === 0 && $('.sponsor-radio').length > 0) {
        $('.sponsor-radio').first().prop('checked', true).trigger('change');
    }
});
</script>

<style>
.notice-info-place {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #0d6efd;
    margin: 0;
}

.sponsor-card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.sponsor-card:hover,
.sponsor-card.hover-effect {
    border-color: #0d6efd;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
    transform: translateY(-2px);
}

.sponsor-card.active {
    border-color: #0d6efd;
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.15);
    background-color: #f8f9ff;
}

.sponsor-card.recommended {
    border-color: #28a745;
}

.recommended-badge {
    position: absolute;
    top: 15px;
    right: -30px;
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 5px 35px;
    font-size: 0.8rem;
    font-weight: 600;
    transform: rotate(45deg);
    z-index: 1;
}

.sponsor-header .form-check {
    margin-bottom: 0;
}

.sponsor-radio {
    transform: scale(1.2);
    margin-right: 10px;
}

.sponsor-radio:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    padding: 4px 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.sponsor-pricing {
    border-top: 1px solid #e9ecef;
    padding-top: 15px;
}

.selected-animation {
    animation: selectPulse 0.3s ease;
}

@keyframes selectPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
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

    .sponsor-card {
        margin-bottom: 1rem;
    }

    .recommended-badge {
        font-size: 0.7rem;
        padding: 4px 30px;
    }

    .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .d-flex.justify-content-center {
        flex-direction: column;
        align-items: stretch;
    }

    .d-flex.justify-content-center .btn {
        margin: 5px 0;
    }
}

@media (max-width: 576px) {
    .head-title h1 {
        font-size: 1.3rem;
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
    }

    .sponsor-card .card-body {
        padding: 1rem;
    }

    .sponsor-card .card-title {
        font-size: 1.1rem;
    }

    .feature-list li {
        font-size: 0.85rem;
    }

    .recommended-badge {
        font-size: 0.65rem;
        padding: 3px 25px;
        right: -35px;
    }

    #selected-plan-summary .row {
        text-align: center;
    }

    #selected-plan-summary .col-md-4 {
        margin-top: 10px;
    }
}

/* Touch-friendly elements */
@media (hover: none) and (pointer: coarse) {
    .sponsor-card {
        min-height: 200px;
    }

    .btn {
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sponsor-radio {
        min-width: 20px;
        min-height: 20px;
    }
}
</style>
@endpush
