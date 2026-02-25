@extends('backend.partials.master')

@section('title', 'Edit Organization Slider')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Organization Sliders</a>
                </li>
                <li class="breadcrumb-item active">Edit Organization Slider</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        @include('backend.partials._message')
        <form action="{{ route('admin.organization_sliders.update' , $organization_slider->id) }}" method="POST" enctype="multipart/form-data">
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
                                            <p class="card-title mb-75"> Title</p>
                                            <input type="text" class="form-control" name="title" placeholder="Title" value="{{ $organization_slider->title }}">
                                            @if ($errors->has('title'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                            <p class="card-title mb-75"> Description</p>
                                            <textarea type="text" name="description" class="form-control" rows="3" placeholder="Description">{{ $organization_slider->description }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                            <p class="card-title mb-75">Video</p>
                                            <input type="file" class="form-control" name="video" placeholder="Video">
                                            @error('video')
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <video width="320" height="240">
                                                <source src="{{ asset($organization_slider->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
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
@endsection
