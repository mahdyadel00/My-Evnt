@extends('backend.partials.master')

@section('title', 'All Events')

@section('css')
    @include('backend.events.partials.style')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">All Events</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Events Management</h5>
                <div class="d-flex flex-wrap gap-2 flex-column justify-content-end align-items-end">
                    <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-outline-primary ">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New Event
                    </a>
                    <form id="export-form" class="export-form">
                        @csrf
                        <div class="d-flex gap-2">
                            <input type="date" class="form-control" name="from" id="from" required>
                            <input type="date" class="form-control" name="to" id="to" required>
                            <button type="submit" class="btn btn-sm btn-outline-primary w-25" id="filter-btn">
                                <i class="ti ti-filter ti-xs me-1"></i> Filter
                            </button>
                            <!-- reset button -->
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="reset-btn">
                                <i class="ti ti-refresh ti-xs me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-outline-success" id="export">
                                <i class="ti ti-download ti-xs me-1"></i> Export
                            </button>
                            <!-- filter by date -->
                        </div>
                    </form>
                </div>
            </div>

            <!-- Event Status Cards Row -->
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <!-- All Events Card -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-secondary text-white border-0 h-100 status-card active" data-filter="all"
                            role="button">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-list fs-3 me-2"></i>
                                    <span class="badge bg-light text-secondary fs-6">{{ $eventCounts['all'] }}</span>
                                </div>
                                <h6 class="card-title mb-0">All Events</h6>
                            </div>
                        </div>
                    </div>

                    <!-- New Events Card -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-info text-white border-0 h-100 status-card" data-filter="new" role="button">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-sparkles fs-3 me-2"></i>
                                    <span class="badge bg-light text-info fs-6">{{ $eventCounts['new'] }}</span>
                                </div>
                                <h6 class="card-title mb-0">New Events</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events Card -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-primary text-white border-0 h-100 status-card" data-filter="upcoming"
                            role="button">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-clock fs-3 me-2"></i>
                                    <span class="badge bg-light text-primary fs-6">{{ $eventCounts['upcoming'] }}</span>
                                </div>
                                <h6 class="card-title mb-0">Upcoming</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Past Events Card -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-dark text-white border-0 h-100 status-card" data-filter="past" role="button">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-history fs-3 me-2"></i>
                                    <span class="badge bg-light text-dark fs-6">{{ $eventCounts['past'] }}</span>
                                </div>
                                <h6 class="card-title mb-0">Past Events</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Events Card -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-success text-white border-0 h-100 status-card" data-filter="weekly"
                            role="button">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-calendar-week fs-3 me-2"></i>
                                    <span class="badge bg-light text-success fs-6">{{ $eventCounts['weekly'] }}</span>
                                </div>
                                <h6 class="card-title mb-0">This Week</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Events Card -->
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-warning text-white border-0 h-100 status-card" data-filter="monthly"
                            role="button">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-calendar-month fs-3 me-2"></i>
                                    <span class="badge bg-light text-warning fs-6">{{ $eventCounts['monthly'] }}</span>
                                </div>
                                <h6 class="card-title mb-0">This Month</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Content Area -->
                <div id="events-content">
                    <!-- All Events (Active by default) -->
                    <div class="events-section" id="all-events">
                        @include('backend.events.partials.events-table', ['events' => $allEvents, 'tableId' => 'eventsTable'])
                    </div>

                    <!-- New Events -->
                    <div class="events-section" id="new-events" style="display: none;">
                        @include('backend.events.partials.events-table', ['events' => $newEvents, 'tableId' => 'newEventsTable'])
                    </div>

                    <!-- Upcoming Events -->
                    <div class="events-section" id="upcoming-events" style="display: none;">
                        @include('backend.events.partials.events-table', ['events' => $upcomingEvents, 'tableId' => 'upcomingEventsTable'])
                    </div>

                    <!-- Past Events -->
                    <div class="events-section" id="past-events" style="display: none;">
                        @include('backend.events.partials.events-table', ['events' => $pastEvents, 'tableId' => 'pastEventsTable'])
                    </div>

                    <!-- Weekly Events -->
                    <div class="events-section" id="weekly-events" style="display: none;">
                        @include('backend.events.partials.events-table', ['events' => $weeklyEvents, 'tableId' => 'weeklyEventsTable'])
                    </div>

                    <!-- Monthly Events -->
                    <div class="events-section" id="monthly-events" style="display: none;">
                        @include('backend.events.partials.events-table', ['events' => $monthlyEvents, 'tableId' => 'monthlyEventsTable'])
                    </div>
                </div>
            </div>

            <!-- Edit Image Modal -->
            <div class="modal fade" id="editImageModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-edit ti-sm me-2"></i>
                                Edit Image Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editImageForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="editEventId" name="event_id">
                                <input type="hidden" id="editMediaId" name="media_id">

                                <!-- Current Image Preview -->
                                <div class="mb-3">
                                    <label class="form-label">Current Image</label>
                                    <div class="text-center">
                                        <img id="currentImagePreview" src="" class="img-fluid rounded"
                                            style="max-height: 200px; max-width: 100%;" alt="Current Image">
                                    </div>
                                </div>

                                <!-- New Image Upload -->
                                <div class="mb-3">
                                    <label for="editNewImage" class="form-label">Replace with New Image (Optional)</label>
                                    <input type="file" class="form-control" id="editNewImage" name="new_image"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <div class="form-text">
                                        <i class="ti ti-info-circle ti-sm me-1"></i>
                                        Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max 5MB)
                                    </div>
                                </div>

                                <!-- New Image Preview -->
                                <div id="newImagePreviewContainer" class="mb-3" style="display: none;">
                                    <label class="form-label">New Image Preview</label>
                                    <div class="text-center">
                                        <img id="newImagePreview" src="" class="img-fluid rounded"
                                            style="max-height: 200px; max-width: 100%;" alt="New Image Preview">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="editImageName" class="form-label">Image Name</label>
                                    <input type="text" class="form-control" id="editImageName" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="editImageDescription" class="form-label">Description (Optional)</label>
                                    <textarea class="form-control" id="editImageDescription" name="description" rows="3"
                                        placeholder="Enter image description..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="editImageOrder" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="editImageOrder" name="order" min="1"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editIsMain" name="is_main">
                                        <label class="form-check-label" for="editIsMain">
                                            Set as Main Image
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="saveImageEdit()">
                                <i class="ti ti-check ti-sm me-1"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Management Modals -->
            @foreach ($allEvents as $event)
                <div class="modal fade" id="galleryModal-{{ $event->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="ti ti-photo ti-sm me-2"></i>
                                    Gallery Management - {{ Str::limit($event->name, 50) }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Upload Section -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ti ti-upload ti-sm me-2"></i>
                                            Upload New Images
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form class="gallery-upload-form" data-event-id="{{ $event->id }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="images-{{ $event->id }}" class="form-label">
                                                        <i class="ti ti-photo ti-sm me-1"></i>
                                                        Select Images (Max 10 files, 5MB each)
                                                    </label>
                                                    <input type="file" name="images[]" id="images-{{ $event->id }}"
                                                        class="form-control" multiple
                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
                                                    <div class="form-text">
                                                        <i class="ti ti-info-circle ti-sm me-1"></i>
                                                        Supported formats: JPEG, PNG, JPG, GIF, WEBP
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="ti ti-upload ti-sm me-1"></i>
                                                        Upload Images
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="uploadProgress-{{ $event->id }}" class="mt-3" style="display: none;">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small class="text-muted">Uploading...</small>
                                            </div>
                                            <div id="imagePreview-{{ $event->id }}" class="mt-3" style="display: none;">
                                                <h6 class="mb-2">Selected Images:</h6>
                                                <div class="row g-2" id="previewContainer-{{ $event->id }}">
                                                    <!-- Preview images will be shown here -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Gallery Display Section -->
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ti ti-photo ti-sm me-2"></i>
                                            Gallery Images
                                        </h6>
                                        <div>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="refreshGallery({{ $event->id }})">
                                                <i class="ti ti-refresh ti-sm me-1"></i>
                                                Refresh
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="toggleReorderMode({{ $event->id }})">
                                                <i class="ti ti-arrows-sort ti-sm me-1"></i>
                                                Reorder
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="galleryContainer-{{ $event->id }}" class="row g-3">
                                            <!-- Gallery images will be loaded here -->
                                            <div class="col-12 text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mt-2 text-muted">Loading gallery...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('backend.events.partials.secript')
@endsection