@extends('backend.partials.master')

@section('title', 'Events Reports')

    
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4">📊 Events Reports</h4>

    {{-- Search Input --}}
    <div class="mb-3">
        <input type="text" class="form-control search-input" placeholder="🔍 Search events by name...">
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table id="example3" class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Category</th>
                    <th>City</th>
                    <th>Currency</th>
                    <th>Status</th>
                    <th>Active</th>
                    <th>Exclusive</th>
                    <th>Normal Price</th>
                    <th>Summary</th>
                    <th>Format</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->company?->company_name ?? '-' }}</td>
                        <td>{{ $event->category?->name ?? '-' }}</td>
                        <td>{{ $event->city?->name ?? '-' }}</td>
                        <td>{{ $event->currency?->code ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $event->status == 1 ? 'success' : 'secondary' }}">
                                {{ $event->status == 1 ? 'Online' : 'Offline' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $event->is_active == 1 ? 'success' : 'danger' }}">
                                {{ $event->is_active == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $event->is_exclusive == 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $event->normal_price ?? '-' }}</td>
                        <td>{{ Str::limit($event->summary, 50) }}</td>
                        <td>{{ $event->format == 1 ? 'Online' : 'Offline' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted">No events found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
