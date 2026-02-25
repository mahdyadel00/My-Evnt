@extends('backend.partials.master')

@section('title', 'Edit Webinar Speaker')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.webinar_speakers.index') }}">Manage Webinar Speakers</a>
                </li>
                <li class="breadcrumb-item active">Edit Webinar Speaker ({{ $webinarSpeaker->name }})</li>
            </ol>
        </nav>

        <form id="edit" action="{{ route('admin.webinar_speakers.update', $webinarSpeaker->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit Webinar Speaker</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name"
                                            class="{{ $errors->has('name') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="name" placeholder="Enter Name"
                                            value="{{ old('name', $webinarSpeaker->name) }}" required>
                                        @error('name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="job_title">Job Title</label>
                                        <input type="text" name="job_title"
                                            class="{{ $errors->has('job_title') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="job_title" placeholder="Enter Job Title"
                                            value="{{ old('job_title', $webinarSpeaker->job_title) }}" required>
                                        @error('job_title')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="webinar_id">Webinar</label>
                                        <select name="webinar_id" id="webinar_id" class="form-control select2" required>
                                            <option value="" disabled selected>Select Webinar</option>
                                            @foreach ($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" {{ old('webinar_id', $webinarSpeaker->webinar_id) == $webinar->id ? 'selected' : '' }}>
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
                                        <label for="aboutwebinar_id">About Webinar</label>
                                        <select name="aboutwebinar_id" id="aboutwebinar_id" class="form-control select2"
                                            required>
                                            <option value="" disabled selected>Select About Webinar</option>
                                            @foreach ($aboutwebinars as $aboutwebinar)
                                                <option value="{{ $aboutwebinar->id }}" {{ old('aboutwebinar_id', $webinarSpeaker->aboutwebinar_id) == $aboutwebinar->id ? 'selected' : '' }}>
                                                    {{ $aboutwebinar->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('aboutwebinar_id')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description"
                                            class="{{ $errors->has('description') ? 'form-control ckeditor is-invalid text-red' : 'form-control ckeditor' }}"
                                            placeholder="Enter Description"
                                            value="{{ old('description', $webinarSpeaker->description) }}">
                                                {{ old('description', $webinarSpeaker->description) }}
                                            </textarea>
                                        @error('description')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" name="facebook"
                                            class="{{ $errors->has('facebook') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="facebook" placeholder="Enter Facebook"
                                            value="{{ old('facebook', $webinarSpeaker->facebook) }}">
                                        @error('facebook')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" name="twitter"
                                            class="{{ $errors->has('twitter') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="twitter" placeholder="Enter Twitter"
                                            value="{{ old('twitter', $webinarSpeaker->twitter) }}">
                                        @error('twitter')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="linkedin">Linkedin</label>
                                        <input type="text" name="linkedin"
                                            class="{{ $errors->has('linkedin') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="linkedin" placeholder="Enter Linkedin"
                                            value="{{ old('linkedin', $webinarSpeaker->linkedin) }}">
                                        @error('linkedin')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" name="instagram"
                                            class="{{ $errors->has('instagram') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="instagram" placeholder="Enter Instagram"
                                            value="{{ old('instagram', $webinarSpeaker->instagram) }}">
                                        @error('instagram')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="youtube">Youtube</label>
                                        <input type="text" name="youtube"
                                            class="{{ $errors->has('youtube') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="youtube" placeholder="Enter Youtube"
                                            value="{{ old('youtube', $webinarSpeaker->youtube) }}">
                                        @error('youtube')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                        @foreach($webinarSpeaker->media as $media)
                                            <img src="{{ asset('storage/' . $media->path) }}" alt="Webinar Speaker Image"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                        @endforeach
                                        @error('image')
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
@section('js')
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>
@endsection