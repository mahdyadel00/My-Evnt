@extends('Frontend.layouts.master')

@section('content')
<div class="filteration-event-main-container">
    <!-- Header Section category and search -->
    <div class="filteration-event-header-wrapper">
            <div class="filteration-event-search-container nebule-event-search-row">
            <div class="nebule-event-search-input-wrap">
                <div class="filteration-event-search-icon">🔍</div>
                    <input type="text" class="filteration-event-search-input" placeholder="ex : Workshop, Music, etc"
                        autocomplete="off" spellcheck="false" />
            </div>
                <button type="button" class="nebule-event-filter-trigger-btn" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" aria-label="Open filters">
                <span class="nebule-event-filter-trigger-icon"><i class="fa-solid fa-filter"></i></span>
                <span class="nebule-event-filter-trigger-text">Filter</span>
            </button>
        </div>

        <div class="filteration-event-categories-container">
            <!-- Category Header -->
            <div class="filteration-event-categories-header">
                <div>
                    <div class="filteration-event-category-title">
                        Search by Category
                    </div>
                </div>
                    <div class="filteration-event-categories-header-actions">
                    <div class="filteration-event-view-toggle">
                            <button class="filteration-event-toggle-btn active" onclick="toggleView('slider')"
                            id="sliderViewBtn">
                            Slider
                        </button>
                            <button class="filteration-event-toggle-btn" onclick="toggleView('grid')" id="gridViewBtn">
                            Grid
                        </button>
                    </div>
                </div>
            </div>
            <!-- Universal Slider Layout -->
                <div class="filteration-event-categories-slider" id="categorySlider">
                <div class="filteration-event-categories-row">
                        {{-- All categories (no filter) --}}
                        <a href="#" class="filteration-event-category-item filteration-event-category-item-ajax selected" data-category-id="all" role="button">
                            <div class="filteration-event-category-icon">
                                <span class="filteration-event-category-icon-all" aria-hidden="true">◉</span>
                            </div>
                            <div class="filteration-event-category-label">All</div>
                        </a>
                        @foreach($categories ?? [] as $category)
                            @php
                                $catSlug = \Illuminate\Support\Str::slug($category->name);
                                $firstMedia = $category->media?->first();
                                $iconPath = $firstMedia && $firstMedia->path
                                    ? asset('storage/' . $firstMedia->path)
                                    : asset('Front/img/category/' . $catSlug . '.svg');
                            @endphp
                            <a href="#" class="filteration-event-category-item filteration-event-category-item-ajax" data-category-id="{{ $category->id }}" role="button">
                        <div class="filteration-event-category-icon">
                                    <img src="{{ $iconPath }}" alt="{{ $category->name }}" />
                        </div>
                        <div class="filteration-event-category-label">
                                    {{ $category->name }}
                        </div>
                    </a>
                        @endforeach
                        </div>
            </div>

            <!-- Slider Navigation Controls -->
            <div class="filteration-event-slider-controls">
                    <button class="filteration-event-slider-btn" id="prevBtn" onclick="slideCategories('prev')"
                    title="Previous Categories">
                    ‹
                </button>
                    <button class="filteration-event-slider-btn" id="nextBtn" onclick="slideCategories('next')"
                    title="Next Categories">
                    ›
                </button>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="filteration-event-results-section">
        <div class="filteration-event-results-container">
            <div class="filteration-event-results-header">
                <div class="filteration-event-results-header-content">
                    <div>
                        <h2 class="filteration-event-results-title">
                            Featured Events
                        </h2>
                        <p class="filteration-event-results-subtitle">
                            Discover amazing opportunities near you
                        </p>
                    </div>
                    <div class="filteration-event-results-controls">
                        <div class="filteration-event-view-toggle">
                                <button class="filteration-event-toggle-btn" onclick="toggleCardsView('slider')"
                                id="cardsSliderViewBtn">
                                Slider
                            </button>

                                <button class="filteration-event-toggle-btn active" onclick="toggleCardsView('grid')"
                                id="cardsGridViewBtn">
                                Grid
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Container with Navigation -->
            <div class="filteration-event-cards-container">
                    <div class="filteration-event-cards-grid" id="cardsContainer">
                        @include('Frontend.events.partials.cards')
                        @if(isset($events) && $events->isEmpty())
                            <div class="filteration-event-empty"
                                style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; grid-column: 1 / -1;">
                                <div class="filteration-event-empty-icon">🔍</div>
                                <p class="filteration-event-empty-text">No events found.</p>
                                <button type="button" class="filteration-event-empty-reset" onclick="window.resetAllFilters ? window.resetAllFilters() : (window.location.href = '{{ route('events') }}')">Clear Filters</button>
                                </div>
                        @endif
                </div>

                <!-- Cards Navigation Controls (for slider mode) - Below the cards -->
                    <div class="filteration-event-cards-controls" id="cardsControls" style="display: none">
                        <button class="filteration-event-slider-btn" id="prevCardsBtn" onclick="slideCards('prev')"
                        title="Previous Cards">
                        ‹
                    </button>
                        <button class="filteration-event-slider-btn" id="nextCardsBtn" onclick="slideCards('next')"
                        title="Next Cards">
                        ›
                    </button>
                </div>

                <!-- Pagination -->
                <div class="filteration-event-pagination">
                        <button class="filteration-event-pagination-btn disabled" onclick="changePage('prev')"
                        id="prevPageBtn">
                        ‹
                    </button>

                        <button class="filteration-event-pagination-btn active" onclick="changePage(1)" data-page="1">
                        1
                    </button>

                        <button class="filteration-event-pagination-btn" onclick="changePage(2)" data-page="2">
                        2
                    </button>

                        <button class="filteration-event-pagination-btn" onclick="changePage(3)" data-page="3">
                        3
                    </button>

                    <span class="filteration-event-pagination-dots">...</span>

                        <button class="filteration-event-pagination-btn" onclick="changePage(8)" data-page="8">
                        8
                    </button>

                        <button class="filteration-event-pagination-btn" onclick="changePage('next')" id="nextPageBtn">
                        ›
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Offcanvas Filters (nebule-event) – Search + filters -->
    <div class="offcanvas offcanvas-start nebule-event-offcanvas" data-bs-scroll="true" data-bs-backdrop="false"
        tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="nebule-event-offcanvas-inner">
        <div class="nebule-event-offcanvas-header">
                <h5 class="nebule-event-offcanvas-title" id="offcanvasScrollingLabel">Filter</h5>
                <button type="button" class="nebule-event-offcanvas-close btn-close" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <form id="nebule-event-filter-form" class="nebule-event-offcanvas-body" novalidate>
            <div class="nebule-event-offcanvas-search-wrap">
                    <label class="nebule-event-offcanvas-label" for="nebule-event-offcanvas-search">Search</label>
                <input type="text" id="nebule-event-offcanvas-search" class="nebule-event-offcanvas-search-input"
                    placeholder="ex : Workshop, Music, etc" autocomplete="off" />
            </div>
            <div class="nebule-event-accordion" id="nebuFilterAccordion">
                <div class="nebule-event-accordion-item">
                    <h6 class="nebule-event-accordion-header">
                        <button class="nebule-event-accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                            Category<i class="fa fa-chevron-right nebule-event-accordion-arrow" aria-hidden="true"></i>
                        </button>
                    </h6>
                    <div id="collapseCategory" class="nebule-event-accordion-collapse collapse show">
                        <div class="nebule-event-accordion-body">
                                @foreach($categories ?? [] as $cat)
                                    <label class="nebule-event-filter-check">
                                        <input type="checkbox" value="{{ \Illuminate\Support\Str::slug($cat->name) }}"
                                            data-filter="category">
                                        {{ $cat->name }}
                                    </label>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div class="nebule-event-accordion-item">
                    <h6 class="nebule-event-accordion-header">
                        <button class="nebule-event-accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCity" aria-expanded="false" aria-controls="collapseCity">
                            City<i class="fa fa-chevron-right nebule-event-accordion-arrow" aria-hidden="true"></i>
                        </button>
                    </h6>
                    <div id="collapseCity" class="nebule-event-accordion-collapse collapse">
                        <div class="nebule-event-accordion-body">
                                @foreach($cities ?? [] as $city)
                                    <label class="nebule-event-filter-check">
                                        <input type="checkbox" value="{{ \Illuminate\Support\Str::slug($city->name) }}"
                                            data-filter="city">
                                        {{ $city->name }}
                                    </label>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div class="nebule-event-accordion-item">
                    <h6 class="nebule-event-accordion-header">
                        <button class="nebule-event-accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseDate" aria-expanded="false" aria-controls="collapseDate">
                            Date<i class="fa fa-chevron-right nebule-event-accordion-arrow" aria-hidden="true"></i>
                        </button>
                    </h6>
                    <div id="collapseDate" class="nebule-event-accordion-collapse collapse">
                        <div class="nebule-event-accordion-body">
                                <input type="text" id="nebule-event-offcanvas-date"
                                    class="nebule-event-offcanvas-date-input" placeholder="Select date"
                                    autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="nebule-event-accordion-item">
                    <h6 class="nebule-event-accordion-header">
                        <button class="nebule-event-accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapsePrice" aria-expanded="false" aria-controls="collapsePrice">
                            Price<i class="fa fa-chevron-right nebule-event-accordion-arrow" aria-hidden="true"></i>
                        </button>
                    </h6>
                    <div id="collapsePrice" class="nebule-event-accordion-collapse collapse">
                        <div class="nebule-event-accordion-body">
                                <label class="nebule-event-filter-check"><input type="checkbox" value="free"
                                        data-filter="price"> Free</label>
                                <label class="nebule-event-filter-check"><input type="checkbox" value="paid"
                                        data-filter="price"> Paid</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nebule-event-offcanvas-actions">
                <button type="button" class="nebule-event-offcanvas-apply-btn" aria-label="Apply">Apply</button>
                <button type="button" class="nebule-event-offcanvas-clear-btn" aria-label="Clear">Clear</button>
            </div>
        </form>
    </div>
</div>

    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/filteration.css') }}" />
    @endpush

    @push('js')
<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('Front/js/filter.js') }}"></script>
<script>
            document.addEventListener("DOMContentLoaded", function () {
        var dateEl = document.getElementById("nebule-event-offcanvas-date");
        if (dateEl && typeof flatpickr !== "undefined") {
                    flatpickr("#nebule-event-offcanvas-date", { dateFormat: "Y-m-d", allowInput: true });
        }
        var form = document.getElementById("nebule-event-filter-form");
        if (form) {
                    form.addEventListener("submit", function (e) {
                e.preventDefault();
                var applyBtn = form.querySelector(".nebule-event-offcanvas-apply-btn");
                if (applyBtn) applyBtn.click();
            });
        }
    });
</script>
    @endpush
@endsection