<section class="carousel">
    <!-- list item -->
    <div class="list">
        @foreach ($sliders as $slider)
            <div class="item">
                <a href="{{ $slider->url }}" target="_blank">
                    <div class="overlay"></div>
                    @foreach ($slider->media as $media)
                        @if ($media->name == 'image')
                            @if (!empty($slider->url))
                                <img src="{{ asset('storage/' . $media->path) }}" alt="Slider Image">
                            @else
                                <img src="{{ asset('storage/' . $media->path) }}" alt="Slider Image">
                            @endif
                        @endif
                    @endforeach
                </a>
            </div>
        @endforeach
    </div>
    <!-- list thumnail -->
    @if ($sliders->count() > 1)
        <div class="thumbnail">
            @foreach ($sliders as $slider)
                <div class="item">
                    @foreach ($slider->media as $media)
                        @if ($media->name == 'image_small')
                            <img src="{{ asset('storage/' . $media->path) }}">
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
        <!-- next prev -->
        <div class="arrows">
            <button id="prev" aria-label="Previous">
                <i class="fa-solid fa-angle-left"></i>
            </button>
            <button id="next" aria-label="Next">
                <i class="fa-solid fa-angle-right"></i>
            </button>
        </div>
    @endif
    <!-- time running -->
    <div class="time"></div>
</section>
