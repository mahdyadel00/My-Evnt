@include('Frontend.organization.auth.inc._header')
<!-- SIDEBAR -->
@include('Frontend.organization.auth.inc._sidebar')
<!-- SIDEBAR -->
<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    @include('Frontend.organization.auth._navbar')
    <!-- NAVBAR -->
    <!-- MAIN -->
    <main>
        @yield('content')
    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->
<!-- FOOTER -->
@include('Frontend.organization.auth.inc._footer')
