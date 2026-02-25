<section class="events-container-exclusive">
    <div class="exclusive">
        <div class="header">
            <h2 class="title">Exclusive By MyEvnt</h2>
            <p class="subtitle">Discover amazing events happening around you</p>
        </div </div>

        <div class="events-container-exclusive" id="eventsContainer-exclusive">
            @foreach ($exclusive_events as $exclusive_event)
                    <div class="event-card-exclusive">
                         @php
                            $isFreeTicket = $exclusive_event->tickets->isNotEmpty() && $exclusive_event->tickets->first()->price == 0;
                            $surveyType = $exclusive_event->survey ?? 0;
                        @endphp 
                         @if($isFreeTicket && $surveyType == 2)
                            <a href="{{ route('checkout_survey', $exclusive_event->eventDates->first()->id) }}"
                                class="event-image-exclusive">
                                @foreach ($exclusive_event->media as $media)
                                    @if ($media->name == 'exclusive_image')
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $exclusive_event->name }}" />
                                    @endif
                                @endforeach
                            </a>
                        @else 
                            <a href="{{ url('event/' . $exclusive_event->uuid) }}" class="event-image-exclusive">
                                @foreach ($exclusive_event->media as $media)
                                    @if ($media->name == 'exclusive_image')
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $exclusive_event->name }}" />
                                    @endif
                                @endforeach
                            </a>
                        @endif

                        <div class="event-content-exclusive">
                            <div>
                                <h3 class="event-title-exclusive">
                                    <!-- <i class="fa-solid fa-bolt"></i>  -->
                                    {{ $exclusive_event->name }}
                                </h3>
                                <p class="event-description-exclusive">
                                    {{ Str::limit($exclusive_event->summary, 150, '...') }}
                                </p>
                                    <div class="event-location-exclusive">
                                        <span class="me-1"><i class="fa-thin fa-map-marker-alt">
                                        </i></span>{{ $exclusive_event->area ?? 'online' }}
                                    </div>
                                <div class="event-organizer-exclusive">
                                    <span class="me-1"><i class="fa-thin fa-calendar-alt"></i></span>
                                    {{ $exclusive_event->eventDates->isNotEmpty() && $exclusive_event->eventDates->first()->start_date
                                        ? \Carbon\Carbon::parse($exclusive_event->eventDates->first()->start_date)->format('d-m-Y')
                                        : \Carbon\Carbon::parse($exclusive_event->start_date)->format('d-m-Y') ?? 'N/A' }}
                                </div>
                                <div class="event-organizer-exclusive">
                                    <span class="me-1"><i
                                            class="fa-thin fa-tag"></i></span>{{ $exclusive_event->category?->name }}
                                </div>

                                <div class="event-organizer-exclusive">
                                    <span class="me-1"><i class="fa-thin fa-user"></i></span>
                                    {{ $exclusive_event->company?->company_name ?? ($exclusive_event->organized_by ?? '') }}
                                </div>
                            </div>

                            <div class="event-meta-exclusive">

                                <div class="event-actions-exclusive">
                                    <span class="event-price-exclusive">
                                        <span><i class="fa-thin fa-ticket"></i></span>
                                        {{ $exclusive_event->tickets->isNotEmpty() && $exclusive_event->tickets->first()->price == 0 ? 'Free' : ($exclusive_event->tickets->isNotEmpty()
                                            ? number_format($exclusive_event->tickets->first()->price, 0)
                                            : number_format($exclusive_event->tickets->first()?->price, 0)) }}
                                        {{ $exclusive_event->tickets->isNotEmpty() ? ($exclusive_event->tickets->first()->price == 0 ? '' : $exclusive_event->currency->code) : '' }}
                                    </span>
                                    <a href="{{ url('event/' . $exclusive_event->uuid) }}" class="event-view-details-exclusive">
                                        <i class="fa-light fa-eye"></i> View Details
                                    </a>
                                    <!-- @if($isFreeTicket && $surveyType == 1)
                                                <a href="{{ route('checkout_survey_hr', $exclusive_event->eventDates->first()->id) }}"
                                                    class="event-view-details-exclusive">
                                                    View Details
                                                </a>
                                            @elseif($surveyType == 2)
                                                <a href="{{ route('checkout_survey', $exclusive_event->eventDates->first()->id) }}"
                                                    class="event-view-details-exclusive">
                                                    View Details
                                                </a> -->
                                    <!-- @else -->
                                    <!-- @endif -->
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>

        <!-- slider dots  -->
        @if($exclusive_events->count() > 1)
            <div class="slider-dots-exclusive">
                @foreach($exclusive_events as $index => $event)
                    <button class="slider-dot-exclusive {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"
                        aria-label="Go to event {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
        @endif
</section>