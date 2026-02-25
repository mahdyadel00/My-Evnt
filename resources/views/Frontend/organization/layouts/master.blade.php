<!DOCTYPE html>
<html lang="en">
@php
    $most_popular_event_category = App\Models\EventCategory::orderBy('id', 'desc')->where('parent_id' , null)->take(4)->get();
    $footer_event_category  = App\Models\EventCategory::orderBy('id', 'desc')->take(4)->get();
    $more_event_category    = App\Models\EventCategory::orderBy('id', 'asc')->skip(3)->take(10)->get();
    $header_event_category  = App\Models\EventCategory::orderBy('id', 'asc')->take(3)->get();
    $socialGallery = App\Models\SocialGallery::first();
@endphp
<head>
    <title>@yield('title', 'MyEvnt')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- fave icon -->
    @foreach($setting->media as $media)
        @if($media->name == 'favicon')
            <link rel="icon" type="image/png" href="{{ asset('storage/'.$media->path) }}" />
        @endif
    @endforeach
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/style.css" />
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/swiper-bundle.min.css" />
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
</head>

<body>
<!--<body {{ request()->is('organization_login') || request()->is('organization_register') || request()->is('organization_forgot_password') || request()->is('organization_reset_password/*') ? 'class=auth-organize-background' : '' }}>-->
<!-- header start -->
@include('Frontend.organization.layouts._header')
<!-- end header -->

@yield('organization')
<!-- Start Footer section -->
@include('Frontend.layouts._footer')
<!-- end Footer section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stack('js')
<script src="{{ asset('Front') }}/js/bootstrap.js"></script>
<script src="{{ asset('Front') }}/js/bootstrap.min.js"></script>
<script src="{{ asset('Front') }}/js/swiper-bundle.min.js"></script>
<script src="{{ asset('Front') }}/js/script.js"></script>
<script src="{{ asset('Front') }}/js/login.js"></script>
<script>
    // to select multiple tag in create event
    // id in select
    new MultiSelectTag("category");
</script>
</body>

</html>
