@extends('Frontend.organization.events.inc.master')
@section('title', 'Edit Event Setup 2')
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
    @include('Frontend.organization.layouts._message')

    <!-- start form-data -->
    <div class="form-data">
        <div class="head">
            <h3> Venue Location </h3>
            <p>Step 2 of 5</p>
        </div>
        <!-- form step 2 -->
        <form class="row g-3" action="{{ route('organization.update_setup2' , $event->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="col-12">
                <select class="form-select p-2" name="country_id">
                    <option disabled selected>Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }} " @if($event->city->country_id == $country->id) selected @endif
                        >{{ $country->name }}</option>
                    @endforeach
                    @error('country_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </select>
            </div>
            <div class="col-12">
                <select class="form-select p-2" name="city_id">
                    <option selected>Select City</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}"
                            {{ $event->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label"> Location</label>
                <input type="text" class="form-control" name="location" placeholder="Add Event Location" value="{{ $event->location }}">
            </div>
            <div class="col-12">
                <label for="exampleFormControlTextarea1" class="form-label"> Event Address</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="address"
                          placeholder="Add Event Description">{!! $event->address !!}</textarea>
            </div>
            <div class="col-12">
                <p class="notice-info-place">If you didn't find your country or city in the proposed list, please <a
                        href="#">contact
                        support</a> to
                    add the value you
                    need.</p>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                <a href="{{ route('create_event') }}" type="submit" class="btn btn-secondary"
                   style="padding: 7px 30px;margin:0 10px">Back</a>
                <button type="submit" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">Next</button>
            </div>
        </form>
    </div>
    <!-- end form-data -->
@endsection
@push('inc_events_js')
    <script>
    //get cities by country id
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
