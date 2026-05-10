@extends('backend.partials.master')

@section('title', __('Add organizer slide'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.organizer-feature-slides.index') }}">{{ __('Organizer carousel') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Add') }}</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Add slide') }}</h5>
                <a href="{{ route('admin.organizer-feature-slides.index') }}" class="btn btn-sm btn-outline-secondary">
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.organizer-feature-slides.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @include('backend.organizer_feature_slides._form', ['slide' => null])
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
