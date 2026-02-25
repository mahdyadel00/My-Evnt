@extends('backend.partials.master')

@section('title', 'Manage Cities')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Cities</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Cities List</h5> -->
                @can('create city')
                    <a href="{{ route('admin.cities.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New City
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Country Name</th>
                            <th style="text-align: center">City Name</th>
                            <th style="text-align: center">Available</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cities as $city)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $city->country?->name ?? 'N/A' }}</td>
                                <td style="text-align: center">{{ $city->name }}</td>
                                <td>
                                    @if ($city->is_available == 1)
                                        <span class="badge bg-success" style="text-align: center">Available</span>
                                    @else
                                        <span class="badge bg-danger" style="text-align: center">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                        @can('update city')
                                            <a href="{{ route('admin.cities.edit', $city->id) }}"
                                                class="btn btn-xs btn-outline-info" style="text-align: center" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete city')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $city->id }}, '{{ $city->name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $city->id }}"
                                                action="{{ route('admin.cities.destroy', $city->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
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