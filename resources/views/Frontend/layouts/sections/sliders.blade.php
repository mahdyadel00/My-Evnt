<article class="carousel-neblue-event-body">
    <div class="carousel-neblue-event-carousel">
        <div class="carousel-neblue-event-list">
            @foreach ($sliders as $slider)
                <div class="carousel-neblue-event-item {{ $loop->first ? 'carousel-neblue-event-active' : '' }}">
                    <a href="{{ !empty($slider->url) ? $slider->url : '#' }}" class="carousel-neblue-event-link" {{ !empty($slider->url) ? 'target="_blank"' : '' }}>
                        @foreach ($slider->media as $media)
                            @if ($media->name == 'image')
                                <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $slider->title ?? 'Slider' }}" class="carousel-neblue-event-image" />
                                @break
                            @endif
                        @endforeach
                    </a>
                </div>
            @endforeach
        </div>

        @if ($sliders->count() > 1)
            <div class="carousel-neblue-event-arrows">
                <button class="carousel-neblue-event-arrow-prev" type="button" aria-label="Previous">
                    <i class="fa-solid fa-angle-left"></i>
                </button>
                <button class="carousel-neblue-event-arrow-next" type="button" aria-label="Next">
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            </div>

            <div class="carousel-neblue-event-progress">
                <div class="carousel-neblue-event-progress-bar"></div>
            </div>
        @endif
    </div>
</article>
