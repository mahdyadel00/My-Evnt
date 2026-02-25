@extends('backend.partials.master')

@section('title', 'Edit Webinar')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.webinars.index') }}">Manage Webinars</a>
                </li>
                <li class="breadcrumb-item active">Edit Webinar ({{ $webinar->webinar_name }})</li>
            </ol>
        </nav>

        <form id="edit" action="{{ route('admin.webinars.update', $webinar->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit Webinar</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country_id">Webinar Name</label>
                                        <input type="text" name="webinar_name" class="form-control" id="webinar_name"
                                            placeholder="Enter Webinar Name" value="{{ old('webinar_name', $webinar->webinar_name) }}">
                                        @error('webinar_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Company Name</label>
                                        <input type="text" name="company_name" class="form-control" id="company_name"
                                            placeholder="Enter Company Name" value="{{ old('company_name', $webinar->company_name) }}">
                                        @error('company_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="" disabled>Select Status</option>
                                            <option value="1" {{ old('status', $webinar->status) == '1' ? 'selected' : '' }}> Active</option>
                                            <option value="0" {{ old('status', $webinar->status) == '0' ? 'selected' : '' }}> Not Active</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger d-block"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="text" name="link" class="form-control" id="link"
                                            placeholder="Enter Link" value="{{ old('link', $webinar->link) }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" class="form-control" id="title"
                                            placeholder="Enter Title" value="{{ old('title', $webinar->title) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control ckeditor" placeholder="Enter Description">{{ old('description', $webinar->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" class="form-control" id="date"
                                            placeholder="Enter Date" value="{{ old('date', date('Y-m-d', strtotime($webinar->date))) }}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" name="time" class="form-control" id="time"
                                            placeholder="Enter Time" value="{{ old('time', date('H:i', strtotime($webinar->time))) }}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" name="facebook" class="form-control" id="facebook"
                                            placeholder="Enter Facebook" value="{{ old('facebook', $webinar->facebook) }}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="linkedin">LinkedIn</label>
                                        <input type="text" name="linkedin" class="form-control" id="linkedin"
                                            placeholder="Enter LinkedIn" value="{{ old('linkedin', $webinar->linkedin) }}">
                                    </div>
                                </div>  

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" name="instagram" class="form-control" id="instagram"
                                            placeholder="Enter Instagram" value="{{ old('instagram', $webinar->instagram) }}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="youtube">Youtube</label>
                                        <input type="text" name="youtube" class="form-control" id="youtube"
                                            placeholder="Enter Youtube" value="{{ old('youtube', $webinar->youtube) }}">
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
                                    @foreach($webinar->media as $media)
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="Webinar Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @endforeach
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
