@extends('Frontend.layouts.master')
@section('title', 'Company Profile')

@push('css')
<style>
    .social-media-section {
        padding: 15px 0;
        border-top: 1px solid rgba(0,0,0,0.1);
    }
    
    .social-media-section h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }
    
    .social-links a {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
        font-size: 16px;
    }
    
    .social-links a:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .social-links a i {
        margin: 0 !important;
    }
    
    /* Custom colors for each platform */
    .social-links a[title="Facebook"]:hover {
        background-color: #1877f2;
        border-color: #1877f2;
        color: white !important;
    }
    
    .social-links a[title="Twitter"]:hover {
        background-color: #1da1f2;
        border-color: #1da1f2;
        color: white !important;
    }
    
    .social-links a[title="LinkedIn"]:hover {
        background-color: #0077b5;
        border-color: #0077b5;
        color: white !important;
    }
    
    .social-links a[title="Instagram"]:hover {
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        border-color: #dc2743;
        color: white !important;
    }
    
    .social-links a[title="YouTube"]:hover {
        background-color: #ff0000;
        border-color: #ff0000;
        color: white !important;
    }
    
    .social-links a[title="Snapchat"]:hover {
        background-color: #fffc00;
        border-color: #fffc00;
        color: #000 !important;
    }
    
    .social-links a[title="TikTok"]:hover {
        background-color: #000000;
        border-color: #000000;
        color: white !important;
    }
</style>
@endpush

