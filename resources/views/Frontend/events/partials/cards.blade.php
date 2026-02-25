@forelse ($events as $event)
    @php
        $firstDate = $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
            ? $event->eventDates->first()->start_date
            : ($event->start_date ?? '');
        $isFree = $event->tickets->isNotEmpty() && (float) $event->tickets->first()->price == 0;
        $priceType = $isFree ? 'free' : 'paid';
        $categorySlug = $event->category ? \Illuminate\Support\Str::slug($event->category->name) : '';
        $citySlug = $event->city ? \Illuminate\Support\Str::slug($event->city->name) : '';
    @endphp
    <div class="filteration-event-card"
         data-event-category="{{ $categorySlug }}"
         data-event-city="{{ $citySlug }}"
         data-event-date="{{ $firstDate }}"
         data-event-price="{{ $priceType }}">
        @if($event->media && $event->media->isNotEmpty() && $event->media->first()->path)
            <img src="{{ asset('storage/' . $event->media->first()->path) }}"
                 alt="{{ $event->name }}"
                 class="filteration-event-card-image" />
        @else
            <img src="{{ asset('Front/img/default-event.jpg') }}"
                 alt="{{ $event->name }}"
                 class="filteration-event-card-image" />
        @endif
        <div class="filteration-event-card-content">
            <div class="filteration-event-card-header">
                <h3 class="filteration-event-card-title">
                    {{ str($event->name)->limit(25) }}
                </h3>
                <div class="filteration-event-card-category">
                    {{ $event->category?->name }}
                </div>
            </div>

            <p class="filteration-event-card-description">
                {{ str($event->summary ?? '')->limit(125) }}
            </p>

            <div class="filteration-event-card-details">
                @if($event->format == 0 && $event->city?->name)
                <div class="filteration-event-card-detail">
                    <span class="filteration-event-card-detail-icon">📍</span>
                    <span class="filteration-event-card-detail-value">{{ $event->city->name }}</span>
                </div>
                @endif
                <div class="filteration-event-card-detail">
                    <span class="filteration-event-card-detail-icon">📅</span>
                    <span class="filteration-event-card-detail-value">
                        @if($event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date)
                            {{ \Carbon\Carbon::parse($event->eventDates->first()->start_date . ' ' . ($event->eventDates->first()->start_time ?? '00:00:00'))->format('D, M d, g:i A') }}
                        @elseif($event->start_date ?? null)
                            {{ \Carbon\Carbon::parse($event->start_date . ' ' . ($event->start_time ?? '00:00:00'))->format('D, M d, g:i A') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="filteration-event-card-footer">
            <div class="filteration-event-card-price">
                <span class="filteration-event-card-price-amount">
                    @if($isFree)
                        Free
                    @else
                        {{ $event->tickets->isNotEmpty() ? number_format($event->tickets->first()->price, 0) : '0' }}
                    @endif
                </span>
                <span class="filteration-event-card-price-currency">
                    {{ !$isFree && $event->tickets->isNotEmpty() && $event->currency ? $event->currency->code : '' }}
                </span>
            </div>
            <a href="{{ route('event', $event->uuid) }}" class="filteration-event-details-btn">View Details</a>
        </div>
    </div>
@empty
@endforelse
