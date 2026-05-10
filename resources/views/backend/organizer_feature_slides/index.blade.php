@extends('backend.partials.master')

@section('title', __('Organizer carousel'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ __('Organizer carousel') }}</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="mb-0">{{ __('Slides shown in “Are you organizer” section') }}</span>
                <a href="{{ route('admin.organizer-feature-slides.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus ti-xs"></i> {{ __('Add slide') }}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table border-top table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Sort') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Active') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->sort_order }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($row->title, 50) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit(strip_tags((string) $row->subtitle), 40) ?: '—' }}</td>
                                <td>{{ $row->is_active ? __('Yes') : __('No') }}</td>
                                <td>
                                    <a href="{{ route('admin.organizer-feature-slides.edit', $row->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="ti ti-pencil ti-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.organizer-feature-slides.destroy', $row->id) }}"
                                          method="post" class="d-inline-block"
                                          onsubmit="return confirm(@json(__('Delete this slide?')))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash ti-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('No slides yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
