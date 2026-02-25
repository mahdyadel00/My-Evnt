@extends('backend.partials.master')

@section('title', 'Edit Customer')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.customers.index') }}">Customers</a>
                </li>
                <li class="breadcrumb-item active">Edit Customer</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.customers.update' , $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-5 col-12 mt-1 mb-1">
                                        <p class="card-title mb-75">Title</p>
                                        <input type="text" name="title" class="form-control" value="{{ $customer->title }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-1 mb-1">
                                        <p class="card-title mb-75">Description</p>
                                        <textarea name="description" class="form-control" rows="4">{!! $customer->description !!}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-1 mb-1">
                                        <p class="card-title  mb-75">Cover</p>
                                        <input type="file" name="cover" class="form-control">
                                        @error('cover')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @foreach($customer->media as $media)
                                            @if($media->name == 'cover')
                                                <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 50px; height: 50px;">
                                            @endif
                                        @endforeach
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
