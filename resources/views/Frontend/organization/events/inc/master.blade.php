@include('Frontend.organization.events.inc._header')
<body>
<!-- SIDEBAR -->
    @include('Frontend.organization.events.inc._sidebar')
<!-- SIDEBAR -->
<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    @include('Frontend.organization.events.inc._navbar')
    <!-- NAVBAR -->
    <!-- MAIN -->
    <main>
        @yield('content')
    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->
@include('Frontend.organization.events.inc._footer')
