@extends('backend.partials.master')

@section('title', 'Edit Currency')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.currencies.index') }}">Currencies</a>
                </li>
                <li class="breadcrumb-item active">Edit Currency</li>
            </ol>
        </nav>
        <!-- Users List Table -->

        <form action="{{ route('admin.currencies.update' , $currency->id) }}" method="POST"
              enctype="multipart/form-data">
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
                                        <p class="card-title mb-75"> Currency Name</p>
                                        <input type="text" class="form-control" name="name" placeholder="Currency Name"
                                               value="{{ $currency->name }}">
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Currency Code</p>
                                        <input type="text" class="form-control" name="code" placeholder="Currency Code"
                                               value="{{ $currency->code }}">
                                        @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Currency Status</p>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ $currency->status == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ $currency->status == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Currency Image</p>
                                        <input type="file" name="image" class="form-control">
                                        @if ($errors->has('image'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('image') }}</strong>
                                                </span>
                                        @endif
                                        @foreach($currency->media as $media)
                                            <img src="{{ asset('storage/'.$media->path) }}" alt=""
                                                 style="width: 50px; height: 50px;">
                                        @endforeach
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
        </form>
        <!-- / Content -->
@endsection
