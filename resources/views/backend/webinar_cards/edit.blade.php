@extends('backend.partials.master')

@section('title', 'Edit Webinar Card')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.webinar_cards.index') }}">Manage Webinar Cards</a>
                </li>
                <li class="breadcrumb-item active">Edit Webinar Card ({{ $webinarCard->title }})</li>
            </ol>
        </nav>

        <form id="edit" action="{{ route('admin.webinar_cards.update', $webinarCard->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit Webinar Card</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="webinar_id">Webinar</label>
                                        <select name="webinar_id" id="webinar_id" class="form-control select2">
                                            <option value="" disabled selected>Select Webinar</option>
                                            @foreach ($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" {{ old('webinar_id', $webinarCard->webinar_id) == $webinar->id ? 'selected' : '' }}>
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
                                        <label for="title">Title</label>
                                        <input type="text" name="title"
                                            class="{{ $errors->has('title') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="title" placeholder="Enter Title"
                                            value="{{ old('title', $webinarCard->title ?? '') }}">
                                        @error('title')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @error('image')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                        @if($webinarCard->media->firstWhere('name', 'image'))
                                            <img src="{{ asset('storage/' . $webinarCard->media->firstWhere('name', 'image')->path) }}" alt="Webinar Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description"
                                            class="{{ $errors->has('description') ? 'form-control is-invalid text-red' : 'form-control ckeditor' }}"
                                            placeholder="Enter Description">{!! old('description', $webinarCard->description ?? '') !!}</textarea>
                                        @error('description')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="text" name="link"
                                            class="{{ $errors->has('link') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="link" placeholder="Enter Link"
                                            value="{{ old('link', $webinarCard->link ?? '') }}">
                                        @error('link')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control select2">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="1" {{ old('status', $webinarCard->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $webinarCard->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
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