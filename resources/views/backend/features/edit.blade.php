@extends('backend.partials.master')

@section('title', 'Edit Feature')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.features.index') }}">Features</a>
                </li>
                <li class="breadcrumb-item active">Edit Feature</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.features.update' , $feature->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75"> Package Title</p>
                                        <select name="package_id" class="form-control">
                                            <option value="">Select Package</option>
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}" {{ $feature->package_id == $package->id ? 'selected' : '' }}>{{ $package->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('package_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75"> Feature Title</p>
                                        <input type="text" name="title" class="form-control" value="{{ $feature->title }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75"> Status</p>
                                        <select name="status" class="form-control">
                                            <option disabled selected>Select Status</option>
                                            <option value="1" {{ $feature->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $feature->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
            </div>
        </form>
    </div>
        <!-- / Content -->
@endsection
