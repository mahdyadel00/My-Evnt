@php
    $slides = $organizer_feature_slides ?? collect();
    $total = $slides->count();
    $defaultHero = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=550&h=400&fit=crop';
@endphp
<!-- ═══════════════════════════════════════════
            ARE YOU ORGANIZER — SLIDER SECTION (dynamic)
        ═══════════════════════════════════════════ -->
@if ($total > 0)
    <section class="section-you-owner--feature" aria-label="Organizer features">
        <div class="owner-container--feature">
            <div class="owner-slider-wrapper--feature">
                <div class="owner-slides-track--feature" role="region" aria-roledescription="carousel"
                    aria-label="Organizer feature slides">
                    <div class="owner-slides-inner--feature" id="slidesInner">
                        @foreach ($slides as $slide)
                            <div class="owner-slide--feature {{ $loop->first ? 'is-active--feature' : '' }}" role="group"
                                aria-roledescription="slide" aria-label="Slide {{ $loop->iteration }} of {{ $total }}">
                                <div class="owner-bg-shapes--feature" aria-hidden="true">
                                    <div class="shape--feature shape-1--feature"></div>
                                    <div class="shape--feature shape-2--feature"></div>
                                    <div class="shape--feature shape-3--feature"></div>
                                </div>

                                <div class="owner-visual-content--feature">
                                    <div class="owner-image-container--feature">
                                        <div class="owner-mockup--feature">
                                            @php
                                                $heroSrc = $slide->heroImageSrc();
                                                if ($heroSrc === '') {
                                                    $heroSrc = $defaultHero;
                                                }
                                            @endphp
                                            <img src="{{ $heroSrc }}" alt="{{ e($slide->title) }}"
                                                class="owner-image--feature" width="550" height="400" loading="lazy" />
                                        </div>
                                    </div>
                                </div>

                                <div class="owner-text-content--feature">
                                    <h2 class="owner-title--feature">{{ $slide->title }}</h2>
                                    @if (filled($slide->subtitle))
                                        <p class="owner-subtitle--feature">{{ $slide->subtitle }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="slider-controls--feature" role="tablist" aria-label="Slide indicators">
                    @foreach ($slides as $idx => $slide)
                        <button class="dot--feature {{ $idx === 0 ? 'active--feature' : '' }}" role="tab"
                            aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $idx + 1 }}" data-index="{{ $idx }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @push('js')
        <script>
            (function() {
                "use strict";

                const DRAG_THRESHOLD = 60;
                const track = document.getElementById("slidesInner");
                if (!track) return;

                const slides = Array.from(
                    track.querySelectorAll(".owner-slide--feature"),
                );
                const dots = Array.from(
                    document.querySelectorAll(".dot--feature"),
                );

                if (slides.length === 0) return;

                let current = 0;
                let startX = 0;
                let isDragging = false;

                function goTo(index) {
                    const prev = current;
                    current =
                        ((index % slides.length) + slides.length) %
                        slides.length;

                    track.style.transform = `translateX(-${current * 100}%)`;

                    slides[prev].classList.remove("is-active--feature");
                    slides[current].classList.add("is-active--feature");

                    if (dots.length === slides.length) {
                        dots[prev].classList.remove("active--feature");
                        dots[prev].setAttribute("aria-selected", "false");
                        dots[current].classList.add("active--feature");
                        dots[current].setAttribute("aria-selected", "true");
                    }

                    const img = slides[current].querySelector(
                        ".owner-image--feature",
                    );
                    if (img) {
                        img.style.animation = "none";
                        img.offsetHeight;
                        img.style.animation = "";
                    }
                }

                dots.forEach((dot) =>
                    dot.addEventListener("click", () =>
                        goTo(+dot.dataset.index),
                    ),
                );

                document.addEventListener("keydown", (e) => {
                    if (e.key === "ArrowRight") goTo(current + 1);
                    if (e.key === "ArrowLeft") goTo(current - 1);
                });

                track.addEventListener(
                    "touchstart",
                    (e) => {
                        startX = e.touches[0].clientX;
                    }, {
                        passive: true
                    },
                );
                track.addEventListener("touchend", (e) => {
                    const diff = startX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > DRAG_THRESHOLD)
                        goTo(diff > 0 ? current + 1 : current - 1);
                });

                track.addEventListener("mousedown", (e) => {
                    isDragging = true;
                    startX = e.clientX;
                    track.style.cursor = "grabbing";
                });
                track.addEventListener("mouseup", (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    track.style.cursor = "";
                    const diff = startX - e.clientX;
                    if (Math.abs(diff) > DRAG_THRESHOLD)
                        goTo(diff > 0 ? current + 1 : current - 1);
                });
                track.addEventListener("mouseleave", () => {
                    isDragging = false;
                    track.style.cursor = "";
                });

                goTo(0);
            })();
        </script>
    @endpush
@endif
