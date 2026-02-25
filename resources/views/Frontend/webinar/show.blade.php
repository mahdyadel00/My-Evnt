<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <!-- favicon -->
    <link rel="icon" type="image/png"
        href="{{ asset('storage/' . $setting->media->where('name', 'favicon')->first()->path) }}" />
    <link rel="stylesheet" href="{{ asset('webinar/styles.css') }}" />
    <title>{{ $webinar->webinar_name }} - Webinar</title>
</head>

<body>
    <nav>
        <div class="nav__header">
            <div class="nav__logo">
                <a href="{{ route('webinar.show', $webinar->slug) }}">
                    <img src="{{ asset('webinar/assets/logo.png') }}" alt="logo" />
                    <span>Webinar</span>
                </a>
            </div>
        </div>
    </nav>

    <header class="header__container">
        <div class="header__image">
            @if($webinar->media->where('name', 'image')->first())
                <img src="{{ asset('storage/' . $webinar->media->where('name', 'image')->first()->path) }}" alt="header" />
            @else
                <img src="{{ asset('webinar/assets/header.png') }}" alt="header" />
            @endif
        </div>
        <div class="header__content">
            <h2>{{ $webinar->company_name }}</h2>
            <h1>
                {{ $webinar->webinar_name }}<br />
                <!-- <span class="h1__span-1">{{ $webinar->title }}</span> -->
            </h1>
            <p>{!! $webinar->description !!}</p>

            <!-- time and date -->
            <div class="header__time">
                <div class="time-item">
                    <i class="ri-calendar-line time-icon"></i>
                    <span class="time-text">Date: {{ $webinar->date->format('d/m/Y') }}</span>
                </div>
                <div class="time-item">
                    <i class="ri-time-line time-icon"></i>
                    <span class="time-text">Time: {{ $webinar->time->format('h:i A') }}</span>
                </div>
            </div>

            <div class="header__btn">
                <a href="{{ $webinar->link }}" class="btn" target="_blank">Book Now</a>
            </div>

            <ul class="socials">
                @if($webinar->facebook)
                    <li>
                        <a href="{{ $webinar->facebook }}" title="Follow us on Facebook" target="_blank">
                            <i class="ri-facebook-circle-fill"></i>
                        </a>
                    </li>
                @endif
                @if($webinar->instagram)
                    <li>
                        <a href="{{ $webinar->instagram }}" title="Follow us on Instagram" target="_blank">
                            <i class="ri-instagram-fill"></i>
                        </a>
                    </li>
                @endif
                @if($webinar->youtube)
                    <li>
                        <a href="{{ $webinar->youtube }}" title="Subscribe to our YouTube channel" target="_blank">
                            <i class="ri-youtube-fill"></i>
                        </a>
                    </li>
                @endif
                @if($webinar->linkedin)
                    <li>
                        <a href="{{ $webinar->linkedin }}" title="Follow us on LinkedIn" target="_blank">
                            <i class="ri-linkedin-fill"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </header>

    @if($webinar->aboutwebinars && $webinar->aboutwebinars->count() > 0)
        <section class="about__container">
            <div class="about__image">
                @if($webinar->media->where('name', 'image')->first())
                    <img src="{{ asset('storage/' . $webinar->media->where('name', 'image')->first()->path) }}" alt="about" />
                @else
                    <img src="{{ asset('webinar/assets/header.png') }}" alt="about" />
                @endif
            </div>
            <div class="about__content">
                <h2>{{ $webinar->aboutwebinars->first()->title }}</h2>
                <!-- @foreach($webinar->aboutwebinars as $index => $about)
                        <p>{{ $index + 1 }}- {!! $about->description !!}</p>
                    @endforeach -->
                <p>{!! $webinar->aboutwebinars->first()->description !!}</p>
            </div>
        </section>
    @endif

    <!-- Speakers Section -->
    @if($webinar->speakers && $webinar->speakers->count() > 0)
        <section class="speakers__container">
            <div class="speakers__header">
                <h2>With</h2>
            </div>
            <div class="speakers__grid">
                @foreach($webinar->speakers as $speaker)
                    <div class="speaker__card">
                        <div class="speaker__image">
                            @if($speaker->media->first())
                                <img src="{{ asset('storage/' . $speaker->media->first()->path) }}" alt="{{ $speaker->name }}" />
                            @else
                                <img src="{{ asset('webinar/assets/profile.webp') }}" alt="{{ $speaker->name }}" />
                            @endif
                            <div class="speaker__bg-pattern"></div>
                        </div>
                        <div class="speaker__content">
                            <h3>{{ $speaker->name }}</h3>
                            <p class="speaker__title">{{ $speaker->job_title }}</p>
                            <ul class="speaker__points">
                                <li>{!! trim($speaker->description) !!}</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Features Section -->
    @if($webinar->cards && $webinar->cards->count() > 0)
        <section class="features__container">
            <div class="features__header">
                <h2>Why You Should Register & Attend</h2>
                <p>
                    When you register, you'll instantly get access, reminders, and
                    valuable insights that will help you grow — here's what makes this
                    live webinar special.
                </p>
            </div>

            <div class="features__grid">
                @foreach($webinar->cards as $card)
                    <div class="feature__card">
                        <div class="feature__icon">
                            @if($card->media->first())
                                <img src="{{ asset('storage/' . $card->media->first()->path) }}" alt="{{ $card->title }}"
                                    style="width: 60px; height: 60px;" />
                            @else
                                <i class="ri-star-line"></i>
                            @endif
                        </div>
                        <h3>{{ $card->title }}</h3>
                        <p>{!!  $card->description !!}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="footer__container">
        <div class="footer__content">
            <div class="footer__logo">
                <img src="{{ asset('webinar/assets/logo.png') }}" alt="Logo" />
                <span>Webinar</span>
            </div>
            <div class="footer__copyright">
                <p>
                    Copyright © {{ now()->year }}, All Rights Reserved
                    <a class="footer__link" href="{{ route('home') }}">MyEvent</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('webinar/main.js') }}"></script>
</body>

</html>