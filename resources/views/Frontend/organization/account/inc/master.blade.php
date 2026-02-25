@include('Frontend.organization.account.inc._header')

<body>
<!-- SIDEBAR -->
@include('Frontend.organization.layouts._sidebar')
<!-- SIDEBAR -->
<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    @include('Frontend.organization.account.inc._navbar')
    <!-- NAVBAR -->
    <main>
        @yield('content')
    </main>
</section>
<!-- CONTENT -->
<!-- FOOTER -->
@include('Frontend.organization.account.inc._footer')
<!-- FOOTER -->
