<footer class="footer-section">
    @if($socialGallery)
        <div class="footer-social-gallery">
            <div class="container">
                <div class="social-gallery-header">
                    <h2 class="social-gallery-title">
                        Follow us
                        <span class="instagram-handle">
                            <a href="{{ $socialGallery->instagram_link }}">{{ $socialGallery->instagram_handle }}</a>
                        </span>
                    </h2>
                </div>

                <div class="social-gallery-grid">
                    <!-- Gallery Items -->
                    @foreach($socialGallery->media as $media)
                        <a href="{{ $media->post_url ?? $socialGallery->instagram_link }}" 
                           class="social-gallery-item" 
                           target="_blank"
                           rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $media->path) }}" alt="Beauty post" class="social-post-image"
                                loading="lazy" />
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="footer-content pt-5 pb-3">
            <div class="row">
                <div class="col-xl-4 col-lg-4 mb-50">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="{{ route('home') }}" style="font-size: 30px;font-weight: 700; color:#fff">
                                @foreach($setting->media as $media)
                                    @if($media->name == 'footer_logo')
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="logo">
                                    @endif
                                @endforeach
                            </a>
                        </div>
                        <div class="footer-text">
                            <p>{!! $setting->description !!}</p>
                        </div>
                        <div class="footer-social-icon">
                            <span>Follow us</span>
                            @if(!empty($setting->facebook))
                                <a href="{{ $setting->facebook }}"><i class="fab fa-facebook-f facebook-bg"></i></a>
                            @endif
                            @if(!empty($setting->instagram))
                                <a href="{{ $setting->instagram }}"><i class="fab fa-instagram instagram-bg"></i></a>
                            @endif
                            @if(!empty($setting->phone))
                                <a href="{{ $setting->whats_app }}"><i class="fab fa-whatsapp whatsapp-bg"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 mb-30">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Event Types</h3>
                        </div>
                        <ul>
                            @foreach($most_popular_event_category as $footer_category)
                                <li><a href="{{ route('events_category', $footer_category) }}">
                                        {{ $footer_category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 mb-30">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Useful Links</h3>
                        </div>
                        <ul>
                            @if(Auth::check())
                                <li><a href="{{ route('profile') }}">My Profile</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Login</a></li>
                            @endif
                            <li><a href="{{ route('faq') }}">FAQS</a></li>
                            <li><a href="{{ route('blogs') }}">Blog</a></li>
                            <li><a href="{{ route('contacts') }}">Contact us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Subscribe</h3>
                        </div>
                        <div class="footer-text mb-25">
                            <p>Don't miss to subscribe to our new feeds, kindly fill the form below.</p>
                        </div>
                        <div class="subscribe-form">
                            <form method="post" id="subscribe-forme">
                                @csrf
                                <input type="email" name="email" placeholder="Email">
                                <button id="subscribe-btn"><i class="fab fa-telegram-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                    <div class="copyright-text">
                    <p>Copyright © {{ now()->year }}, All Rights Reserved <a
                                href="{{ route('home') }}">{{ $setting->name }}</a></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                    <div class="footer-menu">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('terms') }}">Terms</a></li>
                            <li><a href="{{ route('privacy') }}">Privacy</a></li>
                            <li><a href="{{ route('contacts') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@include('Frontend.layouts.app_menu')

<style>
    .whatsapp-bg:hover {
        box-shadow: 0 0 15px #25D366;
        transition: box-shadow 0.3s ease-in-out;
    }
</style>

@push('js')

    <script>
        $('#subscribe-forme').submit(function (e) {
            e.preventDefault();
            var email = $('#subscribe-forme input[name="email"]').val();
            $.ajax({
                url: '{{ route('subscribe') }}',
                type: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.message == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Subscribed Successfully',
                        });
                        $('#subscribe-forme').trigger('reset');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                }
            });
        });

    </script>
    <script>
        // Mobile App Menu Active State Management
        document.addEventListener('DOMContentLoaded', function () {
            const menuItems = document.querySelectorAll('.menu-app-item');

            // Remove active class from all items
            menuItems.forEach(item => item.classList.remove('active'));

            // Add active class to current page
            const currentPath = window.location.pathname;
            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && (href === currentPath ||
                    (currentPath.startsWith(href) && href !== '/') ||
                    (currentPath === '/' && href === '{{ route("home") }}'))) {
                    item.classList.add('active');
                }
            });

            // Add click event for mobile menu items
            menuItems.forEach(item => {
                item.addEventListener('click', function (e) {
                    // Remove active from all items
                    menuItems.forEach(i => i.classList.remove('active'));
                    // Add active to clicked item
                    this.classList.add('active');
                });
            });
        });
    </script>
@endpush