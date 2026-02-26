@forelse ($events as $event)
    @php
        $posterUrl = $event->media && $event->media->isNotEmpty() && $event->media->first()->path
            ? asset('storage/' . $event->media->first()->path)
            : asset('Front/img/default-event.jpg');
    @endphp
    <div class="New-card-event-card">
        <a href="{{ route('event', $event->uuid) }}">
            <img src="{{ $posterUrl }}" alt="{{ $event->name }}"
                class="New-card-event-card-image" />
        </a>
        @if(auth()->check())
            @php
                $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
            @endphp
            <a href="#" class="heart-icon" data-event-id="{{ $event->id }}">
                <i class="fa-{{ in_array($event->id, $event->is_favourite) ? 'solid' : 'regular' }} fa-heart"></i>
            </a>
        @endif
        <div class="New-card-event-card-content">
            <div class="New-card-event-card-header">
                <h3 class="New-card-event-card-title">{{ str($event->name)->limit(25) }}</h3>
                <div class="New-card-event-card-category">{{ $event->category?->name }}</div>
            </div>
            <p class="New-card-event-card-description line-clamp-3">{{ $event->summary ?? '' }}</p>
            <div class="New-card-event-card-details">
                @if($event->format == 0 && $event->city?->name)
                    <div class="New-card-event-card-detail">
                        <span class="New-card-event-card-detail-icon"><i class="fa-thin fa-map-marker-alt"></i></span>
                        <span class="New-card-event-card-detail-value">{{ $event->city->name }}</span>
                    </div>
                @endif
                <div class="New-card-event-card-detail">
                    <span class="New-card-event-card-detail-icon"><i class="fa-thin fa-calendar-alt"></i></span>
                    <span class="New-card-event-card-detail-value">
                        @if($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date)
                            {{ \Carbon\Carbon::parse($event->eventDates->first()->start_date . ' ' . \Carbon\Carbon::parse($event->eventDates->first()->start_time ?? '00:00:00')->format('H:i:s'))->format('D, M d, g:i A') }}
                        @elseif($event->start_date ?? null)
                            {{ \Carbon\Carbon::parse($event->start_date . ' ' . \Carbon\Carbon::parse($event->start_time ?? '00:00:00')->format('H:i:s'))->format('D, M d, g:i A') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="New-card-event-card-footer">
            <div class="New-card-event-card-price">
                <span class="New-card-event-card-price-amount">
                    @if($event->tickets->isNotEmpty() && (float) $event->tickets->first()->price == 0)
                        Free
                    @else
                        {{ $event->tickets->isNotEmpty() ? number_format($event->tickets->first()->price, 0) : '0' }}
                    @endif
                </span>
                <span class="New-card-event-card-price-currency">
                    {{ $event->tickets->isNotEmpty() && (float) $event->tickets->first()->price != 0 && $event->currency ? $event->currency->code : '' }}
                </span>
            </div>
            <a href="{{ route('event', $event->uuid) }}" class="New-card-event-details-btn">View Details</a>
        </div>
    </div>
@empty
@endforelse
