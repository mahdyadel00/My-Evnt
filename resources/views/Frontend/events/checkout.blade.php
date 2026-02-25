@extends('Frontend.layouts.master')
@section('title', 'All Events')
@section('content')
    <!-- start section All event -->
    <section class="details">
        <div class="container mt-3">
            <div class="row d-flex flex-wrap align-items-baseline">
                <div class="col-md-7">
                    <!-- start part get ticket info -->
                    <div class="project-info-box">
                        <h4 class="pb-2">{{ $event->name }}</h4>
                        <div class="row">
                            <div class="col-lg-4  col-12">
                                <div class="card-info-payment">
                                    <h5><i class="fas fa-map-marker-alt"></i> {{ $event->city->country->name }}</h5>
                                    <p>
                                        <a href="{{ $event->location }}" target="_blank">
                                            Go to location
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-4  col-12">
                                <div class="card-info-payment">
                                    <h5><i class="fas fa-calendar-alt"></i>{{ date('d M Y', strtotime($event->start_date)) }}</h5>
                                    <p>
                                        <!-- get day name -->
                                        {{ date('l', strtotime($event->start_date)) }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="card-info-payment">
                                    <h5><i class="fas fa-clock"></i>
                                        {{ date('h:i A', strtotime($event->start_time)) }} - {{ date('h:i A', strtotime($event->end_time)) }}</h5>
                                    <p>
                                        <!-- get Give me the difference between the beginning of time and the end of time -->
                                        {{ date('h', strtotime($event->start_time)) - date('h', strtotime($event->end_time)) }} hours
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- section full data to payment  -->
                    <div class="col-12">
                        <div class="project-info-box">
                            <h4 class="pb-2">Contacts </h4>
                            <div class="row">
                                <form class="mb-2 mt-2">
                                    <div class="mb-3">
                                        <input type="text" class="form-control p-2" placeholder="First Name" name="first_name" value="{{ auth()->guard('company')->user()->first_name }}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" class="form-control p-2" placeholder="Last Name" name="last_name" value="{{ auth()->guard('company')->user()->last_name }}">
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control p-2" placeholder="Email Address" name="email" value="{{ auth()->guard('company')->user()->email }}">
                                    </div>
{{--                                    <div class="mb-3">--}}
{{--                                        <input type="email" class="form-control p-2" placeholder="Confirm Email Address">--}}
{{--                                    </div>--}}
                                    <div class="mb-3">
                                        <input type="text" class="form-control p-2" placeholder="Phone Number " name="phone" value="{{ auth()->guard('company')->user()->phone }}">
                                    </div>
                                    <div class="mt-4 d-flex justify-content-center">
                                        <button class="btn btn-primary" type="submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- start part get ticket -->
                <div class="col-md-5">
                    <div class="project-info-box mt-0">
                        <h4>Sponsor Ticket</h4>
                        <div class="mt-4">
{{--                            <div class="d-flex justify-content-between align-items-baseline">--}}
{{--                                <p> <span style="padding-right:5px ;">0</span>Sponsor Ticket</p>--}}
{{--                                <p>0.00 EGP</p>--}}
{{--                            </div>--}}
{{--                            <div class="d-flex justify-content-between align-items-baseline">--}}
{{--                                <p> Stripe Transaction Fee </p>--}}
{{--                                <p>0.00 EGP</p>--}}
{{--                            </div>--}}
                            <div class="d-flex justify-content-between align-items-baseline">
                                <p style="font-weight: bold;"> Total</p>
                                <p>{{ $event->adFee->price }}</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-baseline">
{{--                                <input class="form-check-input" type="checkbox" id="exampleCheckbox" />--}}
                                <p class="mb-0 ms-2">{!! $event->description !!}</p>
                            </div>
                            <div class="p-2 d-flex justify-content-center mt-2">
                                <a href="#  " class="btn-ticket">Complete Order </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end part get ticket -->
            </div>
        </div>
    </section>
    <!-- end section All events -->

@endsection
