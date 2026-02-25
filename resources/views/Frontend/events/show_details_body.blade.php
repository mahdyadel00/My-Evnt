{{-- Main body of event details (nebule design). Uses $event, $event_related. --}}
@php
    $banner = $event->media->where('name', 'banner')->first() ?? $event->media->first();
    $firstDate = $event->eventDates->first();
    $logo = $event->company?->media->firstWhere('name', 'logo');
    $eventUrl = route('event', $event->uuid);
    $isFollowing = $event->company?->followers?->contains('user_id', Auth::id());
    $facilitiesMap = [
        'bathroom' => ['icon' => 'fas fa-restroom', 'text' => 'Bathrooms'],
        'food' => ['icon' => 'fas fa-utensils', 'text' => 'Food Services'],
        'parking' => ['icon' => 'fas fa-parking', 'text' => 'Parking'],
        'security' => ['icon' => 'fas fa-shield-alt', 'text' => 'Security'],
        'wifi' => ['icon' => 'fas fa-wifi', 'text' => 'Wifi'],
    ];
    $facilitiesArray = is_array($event->facility) ? $event->facility : (is_string($event->facility) ? explode(',', $event->facility) : []);
    $eventDate = $firstDate;
    $isEventExpired = $eventDate && $eventDate->end_date < now()->toDateString();
    $isEventStarted = $eventDate && $eventDate->start_date <= now()->toDateString() && $eventDate->end_date >= now()->toDateString();
    $hasExternalLink = !empty($event->external_link);
    $firstTicket = optional($event->tickets)->first();
    $isFreeTicket = $firstTicket && $firstTicket->price == 0;
    $surveyType = $event->survey ?? 0;
    $isGuestSurvey = $hasExternalLink && $surveyType == 3;
    $iscarerha = $hasExternalLink && $surveyType == 4;
    $finshed_survey = $surveyType == 1;
    $upcomingDates = $event->eventDates->filter(fn($ed) => $ed->end_date >= now()->toDateString());
    $hasMultipleDates = $upcomingDates->count() > 1;
    $hasMultipleTickets = $event->tickets->count() > 1;
@endphp

