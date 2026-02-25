@foreach ($events as $event)
    
<tr>
        <td>
            {{ $loop->iteration }}
        </td>
        <td>
            <div class="d-flex align-items-center">
                @foreach ($event->media as $media)
                    @if ($media->name == 'poster')
                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $event->name }}" class="avatar-img image-link" width="50" height="50">
                    @endif
                @endforeach
            </div>
        </td>
        <td>
            {{ $event->name }}
        </td>
        <td>
                {{ $event->company?->company_name }}
        </td>
        <td>
            {{ $event->category?->name }}
        </td>
        <td>
            {{ $event->city?->name }}
        </td>
        <td>
            @if($event->is_active)
                <span class="badge bg-label-success me-1">Active</span>
            @else
                <span class="badge bg-label-danger me-1">Inactive</span>
            @endif
        </td>
        <td>
            @if ($event->format == 1)
                <span class="badge bg-label-success me-1">Online</span>
            @else
                <span class="badge bg-label-danger me-1">Offline</span>
            @endif
        </td>
        <td>
            @if ($event->is_exclusive == 1)
                <span class="badge bg-label-success me-1">Yes</span>
            @else
                <span class="badge bg-label-danger me-1">No</span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-xs btn-outline-warning" title="View">
                    <i class="ti ti-eye ti-sm"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach