<section class="trusted-section">
            <div class="header">
                <h2 class="title">Our Partners</h2>
                <p class="subtitle">    We collaborate with trusted partners to bring you the best experiences.                </p>
            </div>
            <div class="container">
                <div class="logo-container">
                    <!-- Original logos -->
                    @foreach ($partners as $partner)
                        @foreach ($partner->media as $media)
                            @if ($media->name == 'image')
                                <div class="logo-item">
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $partner->name }}" />
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                    <!-- Duplicated logos for seamless scroll -->
                    @foreach ($partners as $partner)
                        @foreach ($partner->media as $media)
                            @if ($media->name == 'image')
                                <div class="logo-item">
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $partner->name }}" />
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>