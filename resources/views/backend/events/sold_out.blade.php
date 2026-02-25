@extends('backend.partials.master')

@section('title', 'Event Sold Out')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('backend') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
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

        .event-poster {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .btn-outline-primary,
        .btn-outline-info,
        .btn-outline-danger,
        .btn-outline-warning {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .export-form {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .modal-body .form-control {
            border-radius: 0.375rem;
        }

        #export {
            padding: 0.5rem 2.25rem;
            font-size: 1rem;
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
                <li class="breadcrumb-item active">Event Sold Out</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Event Sold Out List</h5>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table id="eventsTable" class="table border-top table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Company Name</th>
                            <th>Poster</th>
                            <th>Category</th>
                            <th>City</th>
                            <th>Currency</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->company?->company_name ?? ($event->organized_by ?? 'N/A') }}</td>
                                <td>
                                    @php
                                        $poster = $event->media->firstWhere('name', 'poster');
                                    @endphp
                                    @if ($poster)
                                        <a href="{{ asset('storage/' . $poster->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $poster->path) }}" alt="{{ $event->name }}"
                                                class="event-poster">
                                        </a>
                                    @else
                                        <img src="{{ asset('backend/assets/img/avatars/1.png') }}" alt="No Poster"
                                            class="event-poster">
                                    @endif
                                </td>
                                <td>{{ $event->category?->name ?? 'N/A' }}</td>
                                <td>{{ $event->city?->name ?? 'N/A' }}</td>
                                <td>{{ $event->currency?->code ?? 'N/A' }}</td>                               
                                <td>{{ $event->tickets->isNotEmpty() ? number_format($event->tickets->first()->price, 2) : '0.00' }}
                                </td>
                                <!-- <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.events.show', $event->id) }}"
                                            class="btn btn-xs btn-outline-warning">
                                            <i class="ti ti-eye ti-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->id) }}"
                                            class="btn btn-xs btn-outline-info">
                                            <i class="ti ti-pencil ti-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td> -->
                                
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
        $(document).ready(function () {
            var table = $('#eventsTable').DataTable({
                responsive: true,
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [1, 'asc']
                ], // Sort by Event Name
                language: {
                    search: "Search Events:",
                    emptyTable: "No Events available",
                    info: "Showing _START_ to _END_ of _TOTAL_ Events"
                }
            });

            table.buttons().container()
                .appendTo('#eventsTable_wrapper .col-md-6:eq(0)');

            // SweetAlert2 for delete confirmation
            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this event!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Deleted!',
                            'The event has been deleted.',
                            'success'
                        );
                    }
                });
            });

            // Gallery upload form submission
            $('.gallery-upload-form').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                var eventId = $(this).data('event-id');
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.events.gallery.upload') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        $('#uploadStatus-' + eventId).html(
                            '<p class="text-info">Uploading...</p>');
                    },
                    success: function (response) {
                        $('#uploadStatus-' + eventId).html(
                            '<p class="text-success">' + response.message + '</p>');
                        $(form).find('input[type="file"]').val('');
                        setTimeout(() => {
                            $('#galleryModal-' + eventId).modal('hide');
                            $('#uploadStatus-' + eventId).empty();
                        }, 2000);
                    },
                    error: function (xhr) {
                        let errorMsg = xhr.responseJSON?.error || 'Error uploading images!';
                        $('#uploadStatus-' + eventId).html(
                            '<p class="text-danger">' + errorMsg + '</p>');
                    }
                });
            });
        });

    </script>
@endsection