@section('content')
    <!-- section profile -->
    <section id="about-company-section">
        <!-- about left  -->
        <div class="about-company-left">
            @foreach($company->media as $media)
                @if($media->name == 'logo')
                    <a href="{{ asset('storage/' . $media->path) }}" data-lightbox="image-1">
                        <img src="{{ asset('storage/' . $media->path) }}" alt="About Img" />
                    </a>
                @endif
            @endforeach
        </div>
        <!-- about right  -->
        <div class="about-company-right">
            <h1>{{ $company->company_name }}</h1>
            <p>{!! $company->description !!}</p>
            <div class="address">
                <ul>
                    <li {!! !$company->address ? 'class="d-none"' : '' !!}>
                        <span class="address-logo"> <i class="fas fa-paper-plane"></i> </span>
                        <p>Address</p>
                        <span class="saprater">:</span>
                        <p>{!! $company->address !!}</p>
                    </li>
                    <li {!! !$company->phone ? 'class="d-none"' : '' !!}>
                        <span class="address-logo"><i class="fas fa-phone-alt"></i></span>
                        <p>Phone No</p>
                        <span class="saprater">:</span>
                        <a class="text-dark" href="tel:{!! $company->phone !!}">{!! $company->phone !!}</a>
                    </li>
                    <li {!! !$company->email ? 'class="d-none"' : '' !!}>
                        <span class="address-logo"><i class="far fa-envelope"></i></span>
                        <p>Email ID</p>
                        <span class="saprater">:</span>
                        <a class="text-dark" href="mailto:{!! $company->email !!}">{!! $company->email !!}</a>
                    </li>
                    <li {!! !$company->website ? 'class="d-none"' : '' !!}>
                        <span class="address-logo"><i class="fa-solid fa-globe"></i></span>
                        <p>Url Site</p>
                        <span class="saprater">:</span>
                        <a href="{{ $company->website }}" class="text-dark btn btn-outline-primary" target="_blank">
                            Go to website
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
            
            {{-- Social Media Links --}}
            @if($company->facebook || $company->twitter || $company->linkedin || $company->instagram || $company->youtube || $company->snapchat || $company->tiktok)
                <div class="social-media-section mt-4">
                    <h5 class="mb-3">Connect With Us</h5>
                    <div class="social-links d-flex gap-2 flex-wrap">
                        @if($company->facebook)
                            <a href="{{ $company->facebook }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        
                        @if($company->twitter)
                            <a href="{{ $company->twitter }}" target="_blank" class="btn btn-outline-info btn-sm" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        
                        @if($company->linkedin)
                            <a href="{{ $company->linkedin }}" target="_blank" class="btn btn-outline-primary btn-sm" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                        
                        @if($company->instagram)
                            <a href="{{ $company->instagram }}" target="_blank" class="btn btn-outline-danger btn-sm" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        
                        @if($company->youtube)
                            <a href="{{ $company->youtube }}" target="_blank" class="btn btn-outline-danger btn-sm" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                        
                        @if($company->snapchat)
                            <a href="{{ $company->snapchat }}" target="_blank" class="btn btn-outline-warning btn-sm" title="Snapchat">
                                <i class="fab fa-snapchat-ghost"></i>
                            </a>
                        @endif
                        
                        @if($company->tiktok)
                            <a href="{{ $company->tiktok }}" target="_blank" class="btn btn-outline-dark btn-sm" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            
            @if(auth()->check())
                <div class="expertise">
                    <h5>You Can Make Follow Us To See Our Events </h5>
                    <ul>
                        <li id="followBtn">
                            <a href="#" id="{{ auth()->user()->companies->contains($company) ? 'unfollow' : 'follow' }}"
                                data-company-id="{{ $company->id }}"
                                class="btn btn-outline-{{ auth()->user()->companies->contains($company) ? 'danger' : 'primary' }}">
                                <i class="fas fa-user-{{ auth()->user()->companies->contains($company) ? 'minus' : 'plus' }} me-1"></i>
                                {{ auth()->user()->companies->contains($company) ? 'Unfollow' : 'Follow' }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </section>

    <!-- end section profile -->

    <!-- start following Events section -->
    <section class="Events" id="follow-event"
        style="display: {{ auth()->check() && auth()->user()->companies->contains($company) ? 'block' : 'none' }}">
        <h1>follow Events</h1>
        <p>Show New Events </p>
        <div class="product-container">
            <!-- New Event -->
            @foreach($company->events->take(4) as $event)
                <div class="product swiper-slide">
                    <article class="card__article">
                        <a href="{{ url('event/' . $event->uuid) }}">
                            @foreach($event->media as $media)
                                @if($media->name == 'poster')
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="image" class="card__img">
                                @endif
                            @endforeach
                        </a>
                        <div class="card__data">
                            <span
                                class="card__description">{{ \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d M Y') }}
                                - {{ \Carbon\Carbon::parse($event->eventDates->first()->end_date)->format('d M Y') }}</span>
                            <h2 class="card__title">{{ $event->name }}</h2>
                            <span class="card__description">{!! Str::limit($event->description, 50) !!}</span>
                            <span
                                class="text-dark bg-dark bg-opacity-10 rounded-2 text-capitalize px-2 py-1 mb-2 text-start d-inline-block fs-7">{{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price : 0   }}{{ $event->currency?->code }}</span>
                        </div>
                        @php
                            if (auth()->check()) {
                                $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
                            }
                        @endphp
                        @if(auth()->check())
                            <a href="#" class="heart-icon" data-event-id="{{ $event->id }}">
                                <i class="fa-{{ in_array($event->id, $event->is_favourite) ? 'solid' : 'regular' }} fa-heart"></i>
                            </a>
                        @else
                            <a href="#" class="heart-icon"><i class="fa-regular fa-heart"></i></a>
                        @endif
                    </article>
                </div>
            @endforeach
        </div>
    </section>
    <!-- end following Events section -->

    <!-- Gallery feedback  -->
    <section class="gallery-feedback-section">
        <h1>Album Events of Gallery</h1>
        <div class="Album-events">
            @foreach($company->events->take(8) as $event)
                @foreach($event->media as $media)
                    @if($media->name == 'banner')
                        <div class="event-gallery-item" data-event-id="{{ $event->id }}">
                            <img src="{{ asset('storage/' . $media->path) }}" alt="Image 1">
                            <div class="event-Name">{{ $event->name }}</div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>

        <!-- Single Modal for all events -->
        <div class="modal-events" id="gallery-modal" style="display: none;">
            <div class="modal-content-event">
                <span class="close_popup">&times;</span>
                <div class="slideImageEvent"></div>
                <button class="prev_slide">&#10094;</button>
                <button class="next_slide">&#10095;</button>
            </div>
        </div>
    </section>
    <!-- Gallery feedback  -->
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // استخدام Event Delegation للأزرار الديناميكية
            $(document).on('click', '#follow, #unfollow', function (e) {
                e.preventDefault();
                const companyId = $(this).data('company-id');
                const action = $(this).attr('id');
                const button = $(this);

                if (button.prop('disabled')) return;

                button.prop('disabled', true);
                const originalText = button.html();
                button.html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                const url = action === 'follow'
                    ? '{{ route('company.follow', $company->id) }}'
                    : '{{ route('company.unfollow', $company->id) }}';

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        if (data === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: action === 'follow'
                                    ? 'You are now following this company!'
                                    : 'You have unfollowed this company.',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });

                            // Live Update
                            if (action === 'follow') {
                                $('#followBtn').html(
                                    '<a href="#" id="unfollow" data-company-id="' + companyId + '" ' +
                                    'class="btn btn-outline-danger">' +
                                    '<i class="fas fa-user-minus me-1"></i>Unfollow</a>'
                                );
                                $('#follow-event').slideDown(400);
                            } else {
                                $('#followBtn').html(
                                    '<a href="#" id="follow" data-company-id="' + companyId + '" ' +
                                    'class="btn btn-outline-primary">' +
                                    '<i class="fas fa-user-plus me-1"></i>Follow</a>'
                                );
                                $('#follow-event').slideUp(400);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred. Please try again later.'
                            });
                            button.prop('disabled', false);
                            button.html(originalText);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while communicating with the server. Please try again later.'
                        });
                        button.prop('disabled', false);
                        button.html(originalText);
                    }
                });
            });
        });

        // ==================== Event Favorite Handler ====================
        // Note: Favorite functionality is now handled by event-favorite.js
        // No need for custom code here - just use the .heart-icon class and data-event-id attribute

        // ==================== Gallery Modal Handler ====================
        $(document).ready(function () {
            const modal = $('#gallery-modal');
            console.log('Gallery Modal initialized:', modal.length > 0 ? 'Found' : 'NOT FOUND');
            console.log('Gallery items count:', $('.event-gallery-item').length);

            // Test: Click anywhere to verify jQuery is working
            if (modal.length === 0) {
                console.error('ERROR: Gallery modal #gallery-modal not found in DOM!');
            }

            // Open modal and load images when clicking on gallery item
            $('.event-gallery-item').on('click', function (e) {
                e.preventDefault();
                const eventId = $(this).data('event-id');
                
                console.log('Gallery item clicked, Event ID:', eventId);

                // Show modal immediately with loading state
                modal.find('.slideImageEvent').html(
                    '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-white"></i></div>'
                ).addClass('active');
                
                // Show modal with proper CSS classes
                modal.css('display', 'block');
                setTimeout(function() {
                    modal.addClass('show');
                }, 10);
                
                console.log('Modal should be visible now');

                $.ajax({
                    url: '{{ route('event_company_gallery') }}',
                    type: 'GET',
                    data: { event_id: eventId },
                    success: function (response) {
                        console.log('AJAX Response:', response);
                        if (response.media && response.media.length > 0) {
                            modal.data('images', response.media);
                            modal.data('currentIndex', 0);
                            updateImage();
                        } else {
                            modal.find('.slideImageEvent').html(
                                '<p class="text-center text-white py-5">No images found for this event.</p>'
                            );
                            console.warn('No images found for event ID:', eventId);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error, xhr);
                        modal.find('.slideImageEvent').html(
                            '<p class="text-center text-danger py-5">Error loading images. Please try again.</p>'
                        );
                    }
                });
            });

            // Navigate to next image
            $('.next_slide').on('click', function () {
                const images = modal.data('images');
                let currentIndex = modal.data('currentIndex');

                if (images && images.length > 0) {
                    currentIndex = (currentIndex + 1) % images.length;
                    modal.data('currentIndex', currentIndex);
                    updateImage();
                }
            });

            // Navigate to previous image
            $('.prev_slide').on('click', function () {
                const images = modal.data('images');
                let currentIndex = modal.data('currentIndex');

                if (images && images.length > 0) {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    modal.data('currentIndex', currentIndex);
                    updateImage();
                }
            });

            // Close modal
            $('.close_popup').on('click', function (e) {
                e.preventDefault();
                console.log('Close button clicked');
                closeModal();
            });

            // Close modal when clicking outside
            modal.on('click', function (e) {
                if ($(e.target).is('#gallery-modal') || $(e.target).hasClass('modal-events')) {
                    console.log('Clicked outside modal content');
                    closeModal();
                }
            });

            // Close modal function
            function closeModal() {
                modal.removeClass('show');
                setTimeout(function() {
                    modal.css('display', 'none');
                }, 300);
            }

            // Keyboard navigation
            $(document).on('keydown', function (e) {
                if (modal.is(':visible')) {
                    const images = modal.data('images');
                    let currentIndex = modal.data('currentIndex');

                    if (e.key === 'ArrowRight' && images && images.length > 0) {
                        e.preventDefault();
                        currentIndex = (currentIndex + 1) % images.length;
                        modal.data('currentIndex', currentIndex);
                        updateImage();
                        console.log('Next image:', currentIndex + 1, '/', images.length);
                    } else if (e.key === 'ArrowLeft' && images && images.length > 0) {
                        e.preventDefault();
                        currentIndex = (currentIndex - 1 + images.length) % images.length;
                        modal.data('currentIndex', currentIndex);
                        updateImage();
                        console.log('Previous image:', currentIndex + 1, '/', images.length);
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        console.log('Escape pressed - closing modal');
                        closeModal();
                    }
                }
            });

            // Update displayed image
            function updateImage() {
                const images = modal.data('images');
                const currentIndex = modal.data('currentIndex');

                if (images && images.length > 0 && currentIndex >= 0 && currentIndex < images.length) {
                    const image = images[currentIndex];
                    if (image && image.path) {
                        const imageUrl = "{{ asset('storage/') }}" + '/' + image.path;
                        const imageHtml = `
                            <img src="${imageUrl}" alt="Event Image ${currentIndex + 1}" class="card__img">
                            <p class="text-center mt-2 text-white">${currentIndex + 1} / ${images.length}</p>
                        `;
                        modal.find('.slideImageEvent').html(imageHtml).addClass('active');
                    } else {
                        console.error('Invalid image data:', image);
                        modal.find('.slideImageEvent').html(
                            '<p class="text-center text-danger py-5">Invalid image data.</p>'
                        ).addClass('active');
                    }
                }
            }
        });

    </script>
@endpush
