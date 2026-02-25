@extends('backend.partials.master')

@section('title', 'Edit Terms & Conditions')

@section('css')
<link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/quill/typography.css" />
<link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/quill/katex.css" />
<link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/quill/editor.css" />
@endsection

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.terms-condition.edit') }}">Terms & Conditions</a>
                </li>
                <li class="breadcrumb-item active">Edit Terms & Conditions</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        @include('backend.partials._message')

        <form action="{{ route('admin.terms-condition.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-12">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                        <p class="card-title mb-75"> Terms & Conditions</p>
                                        <textarea class="form-control" name="terms_condition" id="terms_condition" rows="10">{{ $terms_conditions->terms_condition }}</textarea>
                                        @error('terms_condition')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                        <br />
                                        <p class="card-title mb-75"> Privacy Policy</p>
                                        <textarea class="form-control" name="privacy_policy" id="privacy_policy" rows="10">{{ $terms_conditions->privacy_policy }}</textarea>
                                        @error('privacy_policy')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                        <br />
                                        <p class="card-title mb-75"> About Us</p>
                                        <textarea class="form-control" name="about_us" id="about_us" rows="10">{{ $terms_conditions->about_us }}</textarea>
                                        @error('refund_exchange')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                            </div>
                        </div>
                        <br />
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

@section('js')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'terms_condition' );
    CKEDITOR.replace( 'privacy_policy' );
    CKEDITOR.replace( 'about_us' );
    // CKEDITOR.replace( 'shipping_payment' );
  </script>
@endsection
