<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Events')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/font-awesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/login.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front/css/filteration.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    @stack('css')
</head>
<body>
    @yield('content')

    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('Front/js/filter.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var dateEl = document.getElementById("nebule-event-offcanvas-date");
            if (dateEl && typeof flatpickr !== "undefined") {
                flatpickr("#nebule-event-offcanvas-date", {
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            }
            var form = document.getElementById("nebule-event-filter-form");
            if (form) {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var applyBtn = form.querySelector(".nebule-event-offcanvas-apply-btn");
                    if (applyBtn) applyBtn.click();
                });
            }
        });
    </script>
    @stack('js')
</body>
</html>
