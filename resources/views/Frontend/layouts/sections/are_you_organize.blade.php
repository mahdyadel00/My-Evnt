<section class="section-you-owner">
        <div class="owner-container">
            <div class="owner-content">
                <!-- Background Shapes -->
                <div class="owner-background-shapes">
                    <div class="shape-1"></div>
                    <div class="shape-2"></div>
                    <div class="shape-3"></div>
                </div>

                <!-- Visual Content -->
                <div class="owner-visual-content">
                    <div class="owner-image-container">
                        <div class="owner-mockup">
                            <img src="{{ asset('Front/img/blog.jpg') }}" alt="Business Owner Dashboard" class="owner-image"
                                width="550" height="400" loading="lazy" />
                        </div>
                        <!-- Floating Elements for Enhanced Visual Appeal -->
                        <div class="owner-floating-elements">
                            <div class="floating-element">📊 Dashboard</div>
                            <div class="floating-element">🎯 Easy Management</div>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <div class="owner-text-content">
                    <div class="owner-header">
                        <h2 class="owner-title">Are You Organizer ?</h2>
                        <p class="owner-subtitle">
                            Transform your event management experience with our powerful platform.
                            Easily create and customize your events, manage tickets, and engage with your audience —
                            all from a simple and intuitive dashboard designed to give you full control.
                        </p>
                    </div>
                    <div class="owner-cta">
                        <a href="{{ route('organization') }}" class="owner-button" id="requestDemoBtn"
                            aria-label="View business owner dashboard">
                            <i class="fa-light fa-arrow-right pe-1" aria-hidden="true"></i>
                            Create Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>