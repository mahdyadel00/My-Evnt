@extends('Frontend.layouts.master')
@section('content')
    <!-- Google Sign-In Button -->
    <div class="google-signin-container" style="text-align: center;">
        <div id="g_id_onload" data-client_id="{{ config('services.google.client_id') }}"
            data-callback="handleCredentialResponse" data-auto_select="true" data-itp_support="true">
        </div>
        {{-- <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline"
            data-text="signin_with" data-size="large" data-logo_alignment="left">
        </div> --}}
    </div>
    <!-- start slider section -->
    @if($sliders->count() > 0)
        @include('Frontend.layouts.sections.sliders')
    @endif

    @if($home_popup_features->count() > 0)
        @include('Frontend.layouts.sections.home_popup_features')
    @endif
    <!-- end slider section -->
    <!-- Start New Category Section -->
    @if($event_category->count() > 0)
        <div class="js-hide-on-search">
            @include('Frontend.layouts.sections.categories')
        </div>
    @endif
    <!-- end New Category Section -->
    <!-- plan of month -->
    @if($plan_month->count() > 0)
        <div class="js-hide-on-search">
            @include('Frontend.layouts.sections.plan_of_month')
        </div>
    @endif
    <!-- plan of month -->
    <!-- Exclusive Events Section -->
    @if($exclusive_events->count() > 0)
        <div class="js-hide-on-search">
            @include('Frontend.layouts.sections.exclusive_events')
        </div>
    @endif
    <!-- end Exclusive Events Section -->
    <!-- start Events section -->
    @if($top_events->count() > 0)
        <div class="js-hide-on-search">
            @include('Frontend.layouts.sections.top_events')
        </div>
    @endif
    <!-- end Events section -->
    @include('Frontend.layouts.sections.subscribe')
    <!-- start New Event section -->
    @if($new_events->count() > 0)
        @include('Frontend.layouts.sections.new_events')
    @endif

    <!-- end New Event section -->
    @if($upcoming_events->count() > 0)
        @include('Frontend.layouts.sections.upcoming_events')
    @endif
    <!-- end Upcoming Event section -->
    <!-- start New Event section -->
    {{--@if($past_events->count() > 0)
    @include('Frontend.layouts.sections.past_events')
    @endif--}}

    <!-- end New Event section -->

    <!-- Are you owner -->
    @if (isset($organizer_feature_slides) && $organizer_feature_slides->isNotEmpty())
        @include('Frontend.layouts.sections.are_you_organize')
    @endif
    <!-- Are you owner -->

    <!-- trusted companies -->
    @if($partners->count() > 0)
        <div class="js-hide-on-search">
            @include('Frontend.layouts.sections.partners')
        </div>
    @endif
    <!-- trusted companies -->

    <!-- start elementor-section -->
    @include('Frontend.layouts.sections.elementor')
    <!-- end elementor-section -->
    @include('Frontend.layouts.search')
    <div class="js-hide-on-search">
        <!-- Other sections that should be hidden during search -->
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="{{ asset('Front/js/event-filter.js') }}"></script> -->
    <script>
        // Google Sign-In callback function
        function handleCredentialResponse(response) {
            console.log("Encoded JWT ID token: " + response.credential);

            // Send the credential to your backend
            $.ajax({
                url: '{{ route("google.callback.post") }}',
                type: 'POST',
                data: {
                    credential: response.credential,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'تم تسجيل الدخول بنجاح!',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'حدث خطأ أثناء تسجيل الدخول',
                        });
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'حدث خطأ أثناء تسجيل الدخول. يرجى المحاولة مرة أخرى.',
                    });
                }
            });
        }

        // Initialize Google Sign-In when the page loads
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "{{ config('services.google.client_id') }}",
                callback: handleCredentialResponse
            });
        };
    </script>
    <script>
        // swiper events
        document.addEventListener("DOMContentLoaded", function () {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 2
                    },
                    992: {
                        slidesPerView: 4
                    },
                },
            });
        });
        // ==================== Event Favorite Handler ====================
        // Note: Favorite functionality is now handled by event-favorite.js (included in master.blade.php)
        // The script automatically handles all elements with class "heart-icon" and data-event-id attribute
        // No need for custom code here!


        $('#subscribe-form').submit(function (e) {
            e.preventDefault();
            let email = $('input[name="email"]').val();
            $.ajax({
                url: '{{ route('subscribe') }}',
                type: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    console.log(data.message);
                    if (data.message == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Subscribed Successfully',
                        });
                        $('#subscribe-form').trigger('reset');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                }
            });
        });
        //i need title take one line and when hover show all title by id = title
        $(document).ready(function () {
            $(".title").tooltip();
        });


        // ============== Exclusive / Plan of Month sliders (independent per section) ==============
        document.querySelectorAll('.js-exclusive-slider').forEach(function (sliderRoot) {
            const cards = sliderRoot.querySelectorAll('.event-card-exclusive');
            const dots = sliderRoot.querySelectorAll('.slider-dot-exclusive');

            if (cards.length === 0) {
                return;
            }

            let current = 0;
            let isAnimating = false;

            function showCard(idx) {
                if (isAnimating || idx < 0 || idx >= cards.length) {
                    return;
                }

                isAnimating = true;

                cards.forEach(function (card, i) {
                    if (i !== idx && card.style.display === 'flex') {
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(-30px)';
                        setTimeout(function () {
                            card.style.display = 'none';
                        }, 400);
                    }
                });

                setTimeout(function () {
                    const targetCard = cards[idx];
                    targetCard.style.display = 'flex';
                    targetCard.style.opacity = '0';
                    targetCard.style.transform = 'translateX(30px)';

                    setTimeout(function () {
                        targetCard.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                        targetCard.style.opacity = '1';
                        targetCard.style.transform = 'translateX(0)';
                        isAnimating = false;
                    }, 50);
                }, 400);

                dots.forEach(function (dot, i) {
                    dot.classList.toggle('active', i === idx);
                });
            }

            dots.forEach(function (dot, index) {
                dot.addEventListener('click', function () {
                    if (current !== index) {
                        current = index;
                        showCard(current);
                    }
                });
            });

            cards.forEach(function (card, i) {
                card.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                if (i === 0) {
                    card.style.display = 'flex';
                    card.style.opacity = '1';
                    card.style.transform = 'translateX(0)';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // <!-- counter section -->

        // Counter Animation Function
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            const isDecimal = target.toString().includes(".");

            function updateCounter() {
                start += increment;
                if (start >= target) {
                    element.textContent = isDecimal
                        ? target.toFixed(1)
                        : Math.floor(target);
                } else {
                    element.textContent = isDecimal
                        ? start.toFixed(1)
                        : Math.floor(start);
                    requestAnimationFrame(updateCounter);
                }
            }

            updateCounter();
        }

        // Intersection Observer for triggering animations
        const observerOptions = {
            threshold: 0.3,
            rootMargin: "0px 0px -50px 0px",
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const counterItem = entry.target;
                    const numberElement =
                        counterItem.querySelector(".counter-number");
                    const target = parseFloat(
                        numberElement.getAttribute("data-target")
                    );

                    // Add animation class
                    counterItem.classList.add("animated");

                    // Start counter animation
                    animateCounter(numberElement, target);

                    // Unobserve after animation starts
                    observer.unobserve(counterItem);
                }
            });
        }, observerOptions);

        // Observe all counter items
        document.addEventListener("DOMContentLoaded", function () {
            const counterItems = document.querySelectorAll(".counter-item");
            counterItems.forEach((item) => {
                observer.observe(item);
            });
        });
    </script>

    <!-- popup overlay backdrop action  -->
    <script>
        (function () {
            var backdrop = document.getElementById("jsPopupBackdrop");
            if (!backdrop) {
                return;
            }
            var openBtn = document.getElementById("jsOpenModal");
            var closeBtn = document.getElementById("jsCloseBanner");
            var dismissBtn = document.getElementById("jsDismissBtn");
            var confirmBtn = document.getElementById("jsConfirmBtn");

            function openPopup() {
                backdrop.classList.add("is-open");
                document.body.style.overflow = "hidden";
            }

            function closePopup() {
                backdrop.classList.remove("is-open");
                document.body.style.overflow = "";
            }

            /* Auto-open on page load */
            window.addEventListener("load", function () {
                setTimeout(openPopup, 300);
            });

            if (openBtn) {
                openBtn.addEventListener("click", openPopup);
            }
            if (closeBtn) {
                closeBtn.addEventListener("click", closePopup);
            }
            if (dismissBtn) {
                dismissBtn.addEventListener("click", closePopup);
            }
            if (confirmBtn) {
                confirmBtn.addEventListener("click", closePopup);
            }

            /* Click outside modal */
            backdrop.addEventListener("click", function (e) {
                if (e.target === backdrop) closePopup();
            });

            /* Escape key */
            document.addEventListener("keydown", function (e) {
                if (e.key === "Escape") closePopup();
            });
        })();
    </script>

    {{-- Slider (carousel-neblue-event) JS --}}
    <script>
        (function () {
            var NEBLUE_CONFIG = { swipeThreshold: 50 };

            var neblueCurrentSlide = 0;
            var neblueTouchStartX = null;
            var neblueTouchEndX = null;

            var neblueCarousel = document.querySelector('.carousel-neblue-event-carousel');
            var neblueItems = document.querySelectorAll('.carousel-neblue-event-item');
            var nebluePrevBtn = document.querySelector('.carousel-neblue-event-arrow-prev');
            var neblueNextBtn = document.querySelector('.carousel-neblue-event-arrow-next');

            if (!neblueItems.length) return;

            function neblueShowSlide(index) {
                if (index >= neblueItems.length) index = 0;
                else if (index < 0) index = neblueItems.length - 1;

                neblueCurrentSlide = index;
                neblueItems.forEach(function (item) {
                    item.classList.remove('carousel-neblue-event-active');
                });
                neblueItems[index].classList.add('carousel-neblue-event-active');
            }

            if (nebluePrevBtn) {
                nebluePrevBtn.addEventListener('click', function () {
                    neblueShowSlide(neblueCurrentSlide - 1);
                });
            }
            if (neblueNextBtn) {
                neblueNextBtn.addEventListener('click', function () {
                    neblueShowSlide(neblueCurrentSlide + 1);
                });
            }

            if (neblueCarousel) {
                neblueCarousel.addEventListener('touchstart', function (e) {
                    neblueTouchStartX = e.touches[0].clientX;
                }, { passive: true });
                neblueCarousel.addEventListener('touchmove', function (e) {
                    neblueTouchEndX = e.touches[0].clientX;
                }, { passive: true });
                neblueCarousel.addEventListener('touchend', function () {
                    if (!neblueTouchStartX || !neblueTouchEndX) return;
                    var dist = neblueTouchStartX - neblueTouchEndX;
                    if (dist > NEBLUE_CONFIG.swipeThreshold) neblueShowSlide(neblueCurrentSlide + 1);
                    else if (dist < -NEBLUE_CONFIG.swipeThreshold) neblueShowSlide(neblueCurrentSlide - 1);
                    neblueTouchStartX = neblueTouchEndX = null;
                });
            }

            neblueShowSlide(0);
        })();
    </script>
@endpush