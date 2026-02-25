@extends('backend.partials.master')

@section('title', 'Add Country')


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.countries.index') }}">Manage Countries</a>
                </li>
                <li class="breadcrumb-item active">Add Country</li>
            </ol>
        </nav>

        <form id="add" action="{{ route('admin.countries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add New Country</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3"> <!-- Grid with gaps -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Country Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Country Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="code">Country Code</label>
                                        <input type="text" name="code" class="form-control" id="code"
                                            placeholder="Enter Country Code (e.g., US)" value="{{ old('code') }}" required>
                                        @error('code')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="is_available">Availability</label>
                                        <select name="is_available" id="is_available" class="form-control" required>
                                            <option value="" disabled selected>Select Availability</option>
                                            <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>
                                                Available</option>
                                            <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Not
                                                Available</option>
                                        </select>
                                        @error('is_available')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="logo">Country Logo</label>
                                        <input type="file" name="logo" class="form-control" id="logo" accept="image/*"
                                            required>
                                        @error('logo')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                <i class="ti ti-device-floppy ti-xs"></i> Add Country
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection