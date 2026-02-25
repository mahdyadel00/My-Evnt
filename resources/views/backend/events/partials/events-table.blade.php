<div class="table-responsive p-3">
    @if($events->isEmpty())
        <div class="text-center py-5">
            <i class="ti ti-calendar-off ti-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Events Found</h5>
            <p class="text-muted">Events will appear here when they become available</p>
        </div>
    @else
        <table id="{{ $tableId }}" class="table table-striped border-top">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Event Name</th>
                    <th width="12%">Company</th>
                    <th width="8%">Poster</th>
                    <th width="10%">Category</th>
                    <th width="8%">City</th>
                    <th width="8%">Currency</th>
                    <th width="8%">Event Status</th>
                    <th width="6%">Format</th>
                    <th width="6%">Active</th>
                    <th width="6%">Exclusive</th>
                    <th width="8%">Price</th>
                    <th width="10%">Actions</th>
                    <th width="10%">Gallery</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ Str::limit($event->name, 30) }}</span>
                                @if($event->eventDates->isNotEmpty())
                                    <small class="text-muted">
                                        <i class="ti ti-calendar ti-xs me-1"></i>
                                        {{ $event->eventDates->first()->start_date ? \Carbon\Carbon::parse($event->eventDates->first()->start_date)->format('d/m/Y') : 'N/A' }}
                                    </small>
                                @endif
                            </div>
                        </td>
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
                                <img src="{{ asset('backend/assets/img/avatars/1.png') }}" alt="No Image" class="event-poster">
                            @endif
                        </td>
                        <td>{{ $event->category?->name ?? 'N/A' }}</td>
                        <td>{{ $event->city?->name ?? 'N/A' }}</td>
                        <td>{{ $event->currency?->code ?? 'N/A' }}</td>
                        <td>
                            <span class="badge status-badge {{ $event->statusLabel['class'] }}">
                                {{ $event->statusLabel['text'] }}
                            </span>
                        </td>
                        <td>
                            <a href="#" class="toggle-format" data-id="{{ $event->id }}">
                                <i class="fas {{ $event->format == 1 ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger' }} fa-2x"
                                    id="format-icon-{{ $event->id }}"></i>
                            </a>
                        </td>
                        <td>
                            <a href="#" class="toggle-active" data-id="{{ $event->id }}">
                                <i class="fas {{ $event->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger' }} fa-2x"
                                    id="active-icon-{{ $event->id }}"></i>
                            </a>
                        </td>
                        <td>
                            @if ($event->is_exclusive == 1)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </td>
                        <td>
                            @if($event->tickets->isNotEmpty())
                                {{ number_format($event->tickets->first()->price, 2) }}
                                <small class="text-muted d-block">{{ $event->currency?->code ?? '' }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-xs btn-outline-warning"
                                    title="View">
                                    <i class="ti ti-eye ti-sm"></i>
                                </a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-xs btn-outline-info"
                                    title="Edit">
                                    <i class="ti ti-pencil ti-sm"></i>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                    class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete">
                                        <i class="ti ti-trash ti-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-info">
                                    <i class="ti ti-photo ti-sm me-1"></i>
                                    {{ $event->gallery()->count() }}
                                </span>
                                <button class="btn btn-xs btn-outline-primary add-gallery-btn" data-event-id="{{ $event->id }}"
                                    data-bs-toggle="modal" data-bs-target="#galleryModal-{{ $event->id }}"
                                    title="Manage Gallery">
                                    <i class="ti ti-image ti-sm"></i>
                                    Manage Gallery
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>