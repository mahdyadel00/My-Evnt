@extends('backend.partials.master')

@section('title', 'Edit Coupon')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.coupons.index') }}">All Coupons</a>
                </li>
                <li class="breadcrumb-item active">Edit Coupon</li>
            </ol>
        </nav>
        <!-- Users List Table -->

        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <label for="event_id">Event</label>
                                        <select class="form-control select2" name="event_id" id="event_id">
                                            <option disabled selected>Select Event</option>
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}" {{ $coupon->event_id == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('event_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <label for="type">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                            <option value="percent" {{ $coupon->type == 'percent' ? 'selected' : '' }}>Percent</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <label for="value">Value</label>
                                        <input type="text" name="value" id="value" class="form-control" placeholder="Value"
                                            value="{{ $coupon->value }}">
                                        @error('value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control"
                                            placeholder="Start Date" value="{{ date('Y-m-d', strtotime($coupon->start_date)) }}">
                                        @error('start_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <label for="end_date">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control"
                                            placeholder="End Date" value="{{ date('Y-m-d', strtotime($coupon->end_date)) }}">
                                        @error('end_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control"
                                            placeholder="Description">{{ $coupon->description }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                <i class="ti ti-device-floppy ti-xs">Edit</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- / Content -->
    </div>
@endsection
@section('js')
    <!-- Page JS -->
    <script src="{{ asset('backend') }}/assets/js/app-user-list.js"></script>
@endsection
