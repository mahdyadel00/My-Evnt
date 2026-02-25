@extends('backend.partials.master')

@section('title', 'Edit Package')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.packages.index') }}">Packages</a>
                </li>
                <li class="breadcrumb-item active">Edit Package</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.packages.update' , $package->id) }}" method="POST" enctype="multipart/form-data">
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
                                        <input type="text" class="form-control" name="title" placeholder="Package Title" value="{{ $package->title }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75"> Package Description</p>
                                        <textarea name="description" class="form-control" placeholder="Package Description">{!! $package->description !!}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Price Monthly</p>
                                        <input type="text" class="form-control" name="price_monthly" placeholder="Price Monthly" value="{{ $package->price_monthly }}">
                                        @error('price_monthly')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Price Yearly</p>
                                        <input type="text" class="form-control" name="price_yearly" placeholder="Price Yearly" value="{{ $package->price_yearly }}">
                                        @error('price_yearly')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Discount</p>
                                        <input type="text" class="form-control" name="discount" placeholder="Discount" value="{{ $package->discount }}">
                                        @error('discount')
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
