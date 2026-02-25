<!-- New Events Section -->
<section class="New-card-event-results-section">
    <div class="New-card-event-results-container">
        <div class="header">
            <h2 class="title">New Events</h2>
            <p class="subtitle">Explore the most recent events curated just for you .</p>
        </div>
        <!-- Cards Container with Navigation -->
        <div class="New-card-event-cards-container">
            <div class="New-card-event-cards-grid">
                @foreach ($new_events as $event)
                            <div class="New-card-event-card">
                                <a href="{{ url('event/' . $event->uuid) }}">
                                    <img src="{{ asset('storage/' . $event->media->first()->path) }}" alt="Tech Innovation Summit"
                                        class="New-card-event-card-image" />
                                </a>
                                @if(auth()->check())
                                    @php
                                        $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
                                    @endphp
                                    <a href="#" class="heart-icon" data-event-id="{{ $event->id }}">
                                        <i
                                            class="fa-{{ in_array($event->id, $event->is_favourite) ? 'solid' : 'regular' }} fa-heart"></i>
                                    </a>
                                @endif
                                <div class="New-card-event-card-content">
                                    <div class="New-card-event-card-header">
                                        <h3 class="New-card-event-card-title">
                                            {{ str($event->name)->limit(25) }}
                                        </h3>
                                        <div class="New-card-event-card-category">
                                            {{ $event->category?->name }}
                                        </div>
                                    </div>

                                    <p class="New-card-event-card-description line-clamp-3">
                                          {{ $event->summary }}
                                    </p>

                                    <div class="New-card-event-card-details">
                                        @if($event->format == 0)
                                            <div class="New-card-event-card-detail">
                                              <span class="New-card-event-card-detail-icon"><i class="fa-thin fa-map-marker-alt"></i></span>
                                            <span class="New-card-event-card-detail-value">{{ $event->city?->name }}</span>
                                        </div>
                                        @endif
                                        <div class="New-card-event-card-detail">
                                            <span class="New-card-event-card-detail-icon"><i class="fa-thin fa-calendar-alt"></i></span>
                                            <span class="New-card-event-card-detail-value">
                                                {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                    ? \Carbon\Carbon::parse(
                        $event->eventDates->first()->start_date . ' ' . \Carbon\Carbon::parse($event->eventDates->first()->start_time)->format('H:i:s')
                    )->format('D, M d , g:i A')
                    : (\Carbon\Carbon::parse(
                        $event->start_date . ' ' . \Carbon\Carbon::parse($event->start_time)->format('H:i:s')
                    )->format('D, M d , g:i A') ?? 'N/A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="New-card-event-card-footer">
                                    <div class="New-card-event-card-price">
                                        <span class="New-card-event-card-price-amount">
                                            <!-- format price to 0 decimal places -->
                                            {{  $event->tickets->isNotEmpty() && $event->tickets->first()->price == 0 ? 'Free' : ($event->tickets->isNotEmpty() ? number_format($event->tickets->first()->price, 0) : number_format($event->tickets->first()?->price, 0)) }}
                                        </span>
                                        <span class="New-card-event-card-price-currency">
                                            {{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price == 0 ? '' : $event->currency->code : '' }}
                                        </span>
                                    </div>
                                    <a href="{{ url('event/' . $event->uuid) }}" class="New-card-event-details-btn">View Details</a>
                                </div>
                            </div>
                @endforeach
            </div>
            <div class="button-card-event">
                <a href="{{ route('new_events') }}" class="New-card-event-details-btn">
                    <i class="fas fa-arrow-right"></i> Explore More</a>
            </div>
        </div>
    </div>
</section>