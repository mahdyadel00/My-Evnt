@extends('backend.partials.master')

@section('title', 'Add Webinar')

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
                <li class="breadcrumb-item active">Add Webinar</li>
            </ol>
        </nav>

        <form id="add" action="{{ route('admin.webinars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add New Webinar</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country_id">Webinar Name</label>
                                        <input type="text" name="webinar_name"
                                            class="{{ $errors->has('webinar_name') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="webinar_name" placeholder="Enter Webinar Name"
                                            value="{{ old('webinar_name') }}" required>
                                        @error('webinar_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Company Name</label>
                                        <input type="text" name="company_name"
                                            class="{{ $errors->has('company_name') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="company_name" placeholder="Enter Company Name"
                                            value="{{ old('company_name') }}" required>
                                        @error('company_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Not
                                                Active</option>
                                        </select>
                                        @error('status')
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
                                            id="title" placeholder="Enter Title" value="{{ old('title') }}" required>
                                        @error('title')
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
                                            placeholder="Enter Description" value="{{ old('description') }}"
                                            required></textarea>
                                        @error('description')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" name="date"
                                            class="{{ $errors->has('date') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="date" placeholder="Enter Date" value="{{ old('date') }}" required>
                                        @error('date')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" name="time"
                                            class="{{ $errors->has('time') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                            id="time" placeholder="Enter Time" value="{{ old('time') }}" required>
                                        @error('time')
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
                                            id="link" placeholder="Enter Link" value="{{ old('link') }}" required>
                                        @error('link')
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
                                            id="facebook" placeholder="Enter Facebook" value="{{ old('facebook') }}">
                                        @error('facebook')
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
                                            id="linkedin" placeholder="Enter Linkedin" value="{{ old('linkedin') }}">
                                        @error('linkedin')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" name="instagram" class="{{ $errors->has('instagram') ? 'form-control is-invalid text-red' : 'form-control' }}" id="instagram" placeholder="Enter Instagram" value="{{ old('instagram') }}">
                                        @error('instagram')
                                            <span class="text-danger d-block"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="youtube">Youtube</label>
                                        <input type="text" name="youtube" class="{{ $errors->has('youtube') ? 'form-control is-invalid text-red' : 'form-control' }}" id="youtube" placeholder="Enter Youtube" value="{{ old('youtube') }}">
                                        @error('youtube')
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
    </script>
@endsection