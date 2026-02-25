@include('Frontend.organization.account._header')

<body>
    <!-- SIDEBAR -->
    @include('Frontend.organization.auth._sidebar')
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        @include('Frontend.organization.account._navbar')
        <!-- NAVBAR -->
        <!-- MAIN -->
        <main>
            <!-- start breadcrumb  -->
            <div class="head-title" style="margin-bottom: 20px; ">
                <div class="left">
                    <ul class="breadcrumb" style="margin-bottom: 20px;">
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="#">My Events </a>
                        </li>
                    </ul>
                    <h1>My Events </h1>
                </div>
                <a href="createEventstep1.html" class="btn btn-primary">Create New Event</a>
            </div>
            <!-- end breadcrumb  -->
            <!-- search part -->
            <div style="width: 100%; " class="row d-flex justify-content-between flex-wrap align-items-baseline">
                <div class="col-md-3 m-2 ">
                    <select class="form-select p-2">
                        <option selected>Sorted by</option>
                        <option>Status</option>
                        <option>Data</option>
                    </select>
                </div>
                <div class="col-md-3 m-2">
                    <form action="{{ route('organization.events.my_events') }}" method="get">
                        <input class="form-control p-2" placeholder="Search...." name="search" type="text" id="search">
                    </form>
                </div>
            </div>
            <!-- start form-data -->
            <div class="form-data">
                <!-- start event details -->
                @foreach($events as $event)
                    <div class="row">
                        <div class="card mb-3">
                            <div class="row g-0 p-2">
                                <!-- image poster -->
                                <div class="col-md-2">
                                    @foreach($event->media as $media)
                                        @if($media->name == 'poster')
                                            <img style="width: 250px;height: auto;" src="{{ asset('storage/' . $media->path) }}"
                                                class="img-fluid rounded-1" alt="">
                                        @endif
                                    @endforeach
                                </div>
                                <!-- data event all  -->
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h4 style="font-weight: 700;">{{ $event->name }}</h4>
                                        <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                                            <i class='bx bxs-calendar-event p-2'></i>
                                            <p>{{ $event->start_date->format('d-m-Y') }} <span
                                                    class="p-2">{{ Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                                            </p>
                                            <p> , {{ $event->end_date->format('d-m-Y') }} <span
                                                    class="p-2">{{ Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- details event actions  -->
                                    <div class="row">
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Price</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ $event->price }}</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Available</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">{{ $event->limit_quantity }}</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center">
                                            <p class="card-text">Sold</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Views</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Income</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">In Cart</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Scans</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                        <div class="col-md-4 col-6 text-center mt-2 mb-2">
                                            <p class="card-text">Status</p>
                                            <span
                                                style="padding: 5px 10px;border-radius: 5px;background-color: #cccccc54;">0</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end m-2">
                                        <a href="#" class="btn-outline-website">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- end event details -->
                <!-- pagination -->
                <div aria-label="Page navigation example">
                    {{-- <ul class="pagination">--}}
                        {{-- @if($events->previousPageUrl())--}}
                        {{-- <li class="page-item">--}}
                            {{-- <a class="page-link" href="{{ $events->previousPageUrl() }}" aria-label="Previous">--}}
                                {{-- <span aria-hidden="true">«</span>--}}
                                {{-- </a>--}}
                            {{-- </li>--}}
                        {{-- @for($i = 1; $i <= $events->lastPage(); $i++)--}}
                            {{-- <li class="page-item"><a class="page-link" href="{{ $events->url($i) }}">{{ $i }}</a>
                            </li>--}}
                            {{-- @endfor--}}
                            {{-- <li class="page-item">--}}
                                {{-- <a class="page-link" href="{{ $events->nextPageUrl() }}" aria-label="Next">--}}
                                    {{-- <span aria-hidden="true">»</span>--}}
                                    {{-- </a>--}}
                                {{-- </li>--}}
                            {{-- @endif--}}
                            {{-- </ul>--}}
                </div>
            </div>
            <!-- end form-data -->
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
    <script src="{{ asset('Front') }}/EventDash/js/script.js"></script>
    <script>
        new MultiSelectTag("category");
    </script>
    <script>
        //search form when write in input
        $('#search').on('keyup', function () {
            $('#search').submit();
        });
    </script>
</body>

</html>