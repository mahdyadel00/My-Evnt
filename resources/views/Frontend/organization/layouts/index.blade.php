@extends('Frontend.organization.layouts.master')
@section('organization')
<!-- start home section -->
<section>
    <div class="home-organizer-part">
        <video muted="" loop="" autoplay="">
            <source src="{{ $organization_slider->video }}" type="video/mp4">
        </video>
        <div class="home-content-organizer">
            <h1><span>{{ $organization_slider->title }}</span></h1>
            <p class="col-md-6">{{ $organization_slider->description }}</p>
            <div class="buttons-home d-flex justify-content-center align-items-baseline ">
                @if(auth()->guard('company')->check())
                <a href="{{ route('create_event') }}" class="button-event">Create Event</a>
                @else
                <a href="{{ route('organization_login') }}" class="button-event">Create Event</a>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- end home section -->

<!-- start customer section -->
<!-- <section class="section-customers" id="customers">
    <div class="container">
        <div class="row justify-content-center text-center ">
            <div class="col-md-10 col-lg-8">
                <div class="header-section">
                    <h2 class="title">Who Are Our <span>Customers</span></h2>
                    <p class="description">There are many variations of passages of Lorem Ipsum available but the
                        majority have
                        suffered alteration in some injected humour</p>
                </div>
            </div>
        </div>
        <div id="customer-area">
            <div class="customer-wrapper">
                <div class="customer-box-area">
                    @foreach($customers as $customer)
                    <div class="customer-box">
                        @foreach($customer->media as $media)
                        @if($media->name == 'cover')
                        <img src="{{ asset('storage/'.$media->path) }}">
                        @endif
                        @endforeach
                        <div class="overlay">
                            <h3>{{ $customer->title }}</h3>
                            <p>{!! $customer->description !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- end customer section -->

<!-- start benefits section  -->
<section class="section_all" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section_title_all text-center">
                    <h3 class="font-weight-bold title">Why myevnt</h3>
                </div>
            </div>
        </div>

        <!-- single benefits -->
        <div class="row vertical_content_manage column-reverse  mt-2 mb-5">
            <div class="col-lg-6">
                <div class="about_header_main mt-3">
                    <div class="about_icon_box">
                        <p class="text_custom font-weight-bold">1</p>
                    </div>
                    <h4 class="about_heading text-capitalize font-weight-bold">Event Constructort</h4>
                    <p class="text-muted mt-3">
                        My-Event's Create New Event tool will help you make attractive event listings and start
                        selling tickets
                        online.
                    </p>
                    <p class="text-muted mt-3"> Follow a couple of simple steps, fill in event information, and post
                        your event
                        to our catalog in
                        a few minutes.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img_about mt-3">
                    <img src="https://i.ibb.co/qpz1hvM/About-us.jpg" alt="" class="img-fluid mx-auto d-block">
                </div>
            </div>
        </div>
        <!-- single benefits -->
        <div class="row vertical_content_manage  mt-2 mb-5">
            <div class="col-lg-6 d-flex">
                <div class="img_about mt-3 ">
                    <img src="https://i.ibb.co/qpz1hvM/About-us.jpg" alt="" class="img-fluid mx-auto d-block">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about_header_main mt-3">
                    <div class="about_icon_box">
                        <p class="text_custom font-weight-bold">2</p>
                    </div>
                    <h4 class="about_heading text-capitalize font-weight-bold">Smart Event Management </h4>
                    <p class="text-muted mt-3">
                        Use our event ticketing system to automate your sales. Draft event listings in advance and
                        instantly edit
                        events that are already published.
                    </p>
                    <p class="text-muted mt-3"> You will have 24/7 access to detailed ticket sales statistics, as
                        well as
                        technical support.</p>
                </div>
            </div>
        </div>

        <!-- Security Policy -->

        <div class="row mt-3">
            <!-- Customer Security -->
            <div class="col-lg-4">
                <div class="about_content_box_all mt-3">
                    <div class="about_detail text-center">
                        <div class="about_icon">
                            <i class="fa-solid fa-file-shield"></i>
                        </div>
                        <h5 class="text-dark text-capitalize mt-3 font-weight-bold">Customer Security</h5>
                        <p class="edu_desc mt-3 mb-0 text-muted">All collected customer information is protected and
                            encrypted
                        </p>
                    </div>
                </div>
            </div>
            <!-- Data Security -->
            <div class="col-lg-4">
                <div class="about_content_box_all mt-3">
                    <div class="about_detail text-center">
                        <div class="about_icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h5 class="text-dark text-capitalize mt-3 font-weight-bold">Data Security</h5>
                        <p class="edu_desc mb-0 mt-3 text-muted">Data is not transferred or used by third
                            parties </p>
                    </div>
                </div>
            </div>

            <!-- Best Solutions -->
            <div class="col-lg-4">
                <div class="about_content_box_all mt-3">
                    <div class="about_detail text-center">
                        <div class="about_icon">
                            <i class="fa-solid fa-handshake-angle"></i>
                        </div>
                        <h5 class="text-dark text-capitalize mt-3 font-weight-bold">Best Solutions </h5>
                        <p class="edu_desc mb-0 mt-3 text-muted">Solution is universal for all players in the
                            cultural and
                            entertainment market
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end benefits section  -->
<!-- start section How it works -->
<section class="section-how-work" id="how-it-works">
    <div class="container">
        <div class="row justify-content-center text-center ">
            <div class="col-md-10 col-lg-8">
                <div class="header-section">
                    <h2 class="title">How it <span>Works</span></h2>
                    <p class="description">My-Event was created so that giving people new experiences would also
                        bring you
                        profit. With a clear plan, this ticket selling platform will make sure you get great
                        results. </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-how-it-works">
        <div class="timeline">
            <ul>
                <li>
                    <div class="timeline-content">
                        <h3 class="date">Step 1</h3>
                        <h1>Sign Up & List Your Event</h1>
                        <p>It only takes 10 minutes to start selling tickets online for your next event. . </p>
                    </div>
                </li>
                <li>
                    <div class="timeline-content">
                        <h3 class="date">Step 2</h3>
                        <h1>Set Prices And Allocate Quotas </h1>
                        <p>Create as many ticket types as you need and set the price for each one . </p>
                    </div>
                </li>
                <li>
                    <div class="timeline-content">
                        <h3 class="date">Step 3</h3>
                        <h1>Take Your Money </h1>
                        <p>Proceeds from each ticket sold go directly to your bank account . </p>
                    </div>
                </li>
                <li>
                    <div class="timeline-content">
                        <h3 class="date">Step 4</h3>
                        <h1>Analyze Your Sales </h1>
                        <p>Improve your results with each new event listing you create . </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center m-4">
        @if(auth()->guard('company')->check())
        <a href="{{ route('create_event') }}" class="button-event">Create Event</a>
        @else
        <a href="{{ route('organization_login') }}" class="button-event">Create Event</a>
        @endif
    </div>
</section>
<!-- end section How it works -->

{{--    <!-- start section pricing  -->--}}
{{--
<section class="section-pricing">--}}
    {{--
    <div class="row justify-content-center text-center ">--}}
        {{--
        <div class="col-md-10 col-lg-8">--}}
            {{--
            <div class="header-section">--}}
                {{-- <h2 class="title"><span>pricing</span></h2>--}}
                {{--
            </div>
            --}}
            {{--
        </div>
        --}}
        {{--
    </div>
    --}}
    {{--
    <div class="wrapper">--}}
        {{--
        <div class="table basic">--}}
            {{--
            <div class="price-section">--}}
                {{--
                <div class="price-area">--}}
                    {{--
                    <div class="inner-area">--}}
                        {{-- <span class="text">$</span>--}}
                        {{-- <span class="price">{{ $package_basic->price_monthly }}</span>--}}
                        {{--
                    </div>
                    --}}
                    {{--
                </div>
                --}}
                {{--
            </div>
            --}}
            {{-- <p style="text-align: center;padding: 15px 0 ; font-size: 20px">{{ $package_basic->title }}</p>--}}
            {{--
            <ul class="features">--}}
                {{-- @foreach($package_basic->features as $feature)--}}
                {{--
                <li>--}}
                    {{-- <span class="list-name">{{ $feature->title }}</span>--}}
                    {{-- <span class="icon {{ $feature->status ? 'check' : 'cross' }}">--}}
{{--                                <i class="fas fa-{{ $feature->status ? 'check' : 'times' }}"></i></span>--}}
                    {{--
                </li>
                --}}
                {{-- @endforeach--}}
                {{--
            </ul>
            --}}
            {{--
            <div class="btn">--}}
                {{--
                <button>Get Started</button>
                --}}
                {{--
            </div>
            --}}
            {{--
        </div>
        --}}
        {{--
        <div class="table premium">--}}
            {{--
            <div class="ribbon"><span>Recommend</span></div>
            --}}
            {{--
            <div class="price-section">--}}
                {{--
                <div class="price-area">--}}
                    {{--
                    <div class="inner-area">--}}
                        {{-- <span class="text">$</span>--}}
                        {{-- <span class="price">{{ $package_pro->price_monthly }}</span>--}}
                        {{--
                    </div>
                    --}}
                    {{--
                </div>
                --}}
                {{--
            </div>
            --}}
            {{-- <p style="text-align: center;padding: 15px 0 ; font-size: 20px">{{ $package_pro->title }}</p>--}}
            {{--
            <ul class="features">--}}
                {{-- @foreach($package_pro->features as $feature)--}}
                {{--
                <li>--}}
                    {{-- <span class="list-name">{{ $feature->title }}</span>--}}
                    {{-- <span class="icon {{ $feature->status ? 'check' : 'cross' }}">--}}
{{--                                <i class="fas fa-{{ $feature->status ? 'check' : 'times' }}"></i></span>--}}
                    {{--
                </li>
                --}}
                {{-- @endforeach--}}
                {{--
            </ul>
            --}}
            {{--
            <div class="btn">--}}
                {{--
                <button>Get Started</button>
                --}}
                {{--
            </div>
            --}}
            {{--
        </div>
        --}}
        {{--
        <div class="table ultimate">--}}
            {{--
            <div class="price-section">--}}
                {{--
                <div class="price-area">--}}
                    {{--
                    <div class="inner-area">--}}
                        {{-- <span class="text">$</span>--}}
                        {{-- <span class="price">{{ $package_ultimate->price_monthly }}</span>--}}
                        {{--
                    </div>
                    --}}
                    {{--
                </div>
                --}}
                {{--
            </div>
            --}}
            {{-- <p style="text-align: center;padding: 15px 0 ; font-size: 20px">{{ $package_ultimate->title }}</p>--}}
            {{--
            <ul class="features">--}}
                {{-- @foreach($package_ultimate->features as $feature)--}}
                {{--
                <li>--}}
                    {{-- <span class="list-name">{{ $feature->title }}</span>--}}
                    {{-- <span class="icon {{ $feature->status ? 'check' : 'cross' }}">--}}
{{--                                <i class="fas fa-{{ $feature->status ? 'check' : 'times' }}"></i></span>--}}
                    {{--
                </li>
                --}}
                {{-- @endforeach--}}
                {{--
            </ul>
            --}}
            {{--
            <div class="btn">--}}
                {{--
                <button>Get Started</button>
                --}}
                {{--
            </div>
            --}}
            {{--
        </div>
        --}}
        {{--
    </div>
    --}}
    {{--
</section>--}}
{{--    <!-- end section pricing  -->--}}

<!-- start section blogs  -->
<!--    <section class="section-blogs">-->
<!--        <div class="row justify-content-center text-center ">-->
<!--            <div class="col-md-10 col-lg-8">-->
<!--                <div class="header-section">-->
<!--                    <h2 class="title"><span>Blogs</span></h2>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="section-blog">-->
<!--            <div class="blog-container">-->
<!--                <div class="swiper-container">-->
<!--                    <div class="swiper-wrapper">-->
<!--                        @foreach($blogs as $blog)-->
<!--                            <div class="swiper-slide">-->
<!--                                <div class="card-wrapper">-->
<!--                                    <div class="card-wrapper-thumb">-->
<!--                                        @foreach($blog->media as $media)-->
<!--                                            <img src="{{ asset('storage/'.$media->path) }}" alt="blog-img">-->
<!--                                        @endforeach-->
<!--                                        <span class="blog-name">{{ $blog->user?->user_name }}</span>-->
<!--                                    </div>-->
<!--                                    <div class="card-blog-body">-->
<!--                                        <h1 class="blog-title">{{ $blog->title }}</h1>-->
<!--                                        <p class="blog-review">-->
<!--                                            {!! Str::limit($blog->content, 200) !!}-->
<!--                                        </p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        @endforeach-->
<!--                    </div>-->
<!--                    <div class="swiper-pagination"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </section>-->
<!--end section blogs  -->

<!-- start section box info -->
<section class="section-box">
    <div class="container-custom">
        <div class="row card-boxs">
            <div class="col-sm-12 col-md-4">
                <div class="box-info">
                    <div class="first-content">
                        <img src="{{ asset('Front') }}/img/customer1.svg" alt="icon">
                        <h1>500K+</h1>
                        <!--<h1>{{ $events->count() }}+</h1>-->
                    </div>
                    <div class="second-content">
                        <span>Users will View Your Event</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="box-info">
                    <div class="first-content">
                        <img src="{{ asset('Front') }}/img/customer2.svg" alt="icon">
                        <h1>2491+</h1>
                        <!--                            <h1>{{ $tickets->count() }}+</h1>-->
                    </div>
                    <div class="second-content">
                        <span>From Registration to Launch</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="box-info">
                    <div class="first-content">
                        <img src="{{ asset('Front') }}/img/customer3.svg" alt="icon">
                        <h1>{{ $categories->count() + $categories->sum(fn($category) => $category->child->count() ?? 0)
                            }}+</h1>
                    </div>
                    <div class="second-content">
              <span>Event Categories
                in Catalog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section box info -->
<!-- start Ready to Try the Platform? -->
<section class="section-how-work">
    <div class="container">
        <div class="row justify-content-center text-center ">
            <div class="col-md-10 col-lg-8">
                <div class="header-section">
                    <h2 class="title">Ready to Try the <span>Platform?</span></h2>
                    <p class="description">Selling tickets has never been easier. Sign up and create your first
                        event listing on
                        My-Event. Let’s fill every seat in the hall together.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center m-4">
        @if(auth()->guard('company')->check())
            <a href="{{ route('create_event') }}" class="button-event">Create Event</a>
        @else
            <a href="{{ route('organization_login') }}" class="button-event">Create Event</a>
        @endif
    </div>
</section>
<!-- end Ready to Try the Platform? -->

@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
