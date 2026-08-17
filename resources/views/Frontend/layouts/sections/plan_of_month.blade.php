<section class="New-card-event-results-section js-exclusive-slider">
    <div class="New-card-event-results-container">
        <div class="header">
            <h2 class="title">Plan Your Month</h2>
            <p class="subtitle">Make every month unforgettable with handpicked events .</p>
        </div>

        <div class="events-container-exclusive">
            @foreach ($plan_month as $plan_month_event)
                    <div class="event-card-exclusive">
                        @php
                            $isFreeTicket = $plan_month_event->tickets->isNotEmpty() && $plan_month_event->tickets->first()->price == 0;
                        @endphp
                        @if($isFreeTicket)
                            <a href="{{ route('checkout_survey', $plan_month_event->eventDates->first()->id) }}"
                                class="event-image-exclusive">
                                
                                    <img src="{{ asset('storage/' . $plan_month_event->media->first()->path) }}" alt="{{ $plan_month_event->name }}" />
                            </a>
                        @endif

                        <div class="event-content-exclusive">
                            <div>
                                <h3 class="event-title-exclusive">
                                    {{ $plan_month_event->name }}
                                </h3>
                                <p class="event-description-exclusive">
                                    {{ Str::limit($plan_month_event->summary, 150, '...') }}
                                </p>
                                <div class="event-location-exclusive">
                                    <span class="me-1"><i class="fa-thin fa-map-marker-alt"></i></span>
                                    {{ $plan_month_event->area ?? 'online' }}
                                </div>
                                <div class="event-organizer-exclusive">
                                    <span class="me-1"><i class="fa-thin fa-calendar-alt"></i></span>
                                    {{ $plan_month_event->eventDates->isNotEmpty() && $plan_month_event->eventDates->first()->start_date
                ? \Carbon\Carbon::parse($plan_month_event->eventDates->first()->start_date)->format('d-m-Y')
                : \Carbon\Carbon::parse($plan_month_event->start_date)->format('d-m-Y') ?? 'N/A' }}
                                </div>
                                <div class="event-organizer-exclusive">
                                    <span class="me-1"><i class="fa-thin fa-tag"></i></span>
                                    {{ $plan_month_event->category?->name }}
                                </div>
                                <div class="event-organizer-exclusive">
                                    <span class="me-1"><i class="fa-thin fa-user"></i></span>
                                    {{ $plan_month_event->company?->company_name ?? ($plan_month_event->organized_by ?? '') }}
                                </div>
                            </div>

                            <div class="event-meta-exclusive">
                                <div class="event-actions-exclusive">
                                    <span class="event-price-exclusive">
                                        <span><i class="fa-thin fa-ticket"></i></span>
                                        {{ $plan_month_event->tickets->isNotEmpty() && $plan_month_event->tickets->first()->price == 0
                ? 'Free'
                : ($plan_month_event->tickets->isNotEmpty()
                    ? number_format($plan_month_event->tickets->first()->price, 0)
                    : number_format($plan_month_event->tickets->first()?->price, 0)) }}
                                        {{ $plan_month_event->tickets->isNotEmpty()
                ? ($plan_month_event->tickets->first()->price == 0 ? '' : $plan_month_event->currency->code)
                : '' }}
                                    </span>
                                    <a href="{{ url('event/' . $plan_month_event->uuid) }}"
                                        class="event-view-details-exclusive">
                                        <i class="fa-light fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>

        @if($plan_month->count() > 1)
            <div class="slider-dots-exclusive">
                @foreach($plan_month as $index => $event)
                    <button type="button" class="slider-dot-exclusive {{ $index === 0 ? 'active' : '' }}"
                        data-slide="{{ $index }}" aria-label="Go to event {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>