<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('Front') }}/EventDash/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('Front') }}/EventDash/css/dashboard.css">

    <title>MyWishlist</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="{{ route('home') }}" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">Event</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="{{ route('my_tickets') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">My Tickets</span>
                </a>
            </li>
            <li class="active">
                <a href="{{ route('my_wishlist') }}">
                    <i class='bx bxs-heart'></i>
                    <span class="text">Wishlist</span>
                </a>
            </li>
    {{--        <li>--}}
    {{--            <a href="{{ route('mailing_list') }}">--}}
    {{--                <i class='bx bxs-envelope'></i>--}}
    {{--                <span class="text">Mailing List</span>--}}
    {{--            </a>--}}
    {{--        </li>--}}
            <li>
                <a href="{{ route('profile') }}">
                    <i class='bx bxs-user-account'></i>
                    <span class="text">Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('contacts') }}">
                    <i class='bx bxs-help-circle'></i>
                    <span class="text">Support</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu logout">
            <li>
                <a href="{{ route('logout') }}" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->
    <!-- CONTENT -->
        <section id="content">
        <!-- NAVBAR -->
        <nav class="navbar">
            <i class='bx bx-menu'></i>
            <a href="{{ route('profile') }}" class="profile">
                @if(auth()->user()->media->isNotEmpty())
                    @foreach(auth()->user()->media as $media)
                        <img src="{{ asset('storage/'.$media->path) }}" alt="">
                    @endforeach
                @else
                    <img src="{{ asset('Front') }}/img/profile.png" alt="">
                @endif
            </a>
        </nav>
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <!-- start breadcrumb -->
            <div class="head-title" style="margin-bottom: 20px;">
                <div class="left">
                    <ul class="breadcrumb" style="margin-bottom: 20px;">
                        <li>
                            <a class="active" href="{{ route('home') }}">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="{{ route('my_wishlist') }}">Wishlist</a>
                        </li>
                    </ul>
                    <h1>Wishlist</h1>
                    <p>Save events to Wishlist if you're interested but choose not to buy tickets yet. You can go back any time
                        and purchase tickets while the sale lasts. </p>
                </div>
            </div>
            <!-- end breadcrumb -->
            <!-- head-search -->
            <div class="wishlist-search-container">
                <div class="wishlist-filter">
                    <select name="category">
                        <option value="" disabled selected>Event Category</option>
                        <option value="all">All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select required>
                        <option value="" disabled selected>Sort By</option>
                        <option value="name-asc">Name(A-Z)</option>
                        <option value="name-desc">Name(Z-A)</option>
                    </select>
                </div>
                <div class="wishlist-search">
                    <input type="text" placeholder="Search">
                </div>
            </div>
            <!-- Events -->
            <div class="container-events">
                <div class="events">
                    @foreach($favourites as $favourite)
                        <div class="product card-event">
                            @foreach($favourite->event->media as $media)
                                @if($media->name == 'poster')
                                    <img src="{{ asset('storage/'.$media->path) }}" alt="">
                                @endif
                            @endforeach
                        <div class="des">
                            <p class="date">
                                {{ $favourite->event->eventDates->isNotEmpty() && $favourite->event->eventDates->first()->start_date
                                ? \Carbon\Carbon::parse($favourite->event->eventDates->first()->start_date)->format('d-m-Y')
                                : (\Carbon\Carbon::parse($favourite->event->start_date)->format('d-m-Y') ?? 'N/A') }}
                            </p>
                            <h5 class="product-name">{{ $favourite->event->name }}</h5>
                            <p>{{ $favourite->event->category?->name }}</p>
                            <div class="price">
                                <span>
                                    {{ $favourite->event->tickets->isNotEmpty() ? $favourite->event->tickets->first()->price == 0 ? 'Free' : $favourite->event->tickets->first()->price : 0 }}
                                    <span class="currency">
                                        {{ $favourite->event->tickets->isNotEmpty() ? $favourite->event->tickets->first()->price == 0 ? '' : $favourite->event->currency->code : '' }}
                                    </span>
                                </span>
                            </div>
                            <div class="social-media">
                                <p>Share this event:</p>
                                <!-- share to face book -->
                                @php
                                    $shareUrlFacebook = "https://www.facebook.com/sharer.php?u=" . urlencode(route('event', $favourite->event->id));
                                    $shareUrlTwitter = "https://twitter.com/intent/tweet?url=" . urlencode(route('event', $favourite->event->id));
                                    $shareUrlInstagram = "https://www.instagram.com/shar.php?u=" . urlencode(route('event', $favourite->event->id));

                                @endphp
                                <a href="{{ $shareUrlFacebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="{{ $shareUrlTwitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="{{ $shareUrlInstagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="actions">
                                <a href="{{ route('event' , $favourite->event->id) }}">More Info</a>
                                <form action="{{ route('unfavourite' , $favourite->event->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-outline-danger">Remove</button>
                                </form>
    {{--                            <a href="{{ route('unfavourite' , $favourite->event->id) }}" class="delete bottom-heart-icon" data-event-id="{{ $favourite->event->id }}">>Remove</a>--}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="pagination" id="pagination"></div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('Front') }}/EventDash/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('.heart-icon').click(function (e) {
                e.preventDefault();
                let event_id = $(this).data('event-id');
                let icon = $(this);
                $.ajax({
                    url: '{{ route('favourite') }}',
                    type: 'POST',
                    data: {
                        event_id: event_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                            });
                            // $('.events').load(location.href + ' .events');
                            //trigger when click on heart icon
                            $('.events').trigger('click');

                            if (icon.hasClass('active')) {
                                icon.removeClass('active');
                            } else {
                                icon.addClass('active');
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'please login first!',
                            });
                        }
                    }
                });
            });
            $('.bottom-heart-icon').click(function (e) {
                e.preventDefault();
                let event_id = $(this).data('event-id');
                let icon = $(this);
                $.ajax({
                    url: '{{ route('favourite') }}',
                    type: 'POST',
                    data: {
                        event_id: event_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                            });
                            $('.events').load(location.href + ' .events');
                            if (icon.hasClass('active')) {
                                icon.removeClass('active');
                            } else {
                                icon.addClass('active');
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'please login first!',
                            });
                        }
                    }
                });
            });
            //get all events by category id by filter
            $('select[name="category"]').change(function () {
                let category_id = $(this).val();
                $.ajax({
                    url: '{{ route('get_events_by_category') }}',
                    type: 'GET',
                    data: {
                        category_id: category_id
                    },
                    success: function (data) {
                        // console.log(data);
                        $.each(data, function (index, event) {
                            let eventHtml = `
                                <div class="product card-event">';
                                let media = $.each(event.media, function (index, media) {
                                    return media.path;
                                });
                                let mediaPath = media[0];
                                    let eventHtml = `
                                    <img src="{{ asset('storage/') }}/` + mediaPath + `" alt="">
                                    <div class="des">
                                        <p class="date">${event.start_date}</p>
                                        <h5 class="product-name">${event.name}</h5>
                                        <p>${event.description}</p>
                                        <div class="price">
                                            <span>${event.price}<span class="currency">${event.currency.name}</span></span>
                                            <div>
                                                <a href="#" class="bottom-heart-icon" data-event-id="${event.id}">
                                                    <i class="fa-regular fa-heart"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="social-media">
                                            <p>Share this event:</p>
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </div>
                                        <div class="actions">
                                            <a href="#">More Info</a>
                                            <a href="#" class="delete bottom-heart-icon" data-event-id="${event.id}">>Remove</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('.events').html(eventHtml);
                            // $('.events').load(location.href + ' .events');
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
