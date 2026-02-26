<!DOCTYPE html>
<html lang="en" dir="ltr">
@php
    $most_popular_event_category = App\Models\EventCategory::whereHas('events', function ($query) {
        $query->where('view_count', '>', 0);
    })
        ->where('parent_id', null)
        ->take(4)
        ->get();
    $footer_event_category = App\Models\EventCategory::orderBy('id', 'desc')->take(4)->get();
    $more_event_category = App\Models\EventCategory::orderBy('id', 'asc')
        ->where('parent_id', null)
        ->skip(3)
        ->take(10)
        ->get();
    $event_category = App\Models\EventCategory::where('is_parent', true)->take(9)->get();
    $cities = App\Models\City::where('is_available', true)
    ->whereHas('events', function ($query) {
        $query->where('is_active', true);
    })
    ->get();
    $socialGallery = App\Models\SocialGallery::first();
@endphp

@php
    // Generate SEO meta tags
    $seoService = app(\App\Services\SeoService::class);
    
    // Check if we're on an event page
    if (isset($event) && $event instanceof \App\Models\Event) {
        $seoMeta = $seoService->generateEventMetaTags($event);
    } else {
        // For general pages, use general SEO or check for custom SEO variables
        $seoTitle = $seo_title ?? null;
        $seoDescription = $seo_description ?? null;
        $seoKeywords = $seo_keywords ?? null;
        $seoImage = $seo_image ?? null;
        $seoUrl = $seo_url ?? null;
        
        $seoMeta = $seoService->generateGeneralMetaTags(
            title: $seoTitle,
            description: $seoDescription,
            keywords: $seoKeywords,
            image: $seoImage,
            url: $seoUrl
        );
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>{{ $seoMeta['title'] }}</title>
    <meta name="title" content="{{ $seoMeta['title'] }}" />
    <meta name="description" content="{{ $seoMeta['description'] }}" />
    <meta name="keywords" content="{{ $seoMeta['keywords'] }}" />
    <meta name="author" content="{{ $seoMeta['site_name'] }}" />
    <meta name="robots" content="index, follow" />
    <meta name="language" content="Arabic" />
    <meta name="revisit-after" content="7 days" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $seoMeta['type'] }}" />
    <meta property="og:url" content="{{ $seoMeta['url'] }}" />
    <meta property="og:title" content="{{ $seoMeta['title'] }}" />
    <meta property="og:description" content="{{ $seoMeta['description'] }}" />
    @if($seoMeta['image'])
    <meta property="og:image" content="{{ $seoMeta['image'] }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="{{ $seoMeta['title'] }}" />
    @endif
    <meta property="og:site_name" content="{{ $seoMeta['site_name'] }}" />
    <meta property="og:locale" content="ar_AR" />
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ $seoMeta['url'] }}" />
    <meta name="twitter:title" content="{{ $seoMeta['title'] }}" />
    <meta name="twitter:description" content="{{ $seoMeta['description'] }}" />
    @if($seoMeta['image'])
    <meta name="twitter:image" content="{{ $seoMeta['image'] }}" />
    <meta name="twitter:image:alt" content="{{ $seoMeta['title'] }}" />
    @endif
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seoMeta['url'] }}" />
    
    <!-- Alternate Language -->
    <link rel="alternate" hreflang="ar" href="{{ $seoMeta['url'] }}" />
    <link rel="alternate" hreflang="x-default" href="{{ $seoMeta['url'] }}" />
    <!-- fave icon -->
    @foreach ($setting->media as $media)
        @if ($media->name == 'favicon')
            <link rel="icon" type="image/png" href="{{ asset('storage/' . $media->path) }}" />
        @endif
    @endforeach
    <!-- Meta Pixel Code -->
    <script defer>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-W4CWLZQK');
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/style.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/login.css" />
    <!-- {{-- font awesome --}} -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/all.min.css" />
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/swiper-bundle.min.css" />
    <!-- Event Favorite Styles -->
    <!-- Google Sign In -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
        <!-- Flatpickr  -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"/>

    <style>
        /* new slider */
        /* carousel */
        .carousel {
            height: 100vh;
            /* margin-top: -50px; */
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .carousel .list .item {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0 0 0 0;
        }

        .item .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background: rgba(0, 0, 0, 0.2); */
            z-index: 0;
        }

        .carousel .list .item img {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
        }

        .carousel .list .item .content {
            position: absolute;
            top: 20%;
            width: 1140px;
            max-width: 80%;
            left: 50%;
            transform: translateX(-50%);
            padding-right: 30%;
            box-sizing: border-box;
            color: #fff;
            text-shadow: 0 5px 10px transparent;
        }

        .carousel .list .item .author {
            font-weight: bold;
            letter-spacing: 10px;
        }

        .carousel .list .item .title,
        .carousel .list .item .topic {
            font-size: 4em;
            font-weight: bold;
            line-height: 1.1em;
            margin-bottom: 5px;
        }

        .carousel .list .item .topic {
            color: #f1683a;
        }

        .carousel .list .item .buttons {
            /* display: grid;
            grid-template-columns: repeat(2, 130px);
            grid-template-rows: 40px; */
            /* gap: 5px; */
            margin-top: 25px;
        }

        .carousel .list .item .buttons a {
            border: none;
            background-color: #d3682e;
            color: #fff;
            font-family: Poppins;
            font-weight: 500;
            cursor: pointer;
            z-index: 999;
            border-radius: 10px;
            padding: 12px 25px;
        }

        .carousel .list .item .buttons button:nth-child(2) {
            background-color: transparent;
            border: 1px solid #fff;
            color: #eee;
            border-radius: 10px;
        }

        /* thumbail */
        .thumbnail {
            position: absolute;
            bottom: 20px;
            left: 50%;
            width: max-content;
            z-index: 9;
            display: flex;
            gap: 20px;
        }

        .thumbnail .item {
            width: 150px;
            height: 150px;
            flex-shrink: 0;
            position: relative;
        }

        .thumbnail .item img {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
            border-radius: 15px;
        }

        .thumbnail .item .content {
            color: #fff;
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
        }

        .thumbnail .item .content .title {
            font-weight: 600;
        }

        .thumbnail .item .content .description {
            font-weight: 500;
        }

        .carousel .list .item:nth-child(1) .content .des {
            font-size: 20px !important;
            text-align: justify;
        }


        /* arrows */
        .arrows {
            position: absolute;
            bottom: 9%;
            left: 20%;
            transform: translateX(-50%);
            z-index: 9;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .arrows button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f1683a;
            border: none;
            color: #fff;
            font-family: monospace;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease, color 0.3s ease;
            cursor: pointer;
        }

        .arrows button:hover {
            background-color: #fff;
            color: #000;
        }

        .arrows button i {
            font-size: 22px;
        }


        /* animation */
        .carousel .list .item:nth-child(1) {
            z-index: 1;
        }

        /* animation text in first item */

        .carousel .list .item:nth-child(1) .content .author,
        .carousel .list .item:nth-child(1) .content .title,
        .carousel .list .item:nth-child(1) .content .topic,
        .carousel .list .item:nth-child(1) .content .des,
        .carousel .list .item:nth-child(1) .content .buttons {
            transform: translateY(50px);
            filter: blur(20px);
            opacity: 0;
            animation: showContent .5s .5s linear 1 forwards;
        }

        @keyframes showContent {
            to {
                transform: translateY(0px);
                filter: blur(0px);
                opacity: 1;
            }
        }

        .carousel .list .item:nth-child(1) .content .title {
            animation-delay: .5s !important;
        }

        .carousel .list .item:nth-child(1) .content .topic {
            animation-delay: .5s !important;
        }

        .carousel .list .item:nth-child(1) .content .des {
            animation-delay: .6s !important;
            font-size: 16px;
        }

        .carousel .list .item:nth-child(1) .content .buttons {
            animation-delay: .5s !important;
        }

        /* create animation when next click */
        .carousel.next .list .item:nth-child(1) img {
            width: 150px;
            height: 200px;
            position: absolute;
            bottom: 50px;
            left: 50%;
            border-radius: 30px;
            animation: showImage .5s linear 1 forwards;
        }

        @keyframes showImage {
            to {
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border-radius: 0;
            }
        }

        .carousel.next .thumbnail .item:nth-last-child(1) {
            overflow: hidden;
            animation: showThumbnail .5s linear 1 forwards;
        }

        .carousel.prev .list .item img {
            z-index: 100;
        }

        @keyframes showThumbnail {
            from {
                width: 0;
                opacity: 0;
            }
        }

        .carousel.next .thumbnail {
            animation: effectNext .5s linear 1 forwards;
        }

        @keyframes effectNext {
            from {
                transform: translateX(150px);
            }
        }

        /* running time */

        .carousel .time {
            position: absolute;
            z-index: 9;
            width: 0%;
            height: 5px;
            background-color: #f1683a;
            left: 0;
            top: 0;
        }

        .carousel.next .time,
        .carousel.prev .time {
            animation: runningTime .5s linear 1 forwards;
        }

        @keyframes runningTime {
            from {
                width: 100%
            }

            to {
                width: 0
            }
        }


        /* prev click */

        .carousel.prev .list .item:nth-child(2) {
            z-index: 2;
        }

        .carousel.prev .list .item:nth-child(2) img {
            animation: outFrame 0.5s linear 1 forwards;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        @keyframes outFrame {
            to {
                width: 150px;
                height: 220px;
                bottom: 50px;
                left: 50%;
                border-radius: 20px;
            }
        }

        .carousel.prev .thumbnail .item:nth-child(1) {
            overflow: hidden;
            opacity: 0;
            animation: showThumbnail .5s linear 1 forwards;
        }

        .carousel.next .arrows a,
        .carousel.prev .arrows a {
            pointer-events: none;
        }

        .carousel.prev .list .item:nth-child(2) .content .author,
        .carousel.prev .list .item:nth-child(2) .content .title,
        .carousel.prev .list .item:nth-child(2) .content .topic,
        .carousel.prev .list .item:nth-child(2) .content .des,
        .carousel.prev .list .item:nth-child(2) .content .buttons {
            animation: contentOut .5s linear 1 forwards !important;
        }

        @keyframes contentOut {
            to {
                transform: translateY(-150px);
                filter: blur(20px);
                opacity: 0;
            }
        }

        @media screen and (max-width: 1200px) {
            .carousel {
                height: 65vh;
            }

            .arrows button {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }

            .arrows button i {
                font-size: 15px;
            }

        }

        @media screen and (max-width: 678px) {
            .carousel .list .item .content {
                padding-right: 0;
            }

            .carousel {
                height: 65vh;
            }

            .carousel .list .item .content {
                top: 13%;
                max-width: 90%;
            }

            .thumbnail .item {
                height: 100px;
                width: 100px;
            }

            .carousel .list .item .title,
            .carousel .list .item .topic {
                font-size: 3em;
                margin-bottom: 0px;
            }

            .carousel .list .item:nth-child(1) .content .des {
                font-size: 17px !important;
            }
        }

        @media screen and (max-width:385px) {
            /* .carousel {
                height: 85vh;
            } */

            .carousel .list .item .content {
                top: 8%;
            }

            .carousel .list .item .title,
            .carousel .list .item .topic {
                font-size: 2.5em;
            }
        }

        @media screen and (max-width: 500px) {
            .carousel {
                height: 35vh;

            }
        }

        /* new slider */
    </style>
</head>

<body>
    <!--<body {{ request()->is('login') || request()->is('register') || request()->is('forgot-password') || request()->is('reset-password/*') ? 'class=auth-background' : '' }}>-->
    <!-- header start -->
    @include('Frontend.layouts._header', get_defined_vars())
    <!-- end header -->

    @yield('content')
    <!-- Start Footer section -->
    @include('Frontend.layouts._footer')
    <!-- end Footer section -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W4CWLZQK" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!--<script src="https://myartica.polygonsoftware.tech/bot/iframe.js"></script>-->

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('js')
    <script src="{{ url('Front') }}/js/bootstrap.js"></script>
    <script src="{{ url('Front') }}/js/bootstrap.min.js"></script>
    <script src="{{ url('Front') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ url('Front') }}/js/script.js"></script>
    <script src="{{ url('Front') }}/js/login.js"></script>
    <!-- Event Favorite Handler - Reusable across all pages -->
    <script src="{{ url('Front') }}/js/event-favorite.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>
