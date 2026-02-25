@extends('backend.partials.master')

@section('title', 'Update FAQ')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.faqs.index') }}">FAQs</a>
                </li>
                <li class="breadcrumb-item active">Update FAQ</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.faqs.update' , $faq->id) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-10 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Question</p>
                                        <input type="text" name="question" class="form-control" value="{{ $faq->question }}">
                                        @error('question')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-10 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Answer</p>
                                        <textarea name="answer" class="form-control ckeditor" rows="4">{!! $faq->answer !!}</textarea>
                                        @error('answer')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                                <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                    <i class="ti ti-device-floppy ti-xs">Save</i>
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
@section('js')
    <script>
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endsection