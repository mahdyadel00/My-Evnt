@extends('backend.partials.master')

@section('title', 'Show Company')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-avatar-section img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }

        .info-container ul li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-container ul li span:first-child {
            flex: 0 0 150px;
            color: #495057;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.875rem;
            padding: 0.5em 0.75em;
        }

        .dt-buttons .dt-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            margin: 4px 2px;
            border-radius: 4px;
            transition: opacity 0.4s;
        }

        .dt-buttons .dt-button.buttons-copy {
            background-color: #6c757d;
        }

        .dt-buttons .dt-button.buttons-excel {
            background-color: #28a745;
        }

        .dt-buttons .dt-button.buttons-pdf {
            background-color: #dc3545;
        }

        .dt-buttons .dt-button.buttons-print {
            background-color: #17a2b8;
        }

        .dt-buttons .dt-button:hover {
            opacity: 0.8;
        }

        .event-poster {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
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
                    <a href="{{ route('admin.companies.index') }}">All Companies</a>
                </li>
                <li class="breadcrumb-item active">Show Company</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="row">
            <!-- Company Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section text-center">
                            @php
                                $logo = $company->media->firstWhere('name', 'logo');
                            @endphp
                            @if ($logo)
                                <a href="{{ asset('storage/' . $logo->path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $logo->path) }}" class="img-fluid rounded-circle" alt="Company Logo">
                                </a>
                            @else
                                <img src="{{ asset('backend/assets/img/avatars/1.png') }}" class="img-fluid rounded-circle" alt="Default Avatar">
                            @endif
                            <div class="user-info mt-3">
                                <h4 class="mb-2">{{ $company->company_name }}</h4>
                                <span class="badge bg-label-primary">Company</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap mt-4 pt-3 pb-4 border-bottom">
                            <div class="d-flex align-items-start me-4 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-checkbox ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $company->events->count() }}</p>
                                    <small>Total Events</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-briefcase ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $company->events->where('status', 1)->count() }}</p>
                                    <small>Active Events</small>
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-4 mb-3">Details</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li>
                                    <span class="fw-semibold">First Name:</span>
                                    <span>{{ $company->first_name ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Last Name:</span>
                                    <span>{{ $company->last_name ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="fw-semibold">User Name:</span>
                                    <span>{{ $company->user_name ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Company Name:</span>
                                    <span>{{ $company->company_name ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Email:</span>
                                    <span><a href="mailto:{{ $company->email }}">{{ $company->email ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Phone:</span>
                                    <span><a href="tel:{{ $company->phone }}">{{ $company->phone ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">WhatsApp:</span>
                                    <span><a href="https://wa.me/{{ $company->whats_app }}" target="_blank">{{ $company->whats_app ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Website:</span>
                                    <span><a href="{{ $company->website }}" target="_blank">{{ $company->website ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Facebook:</span>
                                    <span><a href="{{ $company->facebook }}" target="_blank">{{ $company->facebook ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Twitter:</span>
                                    <span><a href="{{ $company->twitter }}" target="_blank">{{ $company->twitter ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Instagram:</span>
                                    <span><a href="{{ $company->instagram }}" target="_blank">{{ $company->instagram ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">LinkedIn:</span>
                                    <span><a href="{{ $company->linkedin }}" target="_blank">{{ $company->linkedin ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">YouTube:</span>
                                    <span><a href="{{ $company->youtube }}" target="_blank">{{ $company->youtube ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Snapchat:</span>
                                    <span><a href="{{ $company->snapchat }}" target="_blank">{{ $company->snapchat ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">TikTok:</span>
                                    <span><a href="{{ $company->tiktok }}" target="_blank">{{ $company->tiktok ?? 'N/A' }}</a></span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Status:</span>
                                    <span>
                                        @if ($company->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Address:</span>
                                    <span>{{ $company->address ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="fw-semibold">Description:</span>
                                    <span>{{ Str::limit(strip_tags($company->description), 100) ?? 'N/A' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Company Sidebar -->

            <!-- Company Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Company Events</h5>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="eventsTable" class="table border-top table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Poster</th>
                                    <th>Category</th>
                                    <th>City</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($company->events as $event)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.events.show', $event->id) }}">{{ $event->name }}</a>
                                        </td>
                                        <td>{{ $event->company->company_name }}</td>
                                        <td>
                                            @php
                                                $poster = $event->media->firstWhere('name', 'poster');
                                            @endphp
                                            @if ($poster)
                                                <a href="{{ asset('storage/' . $poster->path) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $poster->path) }}" class="event-poster" alt="Event Poster">
                                                </a>
                                            @else
                                                <img src="{{ asset('backend/assets/img/avatars/1.png') }}" class="event-poster" alt="Default Poster">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.event_categories.index') }}">{{ $event->eventCategory?->name ?? 'N/A' }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.cities.index') }}">{{ $event->city?->name ?? 'N/A' }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ $event->location }}" target="_blank">{{ Str::limit($event->location, 30) }}</a>
                                        </td>
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
            <!-- /Company Content -->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            var table = $('#eventsTable').DataTable({
                responsive: true,
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[1, 'asc']], // Sort by Event Name
                language: {
                    search: "Search events:",
                    emptyTable: "No events available",
                    info: "Showing _START_ to _END_ of _TOTAL_ events"
                }
            });

            table.buttons().container()
                .appendTo('#eventsTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
