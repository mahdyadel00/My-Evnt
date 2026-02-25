@extends('backend.partials.master')

@section('title', 'Manage Categories')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Categories</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Manage Categories</h5> -->
                <a href="{{ route('admin.event_categories.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-plus ti-xs me-1"></i> Add Category
                </a>
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Is Parent</th>
                            <th style="text-align: center">Parent</th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eventCategories as $event_category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($event_category->media->isNotEmpty())
                                        @if ($event_category->media->first()->path)
                                        <a href="{{ asset('storage/' . $event_category->media->first()->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $event_category->media->first()->path) }}" alt="{{ $event_category->name }}"
                                                class="category-image" width="50" height="50">
                                        </a>
                                    @else
                                        <img src="{{ asset('backend/assets/img/avatars/1.png') }}" alt="Default Image"
                                            class="category-image" width="50" height="50">
                                    @endif
                                    @endif
                                </td>
                                <td>{{ $event_category->name }}</td>
                                <td>
                                    <span class="badge {{ $event_category->parent_id == null ? 'bg-success' : 'bg-info' }}">
                                        {{ $event_category->parent_id == null ? 'Parent' : 'Child' }}
                                    </span>
                                </td>
                                <td>{{ $event_category->parent?->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('update category')
                                            <a href="{{ route('admin.event_categories.edit', $event_category->id) }}"
                                                class="btn btn-xs btn-outline-info">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete category')
                                        <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $event_category->id }}, '{{ $event_category->name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $event_category->id }}"
                                                action="{{ route('admin.event_categories.destroy', $event_category->id) }}" method="POST"
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