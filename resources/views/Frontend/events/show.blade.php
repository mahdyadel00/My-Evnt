@extends('Frontend.layouts.master')

@php
    $seoService = app(\App\Services\SeoService::class);
    $eventSeoMeta = $seoService->generateEventMetaTags($event);
@endphp

@section('title', $eventSeoMeta['title'])

@section('content')
    @php
        if (auth()->check()) {
            $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
        }
        $firstDate = $event->eventDates->first();
        $eventDate = $firstDate;
    @endphp
    @include('Frontend.events.style')
    @include('Frontend.events.show_details_body')

    {{-- Share modal (nebule design) --}}
    <div class="modal nebule-event-details__modal nebule-event-details__share-modal" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content nebule-event-details__modal-content">
                <div class="modal-header nebule-event-details__modal-header nebule-event-details__share-modal-header">
                    <h5 class="modal-title nebule-event-details__modal-title" id="shareModalLabel">Share & Invite your friends</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body nebule-event-details__modal-body">
                    @php $banner = $event->media->where('name', 'banner')->first() ?? $event->media->first(); @endphp
                    <div class="nebule-event-details__share-event-card">
                        @if($banner)
                            <img src="{{ asset('storage/' . $banner->path) }}" alt="" class="nebule-event-details__share-event-thumb" width="80" height="80" />
                        @endif
                        <div class="nebule-event-details__share-event-info">
                            <span class="nebule-event-details__share-event-name">{{ $event->name }}</span>
                            <span class="nebule-event-details__share-event-date"><i class="far fa-calendar-alt"></i> {{ $event->eventDates->first() ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('D, j M Y – g:i A') : '—' }}</span>
                        </div>
                    </div>
                    <div class="nebule-event-details__share-link-wrap">
                        <input type="text" readonly class="nebule-event-details__share-link-input" id="shareLinkInput" value="{{ route('event', $event->uuid) }}" aria-label="Share link" />
                        <button type="button" class="nebule-event-details__share-copy-btn" id="shareCopyBtn" aria-label="Copy link"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                    <div class="nebule-event-details__share-social-wrap">
                        <span class="nebule-event-details__share-social-title">Social share</span>
                        <div class="nebule-event-details__share-social-list">
                            <a href="mailto:?body={{ urlencode(route('event', $event->uuid)) }}" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--email" title="Email" aria-label="Share via Email"><i class="fas fa-envelope"></i><span>Email</span></a>
                            <a href="https://wa.me/?text={{ urlencode(route('event', $event->uuid)) }}" target="_blank" rel="noopener" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--whatsapp" title="WhatsApp"><i class="fab fa-whatsapp"></i><span>WhatsApp</span></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('event', $event->uuid)) }}" target="_blank" rel="noopener" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--facebook" title="Facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('event', $event->uuid)) }}" target="_blank" rel="noopener" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--linkedin" title="LinkedIn"><i class="fab fa-linkedin-in"></i><span>LinkedIn</span></a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('event', $event->uuid)) }}" target="_blank" rel="noopener" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--twitter" title="Twitter"><i class="fab fa-x-twitter"></i><span>Twitter</span></a>
                            <a href="https://www.tiktok.com/" target="_blank" rel="noopener" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--tiktok" title="TikTok"><i class="fab fa-tiktok"></i><span>TikTok</span></a>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener" class="nebule-event-details__share-social-item nebule-event-details__share-social-item--instagram" title="Instagram"><i class="fab fa-instagram"></i><span>Instagram</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($event->cancellation_policy)
        <div class="modal" id="cancellation_policy" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Cancellation policy</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">{!! $event->cancellation_policy !!}</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Guest Survey Modal -->
                <div class="modal fade" id="guestSurveyModal" tabindex="-1" aria-labelledby="guestSurveyModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                            <div class="modal-header"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                                <h5 class="modal-title" id="guestSurveyModalLabel">
                                    <i class="fa fa-clipboard-list me-2"></i>Get Your 50% Off Promo Code
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="guestSurveyForm">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                                    <div class="mb-3">
                                        <label for="first_name" class="form-label fw-bold">
                                            <i class="fa fa-user me-1"></i>First Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required
                                            placeholder="Enter your first name">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="last_name" class="form-label fw-bold">
                                            <i class="fa fa-user me-1"></i>Last Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required
                                            placeholder="Enter your last name">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">
                                            <i class="fa fa-envelope me-1"></i>Email Address <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            placeholder="your.email@example.com">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-bold">
                                            <i class="fa fa-phone me-1"></i>Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required
                                            placeholder="+20 123 456 7890">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label fw-bold">
                                            <i class="fa fa-comment me-1"></i>Notes (Optional)
                                        </label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                            placeholder="Any special requests or notes..."></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="alert alert-info mb-3" role="alert">
                                        <i class="fa fa-info-circle me-2"></i>
                                        <small>Fill out the form below and receive your exclusive discount
                                            code instantly..</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100" id="submitGuestSurvey"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; border-radius: 8px;">
                                        <i class="fa fa-paper-plane me-2"></i>Submit & Continue to Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end part get ticket -->

                <!-- Carerha Modal -->
                <div class="modal fade" id="carerhaModal" tabindex="-1" aria-labelledby="carerhaModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                            <div class="modal-header"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                                <h5 class="modal-title" id="carerhaModalLabel">
                                    <i class="fa fa-clipboard-list me-2"></i>Get Your 20% Off Promo Code
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="carerhaForm">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                                    <div class="mb-3">
                                        <label for="carerha_first_name" class="form-label fw-bold">
                                            <i class="fa fa-user me-1"></i>First Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="carerha_first_name" name="first_name"
                                            required placeholder="Enter your first name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="carerha_last_name" class="form-label fw-bold">
                                            <i class="fa fa-user me-1"></i>Last Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="carerha_last_name" name="last_name"
                                            required placeholder="Enter your last name">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="carerha_email" class="form-label fw-bold">
                                            <i class="fa fa-envelope me-1"></i>Email Address <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="carerha_email" name="email" required
                                            placeholder="your.email@example.com">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="carerha_phone" class="form-label fw-bold">
                                            <i class="fa fa-phone me-1"></i>Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" class="form-control" id="carerha_phone" name="phone" required
                                            placeholder="+20 123 456 7890">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="carerha_notes" class="form-label fw-bold">
                                            <i class="fa fa-comment me-1"></i>Notes (Optional)
                                        </label>
                                        <textarea class="form-control" id="carerha_notes" name="notes" rows="3"
                                            placeholder="Any special requests or notes..."></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="alert alert-info mb-3" role="alert">
                                        <i class="fa fa-info-circle me-2"></i>
                                        <small>Fill out the form below and receive your exclusive promo code
                                            instantly.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100" id="submitCarerha"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; border-radius: 8px;">
                                        <i class="fa fa-paper-plane me-2"></i>Submit & Continue to Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Carerha Modal -->
    </div>

    {{-- You May Also Like (New-card design) --}}
    @if(isset($event_related) && $event_related->isNotEmpty())
        <div class="New-card-event-cards-container related-events-container-nebule">
            <h2 class="related-events-title">You May Also Like</h2>
            <div class="New-card-event-cards-grid">
                @foreach($event_related as $relatedEvent)
                    @php
                        $relBanner = $relatedEvent->media->where('name', 'banner')->first() ?? $relatedEvent->media->first();
                        $relFirstDate = $relatedEvent->eventDates->first();
                        $relIsFree = $relatedEvent->tickets->isNotEmpty() && (float)$relatedEvent->tickets->first()->price == 0;
                        if (auth()->check()) { $relatedEvent->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray(); }
                    @endphp
                    <div class="New-card-event-card">
                        <a href="{{ route('event', $relatedEvent->uuid) }}">
                            <img src="{{ $relBanner ? asset('storage/' . $relBanner->path) : asset('Front/img/default-event.jpg') }}" alt="{{ $relatedEvent->name }}" class="New-card-event-card-image" />
                        </a>
                        @if(auth()->check())
                            <div class="New-card-event-favorite-btn heart" data-event-id="{{ $relatedEvent->id }}" aria-label="Add to favorites">
                                <i class="fa-{{ in_array($relatedEvent->id, $relatedEvent->is_favourite ?? []) ? 'solid' : 'regular' }} fa-heart"></i>
                            </div>
                        @endif
                        <div class="New-card-event-card-content">
                            <div class="New-card-event-card-header">
                                <h3 class="New-card-event-card-title">{{ Str::limit($relatedEvent->name, 30) }}</h3>
                                <div class="New-card-event-card-category">{{ $relatedEvent->category?->name }}</div>
                            </div>
                            <p class="New-card-event-card-description">{{ Str::limit(strip_tags($relatedEvent->description ?? ''), 100) }}</p>
                            <div class="New-card-event-card-details">
                                @if($relatedEvent->city)
                                    <div class="New-card-event-card-detail">
                                        <span class="New-card-event-card-detail-icon"><i class="fas fa-location-dot"></i></span>
                                        <span class="New-card-event-card-detail-value">{{ $relatedEvent->city->name }}</span>
                                    </div>
                                @endif
                                <div class="New-card-event-card-detail">
                                    <span class="New-card-event-card-detail-icon"><i class="fas fa-calendar-days"></i></span>
                                    <span class="New-card-event-card-detail-value">{{ $relFirstDate ? \Carbon\Carbon::parse($relFirstDate->start_date)->format('M j, Y') : '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="New-card-event-card-footer">
                            <div class="New-card-event-card-price">
                                <span class="New-card-event-card-price-amount">{{ $relIsFree ? 'Free' : ($relatedEvent->tickets->isNotEmpty() ? number_format($relatedEvent->tickets->first()->price, 0) : '0') }}</span>
                                <span class="New-card-event-card-price-currency">{{ !$relIsFree && $relatedEvent->currency ? $relatedEvent->currency->code : '' }}</span>
                            </div>
                            <a href="{{ route('event', $relatedEvent->uuid) }}" class="New-card-event-details-btn">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($event->media->where('type', 'gallery')->isNotEmpty())
        @include('Frontend.events.gallery_feedback')
    @endif
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/event_details.css') }}" />
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('Front') }}/js/bootstrap.min2.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Front/js/event_details.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
        var clipboard = new ClipboardJS('#copy');
        clipboard.on('success', function (e) {
        });
    </script>
    <!-- تحميل مكتبة Google Maps API -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE&libraries=places&callback=initMap"
        async defer></script>

    <script>
        function initMap() {
            if (typeof google !== 'object' || typeof google.maps !== 'object') {
                return;
            }
            if (!document.getElementById('map-desktop')) {
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
        $(document).ready(function () {
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '#heart-icon, .heart', function (e) {
                e.preventDefault();
                if (!$(this).data('event-id')) {
                    return;
                }
                let event_id = $(this).data('event-id');
                let icon = $(this).find('i');

                $.ajax({
                    url: '{{ route('favourite') }}',
                    type: 'POST',
                    data: {
                        event_id: event_id
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            icon.toggleClass('fa-solid fa-regular');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'An error occurred',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr) {
                        let message = 'Something went wrong. Please try again.';
                        if (xhr.status === 401) {
                            message = 'Please login to perform this action.';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let followerCount = parseInt($('.follower-count').text().trim());

            if (followerCount <= 0) {
                $('.follower-count').parent().hide();
            } else {
                $('.follower-count').parent().show();
            }

            $('.follow-btn').on('click', function () {
                let companyId = $(this).data('id');
                let button = $(this);
                let followerCountElement = $('.follower-count');

                button.prop('disabled', true);

                if (button.hasClass('btn-outline-primary')) {
                    button.text('Following').removeClass('btn-outline-primary').addClass('btn-primary');
                } else {
                    button.text('Follow').removeClass('btn-primary').addClass('btn-outline-primary');
                }

                $.ajax({
                    url: `/company/follow/${companyId}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.status === 'followed' || response.status === 'unfollowed') {
                            followerCountElement.text(response.followerCount);

                            followerCount = parseInt(followerCountElement.text().trim());

                            if (followerCount <= 0) {
                                $('.follower-count').parent().hide();
                            } else {
                                $('.follower-count').parent().show();
                            }

                            if (response.status === 'followed') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Followed Successfully',
                                    text: 'You are now following this company!',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Unfollowed',
                                    text: 'You have unfollowed this company.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Not Logged In',
                                text: 'You need to log in to follow companies.',
                                confirmButtonText: 'Login'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong, please try again later.',
                                confirmButtonText: 'OK'
                            });
                        }

                        if (button.hasClass('btn-primary')) {
                            button.text('Follow').removeClass('btn-primary').addClass('btn-outline-primary');
                        }
                    },
                    complete: function () {
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    <script type="application/ld+json">
                                {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $event->name,
        'startDate' => optional($event->eventDates->first())->start_date
            ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->toIso8601String()
            : now()->toIso8601String(),
        'endDate' => optional($event->eventDates->first())->end_date
            ? \Carbon\Carbon::parse($event->eventDates->first()->end_date)->toIso8601String()
            : now()->addHours(2)->toIso8601String(),
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => $event->format
            ? 'https://schema.org/OnlineEventAttendanceMode'
            : 'https://schema.org/OfflineEventAttendanceMode',
        'location' => $event->format
            ? [
                '@type' => 'VirtualLocation',
                'url' => $event->link_meeting ?? url()->current(),
            ]
            : array_filter([
                '@type' => 'Place',
                'name' => $event->location ?? 'Event Location',
                'address' => array_filter([
                    '@type' => 'PostalAddress',
                    'streetAddress' => $event->address,
                    'addressLocality' => $event->city?->name,
                    'addressRegion' => $event->city?->country?->name,
                    'addressCountry' => 'EG',
                ]),
                'geo' => ($event->latitude && $event->longitude) ? [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $event->latitude,
                    'longitude' => $event->longitude,
                ] : null,
            ]),
        'image' => [
            $event->media->where('name', 'banner')->first()
            ? asset('storage/' . $event->media->where('name', 'banner')->first()->path)
            : asset('images/default-event.jpg')
        ],
        'description' => strip_tags($event->description ?? ''),
        'offers' => $event->tickets->isNotEmpty() ? [
            '@type' => 'Offer',
            'url' => route('event', $event->uuid),
            'price' => (float) $event->tickets->first()->price,
            'priceCurrency' => $event->currency?->code ?? 'EGP',
            'availability' => 'https://schema.org/' . ($event->tickets->first()->quantity > 0 ? 'InStock' : 'SoldOut'),
            'validFrom' => $event->created_at->toIso8601String(),
        ] : null,
        'organizer' => [
            '@type' => 'Organization',
            'name' => $event->company->company_name ?? $event->organized_by ?? config('app.name'),
            'url' => $event->company ? route('company_profile', $event->company->id) : url('/'),
        ],
        'performer' => $event->company ? [
            '@type' => 'Organization',
            'name' => $event->company->company_name,
        ] : null,
        'eventCategory' => $event->category?->name,
        'keywords' => trim($event->category?->name . ' ' . $event->city?->name),
        'url' => route('event', $event->uuid),
    ], fn($v) => !is_null($v)), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
                            </script>

    <script>
        // Carerha Form Submission
        $(document).ready(function () {
            $('#carerhaForm').on('submit', function (e) {
                e.preventDefault();

                // Remove previous error states
                $('#carerhaForm .form-control').removeClass('is-invalid');
                $('#carerhaForm .invalid-feedback').text('');

                // Get form data
                const formData = new FormData(this);
                const submitButton = $('#submitCarerha');
                const originalButtonText = submitButton.html();

                // Disable button and show loading state
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin me-2"></i>Processing...');

                // Send AJAX request
                $.ajax({
                    url: '{{ url('/carerha/store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            // Close the form modal first
                            $('#carerhaModal').modal('hide');

                            // Show promo code with copy button
                            const promoCode = response.promo_code || 'ME20';

                            Swal.fire({
                                title: '<strong style="color: #667eea;">🎉 Registration Successful!</strong>',
                                html: `
                                            <div style="padding: 20px;">
                                                <p style="font-size: 16px; margin-bottom: 20px;">
                                                    Thank you! Your registration was successful
                                                </p>
                                                <p style="font-size: 14px; color: #667eea; margin-bottom: 15px; font-weight: 600;">
                                                    <i class="fa fa-envelope me-2"></i>
                                                    Please check your email
                                                </p>
                                                <p style="font-size: 14px; color: #666; margin-bottom: 15px;">
                                                    Here's your exclusive promo code:
                                                </p>
                                                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                                            padding: 15px 25px; 
                                                            border-radius: 10px; 
                                                            margin: 20px 0;
                                                            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                                        <i class="fa fa-gift" style="color: white; font-size: 24px;"></i>
                                                        <span id="carerhaPromoCodeText" style="font-size: 28px; 
                                                                                       font-weight: bold; 
                                                                                       color: white; 
                                                                                       letter-spacing: 3px; 
                                                                                       font-family: 'Courier New', monospace;">
                                                            ${promoCode}
                                                        </span>
                                                    </div>
                                                </div>
                                                <p style="font-size: 13px; color: #888; margin-top: 10px;">
                                                    <i class="fa fa-info-circle"></i>
                                                    Click the button below to copy the code and continue to the booking page
                                                </p>
                                            </div>
                                        `,
                                showCancelButton: false,
                                confirmButtonText: '<i class="fa fa-copy me-2"></i>Copy & Continue',
                                confirmButtonColor: '#667eea',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    popup: 'promo-code-popup',
                                    confirmButton: 'promo-code-btn'
                                },
                                didOpen: () => {
                                    // Add custom styles
                                    const style = document.createElement('style');
                                    style.innerHTML = `
                                                .promo-code-popup {
                                                    border-radius: 20px !important;
                                                    padding: 20px !important;
                                                }
                                                .promo-code-btn {
                                                    padding: 12px 30px !important;
                                                    font-size: 16px !important;
                                                    border-radius: 10px !important;
                                                    font-weight: 600 !important;
                                                    transition: all 0.3s ease !important;
                                                }
                                                .promo-code-btn:hover {
                                                    transform: translateY(-2px);
                                                    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4) !important;
                                                }
                                            `;
                                    document.head.appendChild(style);
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Copy promo code to clipboard
                                    const textToCopy = promoCode;
                                    const checkoutUrl = '{{ $event->external_link ?? route("checkout_user", optional($eventDate)->id) }}';

                                    // Modern clipboard API
                                    if (navigator.clipboard && navigator.clipboard.writeText) {
                                        navigator.clipboard.writeText(textToCopy).then(() => {
                                            // Show copied confirmation
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Copied!',
                                                text: 'Promo code copied to clipboard. Redirecting...',
                                                showConfirmButton: false,
                                                timer: 1500,
                                                toast: true,
                                                position: 'top-end'
                                            }).then(() => {
                                                // Redirect to external link
                                                window.location.href = checkoutUrl;
                                            });
                                        }).catch(err => {
                                            // Fallback if clipboard API fails
                                            fallbackCopyTextToClipboard(textToCopy);
                                            // Redirect even if copy fails
                                            setTimeout(() => {
                                                window.location.href = checkoutUrl;
                                            }, 1500);
                                        });
                                    } else {
                                        // Fallback for older browsers
                                        fallbackCopyTextToClipboard(textToCopy);
                                        // Redirect after copy attempt
                                        setTimeout(() => {
                                            window.location.href = checkoutUrl;
                                        }, 1500);
                                    }
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        submitButton.prop('disabled', false);
                        submitButton.html(originalButtonText);

                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;

                            // Display validation errors
                            $.each(errors, function (field, messages) {
                                const input = $('#carerhaForm [name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form and try again.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // General error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            // Remove error state on input change for Carerha form
            $('#carerhaForm .form-control').on('input change', function () {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            });

            // Reset form when Carerha modal is closed
            $('#carerhaModal').on('hidden.bs.modal', function () {
                $('#carerhaForm')[0].reset();
                $('#carerhaForm .form-control').removeClass('is-invalid');
                $('#carerhaForm .invalid-feedback').text('');
                $('#submitCarerha').prop('disabled', false);
                $('#submitCarerha').html('<i class="fa fa-paper-plane me-2"></i>Submit & Continue to Booking');
            });

            // Guest Survey Form Submission
            $('#guestSurveyForm').on('submit', function (e) {
                e.preventDefault();

                // Remove previous error states
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get form data
                const formData = new FormData(this);
                const submitButton = $('#submitGuestSurvey');
                const originalButtonText = submitButton.html();

                // Disable button and show loading state
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin me-2"></i>Processing...');

                // Send AJAX request
                $.ajax({
                    url: '{{ url('/guest-survey/store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            // Close the form modal first
                            $('#guestSurveyModal').modal('hide');

                            // Show promo code with copy button
                            const promoCode = 'MyeventX50Engineerex25';

                            Swal.fire({
                                title: '<strong style="color: #667eea;">🎉 Registration Successful!</strong>',
                                html: `
                                                <div style="padding: 20px;">
                                                    <p style="font-size: 16px; margin-bottom: 20px;">
                                                        Thank you! Your registration was successful
                                                    </p>
                                                    <p style="font-size: 14px; color: #667eea; margin-bottom: 15px; font-weight: 600;">
                                                        <i class="fa fa-envelope me-2"></i>
                                                        Please check your email
                                                    </p>
                                                    <p style="font-size: 14px; color: #666; margin-bottom: 15px;">
                                                        Here's your exclusive promo code:
                                                    </p>
                                                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                                                padding: 15px 25px;
                                                                border-radius: 10px;
                                                                margin: 20px 0;
                                                                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                                        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                                            <i class="fa fa-gift" style="color: white; font-size: 24px;"></i>
                                                            <span id="promoCodeText" style="font-size: 28px;
                                                                                           font-weight: bold;
                                                                                           color: white;
                                                                                           letter-spacing: 3px;
                                                                                           font-family: 'Courier New', monospace;">
                                                                ${promoCode}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p style="font-size: 13px; color: #888; margin-top: 10px;">
                                                        <i class="fa fa-info-circle"></i>
                                                        Click the button below to copy the code and continue to the booking page
                                                    </p>
                                                </div>
                                            `,
                                showCancelButton: false,
                                confirmButtonText: '<i class="fa fa-copy me-2"></i>Copy & Continue',
                                confirmButtonColor: '#667eea',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    popup: 'promo-code-popup',
                                    confirmButton: 'promo-code-btn'
                                },
                                didOpen: () => {
                                    // Add custom styles
                                    const style = document.createElement('style');
                                    style.innerHTML = `
                                                    .promo-code-popup {
                                                        border-radius: 20px !important;
                                                        padding: 20px !important;
                                                    }
                                                    .promo-code-btn {
                                                        padding: 12px 30px !important;
                                                        font-size: 16px !important;
                                                        border-radius: 10px !important;
                                                        font-weight: 600 !important;
                                                        transition: all 0.3s ease !important;
                                                    }
                                                    .promo-code-btn:hover {
                                                        transform: translateY(-2px);
                                                        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4) !important;
                                                    }
                                                `;
                                    document.head.appendChild(style);
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Copy promo code to clipboard
                                    const textToCopy = promoCode;
                                    const checkoutUrl = '{{ $event->external_link ?? route("checkout_user", optional($eventDate)->id) }}';

                                    // Modern clipboard API
                                    if (navigator.clipboard && navigator.clipboard.writeText) {
                                        navigator.clipboard.writeText(textToCopy).then(() => {
                                            // Show copied confirmation
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Copied!',
                                                text: 'Promo code copied to clipboard. Redirecting...',
                                                showConfirmButton: false,
                                                timer: 1500,
                                                toast: true,
                                                position: 'top-end'
                                            }).then(() => {
                                                // Redirect to external link
                                                window.location.href = checkoutUrl;
                                            });
                                        }).catch(err => {
                                            // Fallback if clipboard API fails
                                            fallbackCopyTextToClipboard(textToCopy);
                                            // Redirect even if copy fails
                                            setTimeout(() => {
                                                window.location.href = checkoutUrl;
                                            }, 1500);
                                        });
                                    } else {
                                        // Fallback for older browsers
                                        fallbackCopyTextToClipboard(textToCopy);
                                        // Redirect after copy attempt
                                        setTimeout(() => {
                                            window.location.href = checkoutUrl;
                                        }, 1500);
                                    }
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        submitButton.prop('disabled', false);
                        submitButton.html(originalButtonText);

                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;

                            // Display validation errors
                            $.each(errors, function (field, messages) {
                                const input = $('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form and try again.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // General error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            // Remove error state on input change for Guest Survey form
            $('#guestSurveyForm .form-control').on('input change', function () {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            });

            // Reset form when Guest Survey modal is closed
            $('#guestSurveyModal').on('hidden.bs.modal', function () {
                $('#guestSurveyForm')[0].reset();
                $('#guestSurveyForm .form-control').removeClass('is-invalid');
                $('#guestSurveyForm .invalid-feedback').text('');
                $('#submitGuestSurvey').prop('disabled', false);
                $('#submitGuestSurvey').html('<i class="fa fa-paper-plane me-2"></i>Submit & Continue to Booking');
            });

            // Fallback copy function for older browsers
            window.fallbackCopyTextToClipboard = function (text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.top = '-9999px';
                textArea.style.left = '-9999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied!',
                            text: 'Promo code copied to clipboard',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                } catch (err) {
                    console.error('Fallback: Could not copy text', err);
                    Swal.fire({
                        icon: 'info',
                        title: 'Copy Manually',
                        text: 'Please copy the promo code: ' + text,
                        confirmButtonText: 'OK'
                    });
                }

                document.body.removeChild(textArea);
            };

            // Helper function removed - no redirect needed
        });
    </script>
    <script>
        // Gallery Modal
        // Add modal HTML to the document
        document.body.insertAdjacentHTML(
            "beforeend",
            `
        <div class="modal-gallery" id="mediaModal">
            <span class="close-modal">&times;</span>
            <button class="nav-button prev-button">&#10094;</button>
            <button class="nav-button next-button">&#10095;</button>
            <div class="modal-content-gallery" id="modalContent">
                <img id="modalImage" style="display: none;">
                <video id="modalVideo" controls style="display: none;">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    `
        );

        // Get modal elements
        const modal = document.getElementById("mediaModal");
        const modalContent = document.getElementById("modalContent");
        const modalImg = document.getElementById("modalImage");
        const modalVideo = document.getElementById("modalVideo");
        const closeBtn = document.getElementsByClassName("close-modal")[0];
        const prevBtn = document.querySelector(".prev-button");
        const nextBtn = document.querySelector(".next-button");

        // Get all gallery media items (both images and videos)
        const galleryItems = Array.from(
            document.querySelectorAll(
                ".gallery-img img, .gallery-img video"
            )
        );
        let currentItemIndex = 0;

        // Function to show media at current index
        function showMedia(index) {
            currentItemIndex = index;
            const currentItem = galleryItems[index];

            // Reset both elements
            modalImg.style.display = "none";
            modalVideo.style.display = "none";

            if (currentItem.tagName.toLowerCase() === "video") {
                // Handle video
                modalVideo.src = currentItem.src;
                modalVideo.style.display = "block";

                // Autoplay the video when shown
                modalVideo.play().catch(function (error) {
                    console.log("Video autoplay failed:", error);
                });
            } else {
                // Handle image
                modalImg.src = currentItem.src;
                modalImg.style.display = "block";

                // If there was a playing video, pause it
                modalVideo.pause();
            }
        }

        // Add click event to all gallery items
        galleryItems.forEach((item, index) => {
            item.addEventListener("click", function () {
                modal.style.display = "block";
                showMedia(index);
            });
        });

        // Previous button click handler
        prevBtn.addEventListener("click", function () {
            currentItemIndex =
                (currentItemIndex - 1 + galleryItems.length) %
                galleryItems.length;
            showMedia(currentItemIndex);
        });

        // Next button click handler
        nextBtn.addEventListener("click", function () {
            currentItemIndex = (currentItemIndex + 1) % galleryItems.length;
            showMedia(currentItemIndex);
        });

        // Close modal when clicking the close button
        closeBtn.addEventListener("click", function () {
            modal.style.display = "none";
            modalVideo.pause(); // Pause video when closing
        });

        // Close modal when clicking outside the content
        modal.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.style.display = "none";
                modalVideo.pause(); // Pause video when closing
            }
        });

        // Handle keyboard navigation
        document.addEventListener("keydown", function (e) {
            if (modal.style.display === "block") {
                switch (e.key) {
                    case "ArrowLeft":
                        prevBtn.click();
                        break;
                    case "ArrowRight":
                        nextBtn.click();
                        break;
                    case "Escape":
                        modal.style.display = "none";
                        modalVideo.pause(); // Pause video when closing
                        break;
                }
            }
        });
    </script>

@endpush