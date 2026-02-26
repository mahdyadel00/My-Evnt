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
    @include('Frontend.layouts.sections.are_you_organize')
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


        // ============== Exclusive Events Section ==============  //
        // سلايدر بسيط للكروت الثابتة مع dots وتأثيرات انتقال جميلة
        const cards = document.querySelectorAll(".event-card-exclusive");
        const dots = document.querySelectorAll(".slider-dot-exclusive");
        let current = 0;
        let isAnimating = false;

        function showCard(idx) {
            if (isAnimating) return;
            isAnimating = true;

            // إخفاء الكارت الحالي مع تأثير fade out
            cards.forEach((card, i) => {
                if (i !== idx && card.style.display === "flex") {
                    card.style.opacity = "0";
                    card.style.transform = "translateX(-30px)";
                    setTimeout(() => {
                        card.style.display = "none";
                    }, 400);
                }
            });

            // إظهار الكارت الجديد مع تأثير fade in
            setTimeout(() => {
                const targetCard = cards[idx];
                targetCard.style.display = "flex";
                targetCard.style.opacity = "0";
                targetCard.style.transform = "translateX(30px)";

                setTimeout(() => {
                    targetCard.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                    targetCard.style.opacity = "1";
                    targetCard.style.transform = "translateX(0)";
                    isAnimating = false;
                }, 50);
            }, 400);

            // تحديث النقاط النشطة مع تأثير
            dots.forEach((dot, i) => {
                dot.classList.remove("active");
                if (i === idx) {
                    setTimeout(() => {
                        dot.classList.add("active");
                    }, 200);
                }
            });
        }

        // إضافة حدث النقر على النقاط
        dots.forEach((dot, index) => {
            dot.onclick = function () {
                if (current !== index) {
                    current = index;
                    showCard(current);
                }
            };

            // تأثير ripple عند الضغط
            dot.addEventListener('mousedown', function (e) {
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.width = '20px';
                ripple.style.height = '20px';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(237, 115, 38, 0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'rippleEffect 0.6s ease-out';
                ripple.style.pointerEvents = 'none';
                this.style.position = 'relative';
                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Auto-play slider (اختياري)
        let autoPlayInterval;
        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                current = (current + 1) % cards.length;
                showCard(current);
            }, 5000); // كل 5 ثواني
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // إيقاف auto-play عند التفاعل مع النقاط
        dots.forEach(dot => {
            dot.addEventListener('click', stopAutoPlay);
        });

        // إظهار أول كارت عند التحميل
        cards.forEach((card, i) => {
            card.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
            if (i === 0) {
                card.style.display = "flex";
                card.style.opacity = "1";
                card.style.transform = "translateX(0)";
            } else {
                card.style.display = "none";
            }
        });

        // تشغيل auto-play
        // startAutoPlay(); // قم بإزالة التعليق لتفعيل التشغيل التلقائي

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

    {{-- Slider (carousel-neblue-event) JS --}}
    <script>
        (function () {
            var NEBLUE_CONFIG = { swipeThreshold: 50 };

            var neblueCurrentSlide = 0;
            var neblueTouchStartX = null;
            var neblueTouchEndX  = null;

            var neblueCarousel   = document.querySelector('.carousel-neblue-event-carousel');
            var neblueItems      = document.querySelectorAll('.carousel-neblue-event-item');
            var nebluePrevBtn    = document.querySelector('.carousel-neblue-event-arrow-prev');
            var neblueNextBtn    = document.querySelector('.carousel-neblue-event-arrow-next');

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
