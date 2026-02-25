@extends('backend.partials.master')

@section('title', 'Manage Countries')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Countries</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Countries List</h5> -->
                @can('create country')
                    <a href="{{ route('admin.countries.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New Country
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Logo</th>
                            <th style="text-align: center">Country Name</th>
                            <th style="text-align: center">Country Code</th>
                            <th style="text-align: center">Available</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($countries as $country)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td>
                                    @if ($country->media->isNotEmpty())
                                        <img src="{{ asset('storage/' . $country->media->first()->path) }}"
                                            alt="{{ $country->name }}" class="country-logo" width="50" height="50" style="border-radius: 50%;">
                                    @else
                                        <span class="text-muted">No Logo</span>
                                    @endif
                                </td>
                                <td style="text-align: center">{{ $country->name }}</td>
                                <td style="text-align: center">{{ $country->code }}</td>
                                <td>
                                    @if ($country->is_available == 1)
                                        <span class="badge bg-success" style="text-align: center">Available</span>
                                    @else
                                        <span class="badge bg-danger" style="text-align: center">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                        @can('update country')
                                            <a href="{{ route('admin.countries.edit', $country->id) }}" style="text-align: center"
                                                class="btn btn-xs btn-outline-info" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete country')
                                            <button type="button" class="btn btn-xs btn-outline-danger" style="text-align: center"
                                                onclick="confirmDelete({{ $country->id }}, '{{ $country->name }}')" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $country->id }}"
                                                action="{{ route('admin.countries.destroy', $country->id) }}" method="POST"
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