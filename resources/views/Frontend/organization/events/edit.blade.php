@extends('Frontend.organization.events.inc.master')
@section('title', 'Edit Event')
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
        <div class="head ">
            <h3> Event Info </h3>
            <p>Step 1 of 5</p>
        </div>
        <!-- form step 1 -->
        <form class="row g-3" method="post" action="{{ route('organization.update_event' , $event->id) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- event name -->
            <div class="col-md-6">
                <label class="form-label">Event Name</label>
                <input type="text" name="name" class="form-control p-2" placeholder="Name of The Event" value="{{ $event->name }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- currency -->
            <div class="col-md-6">
                <label class="form-label">Currency</label>
                <select class="form-select p-2" name="currency_id">
                    <option disabled selected>Select Currency</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ $event->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
                    @endforeach
                </select>
                @error('currency_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- select event type -->
            <div class="col-md-6">
                <label class="form-label"> Category</label>
                <select class="form-select p-2" name="category_id">
                    <option disabled selected>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- sub category -->
            <div class="col-md-6">
                <label class="form-label">Sub Category</label>
                <select class="form-select p-2" name="sub_category_id">
                    <option disabled selected>Select Sub Category</option>
                    @foreach($sub_categories as $sub_ategory)
                        <option value="{{ $sub_ategory->id }}" {{ $event->sub_category_id == $sub_ategory->id ? 'selected' : '' }}>{{ $sub_ategory->name }}</option>
                    @endforeach
                </select>
                @error('sub_category_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- description  -->
            <div class="col-12">
                <label for="exampleFormControlTextarea1" class="form-label"> Event Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"
                          placeholder="Add Event Description">{!! $event->description !!}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- cancellation_policy -->
            <div class="col-12">
                <label for="exampleFormControlTextarea1" class="form-label"> Event Cancellation Policy</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="cancellation_policy"
                          placeholder="Add Event Cancellation Policy">{!! $event->cancellation_policy !!}</textarea>
                @error('cancellation_policy')
                    <span class="text-danger">{{ $message }}</span>
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- start data   -->
            <!-- select between date and calendar  -->
            @foreach($event->eventDates as $date)
                <div id="calendar" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Start Data</label>
                    <input type="date" class="form-control p-2" name="start_date[]" value="{{ \Carbon\Carbon::parse($date->start_date)->format('Y-m-d') }}">
                    @error('start_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- start time  -->
                <div class="col-md-6">
                    <label class="form-label">Start Time</label>
                    <input type="time" class="form-control p-2" name="start_time[]" value="{{ \Carbon\Carbon::parse($date->start_date)->format('H:i') }}">
                    @error('start_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- end data   -->
                <div class="col-md-6">
                    <label class="form-label">End Data</label>
                    <input type="date" class="form-control p-2" name="end_date[]" value="{{ \Carbon\Carbon::parse($date->end_date)->format('Y-m-d') }}">
                    @error('end_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- end time  -->
                <div class="col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="time" class="form-control p-2" name="end_time[]" value="{{ \Carbon\Carbon::parse($date->end_date)->format('H:i') }}">
                    @error('end_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- button remove  -->
                <div class="col-12 mt-4">
                    <button class="btn btn-danger" type="button" id="deleteDate">Remove Date</button>
                </div>
            </div>
            @endforeach
            <div class="col-12 mt-4">
                <button class="btn btn-primary" type="button" id="addDate">Add Date</button>
            </div>
            <!-- event formate -->
            <div class="col-12 mt-4">
                <label style="font-weight: 800; font-size:24px ;">Event Formate</label>
                <div class="form-check pt-3">
                    <input class="form-check-input" type="radio" name="format" id="flexRadioDefault1" value="1" {{ $event->format == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexRadioDefault1">
                        Online
                    </label>
                    @error('format')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-check mt-4">
                    <input class="form-check-input" type="radio" name="format" id="flexRadioDefault2" value="0" {{ $event->format == 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Offline
                    </label>
                    @error('format')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- Event Media data  -->
            <div class="add-images-event">
                <!-- start image event poster -->
                <div class="mt-3">
                    <div>
                        <h4 style="font-weight: bold;">Event Poster </h4>
                        <div class="notice-info col-md-4 d-flex justify-content-center align-items-center">
              <span> Event poster image must be in JPG, PNG, or WEBP format. The
                file
                should not exceed 5 MB.
                Recommended image size: 300px * 300px.
              </span>
                        </div>
                    </div>
                    <div class="image-box" id="imageBox" onclick="document.getElementById('fileInput').click()">
                        @foreach($event->media as $media)
                            @if($media->name == 'poster')
                                <img id="uploadedImage" src="{{ asset('storage/'.$media->path) }}" alt=""
                                     style="width: 100%; height: 100%;">
                            @endif
                        @endforeach
                    </div>
                    <input type="file" id="fileInput" style="display: none;" name="poster" accept="image/*" onchange="loadFile(event)">
                    @error('poster')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- end image event poster -->
                <!-- start image banner  -->
                <div class="mt-3">
                    <div>
                        <h4 style="font-weight: 700;">Event Banner - <span style="color: #777;">optional</span> </h4>
                        <div class="notice-info col-md-4 d-flex justify-content-center align-items-center">
              <span> Add an event banner to be randomly featured in the landing
                pages
                for the country and city of the event
              </span>
                        </div>
                    </div>
                    <div class="custom-image-box" id="customImageBox"
                         onclick="document.getElementById('customFileInput').click()">
                        @foreach($event->media as $media)
                            @if($media->name == 'banner')
                                <img id="customUploadedImage" src="{{ asset('storage/'.$media->path) }}" alt=""
                                     style="width: 100%; height: 100%;">
                            @endif
                        @endforeach
                    </div>
                    <input type="file" id="customFileInput" name="banner" style="display: none;" accept="image/*" onchange="loadCustomFile(event)">
                    @error('banner')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- end image banner  -->
            </div>

            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                <button id="submit" type="submit" class="btn btn-primary" style="padding: 7px 30px;">Next</button>
            </div>

        </form>
    </div>
    <!-- end form-data -->
@endsection
@push('inc_events_js')
    <script>
        //when select category show sub categories
        $('select[name="category_id"]').change(function () {
            var category_id = $(this).val();
            $.ajax({
                url: "{{ route('organization.sub_categories') }}",
                type: 'GET',
                data: {category_id: category_id},
                success: function (data) {
                    $('select[name="sub_category_id"]').empty();
                    $('select[name="sub_category_id"]').append('<option disabled selected>Select Sub Category</option>');
                    $.each(data, function (key, value) {
                        $('select[name="sub_category_id"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });

        //when click on add date button add new date inputs
        $('#addDate').click(function () {
            var html = '<div class="col-md-6">\n' +
                '<label class="form-label">Start Data</label>\n' +
                '<input type="date" class="form-control p-2" name="start_date[]" required>\n' +
                '</div>\n' +
                '<div class="col-md-6">\n' +
                '<label class="form-label">Start Time</label>\n' +
                '<input type="time" class="form-control p-2" name="start_time[]" required>\n' +
                '</div>\n' +
                '<div class="col-md-6">\n' +
                '<label class="form-label">End Data</label>\n' +
                '<input type="date" class="form-control p-2" name="end_date[]" required>\n' +
                '</div>\n' +
                '<div class="col-md-6">\n' +
                '<label class="form-label">End Time</label>\n' +
                '<input type="time" class="form-control p-2" name="end_time[]" required>\n' +
                '</div>';
            html += '<div class="col-12 mt-4">\n' +
                '<button class="btn btn-danger" type="button" id="deleteDate">Delete Date</button>\n' +
                '</div>';
            $('#calendar').append(html);
        });
    </script>
    <script>
        $(document).on('click', '#deleteDate', function () {
            //remove the current row
            $(this).closest('div').prev().remove();
            $(this).closest('div').prev().remove();
            $(this).closest('div').prev().remove();
            $(this).closest('div').prev().remove();
            $(this).closest('div').remove();

        });
    </script>
@endpush
