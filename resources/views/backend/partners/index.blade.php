@extends('backend.partials.master')

@section('title', 'Our Partners')

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

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .dt-buttons .dt-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            margin: 4px 2px;
            border-radius: 4px;
            transition: opacity 0.3s;
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

        .btn-outline-primary,
        .btn-outline-info,
        .btn-outline-danger {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .partner-image {
            width: 60px;
            height: 60px;
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
                <li class="breadcrumb-item active">Our Partners</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Our Partners List</h5>
                <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-plus ti-xs me-1"></i> Add Partner
                </a>
            </div>
            <div class="card-datatable table-responsive">
                <table id="partnersTable" class="table border-top table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @php
                                        $image = $partner->media->first();
                                    @endphp
                                    @if ($image)
                                        <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $partner->name }}"
                                                class="partner-image">
                                        </a>
                                    @else
                                        <img src="{{ asset('backend/assets/img/avatars/1.png') }}" alt="Default Image"
                                            class="partner-image">
                                    @endif
                                </td>
                                <td>{{ $partner->name }}</td>
                                <td>{{ Str::limit($partner->description, 50) }}</td>
                                <td>
                                    <span class="badge {{ $partner->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $partner->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $partner->sort_order }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.partners.edit', $partner->id) }}"
                                            class="btn btn-xs btn-outline-info">
                                            <i class="ti ti-pencil ti-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                            method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#partnersTable').DataTable({
                responsive: true,
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[5, 'asc']], // Sort by Sort Order,
                dom: 'Bfrtip',
                paging: true,
                info: false,
                language: {
                    search: "Search Partners:",
                    emptyTable: "No Partners available",
                    // info: "Showing _START_ to _END_ of _TOTAL_ Partners"
                }
            });

            table.buttons().container()
                .appendTo('#partnersTable_wrapper .col-md-6:eq(0)');

            // SweetAlert2 for delete confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Deleted!',
                            'The Partner has been deleted.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
@endsection