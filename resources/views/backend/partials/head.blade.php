<meta charset="utf-8" />
<meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title')</title>

<meta name="description" content="" />

<!-- Favicon -->
@php
    $setting = \App\Models\Setting::first();
    $favicon = $setting?->media->where('name', 'favicon')->first();
@endphp

@if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon->path) }}" />
@else
    <link rel="icon" type="image/x-icon" href="{{ asset('backend') }}/assets/img/favicon/favicon.ico" />
@endif

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/fonts/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/fonts/tabler-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/fonts/flag-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/fonts/iconify-icons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/css/rtl/core.css') }}"
    class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/css/rtl/theme-default.css') }}"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('/backend/assets/css/demo.css') }}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/libs/node-waves/node-waves.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/libs/typeahead-js/typeahead.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/libs/swiper/swiper.css') }}" />
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet"
    href="{{ asset('/backend/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet"
    href="{{ asset('/backend/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />

<link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet"
    href="{{ asset('backend') }}/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
{{--
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE') }}&libraries=places&callback=initMap"
    async defer></script> --}}
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE') }}&libraries=places&callback=initMap"
    async defer></script>

<!-- <style>
    .dt-buttons .dt-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
        border-radius: 4px;
    }

    /* تصميم خاص لكل زر */
    .dt-buttons .dt-button.buttons-copy {
        background-color: #acacac;
    }

    .dt-buttons .dt-button.buttons-excel {
        background-color: #acacac;
    }

    .dt-buttons .dt-button.buttons-pdf {
        background-color: #acacac;
    }

    .dt-buttons .dt-button.buttons-print {
        background-color: #acacac;
    }

     .dt-buttons .dt-button:hover {
        opacity: 0.8;
    }

    .cke_notification.cke_notification_warning {
        display: none !important;
    }
</style> -->

<!-- Page CSS -->
<link rel="stylesheet" href="{{ asset('/backend/assets/vendor/css/pages/cards-advance.css') }}" />
<!-- Helpers -->
<script src="{{ asset('/backend/assets/vendor/js/helpers.js') }}"></script>

<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
<script src="{{ asset('/backend/assets/vendor/js/template-customizer.js') }}"></script>
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('/backend/assets/js/config.js') }}"></script>