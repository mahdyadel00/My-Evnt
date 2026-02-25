@extends('backend.partials.master')

@section('title', 'Show User')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/select2/select2.css" />
    <style>
        /* General Styles */
        .container-p-y {
            padding: 2rem 1.5rem;
        }

        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        .card-header {
            background: #f8f9fa;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        /* User Sidebar */
        .user-avatar-section {
            padding: 1.5rem 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            border-radius: 12px 12px 0 0;
        }

        .user-avatar-section img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-info h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .user-info .badge {
            background: #e9ecef;
            color: #666;
            font-weight: 500;
            padding: 0.4em 0.8em;
        }

        .info-container ul {
            padding: 0;
            margin: 1.5rem 0;
        }

        .info-container li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .info-container li span:first-child {
            color: #555;
            font-weight: 600;
        }

        .info-container li span:last-child {
            color: #777;
        }

        /* Events Table */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background: #007bff;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .table tbody tr {
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f1f3f5;
        }

        .event-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e9ecef;
        }

        .card-datatable {
            padding: 1rem;
        }

        /* Modal */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .form-label {
            font-weight: 500;
            color: #444;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background: #007bff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-label-secondary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .user-avatar-section img {
                width: 100px;
                height: 100px;
            }

            .info-container li {
                flex-direction: column;
                gap: 0.25rem;
            }

            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

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
                <li class="breadcrumb-item active">User Profile ({{ $user->user_name }})</li>
            </ol>
        </nav>

        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card mb-4">
                    <div class="user-avatar-section text-center">
                        @if ($user->media->isNotEmpty())
                            <img src="{{ asset('storage/' . $user->media->first()->path) }}"
                                class="img-fluid rounded-circle" alt="{{ $user->user_name }}" />
                        @else
                            <img src="{{ asset('assets/img/avatars/1.png') }}" class="img-fluid rounded-circle"
                                alt="Default avatar" />
                        @endif
                        <div class="user-info mt-3">
                            <h4 class="mb-2">{{ $user->user_name }}</h4>
                            <span
                                class="badge bg-label-secondary">{{ $user->roles->pluck('name')->implode(', ') ?: 'Public User' }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
                            <div class="d-flex align-items-start me-4 mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-checkbox ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">1.23k</p>
                                    <small>Events Favorited</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i
                                        class="ti ti-briefcase ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">568</p>
                                    <small>Events Completed</small>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 small text-uppercase text-muted">Details</p>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li><span class="fw-semibold">Username:</span> <span>{{ $user->user_name }}</span></li>
                                <li><span class="fw-semibold">Email:</span> <span>{{ $user->email }}</span></li>
                                <li><span class="fw-semibold">About:</span> <span>{!! $user->about !!}</span></li>
                                <li><span class="fw-semibold">Contact:</span> <span>{{ $user->phone ?? 'N/A' }}</span></li>
                                <li><span class="fw-semibold">Country:</span>
                                    <span>{{ $user->city?->country?->name ?? 'N/A' }}</span></li>
                                <li><span class="fw-semibold">City:</span> <span>{{ $user->city?->name ?? 'N/A' }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center mt-4">
                                <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editUser">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i
                                class="ti ti-user-check ti-xs me-1"></i>Account</a>
                    </li>
                </ul>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Events</h5>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table class="table border-top table-striped text-center" id="eventsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>City</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->events as $event)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->company?->company_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($event->media->isNotEmpty())
                                                <img src="{{ asset('storage/' . $event->media->first()->path) }}"
                                                    class="event-image" alt="{{ $event->name }}" />
                                            @else
                                                <img src="{{ asset('assets/img/avatars/1.png') }}" class="event-image"
                                                    alt="Default" />
                                            @endif
                                        </td>
                                        <td>{{ $event->eventCategory?->name ?? 'N/A' }}</td>
                                        <td>{{ $event->city?->name ?? 'N/A' }}</td>
                                        <td>{{ $event->location ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No Events Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ User Content -->
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Edit User Information</h3>
                            <p class="text-muted">Update user details below.</p>
                        </div>
                        <form id="editUserForm" class="row g-3" action="{{ route('admin.users.update', $user->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control"
                                    value="{{ old('first_name', $user->first_name) }}" required />
                                @error('first_name')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="middle_name">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" class="form-control"
                                    value="{{ old('middle_name', $user->middle_name) }}" required />
                                @error('middle_name')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control"
                                    value="{{ old('last_name', $user->last_name) }}" required />
                                @error('last_name')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="user_name">Username</label>
                                <input type="text" id="user_name" name="user_name" class="form-control"
                                    value="{{ old('user_name', $user->user_name) }}" required />
                                @error('user_name')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required />
                                @error('email')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}" required />
                                @error('phone')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="country_id">Country</label>
                                <select id="country_id" name="country_id" class="form-select select2" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_id', $user->city?->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="city_id">City</label>
                                <select id="city_id" name="city_id" class="form-select select2" required>
                                    <option value="">Select City</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="about">About</label>
                                <textarea id="about" name="about" class="form-control" rows="4" required>{{ old('about', $user->about) }}</textarea>
                                @error('about')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="image">Profile Image</label>
                                <input type="file" id="image" name="image" class="form-control"
                                    accept="image/*" />
                                @if ($user->media->isNotEmpty())
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->media->first()->path) }}"
                                            class="img-fluid rounded" alt="{{ $user->user_name }}" width="100"
                                            height="100" />
                                    </div>
                                @endif
                                @error('image')
                                    <div class="text-danger"><i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Changes</button>
                                <button type="button" class="btn btn-label-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit User Modal -->
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend') }}/assets/js/app-user-list.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
                dropdownParent: $('#editUser') // Ensure dropdown works inside modal
            });

            // Initialize DataTable for events
            $('#eventsTable').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: "Search events:",
                    emptyTable: "No events found",
                    info: "Showing _START_ to _END_ of _TOTAL_ events"
                }
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

            // Trigger change on modal open to populate cities
            $('#editUser').on('shown.bs.modal', function() {
                $('#country_id').trigger('change');
            });

            // Validate file type for image
            $('#image').on('change', function() {
                const file = this.files[0];
                if (file && !file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    this.value = '';
                }
            });
        });
    </script>
@endsection
