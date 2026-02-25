@extends('backend.partials.master')

@section('title', 'Edit About Webinar')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.about_webinars.index') }}">Manage About Webinars</a>
                </li>
                <li class="breadcrumb-item active">Edit About Webinar ({{ $aboutwebinar->title }})</li>
            </ol>
        </nav>

        <form id="edit" action="{{ route('admin.about_webinars.update', $aboutwebinar->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit About Webinar</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country_id">Webinar</label>
                                        <select name="webinar_id" id="webinar_id" class="form-control select2">
                                            <option value="" disabled selected>Select Webinar</option>
                                            @foreach ($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" {{ old('webinar_id', $aboutwebinar->webinar_id) == $webinar->id ? 'selected' : '' }}>
                                                    {{ $webinar->webinar_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('webinar_id')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Title</label>
                                        <input type="text" name="title" class="form-control" id="title"
                                            placeholder="Enter Title" value="{{ old('title', $aboutwebinar->title) }}"
                                            required>
                                        @error('title')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                        @error('image')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @foreach($aboutwebinar->media as $media)
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="About Webinar Image"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @endforeach
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control ckeditor"
                                            placeholder="Enter Description">{{ old('description', $aboutwebinar->description) }}</textarea>
                                        @error('description')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                <i class="ti ti-device-floppy ti-xs"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection