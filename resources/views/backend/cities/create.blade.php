@extends('backend.partials.master')

@section('title', 'Add City')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.cities.index') }}">Manage Cities</a>
                </li>
                <li class="breadcrumb-item active">Add City</li>
            </ol>
        </nav>

        <form id="add" action="{{ route('admin.cities.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add New City</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country_id">Country Name</label>
                                        <select name="country_id" id="country_id" class="{{ $errors->has('country_id') ? 'form-control is-invalid' : 'form-control text-red' }}">
                                            <option value="" disabled selected>Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">City Name</label>
                                        <input type="text" name="name" class="{{ $errors->has('name') ? 'form-control is-invalid text-red' : 'form-control' }}"
                                         id="name" placeholder="Enter City Name" value="{{ old('name') }}" required>
                                        @error('name')
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
