@if($event_related->count() > 0)
    <section class="Events pt-3">
    <h1>You May Also Like </h1>
    <!-- <p>Show New Events </p> -->
    <div class="product-container">
        <!-- New Event -->
        @foreach($event_related as $event)
        <div class="product swiper-slide">
            <article class="card__article">
                <a href="{{ route('event' , $event->uuid) }}">
                    @forelse($event->media as $media)
                        @if($media->name == 'banner')
                            <img src="{{ asset('storage/'.$media->path) }}" alt="image" class="card__img">
                        @endif
                    @empty
                    <img src="{{ asset('img/card1.png') }}" alt="image" class="card__img">
                    @endforelse
                </a>
                <div class="card__data">
                    <span class="card__description"> {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</span>
                    <h2 class="card__title">{{ $event->name }}</h2>
                    <span class="card__description">{!! STR::limit($event->description , 100) !!}</span>
                    <span class="text-dark bg-dark bg-opacity-10 rounded-2 text-capitalize px-2 py-1 mb-2 text-start d-inline-block fs-7">
                        {{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price == 0 ? 'Free' : $event->tickets->first()->price : 0 }}
                        {{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price == 0 ? '' : $event->currency->code : '' }}
                    </span>
                </div>
                @php
                    if(auth()->check()){
                        $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
                    }
                @endphp
                @if(auth()->check())
                <a href="#" class="heart-icon"
                   data-event-uuid="{{ $event->uuid }}">
                    <i class="fa-{{ in_array($event->id , $event->is_favourite) ? 'solid' : 'regular' }} fa-heart"></i>
                </a>
                @endif
            </article>
        </div>
        @endforeach
    </div>
    <div class="text-center mb-4">
        <a href="{{ route('events') }}" class="btn btn-success"> View More </a>
    </div>
</section>
@endif
