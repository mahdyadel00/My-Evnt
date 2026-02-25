@extends('backend.partials.master')

@section('title', 'Add About Webinar')

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
                <li class="breadcrumb-item active">Add About Webinar</li>
            </ol>
        </nav>

        <form id="add" action="{{ route('admin.about_webinars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add New About Webinar</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Webinar</label>
                                        <select name="webinar_id" id="webinar_id" class="form-control select2" required>
                                            <option value="" disabled selected>Select Webinar</option>
                                            @foreach ($webinars as $webinar)
                                                <option value="{{ $webinar->id }}">{{ $webinar->webinar_name }}</option>
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
                                        <input type="text" name="title" class="{{ $errors->has('title') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="title" placeholder="Enter Title"
                                            value="{{ old('title') }}" required>
                                        @error('title')
                                            <span class="text-danger d-block"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">  
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*" required>
                                        @error('image')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                    <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="{{ $errors->has('description') ? 'form-control is-invalid text-red' : 'form-control ckeditor' }}" placeholder="Enter Description" value="{{ old('description') }}" required></textarea>
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
@section('js')
    <script>
        //date dont active date before today
        $('#date').attr('min', new Date().toISOString().split('T')[0]);
        $('.select2').select2();
    </script>
@endsection