<section class="Events pt-3">
        <h1>Past Events </h1>
        <!-- Events -->
        <div class="custom-container container-new-events">
            @foreach ($past_events as $event)
                <div class="product card-event">
                    <a href="{{ route('event', $event->uuid) }}">
                        @foreach ($event->media as $media)
                            @if ($media->name == 'poster')
                                <img src="{{ asset('storage/' . $media->path) }}" alt="image" style="height: 200px;">
                            @endif
                        @endforeach
                        <div class="des">
                            
                            <a href="{{ route('event', $event->uuid) }}">
                                <h5 class="title" class="product-name"> {{ $event->name }}</h5>
                            </a>
                            <p class="date"> <i class="fa-light fa-calendar-days pe-1"></i>
                                {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date
                ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d-m-Y')
                : \Carbon\Carbon::parse($event->start_date)->format('d-m-Y') ?? 'N/A' }}
                            </p>
                            <p class="categoey"> <i class="fa-light fa-tag pe-1"></i> {{ $event->category?->name }}</p>
                            <p class="city"> <i class="fa-light fa-location-dot pe-1"></i> {{ $event?->area ?? $event->city?->name}}</p>
                            <small class="hosted"> <i class="fa-light fa-user-tie pe-1"></i>
                                {{ $event->company?->company_name ?? ($event->organized_by ?? '') }}</small>
                            <div class="price">
                                <span class="badge">  <i class="fa-light fa-ticket pe-1"></i>
                                    {{ $event->tickets->isNotEmpty() && $event->tickets->first()->price == 0 ? 'Free' : ($event->tickets->isNotEmpty() ? number_format($event->tickets->first()->price, 2) : number_format($event->tickets->first()?->price, 2)) }}
                                    <span
                                        class="currency text-white fw-bold">{{ $event->tickets->isNotEmpty() ? ($event->tickets->first()->price == 0 ? '' : $event->currency->code) : '' }}</span>
                                </span>
                                {{-- @if (auth()->check())
                                @php
                                $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
                                @endphp
                                <a href="#" class="heart-icon" data-event-uuid="{{ $event->uuid }}">
                                    <i
                                        class="fa-{{ in_array($event->id, $event->is_favourite) ? 'solid' : 'regular' }} fa-heart"></i>
                                </a>
                                @else
                                <a href="#" class="heart-icon">
                                    <i class="fa-regular fa-heart"></i>
                                </a>
                                @endif --}}
                            </div>
                            

                        </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mb-4">
            <a href="{{ route('past_events') }}" class="btn btn-success"> <i class="fa-light fa-arrow-right"></i> View More</a>
        </div>
    </section>