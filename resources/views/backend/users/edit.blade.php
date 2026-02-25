@extends('backend.partials.master')

@section('title', 'Edit User')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}">All Users</a>
                </li>
                <li class="breadcrumb-item active">Edit User ({{ $user->user_name }})</li>
            </ol>
        </nav>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Edit User</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country_id">Country</label>
                                        <select name="country_id" id="country_id" class="form-control select2" required>
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country_id', $user->city?->country_id) == $country->id ? 'selected' : '' }}>
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
                                        <label for="city_id">City</label>
                                        <select name="city_id" id="city_id" class="form-control select2" required>
                                            <option value="">Select City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            placeholder="Enter First Name"
                                            value="{{ old('first_name', $user->first_name) }}" required>
                                        @error('first_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name" class="form-control"
                                            placeholder="Enter Middle Name"
                                            value="{{ old('middle_name', $user->middle_name) }}" required>
                                        @error('middle_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            placeholder="Enter Last Name" value="{{ old('last_name', $user->last_name) }}"
                                            required>
                                        @error('last_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="user_name">User Name</label>
                                        <input type="text" name="user_name" id="user_name" class="form-control"
                                            placeholder="Enter User Name" value="{{ old('user_name', $user->user_name) }}"
                                            required>
                                        @error('user_name')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Enter Email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="Enter Phone" value="{{ old('phone', $user->phone) }}" required>
                                        @error('phone')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="form-control select2" required>
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="about">About</label>
                                        <textarea name="about" id="about" class="form-control" placeholder="Enter About" required>{{ old('about', $user->about) }}</textarea>
                                        @error('about')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" id="image" class="form-control"
                                            accept="image/*">
                                        @error('image')
                                            <span class="text-danger d-block"><i
                                                    class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}</span>
                                        @enderror
                                        @if ($user->media->isNotEmpty())
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $user->media->first()->path) }}"
                                                    alt="{{ $user->user_name }}" class="user-image" style="width: 100px; height: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                <i class="ti ti-device-floppy ti-xs"></i> Save Changes
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
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).attr('data-placeholder') || 'Select an option';
                },
                allowClear: true
            });

            // Get cities by country id
            $('#country_id').on('change', function() {
                var country_id = $(this).val();
                if (country_id) {
                    $.ajax({
                        url: "{{ route('admin.get_cities') }}",
                        type: "GET",
                        data: {
                            country_id: country_id
                        },
                        success: function(response) {
                            $('#city_id').empty().append(
                                '<option value="">Select City</option>');
                            $.each(response, function(key, value) {
                                $('#city_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                            // Preselect the current city if available
                            $('#city_id').val("{{ old('city_id', $user->city_id) }}").trigger(
                                'change');
                        },
                        error: function() {
                            alert('Error fetching cities. Please try again.');
                        }
                    });
                } else {
                    $('#city_id').empty().append('<option value="">Select City</option>');
                }
            });

            // Trigger change on page load to populate cities
            $('#country_id').trigger('change');

        });
    </script>
@endsection
