<section class="categories-container-event">
        <div class="header">
            <h2 class="title">Our Categories</h2>
        </div>
        <div class="categories-list-event">
            <!-- Example Category Items -->
            @foreach ($event_category as $category)
                <a href="{{ route('events_category', $category->id) }}" class="category-link-event">
                    <div class="category-item-event">
                        <div class="category-icon-event">
                            @foreach ($category->media as $media)
                                <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $category->name }}"
                                    class="category-image-event" />
                            @endforeach
                        </div>
                        <div class="category-name-event">{{ $category->name }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>