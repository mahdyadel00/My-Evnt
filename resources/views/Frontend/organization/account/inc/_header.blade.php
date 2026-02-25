<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $favicon = App\Models\Setting::first()->media->where('name', 'favicon')->first();
    @endphp
    @if($favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon->path) }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('Front') }}/organization/account/img/favicon.png" type="image/x-icon">
    @endif
    <title>
        @yield('title')
    </title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('Front') }}/EventDash/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('Front') }}/EventDash/css/dashboard.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
    <title>@yield('title')</title>
    @yield('css')