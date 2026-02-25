@extends('backend.partials.master')

@section('title', 'Create Slider')
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.sliders.index') }}">Sliders</a>
                </li>
                <li class="breadcrumb-item active">Add Slider</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Title</p>
                                        <input type="text" class="form-control" name="title" placeholder="Title">
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Description</p>
                                        <textarea type="text" name="description" class="form-control" placeholder="Description"></textarea>
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Url</p>
                                        <input type="text" name="url" class="form-control" placeholder="Url">
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Image</p>
                                        <input type="file" name="image" class="form-control">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Small Image</p>
                                        <input type="file" name="image_small" class="form-control">
                                        @error('image_small')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Event</p>
                                        <select name="event_id" class="form-control select2 select">
                                            <option disabled selected>Select Event</option>
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit"><i
                                    class="ti ti-device-floppy ti-xs"> Add</i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- connection -->
            </div>
        </form>
    </div>
    <!-- / Content -->
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.select').select2();
        });
    </script>
@endsection