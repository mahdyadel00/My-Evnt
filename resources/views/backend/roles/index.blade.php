@extends('backend.partials.master')

@section('title', 'Manage Roles')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Roles</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header border-bottom">
                @can('create role')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add Role
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $role->name }}</td>
                                <td>
                                    @if ($role->name !== 'owner')
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('update role')
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-xs btn-outline-info"
                                                    title="Edit">
                                                    <i class="ti ti-pencil ti-sm"></i>
                                                </a>
                                            @endcan
                                            @can('delete role')
                                                <button type="button" class="btn btn-xs btn-outline-danger"
                                                    onclick="confirmDelete({{ $role->id }})" title="Delete">
                                                    <i class="ti ti-trash ti-sm"></i>
                                                </button>
                                            @endcan
                                            <!-- Hidden Delete Form -->
                                            <form id="delete-form-{{ $role->id }}"
                                                action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection