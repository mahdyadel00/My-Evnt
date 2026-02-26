<!-- header start -->
<header class="event-header">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="event-container">
        <!-- top navigation -->
        <nav class="event-top-nav" role="navigation" aria-label="Top navigation">
            <a href="{{ route('home') }}" class="event-logo" aria-label="AllEvents homepage">
                @foreach($setting->media as $media)
                    @if($media->name == 'header_logo')
                        <img src="{{ asset('storage/' . $media->path) }}" alt="logo" class="event-logo-icon">
                    @endif
                @endforeach
            </a>
            <!-- search container -->
            <div class="event-search-container {{ request()->is('event/*', 'events', 'events/new', 'events/top', 'events/upcoming', 'events/plan-of-month', 'events/category/*', 'checkout/user/*', 'checkout/survey/*', 'checkout/survey/hr/*', 'login/*', 'register/*')
    ? 'eventSearchContainer-header' : '' }}">
                <div class="wrapper">
                    <!-- search box -->
                    <div class="search_box">
                        <!-- location field -->
                        <div class="location_field">
                            <div class="default_option" data-city-id="">
                                <i class="fas fa-map-marker-alt"></i>
                                Location
                            </div>
                            <ul>
                                @foreach($cities as $city)
                                    <li data-city-id="{{ $city->id }}">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <a href="#" data-city-id="{{ $city->id }}">{{ $city->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- date field -->
                        <div class="date_field">
                            <!-- <input type="date" class="date_input" id="headerDateFilter" /> -->
                            <input type="text" id="headerDateFilter" class="date_input" placeholder="Select Date" />
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <!-- search field -->
                        <div class="search_field">
                            <input type="text" class="input" placeholder="Search events..." id="eventSearchInput" />
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- user actions -->
            <div class="event-user-actions">
                <!-- create event button -->
                <div class="event-dropdown">
                    <a href="{{ route('organization') }}" class="event-create-btn" aria-label="Create event options"
                        aria-expanded="false">
                        <i class="fas fa-plus"></i>
                        Create Event
                    </a>
                </div>
                <!-- login button -->
                @if(!auth()->check())
                    <div class="event-dropdown">
                        <a href="{{ route('login') }}" class="event-create-btn" aria-label="Login" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            Login
                        </a>
                    </div>
                @endif
                <!-- user profile -->
                @if(auth()->check())
                    <div class="event-user-profile" id="eventUserProfile">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <div class="event-user-avatar" id="eventUserAvatar">
                                @if(auth()->user()->first_name)
                                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                                @else
                                    {{ strtoupper(substr(auth()->user()->user_name, 0, 1)) }}
                                @endif
                            </div>
                            <span class="event-user-name" id="eventUserName">
                                @if(auth()->user()->first_name)
                                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                @else
                                    {{ auth()->user()->user_name }}
                                @endif
                            </span>
                            <i class="fas fa-chevron-down" aria-label="User menu" aria-expanded="false"></i>
                        </div>
                        <div class="event-user-dropdown" role="menu">
                            <div class="event-profile-section">
                                <h4>Main Profile</h4>
                                <div class="event-profile-info">
                                    <div class="event-profile-avatar" id="eventProfileAvatar">
                                        @if(auth()->user()->first_name)
                                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                                        @else
                                            {{ strtoupper(substr(auth()->user()->user_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="event-profile-details">
                                        <h5 id="eventProfileName">
                                            @if(auth()->user()->first_name)
                                                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                            @else
                                                {{ auth()->user()->user_name }}
                                            @endif
                                        </h5>
                                        <span id="eventProfileEvents">{{ auth()->user()->events->count() }} events
                                            attended</span>
                                        <i class="fas fa-chevron-right" style="float: right; margin-top: 2px;"></i>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('my_tickets') }}" class="event-dropdown-item" role="menuitem"><i
                                    class="fas fa-history"></i> Booking
                                history</a>
                            <!-- <a href="{{ route('profile') }}" class="event-dropdown-item" data-action="viewInbox" role="menuitem"><i
                                                                                class="fas fa-inbox"></i> Inbox</a> -->
                            <a href="{{ route('my_wishlist') }}" class="event-dropdown-item" role="menuitem"><i
                                    class="fas fa-heart"></i> Interested
                                events</a>
                            <div class="event-app-promo" data-action="getEventApp" role="button">
                                <div>
                                    <a href="https://play.google.com/store/apps/details?id=com.abdallah.evnt&pli=1">
                                        <i class="fas fa-mobile-alt"></i> Get App
                                    </a>
                                </div>
                                <span class="event-new-badge">New</span>
                            </div>
                            <a href="{{ route('contacts') }}" class="event-dropdown-item" role="menuitem"><i
                                    class="fas fa-question-circle"></i> Need
                                help?</a>
                            <a href="{{ route('profile') }}" class="event-dropdown-item" role="menuitem"><i
                                    class="fas fa-cog"></i> Account
                                settings</a>
                            <div style=" border-top: 1px solid rgba(0, 0, 0, 0.1); margin: 5px 0;"></div>
                            <a href="{{ route('logout') }}" class="event-dropdown-item" style="color: #e53e3e"
                                role="menuitem">
                                <i class="fas fa-sign-out-alt"></i> Log out
                            </a>
                        </div>
                    </div>
                @endif
                <button class="event-mobile-menu-btn" data-action="toggleEventMobileMenu"
                    aria-label="Toggle mobile menu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>

        <!-- main navigation (hidden on auth pages) -->
        @unless(request()->is('login', 'register', 'forgot-password', 'reset-password/*', 'confirmation-email/*'))
            <nav class="event-main-nav" id="eventMainNav" role="navigation" aria-label="Main navigation">
                <!-- all category -->
                <ul class="event-nav-list">
                    <!-- item category -->
                    <li class="event-nav-item">
                        <a href="#" class="event-nav-link event-active" data-category="all" role="menuitem">ALL</a>
                    </li>
                    @foreach($event_category as $category)
                        <li class="event-nav-item event-dropdown">
                            <a href="#" class="event-nav-link" data-category="{{ $category->id }}" role="menuitem"
                                aria-expanded="false">
                                {{ $category->name }}
                                <i class="fas fa-chevron-down event-dropdown-arrow"></i>
                            </a>
                            <div class="event-dropdown-menu" role="menu">
                                @foreach($category->child as $child)
                                    <a href="{{ route('events_category', $child->id) }}" class="event-dropdown-item"
                                        role="menuitem">
                                        <!-- <i class="fas fa-music"></i> -->
                                        <!-- @foreach($child->media as $media)
                                                                                                    @if($media->path)
                                                                                                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $child->name }}"
                                                                                                            class="category-image-event" />
                                                                                                    @endif
                                                                                                @endforeach -->
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                    <li class="event-nav-item event-create-btn-mobile">
                        <a href="{{ route('login') }}" class="event-nav-link">
                            <i class="fas fa-sign-in-alt pe-2"></i> Sign in
                        </a>
                    </li>
                </ul>
            </nav>
        @endunless
    </div>
</header>
<!-- end header -->
@push('css')
    <style>
        /* Mobile Menu Fix */
        .event-mobile-open {
            display: block !important;
        }

        @media (max-width: 768px) {
            .event-main-nav {
                display: none;
            }

            .event-main-nav.event-mobile-open {
                display: block;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        // Detect user location using IP and update location field
        if (!sessionStorage.getItem('location_detected')) {
            fetch('/api/locations/detect')
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data) {
                        const locationField = document.querySelector('.location_field .default_option');
                        if (locationField) {
                            const cityName = data.data.city?.name || data.data.city_name || 'Location';
                            const cityId = data.data.city?.id || '';

                            locationField.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${cityName}`;
                            if (cityId) {
                                locationField.setAttribute('data-city-id', cityId);
                            }

                            sessionStorage.setItem('location_detected', 'true');
                        }
                    }
                })
                .catch(error => {
                    console.log('Location detection skipped or failed:', error);
                });
        }
    </script>
    <script>
        // Carousel functionality - only initialize if elements exist
        document.addEventListener("DOMContentLoaded", function () {
            const nextDom = document.getElementById("next");
            const prevDom = document.getElementById("prev");
            const carouselDom = document.querySelector(".carousel");

            // Only initialize carousel if elements exist
            if (nextDom && prevDom && carouselDom) {
                const SliderDom = carouselDom.querySelector(".carousel .list");
                const thumbnailBorderDom = document.querySelector(".carousel .thumbnail");
                const thumbnailItemsDom = thumbnailBorderDom?.querySelectorAll(".item");
                const timeDom = document.querySelector(".carousel .time");
                const parallaxContainer = document.querySelector(".parallax-container");
                const parallaxImage = document.getElementById("parallax-image");

                if (thumbnailBorderDom && thumbnailItemsDom && thumbnailItemsDom.length > 0) {
                    thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
                }

                let timeRunning = 1000;
                let timeAutoNext = 20000;

                // Step 2: Add event listeners for next and prev buttons
                nextDom.onclick = function () {
                    showSlider("next");
                };

                prevDom.onclick = function () {
                    showSlider("prev");
                };

                // Step 3: Add event listeners for thumbnail items
                if (thumbnailItemsDom) {
                    thumbnailItemsDom.forEach((thumbnail, index) => {
                        thumbnail.addEventListener("click", () => {
                            showSliderByIndex(index);
                            if (showParallaxImage) {
                                showParallaxImage(index); // Display the clicked image in parallax
                            }
                        });
                    });
                }

                let runTimeOut;
                let runNextAuto = setTimeout(() => {
                    if (nextDom) nextDom.click();
                }, timeAutoNext);

                // Step 4: Function to show slider based on type (next/prev)
                function showSlider(type) {
                    if (!SliderDom || !thumbnailBorderDom) return;

                    let SliderItemsDom = SliderDom.querySelectorAll(".carousel .list .item");
                    let thumbnailItemsDom = document.querySelectorAll(".carousel .thumbnail .item");

                    if (type === "next") {
                        SliderDom.appendChild(SliderItemsDom[0]);
                        thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
                        carouselDom.classList.add("next");
                    } else {
                        SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
                        thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
                        carouselDom.classList.add("prev");
                    }

                    clearTimeout(runTimeOut);
                    runTimeOut = setTimeout(() => {
                        carouselDom.classList.remove("next");
                        carouselDom.classList.remove("prev");
                    }, timeRunning);

                    clearTimeout(runNextAuto);
                    runNextAuto = setTimeout(() => {
                        if (nextDom) nextDom.click();
                    }, timeAutoNext);
                }
            }
        });

        // swiper events
        document.addEventListener("DOMContentLoaded", function () {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1, // Default for small screens
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    576: { slidesPerView: 2 }, // 2 slides for screens ≥ 576px
                    768: { slidesPerView: 2 }, // 3 slides for screens ≥ 768px
                    992: { slidesPerView: 4 }, // 4 slides for screens ≥ 992px
                },
            });
        });
    </script>
    <!-- end swiper -->
    <!-- start event system -->
    <script>
        // =====================================================
        // EVENT SYSTEM - MOBILE OPTIMIZED
        // =====================================================

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const nav = document.getElementById("eventMainNav");
            const btn = document.querySelector(".event-mobile-menu-btn");

            if (nav && btn) {
                nav.classList.toggle("event-mobile-open");
                const isOpen = nav.classList.contains("event-mobile-open");
                btn.innerHTML = isOpen
                    ? '<i class="fas fa-times"></i>'
                    : '<i class="fas fa-bars"></i>';
                btn.setAttribute("aria-expanded", isOpen);
            }
        }


        // Text Search Function with debounce
        let searchTimeout;
        function performSearch() {
            const searchInput = document.querySelector(".search_field .input");
            if (!searchInput) return;

            const query = searchInput.value.trim();
            const locationElement = document.querySelector(".location_field .default_option");
            const cityId = locationElement?.getAttribute('data-city-id') || '';
            const dateInput = document.querySelector("#headerDateFilter");
            const date = dateInput?.value || null;

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Need at least: 2 chars text OR date OR explicit city selection
            if (query.length < 2 && !date && cityId === '') {
                return;
            }

            // If user clears the search and no other filter, clear results
            if (query.length === 0 && !date && cityId === '') {
                clearFilters();
                return;
            }

            // Debounce search - wait 500ms after user stops typing
            searchTimeout = setTimeout(() => {
                const filters = {};
                if (cityId !== '') {
                    filters.city_id = [cityId];
                }

                performAjaxSearch(query.length >= 2 ? query : null, null, date || null, filters);
            }, 500);
        }

        // Date Filter Function
        function performDateFilter(date = null) {
            // If no date passed, try to get it from the input
            if (!date) {
                date = document.querySelector("#headerDateFilter")?.value || null;
            }

            // Get city_id from location field
            const locationElement = document.querySelector(".location_field .default_option");
            const cityId = locationElement?.getAttribute('data-city-id');

            // If date is empty and no city selected, clear all filters and show sections
            if ((!date || date.trim() === '') && (!cityId || cityId === '')) {
                clearFilters();
                return;
            }

            // Validate date format (YYYY-MM-DD) if date is provided
            if (date && date.trim() !== '') {
                const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
                if (!dateRegex.test(date)) {
                    return;
                }
            }

            // Prepare filters with city_id if available
            const filters = {};
            if (cityId && cityId !== '') {
                filters.city_id = [cityId];
            }

            // Perform AJAX search for date (may be null if cleared but city still selected)
            performAjaxSearch(null, null, date && date.trim() !== '' ? date : null, filters);
        }

        // Unified AJAX Search Function
        let isSearching = false;
        function performAjaxSearch(query, location, date, additionalFilters = {}) {
            // Prevent multiple simultaneous requests
            if (isSearching) {
                return;
            }

            // Check if we have any search criteria
            const hasSearchCriteria = (query && query.length >= 2) || date ||
                (additionalFilters.city_id && additionalFilters.city_id.length > 0) ||
                (location && location !== "Location" && location !== "");

            // Find the appropriate container for results
            let eventsContainer = document.getElementById('event-container') ||
                document.querySelector('.all-event') ||
                document.getElementById('filteredEventsGrid') ||
                document.getElementById('CardsContainer');

            // If on home page and no container found, use the search section
            if (!eventsContainer) {
                const filterSection = document.getElementById('filterSection');
                if (filterSection) {
                    eventsContainer = document.getElementById('filteredEventsGrid');
                }
            }

            // If still no container and we have search criteria, show search section on home page
            if (!eventsContainer && hasSearchCriteria) {
                const filterSection = document.getElementById('filterSection');
                if (filterSection) {
                    filterSection.style.display = 'block';
                    eventsContainer = document.getElementById('filteredEventsGrid');

                    // Hide other sections on home page
                    document.querySelectorAll('.js-hide-on-search').forEach(section => {
                        section.style.display = 'none';
                    });
                }
            }

            // If no container at all, don't proceed
            if (!eventsContainer) {
                console.warn('No events container found');
                return;
            }

            isSearching = true;

            // Show loading indicator
            const loadingState = document.getElementById('loadingState');
            const noResultsState = document.getElementById('noResultsState');
            const errorState = document.getElementById('errorState');

            if (loadingState) loadingState.style.display = 'block';
            if (noResultsState) noResultsState.style.display = 'none';
            if (errorState) errorState.style.display = 'none';

            eventsContainer.innerHTML = '<div class="col-12 text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Searching...</p></div>';

            // Build search parameters
            const params = new URLSearchParams();

            // Basic search parameters
            if (query && query.length >= 2) {
                params.append('search', query);
            }
            if (location && location !== "Location" && location !== "") {
                params.append('location', location);
            }
            if (date) {
                params.append('date', date);
            }

            // Additional filters (from sidebar)
            Object.keys(additionalFilters).forEach(key => {
                const value = additionalFilters[key];
                if (value !== null && value !== undefined && value !== '') {
                    if (Array.isArray(value)) {
                        value.forEach(v => {
                            if (v) params.append(`${key}[]`, v);
                        });
                    } else {
                        params.append(key, value);
                    }
                }
            });

            // Make AJAX request
            fetch(`{{ route('events.ajax_search') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    isSearching = false;

                    if (loadingState) loadingState.style.display = 'none';

                    if (data.success) {
                        displaySearchResults(data);

                        // Update results count
                        const resultsCount = document.getElementById('resultsCount');
                        if (resultsCount && data.total !== undefined) {
                            resultsCount.textContent = `Found ${data.total} event${data.total !== 1 ? 's' : ''}`;
                        }
                    } else {
                        // Show error message
                        if (errorState) {
                            errorState.style.display = 'block';
                            const errorMessage = document.getElementById('errorMessage');
                            if (errorMessage) {
                                errorMessage.textContent = data.message || 'Error searching';
                            }
                        } else {
                            eventsContainer.innerHTML = `<div class="col-12 text-center py-5"><div class="alert alert-warning">${data.message || 'Error searching'}</div></div>`;
                        }
                    }
                })
                .catch(error => {
                    isSearching = false;
                    console.error('Search error:', error);

                    if (loadingState) loadingState.style.display = 'none';

                    if (errorState) {
                        errorState.style.display = 'block';
                        const errorMessage = document.getElementById('errorMessage');
                        if (errorMessage) {
                            errorMessage.textContent = 'Error searching. Please try again.';
                        }
                    } else {
                        eventsContainer.innerHTML = `<div class="col-12 text-center py-5"><div class="alert alert-danger">Error searching. Please try again.</div></div>`;
                    }
                });
        }

        // Advanced Filter Function (for sidebar filters)
        function performAdvancedFilter() {
            // Get basic search parameters
            const searchInput = document.getElementById('searchInput')?.value || '';
            const locationElement = document.querySelector('.location_field .default_option');
            const cityId = locationElement?.getAttribute('data-city-id');
            const headerDate = document.querySelector('#headerDateFilter')?.value;

            // Get all filter values
            const filters = {};

            // Categories
            const categories = [];
            document.querySelectorAll('input[name="category"]:checked').forEach(checkbox => {
                categories.push(checkbox.value);
            });
            if (categories.length > 0) filters.category = categories;

            // Cities - add header city_id if selected
            const cities = [];
            document.querySelectorAll('input[name="city_id"]:checked').forEach(checkbox => {
                cities.push(checkbox.value);
            });
            // Add city from header if selected
            if (cityId && cityId !== '') {
                cities.push(cityId);
            }
            if (cities.length > 0) filters.city_id = cities;

            // Format
            const formatOnline = document.querySelector('input[name="format"][value="1"]:checked');
            const formatOffline = document.querySelector('input[name="format"][value="0"]:checked');
            if (formatOnline) filters.format = '1';
            if (formatOffline) filters.format = '0';

            // Price range
            const minPrice = document.getElementById('min_price')?.value;
            const maxPrice = document.getElementById('max_price')?.value;
            if (minPrice) filters.min_price = minPrice;
            if (maxPrice) filters.max_price = maxPrice;

            // Date range
            const startDate = document.getElementById('start_date')?.value;
            const endDate = document.getElementById('end_date')?.value;
            if (startDate) filters.start_date = startDate;
            if (endDate) filters.end_date = endDate;

            // Ticket types
            const types = [];
            document.querySelectorAll('input[name="vip"]:checked, input[name="regular"]:checked, input[name="early_bird"]:checked, input[name="fan_zone"]:checked').forEach(checkbox => {
                types.push(checkbox.value);
            });
            if (types.length > 0) filters.type = types;

            // Perform search with all filters - pass null for location since we're using city_id
            performAjaxSearch(searchInput, null, headerDate, filters);
        }

        // Display search results
        function displaySearchResults(data) {
            let eventsContainer = document.getElementById('event-container') ||
                document.querySelector('.all-event') ||
                document.getElementById('filteredEventsGrid') ||
                document.getElementById('CardsContainer');

            if (eventsContainer) {
                // If on home page (filteredEventsGrid), ensure search section is visible
                const filterSection = document.getElementById('filterSection');
                const isHomePage = eventsContainer.id === 'filteredEventsGrid';

                if (isHomePage && filterSection) {
                    filterSection.style.display = 'block';

                    // Hide other sections
                    document.querySelectorAll('.js-hide-on-search').forEach(section => {
                        section.style.display = 'none';
                    });
                }

                // Update HTML content
                if (data.html) {
                    // For home page, the container already has the correct structure
                    eventsContainer.innerHTML = data.html;

                    // Scroll to search section on home page
                    if (isHomePage && filterSection) {
                        setTimeout(() => {
                            filterSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 100);
                    }
                } else if (data.data && Array.isArray(data.data)) {
                    // Handle JSON data format if needed
                    eventsContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="alert alert-info">Found ' + data.total + ' event</div></div>';
                }

                // Update pagination if available
                if (data.pagination) {
                    let paginationContainer = document.querySelector('.filteration-event-pagination');
                    if (!paginationContainer && isHomePage) {
                        // Create pagination container in search section
                        const paginationDiv = document.createElement('div');
                        paginationDiv.className = 'filteration-event-pagination mt-4';
                        paginationDiv.style.textAlign = 'center';
                        if (filterSection) {
                            filterSection.appendChild(paginationDiv);
                        }
                        paginationContainer = paginationDiv;
                    }
                    if (paginationContainer) {
                        paginationContainer.innerHTML = data.pagination;
                    }
                }

                // Check if no results
                const emptyDataPattern = '@' + 'empty';
                if (data.total === 0 || (data.html && (data.html.includes('empty-data') || data.html.includes(emptyDataPattern)))) {
                    const noResultsState = document.getElementById('noResultsState');
                    if (noResultsState) {
                        noResultsState.style.display = 'block';
                        eventsContainer.innerHTML = '';
                    }
                } else {
                    const noResultsState = document.getElementById('noResultsState');
                    if (noResultsState) {
                        noResultsState.style.display = 'none';
                    }
                }

                // Scroll to results section smoothly (if not home page)
                if (!isHomePage) {
                    setTimeout(() => {
                        const resultsSection = document.querySelector('.filteration-event-results-section') ||
                            document.querySelector('.all-event') ||
                            eventsContainer;
                        if (resultsSection) {
                            resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }, 100);
                }
            } else {
                console.warn('Events container not found. Results:', data);
            }
        }

        // Category Navigation
        function navigateToCategory(category) {
            window.location.href = `{{ route('events') }}?category=${encodeURIComponent(category)}`;
        }

        // Clear filters and show all sections (for home page)
        function clearFilters() {
            // Clear search input
            const searchInput = document.querySelector(".search_field .input");
            if (searchInput) {
                searchInput.value = "";
            }

            // Clear date input
            const dateInput = document.querySelector("#headerDateFilter");
            if (dateInput) {
                dateInput.value = "";
                // Clear flatpickr if initialized
                if (dateInput._flatpickr) {
                    dateInput._flatpickr.clear();
                }
            }

            // Reset location to default (no city pre-selected)
            const locationElement = document.querySelector(".location_field .default_option");
            if (locationElement) {
                locationElement.innerHTML = '<i class="fas fa-map-marker-alt"></i> Location';
                locationElement.setAttribute('data-city-id', '');
            }

            // Hide search section
            const filterSection = document.getElementById('filterSection');
            if (filterSection) {
                filterSection.style.display = 'none';
            }

            // Show all hidden sections
            document.querySelectorAll('.js-hide-on-search').forEach(section => {
                section.style.display = '';
            });

            // Clear results
            const eventsContainer = document.getElementById('filteredEventsGrid');
            if (eventsContainer) {
                eventsContainer.innerHTML = '';
            }

            // Hide loading/error states
            const loadingState = document.getElementById('loadingState');
            const noResultsState = document.getElementById('noResultsState');
            const errorState = document.getElementById('errorState');
            if (loadingState) loadingState.style.display = 'none';
            if (noResultsState) noResultsState.style.display = 'none';
            if (errorState) errorState.style.display = 'none';

            // Clear results count
            const resultsCount = document.getElementById('resultsCount');
            if (resultsCount) {
                resultsCount.textContent = '';
            }

            // Scroll to top of page smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Refresh filters (retry search)
        function refreshFilters() {
            const searchInput = document.querySelector(".search_field .input");
            const query = searchInput?.value.trim() || null;
            const locationElement = document.querySelector(".location_field .default_option");
            const cityId = locationElement?.getAttribute('data-city-id');
            const dateInput = document.querySelector("#headerDateFilter");
            const date = dateInput?.value || null;

            const filters = {};
            if (cityId && cityId !== '') {
                filters.city_id = [cityId];
            }

            performAjaxSearch(query && query.length >= 2 ? query : null, null, date, filters);
        }

        // Create eventFilter object for search.blade.php
        window.eventFilter = {
            clearFilters: clearFilters,
            refreshFilters: refreshFilters
        };

        // Expose functions globally for events page
        window.performAdvancedFilter = performAdvancedFilter;
        window.performAjaxSearch = performAjaxSearch;
        window.clearFilters = clearFilters;

        // Sign In Actions
        function handleSignIn() {
            window.location.href = "{{ route('login') }}";
        }

        function handleSignUp() {
            window.location.href = "{{ route('register') }}";
        }

        function handleForgotPassword() {
            window.location.href = "{{ route('forget_password') }}";
        }

        // Clear inputs when page loads (for when user returns from event page)
        function clearInputsOnReturn() {
            // Clear search input
            const searchInput = document.querySelector(
                ".search_field .input"
            );
            if (searchInput) {
                searchInput.value = "";
            }

            // Reset location to default (no city pre-selected)
            const locationDefault = document.querySelector(
                ".location_field .default_option"
            );
            if (locationDefault) {
                locationDefault.innerHTML = '<i class="fas fa-map-marker-alt"></i> Location';
                locationDefault.setAttribute('data-city-id', '');
            }

            // Reset date input
            const dateInput = document.querySelector(".date_input");
            if (dateInput) {
                dateInput.value = "";
            }

            // Close any open dropdowns
            const searchDropdown = document.getElementById(
                "searchResultsDropdown"
            );
            if (searchDropdown) {
                searchDropdown.classList.remove("show");
            }

            const locationDropdown =
                document.querySelector(".location_field ul");
            if (locationDropdown) {
                locationDropdown.classList.remove("active");
            }
        }

        // Check if user came back from event page using browser history
        window.addEventListener("pageshow", function (event) {
            if (
                event.persisted ||
                (window.performance &&
                    window.performance.navigation.type === 2)
            ) {
                // User came back using browser back button
                clearInputsOnReturn();
            }
        });

        // Document Ready
        document.addEventListener("DOMContentLoaded", function () {
            // Clear inputs on page load
            clearInputsOnReturn();
            // Mobile Menu Button
            const mobileMenuBtn = document.querySelector(
                ".event-mobile-menu-btn"
            );
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileMenu();
                });
            }

            // Search Input - Enter Key, Input Event with Debounce
            const searchInput = document.querySelector(
                ".search_field .input"
            );
            if (searchInput) {
                // Handle Enter key - immediate search
                searchInput.addEventListener("keypress", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        // Clear any pending timeout and search immediately
                        if (searchTimeout) {
                            clearTimeout(searchTimeout);
                        }
                        performSearch();
                    }
                });

                // Handle input event with debounce (for real-time search)
                searchInput.addEventListener("input", function () {
                    performSearch();
                });
            }

            // Search Icon Click
            const searchIcon = document.querySelector(
                ".search_field .fas.fa-search"
            );
            if (searchIcon) {
                searchIcon.addEventListener("click", function (e) {
                    e.preventDefault();
                    performSearch();
                });
                searchIcon.style.cursor = "pointer";
            }

            // Date Input Change with debounce and Flatpickr initialization
            const dateInput = document.querySelector("#headerDateFilter");
            if (dateInput) {
                // Initialize Flatpickr if available
                if (typeof flatpickr !== 'undefined') {
                    flatpickr(dateInput, {
                        dateFormat: "Y-m-d",
                        onChange: function (selectedDates, dateStr, instance) {
                            performDateFilter(dateStr);
                        },
                        onClose: function (selectedDates, dateStr, instance) {
                            // Also trigger on close if date was cleared
                            if (!dateStr) {
                                performDateFilter(null);
                            }
                        }
                    });
                } else {
                    // Fallback to native date input
                    const handleDateChange = function (e) {
                        const selectedDate = e.target.value;
                        performDateFilter(selectedDate);
                    };

                    dateInput.addEventListener("change", handleDateChange);
                    dateInput.addEventListener("input", handleDateChange);
                }
            }

            // Clean onclick attributes and add data attributes
            document
                .querySelectorAll('[onclick*="filterSubCategory"]')
                .forEach((element) => {
                    const onclick = element.getAttribute("onclick");
                    if (onclick) {
                        const match = onclick.match(
                            /filterSubCategory\('([^']+)'\)/
                        );
                        if (match && match[1]) {
                            element.setAttribute(
                                "data-subcategory",
                                match[1]
                            );
                            element.removeAttribute("onclick");
                        }
                    }
                });

            // Handle Category Clicks
            document.addEventListener("click", function (e) {
                // Handle data-subcategory
                const subcategoryElement =
                    e.target.closest("[data-subcategory]");
                if (subcategoryElement) {
                    e.preventDefault();
                    const subcategory =
                        subcategoryElement.getAttribute("data-subcategory");
                    navigateToCategory(subcategory);
                    return;
                }

                // Handle data-category
                const categoryElement = e.target.closest("[data-category]");
                if (categoryElement) {
                    e.preventDefault();
                    const category =
                        categoryElement.getAttribute("data-category");
                    navigateToCategory(category);
                    return;
                }

                // Handle data-action (only for specific actions, not all links)
                const actionElement = e.target.closest("[data-action]");
                if (actionElement) {
                    const action = actionElement.getAttribute("data-action");

                    // Only prevent default for specific actions, not all links
                    if (action === "getEventApp" || action === "toggleEventMobileMenu") {
                        e.preventDefault();
                    }

                    switch (action) {
                        case "signIn":
                            e.preventDefault();
                            handleSignIn();
                            break;
                        case "signUp":
                            e.preventDefault();
                            handleSignUp();
                            break;
                        case "forgotPassword":
                            e.preventDefault();
                            handleForgotPassword();
                            break;
                        case "toggleEventMobileMenu":
                            e.preventDefault();
                            toggleMobileMenu();
                            break;
                        case "getEventApp":
                            e.preventDefault();
                            // alert("App coming soon!");
                            window.location.href = "https://play.google.com/store/apps/details?id=com.abdallah.evnt&pli=1";
                            break;
                        default:
                            // Allow normal link behavior for other actions
                            break;
                    }
                    return;
                }
            });

            // Enhanced Dropdown Functionality - Fix for eventSignInSection
            document
                .querySelectorAll(".event-dropdown, .event-user-profile")
                .forEach((dropdown) => {
                    const trigger = dropdown.querySelector(
                        ".event-signin-btn, .event-user-name, button, a"
                    );
                    const hasMenu = !!dropdown.querySelector(
                        ".event-dropdown-menu"
                    );

                    if (trigger) {
                        trigger.addEventListener("click", function (e) {
                            // If this dropdown has no menu, allow default link navigation
                            if (!hasMenu) {
                                return;
                            }

                            e.preventDefault();
                            e.stopPropagation();

                            // Close other dropdowns first
                            document
                                .querySelectorAll(
                                    ".event-dropdown, .event-user-profile"
                                )
                                .forEach((other) => {
                                    if (other !== dropdown) {
                                        other.classList.remove(
                                            "event-open"
                                        );
                                        const otherTrigger =
                                            other.querySelector(
                                                ".event-signin-btn, .event-user-name, button, a"
                                            );
                                        if (otherTrigger) {
                                            otherTrigger.setAttribute(
                                                "aria-expanded",
                                                "false"
                                            );
                                        }
                                    }
                                });

                            // Toggle current dropdown
                            const isOpen =
                                dropdown.classList.toggle("event-open");
                            trigger.setAttribute("aria-expanded", isOpen);
                        });
                    }
                });

            // Close dropdowns when clicking outside
            document.addEventListener("click", function (e) {
                // Close search dropdown when clicking outside
                const searchDropdown = document.getElementById(
                    "searchResultsDropdown"
                );
                const searchField = document.querySelector(".search_field");

                if (searchDropdown && searchField) {
                    if (!searchField.contains(e.target)) {
                        searchDropdown.classList.remove("show");
                    }
                }

                // Close other dropdowns
                if (
                    !e.target.closest(".event-dropdown") &&
                    !e.target.closest(".event-user-profile")
                ) {
                    document
                        .querySelectorAll(
                            ".event-dropdown, .event-user-profile"
                        )
                        .forEach((dropdown) => {
                            dropdown.classList.remove("event-open");
                            const trigger = dropdown.querySelector(
                                ".event-signin-btn, .event-user-name, button, a"
                            );
                            if (trigger) {
                                trigger.setAttribute(
                                    "aria-expanded",
                                    "false"
                                );
                            }
                        });
                }
            });

            // Close mobile menu when clicking outside
            document.addEventListener("click", function (e) {
                const nav = document.getElementById("eventMainNav");
                const btn = document.querySelector(
                    ".event-mobile-menu-btn"
                );

                if (nav && nav.classList.contains("event-mobile-open")) {
                    if (
                        !e.target.closest("#eventMainNav") &&
                        !e.target.closest(".event-mobile-menu-btn")
                    ) {
                        nav.classList.remove("event-mobile-open");
                        if (btn) {
                            btn.innerHTML = '<i class="fas fa-bars"></i>';
                            btn.setAttribute("aria-expanded", "false");
                        }
                    }
                }
            });
        });

        // jQuery Ready
        $(document).ready(function () {
            // Location Dropdown
            $(".location_field .default_option").click(function () {
                $(this).siblings("ul").toggleClass("active");
            });

            $(".location_field ul li").click(function () {
                const text = $(this).text().trim();
                const cityId = $(this).data('city-id');
                const defaultOption = $(this).parents(".location_field").find(".default_option");

                defaultOption.html(`<i class="fas fa-map-marker-alt"></i> ${text}`);
                defaultOption.attr('data-city-id', cityId);

                $(this).parents("ul").removeClass("active");

                // Trigger search when location is selected
                const searchInput = document.querySelector(".search_field .input");
                const query = searchInput?.value.trim() || null;
                const dateInput = document.querySelector("#headerDateFilter");
                const date = dateInput?.value || null;

                // Prepare filters
                const filters = {};
                if (cityId && cityId !== '') {
                    filters.city_id = [cityId];
                }

                // Perform AJAX search with new location
                if (typeof performAjaxSearch === 'function') {
                    performAjaxSearch(query && query.length >= 2 ? query : null, null, date, filters);
                }
            });

            // Do not set a default date; leave it empty unless user selects

            // Prevent search dropdown from closing when clicking inside it
            const searchDropdown = document.getElementById(
                "searchResultsDropdown"
            );
            if (searchDropdown) {
                searchDropdown.addEventListener("click", function (e) {
                    e.stopPropagation();
                });
            }

            // Close Location Dropdown When Clicking Outside
            $(document).click(function (e) {
                if (!$(e.target).closest(".location_field").length) {
                    $(".location_field ul").removeClass("active");
                }
            });

            // Category Links in New Categories Section
            $(".category-link-event").click(function (e) {
                e.preventDefault();
                const href = $(this).attr("href");
                if (href && href !== "#") {
                    window.location.href = href;
                }
            });

            // Mobile optimizations
            $(window).on("resize", function () {
                // Close all dropdowns on resize
                $(".event-dropdown, .event-user-profile").removeClass(
                    "event-open"
                );
                $(".location_field ul").removeClass("active");
            });
        });
        document.addEventListener("click", function (e) {
            if (e.target.closest(".heart-icon-filter")) {
                const element = e.target.closest(".heart-icon-filter");
                const uuid = element.getAttribute("data-uuid");
                toggleFavorite(uuid, element);
            }
        });

    </script>
    <script src="{{ asset('Front/js/event-filter.js') }}"></script>

@endpush