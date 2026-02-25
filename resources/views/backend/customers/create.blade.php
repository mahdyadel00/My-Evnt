@extends('backend.partials.master')

@section('title' , 'Add Customer')
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.customers.index') }}">Customer</a>
                </li>
                <li class="breadcrumb-item active">Add Customer</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-5 col-12 mt-1 mb-1">
                                        <p class="card-title mb-75">Title</p>
                                        <input type="text" name="title" class="form-control" placeholder="Title" required>
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-1 mb-1">
                                        <p class="card-title mb-75">Description</p>
                                        <textarea name="description" class="form-control" placeholder="Description" rows="4" required></textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-1 mb-1">
                                        <p class="card-title mb-75">Cover</p>
                                        <input type="file" name="cover" class="form-control" placeholder="Cover" required>
                                        @error('cover')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit"><i class="ti ti-device-floppy ti-xs"> Add</i></button>
                        </div>
                    </div>
                </div>
                <!-- connection -->
            </div>
        </form>
    </div>
    <!-- / Content -->
@endsection
