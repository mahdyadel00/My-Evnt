<section class="gallery-feedback-section">
    <h1 class="text-center text-white">Event Gallery</h1>
    <div class="container">
        <div class="row">
            <div class="gallery-wrapper" id="gallery1">
                <div class="gallery-inner">
                    @foreach($event->media->take(8) as $media)
                        @if($media->type == 'gallery')
                            <div class="gallery-img">
                                <img src="{{ asset('storage/'.$media->path) }}" alt="gallery image">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