<main class="nebule-event-details-body">
    <div class="nebule-event-details">
        {{-- Sticky bar --}}
        <div class="nebule-event-details__sticky-bar" id="eventStickyBar" aria-hidden="true">
            <div class="nebule-event-details__sticky-bar-inner">
                <div class="nebule-event-details__sticky-bar-info">
                    @if($banner)
                        <img src="{{ asset('storage/' . $banner->path) }}" alt="" class="nebule-event-details__sticky-bar-img" width="56" height="56" />
                    @endif
                    <div class="nebule-event-details__sticky-bar-text">
                        <span class="nebule-event-details__sticky-bar-title">{{ $event->name }}</span>
                        <span class="nebule-event-details__sticky-bar-date">
                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                            @if($firstDate)
                                {{ \Carbon\Carbon::parse($firstDate->start_date . ' ' . ($firstDate->start_time ?? '00:00:00'))->format('D, M j, Y \a\t g:i a') }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                </div>
                <div class="nebule-event-details__sticky-bar-actions">
                    <button type="button" class="nebule-event-details__sticky-bar-share" title="Share" aria-label="Share event" data-bs-toggle="modal" data-bs-target="#shareModal">
                        <i class="fas fa-share-alt" aria-hidden="true"></i>
                    </button>
                    @if($firstDate && !$isEventExpired && !$isEventStarted)
                        @if($isGuestSurvey)
                            <a href="#" class="nebule-event-details__sticky-bar-cta" data-bs-toggle="modal" data-bs-target="#guestSurveyModal">Get Tickets</a>
                        @elseif($iscarerha)
                            <a href="#" class="nebule-event-details__sticky-bar-cta" data-bs-toggle="modal" data-bs-target="#carerhaModal">Get Tickets</a>
                        @else
                            <a href="{{ $event->external_link ?? route('checkout_user', $firstDate->id) }}" class="nebule-event-details__sticky-bar-cta">Get Tickets</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="nebule-event-details__container">
            <div class="nebule-event-details__layout">
                {{-- Left Column --}}
                <div class="nebule-event-details__content">
                    <div class="nebule-event-details__hero-image-wrapper">
                        @if($banner)
                            <img src="{{ asset('storage/' . $banner->path) }}" alt="{{ $event->name }}" class="nebule-event-details__hero-image" />
                        @else
                            <img src="{{ asset('Front/img/default-event.jpg') }}" alt="{{ $event->name }}" class="nebule-event-details__hero-image" />
                        @endif
                        <div class="nebule-event-details__hero-actions">
                            @if(auth()->check() && $firstDate && $firstDate->end_date > now())
                                <button type="button" class="nebule-event-details__hero-action-btn nebule-event-details__hero-action-btn--like" title="Like" aria-label="Like this event" id="heart-icon" data-event-id="{{ $event->id }}">
                                    <i class="fa-{{ in_array($event->id, $event->is_favourite ?? []) ? 'solid' : 'regular' }} fa-heart"></i>
                                </button>
                            @endif
                            <button type="button" class="nebule-event-details__hero-action-btn nebule-event-details__hero-action-btn--share" title="Share" data-bs-toggle="modal" data-bs-target="#shareModal" aria-label="Share">
                                <i class="fas fa-share-alt" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="nebule-event-details__event-meta-block">
                        <h1 class="nebule-event-details__title">{{ $event->name }}</h1>
                        @if($event->company || $event->organized_by)
                            <div class="nebule-event-details__organizer-badge">
                                @if($logo)
                                    <div class="nebule-event-details__organizer-badge-avatar">
                                        <img src="{{ asset('storage/' . $logo->path) }}" alt="{{ $event->company->company_name }}" width="40" height="40" />
                                    </div>
                                @endif
                                <div class="nebule-event-details__organizer-badge-info">
                                    <span class="nebule-event-details__organizer-badge-by">Hosted by</span>
                                    <a href="{{ $event->company ? route('company_profile', $event->company->id) : '#' }}" class="nebule-event-details__organizer-badge-name">{{ $event->company?->company_name ?? $event->organized_by }}</a>
                                </div>
                            </div>
                        @endif
                        @if($event->format == 0 && ($event->location || $event->city?->name))
                            <div class="nebule-event-details__event-meta-row">
                                <span class="nebule-event-details__event-meta-icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                                <span>{{ $event->area ?? $event->location ?? '' }} {{ $event->city ? '· ' . $event->city->name : '' }}</span>
                            </div>
                        @endif
                        @if($firstDate)
                            <div class="nebule-event-details__event-meta-row">
                                <span class="nebule-event-details__event-meta-icon"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                                <span>{{ \Carbon\Carbon::parse($firstDate->start_date)->format('l, M j') }} from {{ optional(\Carbon\Carbon::make($firstDate->start_time ?? '00:00:00'))->format('g:i a') }} to {{ optional(\Carbon\Carbon::make($firstDate->end_time ?? '00:00:00'))->format('g:i a') }}</span>
                            </div>
                        @endif
                    </div>

                    <section class="nebule-event-details__section nebule-event-details__overview-block" id="overviewBlock">
                        <h2 class="nebule-event-details__overview-title">Description</h2>
                        <div class="nebule-event-details__section-content">
                            <div class="nebule-event-details__overview-text event-description">{!! $event->description !!}</div>
                            <button type="button" class="nebule-event-details__read-more" id="readMoreBtn" aria-expanded="false" aria-controls="overviewBlock">
                                <span class="nebule-event-details__read-more-text-expand">Read more</span>
                                <span class="nebule-event-details__read-more-text-collapse">Read less</span>
                            </button>
                        </div>
                    </section>

                    @if($hasMultipleDates)
                        <section class="nebule-event-details__section nebule-event-details__dates-section">
                            <h2 class="nebule-event-details__section-title">Choose Date</h2>
                            <div class="nebule-event-details__dates-grid">
                                @foreach($event->eventDates as $ed)
                                    @if($ed->end_date >= now()->toDateString())
                                        <a href="{{ route('checkout_user', $ed->id) }}" class="nebule-event-details__date-card {{ $loop->first ? 'nebule-event-details__date-card--active' : '' }}" role="button">
                                            <span class="nebule-event-details__date-card-day">{{ \Carbon\Carbon::parse($ed->start_date)->format('D, M j') }}</span>
                                            <span class="nebule-event-details__date-card-time">{{ optional(\Carbon\Carbon::make($ed->start_time))->format('g:i a') }} – {{ optional(\Carbon\Carbon::make($ed->end_time))->format('g:i a') }}</span>
                                            <span class="nebule-event-details__date-card-location">{{ $event->format == 1 ? 'Online' : ($event->location ?? $event->city?->name ?? '—') }}</span>
                                            <span class="nebule-event-details__date-card-status">{{ $loop->first ? 'Main date' : 'Additional session' }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($hasMultipleTickets)
                        <section class="nebule-event-details__section nebule-event-details__tickets-content-section">
                            <h2 class="nebule-event-details__section-title">Tickets</h2>
                            <div class="nebule-event-details__tickets-grid">
                                @foreach($event->tickets as $ticket)
                                    @php
                                        $remaining = $ticket->quantity - $ticket->orders->sum('quantity');
                                        $soldOut = $remaining <= 0;
                                    @endphp
                                    <div class="nebule-event-details__ticket-item">
                                        <span class="nebule-event-details__ticket-item-name">{{ $ticket->ticket_type }}</span>
                                        <span class="nebule-event-details__ticket-item-price">{{ $ticket->price == 0 ? 'Free' : number_format($ticket->price, 0) . ' ' . ($event->currency?->code ?? '') }}</span>
                                        @if(!$soldOut && $firstDate && $firstDate->end_date >= now()->toDateString())
                                            <a href="{{ route('checkout_user', $firstDate->id) }}" class="nebule-event-details__ticket-item-btn">Get ticket</a>
                                        @else
                                            <span class="nebule-event-details__ticket-item-btn disabled">{{ $soldOut ? 'Sold out' : 'Unavailable' }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($event->company)
                        <section class="nebule-event-details__section">
                            <h2 class="nebule-event-details__section-title">About the Organizer</h2>
                            <div class="nebule-event-details__section-content">
                                <div class="nebule-event-details__organizer">
                                    @if($logo)
                                        <div class="nebule-event-details__organizer-logo">
                                            <img src="{{ asset('storage/' . $logo->path) }}" alt="{{ $event->company->company_name }}" class="nebule-event-details__organizer-logo-img" />
                                        </div>
                                    @endif
                                    <div class="nebule-event-details__organizer-info">
                                        <div class="nebule-event-details__organizer-name-row">
                                            <a href="{{ route('company_profile', $event->company->id) }}" class="nebule-event-details__organizer-name">{{ $event->company->company_name }}</a>
                                            @auth
                                                <button type="button" class="nebule-event-details__follow-btn follow-btn {{ $isFollowing ? 'btn-primary' : 'btn-outline-primary' }}" data-id="{{ $event->company->id }}">{{ $isFollowing ? 'Following' : 'Follow' }}</button>
                                            @endauth
                                        </div>
                                        @if($event->company->description ?? null)
                                            <p class="nebule-event-details__organizer-description">{{ Str::limit($event->company->description, 200) }}</p>
                                        @endif
                                        <div class="nebule-event-details__organizer-stats">
                                            <span class="nebule-event-details__stat-item"><i class="fas fa-users"></i> <span class="follower-count">{{ $event->company->followers->count() }}</span> followers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($event->format == 0 && ($event->latitude && $event->longitude))
                        <section class="nebule-event-details__section">
                            <h2 class="nebule-event-details__section-title">Location</h2>
                            <div class="nebule-event-details__section-content">
                                <div class="nebule-event-details__map-wrapper">
                                    <iframe src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_api_key', 'AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE') }}&q={{ $event->latitude }},{{ $event->longitude }}" class="nebule-event-details__map" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($firstDate && $firstDate->end_date >= now()->toDateString())
                        @php
                            $interestedCount = $interestedCount ?? 0;
                            $isInterested = $isInterested ?? false;
                        @endphp
                        <section class="nebule-event-details__section nebule-event-details__interested-section {{ $isInterested ? 'is-done' : '' }}" id="interestedSection">
                            <h2 class="nebule-event-details__section-title">I am interested</h2>
                            <div class="nebule-event-details__interested-card">
                                <p class="nebule-event-details__interested-desc">Save the event, get reminders and updates. One click, no form.</p>
                                <ul class="nebule-event-details__interested-benefits" aria-label="Benefits">
                                    <li><i class="fas fa-bell"></i> Reminder before the event</li>
                                    <li><i class="fas fa-users"></i> <span id="interestedCountNum">{{ $interestedCount }}</span> people interested</li>
                                </ul>
                                <div class="nebule-event-details__interested-row">
                                    <button type="button" id="interestedBtn" class="nebule-event-details__interested-btn {{ $isInterested ? 'is-interested' : '' }}" aria-pressed="{{ $isInterested ? 'true' : 'false' }}" aria-label="Mark interested" data-event-id="{{ $event->id }}" data-url="{{ route('event.interested') }}">
                                        <span class="nebule-event-details__interested-btn-text">{{ $isInterested ? 'Interested' : 'I am interested' }}</span>
                                    </button>
                                </div>
                                <div class="nebule-event-details__interested-done" id="interestedDone" aria-hidden="{{ $isInterested ? 'false' : 'true' }}">
                                    <i class="fas fa-check-circle"></i>
                                    <span>You're interested. We'll remind you before the event.</span>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="nebule-event-details__sidebar">
                    <div class="nebule-event-details__sidebar-sticky">
                        @if($firstDate && $firstDate->end_date >= now()->toDateString())
                            <section class="nebule-event-details__tickets-section">
                                <div class="nebule-event-details__ticket-card">
                                    <div class="nebule-event-details__ticket-card-inner">
                                        <span class="nebule-event-details__ticket-price-label">{{ $firstTicket && $firstTicket->price == 0 ? 'Free' : (number_format($firstTicket->price ?? 0, 0) . ' ' . ($event->currency?->code ?? '')) }}</span>
                                        <span class="nebule-event-details__ticket-date-label"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($firstDate->start_date)->format('M j') }} from {{ optional(\Carbon\Carbon::make($firstDate->start_time))->format('g:i a') }} to {{ optional(\Carbon\Carbon::make($firstDate->end_time))->format('g:i a') }}</span>
                                    </div>
                                </div>
                                @if($isGuestSurvey)
                                    <button type="button" class="nebule-event-details__reserve-btn" data-bs-toggle="modal" data-bs-target="#guestSurveyModal">Get Tickets</button>
                                @elseif($iscarerha)
                                    <button type="button" class="nebule-event-details__reserve-btn" data-bs-toggle="modal" data-bs-target="#carerhaModal">Get Tickets</button>
                                @else
                                    <a href="{{ $event->external_link ?? route('checkout_user', $firstDate->id) }}" class="nebule-event-details__reserve-btn">Get Tickets</a>
                                @endif
                            </section>
                        @endif

                        @if(!empty($facilitiesArray))
                            <section class="nebule-event-details__section nebule-event-details__facilities-section">
                                <h2 class="nebule-event-details__section-title nebule-event-details__facilities-title">Facilities</h2>
                                <div class="nebule-event-details__facilities-grid">
                                    @foreach($facilitiesMap as $key => $fac)
                                        @if(in_array($key, $facilitiesArray))
                                            <div class="nebule-event-details__facility-item">
                                                <span class="nebule-event-details__facility-icon"><i class="{{ $fac['icon'] }}"></i></span>
                                                <span class="nebule-event-details__facility-label">{{ $fac['text'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        @if(isset($event_related) && $event_related->isNotEmpty())
                            <section class="nebule-event-details__explore-section">
                                <h2 class="nebule-event-details__explore-title">Explore more {{ $event->city ? 'in ' . $event->city->name : '' }}</h2>
                                <div class="nebule-event-details__explore-list">
                                    @foreach($event_related->take(3) as $relatedEvent)
                                        @php $relImg = $relatedEvent->media->first(); @endphp
                                        <a href="{{ route('event', $relatedEvent->uuid) }}" class="nebule-event-details__explore-card">
                                            <div class="nebule-event-details__explore-card-img-wrap">
                                                <img src="{{ $relImg ? asset('storage/' . $relImg->path) : asset('Front/img/default-event.jpg') }}" alt="" class="nebule-event-details__explore-card-img" width="120" height="90" />
                                            </div>
                                            <div class="nebule-event-details__explore-card-body">
                                                <span class="nebule-event-details__explore-name">{{ Str::limit($relatedEvent->name, 25) }}</span>
                                                <span class="nebule-event-details__explore-meta"><i class="far fa-calendar-alt"></i> {{ $relatedEvent->eventDates->isNotEmpty() ? \Carbon\Carbon::parse($relatedEvent->eventDates->first()->start_date)->format('D, j M Y') : '—' }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>
</main>
