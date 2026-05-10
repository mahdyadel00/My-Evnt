@extends('backend.partials.master')

@section('title', __('Edit organizer slide'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.organizer-feature-slides.index') }}">{{ __('Organizer carousel') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit') }}</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Edit slide') }}</h5>
                <a href="{{ route('admin.organizer-feature-slides.index') }}" class="btn btn-sm btn-outline-secondary">
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.organizer-feature-slides.update', $slide->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('backend.organizer_feature_slides._form', ['slide' => $slide])
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
