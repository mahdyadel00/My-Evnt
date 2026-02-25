@extends('Frontend.organization.events.inc.master')
@section('title', 'Edit Event Setup 5')
@section('content')
    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px; ">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="{{ route('home') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">Edit Event</a>
                </li>
            </ul>
            <h1>Edit Event</h1>
        </div>
    </div>
    <!-- end breadcrumb  -->

    <!-- start form-data -->
    <div class="form-data">
        <!-- head -->
        <div class="head">
            <h3> Check Info </h3>
            <p>Step 5 of 5</p>
        </div>
        <!-- step 5 -->
        <div class="row">
            <!-- poster info -->
            <div class="col-md-7 poster-info card mb-2">
                <div class="row g-0">
                    <div class="col-md-4 poster-info-image">
                        @foreach($event->media as $media)
                            @if($media->name == 'poster')
                                <img src="{{ asset('storage/'.$media->path) }}" class="img-fluid rounded-1 poster-image mb-2" alt="...">
                            @endif
                        @endforeach
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1>{{ $event->name }} </h1>
                            <p class="card-text">{!! $event->description !!}</p>
                            <p class="category-type mt-4">{{ $event->category->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ticket info -->
            <div class="col-md-5 ticket-info mb-2">
                <div class="ticket-data">
                    <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                        <i class='bx bxs-calendar-event p-2'></i>
                        <p>{{ \Carbon\Carbon::parse($event->eventDates[0]->start_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($event->eventDates[0]->end_date)->format('d M, Y') }}</p>

                    </div>
                    <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                        <i class='bx bxs-location-plus p-2'></i>
                        <p>
                            <a href="{{ $event->location }}" target="_blank">
                                Go to location
                            </a>
                        </p>
                    </div>
                    <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                        <i class='bx bxs-user p-2'></i>
                        <p>{{ $event->company->company_name }}</p>
                    </div>
                    <div style="color: #777;" class="d-flex justify-content-start align-items-baseline">
                        <i class='bx bxs-purchase-tag-alt p-2'></i>
                        <p>{{ $event->tickets->isNotEmpty() ? $event->tickets[0]->price : 'Free' }} {{ $event->currency?->name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- pay info -->
        <div class="pay-info mt-3">
            <div class="card">
                <h5 class="card-header" style="font-weight: 700;">Tickets </h5>
                <div class="card-body">
                    <div class="pay-data ">
                        <div class="d-flex justify-content-between align-items-baseline flex-wrap mt-3 mb-3">
                            <div class="col-md-6 col-12 mb-3 mb-md-0">
                                <p>{{ $event->name }}</p>
                                <span>{{ $event->category->name }}</span>
                            </div>
                            <div class="d-flex flex-wrap justify-content-start col-md-6 col-12">
                                <div class="col-6 mb-3 mb-md-0">
                                    <p>Price</p>
                                    <span style="background-color: #7777771c;padding: 3px 7px;border-radius: 5px;">{{ $event->tickets->isNotEmpty() ? $event->tickets[0]->price : 'Free' }} {{ $event->currency?->name }}</span>
                                </div>
                                <div class="col-6">
                                    <p>Available</p>
                                    <span style="background-color: #7777771c;padding: 3px 7px;border-radius: 5px;">{{ $event->tickets->isNotEmpty() ? $event->tickets[0]->quantity : 'Unlimited' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <p class="card-text mt-4">Description</p>
                    <p class="card-text col-md-7 col-12">{!! $event->description !!}</p>
                </div>
            </div>
        </div>
        <!-- buttons -->
        <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
            <a href="{{ route('create_event_setup5') }}" type="submit" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">Back</a>
            <a href="{{ route('organization.my_events') }}" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">Post to Catalog</a>
        </div>
    </div>
    <!-- end form-data -->
@endsection
@push('inc_events_js')
    <script>
    //get city by country
    $(document).ready(function () {
        $('select[name="country_id"]').on('change', function () {
            var country_id = $(this).val();
            if (country_id) {
                $.ajax({
                    url: '{{ route('get_cities') }}/',
                    data: {country_id: country_id},
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="city_id"]').empty();
                        $.each(data, function (key, value) {
                            //check if value is equal to the selected value
                            $('select[name="city_id"]').append('<option disabled selected>Select City</option>');
                            $('select[name="city_id"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="city_id"]').empty();
            }
        });
    });
</script>
@endpush
