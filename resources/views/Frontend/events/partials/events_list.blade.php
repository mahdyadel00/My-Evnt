@if(isset($events) && $events->isNotEmpty())
    <div class="col-12 mb-3">
        <h5>Found {{ $events->total() }} events for the selected date</h5>
    </div>
    @foreach($events as $event)
        <div class="product card-event">
            <a href="{{ route('event', $event->uuid) }}">
                @foreach($event->media as $media)
                    @if($media->name == 'poster')
                        <img src="{{ asset('storage/' . $media->path) }}" alt=""
                            >
                    @endif
                @endforeach
                <div class="des">
                  
                    <h5 class="product-name title">{{ $event->name }}</h5>
                    <p style="font-size: .9rem;"> 
                    <i class="fa-light fa-calendar-days pe-2"></i>
                        {{ $event->eventDates->isNotEmpty() && $event->eventDates->first()->start_date ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d-m-Y') : (\Carbon\Carbon::parse($event->start_date)->format('d-m-Y') ?? 'N/A') }}
                    </p>
                    <p class="city"> <i class="fa-light fa-location-dot pe-2"></i> {{ $event?->area ?? $event->city?->name }}</p>
                    <p> <i class="fa-light fa-tag pe-2"></i>{{ $event->category?->name }}</p>
                  
                    <p
                        ><i class="fa-light fa-user-tie pe-2"></i>{{ $event->company?->company_name ?? $event->organized_by ?? '' }}</p>
                        <div class="price">
                      <i class=="fa-light fa-tag pe-2"></i>
                        <span class="badge">
                            {{ $event->tickets->isNotEmpty() && $event->tickets->first()->price == 0 ? 'Free' : ($event->tickets->isNotEmpty() ? number_format($event->tickets->first()->price, 2) : number_format($event->tickets->first()?->price, 2)) }}
                            <span
                                class="currency text-white fw-bold">{{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price == 0 ? '' : $event->currency->code : '' }}</span>
                        </span>
                        @if(auth()->check())
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
                        @endif
                    </div>
                </div>
            </a>
        </div>
    @endforeach
@else
    <div class="col-12 text-center">
        <img class="empty-data" src="{{ asset('Front/img/empty-data.svg') }}" alt="empty data">
        <p class="mt-3">No events found for the selected date.</p>
    </div>
@endif
