@extends('backend.partials.master')

@section('title', 'Manage Webinars')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Webinars</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Cities List</h5> -->
                
                    <a href="{{ route('admin.webinars.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New Webinar
                    </a>
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Webinar Name</th>
                            <th style="text-align: center">Company Name</th>
                            <th style="text-align: center">Date</th>
                            <th style="text-align: center">Time</th>
                            <th style="text-align: center">Slug</th>
                            <th style="text-align: center">Link</th>
                            <th style="text-align: center">Status</th>  
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($webinars as $webinar)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                @foreach($webinar->media as $media)
                                    <td style="text-align: center"><img src="{{ asset('storage/' . $media->path) }}" alt="Webinar Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"></td>
                                @endforeach
                                <td style="text-align: center">{{ $webinar->webinar_name }}</td>
                                <td style="text-align: center">{{ $webinar->company_name }}</td>
                                <td style="text-align: center">{{ date('d-m-Y', strtotime($webinar->date)) }}</td>
                                <td style="text-align: center">{{ date('H:i', strtotime($webinar->time)) }}</td>
                                <td style="text-align: center">{{ $webinar->slug }}
                                    <a href="{{ route('webinar.show', $webinar->slug) }}" target="_blank">
                                        <i class="ti ti-link ti-sm"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    <a href="{{ $webinar->link }}" target="_blank">
                                        <i class="ti ti-link ti-sm"></i>
                                    </a>
                                </td>
                                <td>
                                    @if ($webinar->status == true)
                                        <span class="badge bg-success" style="text-align: center">Active</span>
                                    @else
                                        <span class="badge bg-danger" style="text-align: center">Not Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                            <a href="{{ route('admin.webinars.edit', $webinar->id) }}"
                                                class="btn btn-xs btn-outline-info" style="text-align: center" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $webinar->id }}, '{{ $webinar->webinar_name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $webinar->id }}"
                                                action="{{ route('admin.webinars.destroy', $webinar->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
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