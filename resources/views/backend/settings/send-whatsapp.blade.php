@extends('backend.partials.master')

@section('title', 'Send Whatsapp')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Send Whatsapp</a>
                </li>
                <li class="breadcrumb-item active">Send Whatsapp</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        @include('backend.partials._message')
        <form action="{{ route('admin.send.whatsapp.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> User</p>
                                        <select class="form-control" name="user_id" required>
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('user_id'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('user_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75"> Message</p>
                                        <textarea type="text" name="message" class="form-control" rows="3"
                                            placeholder="Message" required></textarea>
                                        @if ($errors->has('message'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('message') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                                    <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                        <i class="ti ti-device-floppy ti-xs">Send</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
        </form>
        <!-- / Content -->
@endsection