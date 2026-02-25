@extends('backend.partials.master')

@section('title', 'Add Event')

@section('css')
    <link rel="stylesheet"
        href="{{ asset('backend') }}/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet" />
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .text-danger {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 0.375rem;
            margin-top: 1rem;
        }

        .dynamic-section {
            border: 1px dashed #ddd;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }

        #link_meeting {
            display: none;
        }
        #exclusive_image {
            display: none;
        }

        /* Calendar Styles */
        .calendar-section {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .calendar-header h5 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }

        #calendar {
            background: #fff;
            border-radius: 0.5rem;
        }

        .selected-dates-section {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .selected-date-item {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selected-date-item:last-child {
            margin-bottom: 0;
        }

        .date-info {
            flex: 1;
        }

        .date-info strong {
            color: #007bff;
            display: block;
            margin-bottom: 0.25rem;
        }

        .date-info small {
            color: #6c757d;
        }

        .remove-date-btn {
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .remove-date-btn:hover {
            background: #c82333;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            padding: 1rem;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
            transition: all 0.3s;
        }

        .step.active .step-number {
            background: #007bff;
            color: #fff;
        }

        .step.completed .step-number {
            background: #28a745;
            color: #fff;
        }

        .step-title {
            font-weight: 500;
            color: #6c757d;
        }

        .step.active .step-title {
            color: #007bff;
        }

        .step-line {
            width: 100px;
            height: 2px;
            background: #e9ecef;
            margin: 0 0.5rem;
        }

        .step.completed .step-line {
            background: #28a745;
        }

        .form-sections {
            display: none;
        }

        .form-sections.active {
            display: block;
        }

        .btn-next-step {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-next-step:hover {
            background: #218838;
        }

        .btn-prev-step {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            margin-right: 1rem;
        }

        .btn-prev-step:hover {
            background: #5a6268;
        }
    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.events.index') }}">All Events</a>
                </li>
                <li class="breadcrumb-item active">Add Event</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <div class="step-title">Select Date & Time</div>
            </div>
            <div class="step-line"></div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <div class="step-title">Event Details</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Add New Event</h5>
                <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-arrow-left ti-xs me-1"></i> Back to Events
                </a>
            </div>
            <div class="card-body">
                <form id="addEventForm" action="{{ route('admin.events.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1: Calendar Section -->
                    <div class="form-sections active" id="step1-section">
                        <div class="calendar-section">
                            <div class="calendar-header">
                                <h5><i class="ti ti-calendar ti-xs me-1"></i> Select Event Date & Time</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-multiple-dates">
                                    <i class="ti ti-plus ti-xs me-1"></i> Add Multiple Dates
                                </button>
                            </div>
                            
                            <div id="calendar"></div>

                            <div class="selected-dates-section" id="selected-dates-section" style="display: none;">
                                <h6 class="mb-3">Selected Dates & Times</h6>
                                <div id="selected-dates-list"></div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-next-step" id="next-to-details">
                                    Next: Event Details <i class="ti ti-arrow-right ti-xs ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Event Details Section -->
                    <div class="form-sections" id="step2-section">
                        <!-- Hidden inputs for dates -->
                        <div id="dates-container" style="display: none;">
                            <!-- Dates will be added here dynamically -->
                        </div>

                        <div class="row">
                            <!-- Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                                    <select class="{{ $errors->has('category_id') ? 'is-invalid' : '' }} form-control select2" name="category_id" id="category_id" required>
                                        <option value="">Select Event Category</option>
                                        @foreach ($eventCategories as $eventCategory)
                                            <option value="{{ $eventCategory->id }}" {{ old('category_id') == $eventCategory->id ? 'selected' : '' }}>
                                                {{ $eventCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sub Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_category_id" class="form-label">Sub Category</label>
                                    <select class="{{ $errors->has('sub_category_id') ? 'is-invalid' : '' }} form-control select2" name="sub_category_id" id="sub_category_id">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                    @error('sub_category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Currency -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select class="{{ $errors->has('currency_id') ? 'is-invalid' : '' }} form-control" name="currency_id" id="currency_id" required>
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                {{ $currency->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('currency_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Company -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_id" class="form-label">Company</label>
                                    <select class="{{ $errors->has('company_id') ? 'is-invalid' : '' }} form-control select2" name="company_id" id="company_id">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Supplied By -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="upload_by" class="form-label">Supplied By <span class="text-danger">*</span></label>
                                    <input type="text" class="{{ $errors->has('upload_by') ? 'is-invalid' : '' }} form-control" name="upload_by" id="upload_by"
                                        value="{{ old('upload_by') }}" placeholder="Enter Supplied By" required>
                                    @error('upload_by')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Organized By -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="organized_by" class="form-label">Organized By</label>
                                    <input type="text" class="form-control" name="organized_by" id="organized_by"
                                        value="{{ old('organized_by') }}" placeholder="Enter Organized By">
                                    @error('organized_by')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Event Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                                        placeholder="Enter Event Name" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Format -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="format" class="form-label">Format <span class="text-danger">*</span></label>
                                    <select class="form-control" name="format" id="format" required>
                                        <option value="">Select Format</option>
                                        <option value="1" {{ old('format') === '1' ? 'selected' : '' }}>Online</option>
                                        <option value="0" {{ old('format') === '0' ? 'selected' : '' }}>Offline</option>
                                    </select>
                                    @error('format')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Link Meeting (Online) -->
                            <div class="col-md-6" id="link_meeting">
                                <div class="form-group">
                                    <label for="link_meeting" class="form-label">Link Meeting</label>
                                    <input type="url" class="form-control" name="link_meeting" id="link_meeting_input"
                                        value="{{ old('link_meeting') }}" placeholder="Enter Link Meeting">
                                    @error('link_meeting')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location (Offline) -->
                            <div class="col-md-6" id="display_location">
                                <div class="form-group">
                                    <label for="location" class="form-label">Event Location</label>
                                    <input type="text" class="form-control" name="location" id="location"
                                        value="{{ old('location') }}" placeholder="Enter Event Location">
                                    @error('location')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- City (Offline) -->
                            <div class="col-md-6" id="display_city">
                                <div class="form-group">
                                    <label for="city_id" class="form-label">City</label>
                                    <select class="{{ $errors->has('city_id') ? 'is-invalid' : '' }} form-control select2" name="city_id" id="city_id">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Map (Offline) -->
                            <div class="col-md-12" id="display_map_location">
                                <div id="map"></div>
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            </div>

                            <!-- External Link -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="external_link" class="form-label">External Link</label>
                                    <input type="url" class="form-control" name="external_link" id="external_link"
                                        value="{{ old('external_link') }}" placeholder="Enter External Link">
                                    @error('external_link')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Facilities -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facility" class="form-label">Facilities</label>
                                    <select class="form-control select2" name="facility[]" id="facility" multiple>
                                        <option value="parking" {{ in_array('parking', old('facility', [])) ? 'selected' : '' }}>Parking</option>
                                        <option value="wifi" {{ in_array('wifi', old('facility', [])) ? 'selected' : '' }}>Wifi</option>
                                        <option value="food" {{ in_array('food', old('facility', [])) ? 'selected' : '' }}>Food</option>
                                        <option value="bathroom" {{ in_array('bathroom', old('facility', [])) ? 'selected' : '' }}>Bathroom</option>
                                        <option value="security" {{ in_array('security', old('facility', [])) ? 'selected' : '' }}>Security</option>
                                    </select>
                                    @error('facility')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Is Exclusive -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_exclusive" class="form-label">Is Exclusive <span class="text-danger">*</span></label>
                                    <select class="form-control" name="is_exclusive" id="is_exclusive" required>
                                        <option value="">Select Is Exclusive</option>
                                        <option value="1" {{ old('is_exclusive') == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('is_exclusive') == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('is_exclusive')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Exclusive Image -->
                            <div class="col-md-6" id="exclusive_image">
                                <div class="form-group">
                                    <label for="exclusive_image" class="form-label">Exclusive Image</label>
                                    <input type="file" class="form-control" name="exclusive_image" id="exclusive_image_input" accept="image/*">
                                    @error('exclusive_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Poster -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="poster" class="form-label">Event Poster <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="poster" id="poster" accept="image/*" required>
                                    @error('poster')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Banner -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="banner" class="form-label">Event Banner <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="banner" id="banner" accept="image/*" required>
                                    @error('banner')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Area -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area" class="form-label">Area</label>
                                    <input type="text" class="form-control" name="area" id="area" value="{{ old('area') }}" placeholder="Enter Area">
                                    @error('area')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="summary" class="form-label">Summary <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="summary" id="summary" placeholder="Enter Summary" required>{{ old('summary') }}</textarea>
                                    @error('summary')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" id="description"
                                        placeholder="Enter Description" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cancellation Policy -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cancellation_policy" class="form-label">Cancellation Policy</label>
                                    <textarea class="form-control" name="cancellation_policy" id="cancellation_policy"
                                        placeholder="Enter Cancellation Policy (Optional)">{{ old('cancellation_policy') }}</textarea>
                                    @error('cancellation_policy')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- SEO Section -->
                            <div class="col-md-12">
                                <div class="dynamic-section mt-4">
                                    <h6 class="mb-3"><i class="ti ti-search ti-xs me-1"></i> SEO Settings <small class="text-muted">(Optional - Leave empty to auto-generate from event data)</small></h6>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="meta_title" class="form-label">Meta Title <small class="text-muted">(Recommended: 50-60 characters)</small></label>
                                                <input type="text" class="form-control" name="meta_title" id="meta_title"
                                                    value="{{ old('meta_title') }}" placeholder="Meta Title" maxlength="60">
                                                <small class="form-text text-muted">If left empty, the event name will be used</small>
                                                @error('meta_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="meta_description" class="form-label">Meta Description <small class="text-muted">(Recommended: 150-160 characters)</small></label>
                                                <textarea class="form-control" name="meta_description" id="meta_description"
                                                    placeholder="Meta Description" rows="3" maxlength="160">{{ old('meta_description') }}</textarea>
                                                <small class="form-text text-muted">If left empty, a description will be generated from event description</small>
                                                @error('meta_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="meta_keywords" class="form-label">Meta Keywords <small class="text-muted">(Comma-separated)</small></label>
                                                <textarea class="form-control" name="meta_keywords" id="meta_keywords"
                                                    placeholder="keyword1, keyword2, keyword3" rows="2">{{ old('meta_keywords') }}</textarea>
                                                <small class="form-text text-muted">If left empty, keywords will be generated from event name, category, city, etc.</small>
                                                @error('meta_keywords')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End SEO Section -->
                            
                            <!-- Tickets Section -->
                            <div class="col-md-12">
                                <div class="dynamic-section" id="tickets-container">
                                    <h6 class="mb-3"><i class="ti ti-ticket ti-xs me-1"></i> Event Tickets</h6>
                                    <div class="row ticket-entry">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ticket_type_0" class="form-label">Ticket Type</label>
                                                <input type="text" class="form-control" name="ticket_type[]" id="ticket_type_0"
                                                    placeholder="Enter Ticket Type">
                                                @error('ticket_type.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price_0" class="form-label">Ticket Price</label>
                                                <input type="number" step="0.01" class="form-control" name="price[]"
                                                    id="price_0" placeholder="Enter Ticket Price">
                                                @error('price.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="quantity_0" class="form-label">Ticket Quantity</label>
                                                <input type="number" class="form-control" name="quantity[]" id="quantity_0"
                                                    placeholder="Enter Ticket Quantity">
                                                @error('quantity.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-ticket">
                                        <i class="ti ti-plus ti-xs me-1"></i> Add Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-prev-step" id="back-to-calendar">
                                <i class="ti ti-arrow-left ti-xs me-1"></i> Back to Calendar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy ti-xs me-1"></i> Save Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
    <!-- Google Maps -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDH7R5dJLjw1WB4bE3sNY8IQNPAuVAGSOE&libraries=places&callback=initMap"
        async defer></script>
        
    <script>
        // Global variables
        let selectedDates = [];
        let dateCounter = 0;
        let ticketCounter = 1;
        let calendar;

        $(document).ready(function () {
            // ============================================
            // FULLCALENDAR INITIALIZATION
            // ============================================
            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    select: function(info) {
                        // Show time selection modal when date is selected
                        showTimeSelectionModal(info.startStr, info.endStr);
                        calendar.unselect(); // Clear selection
                    },
                    eventClick: function(info) {
                        // Edit existing date when event is clicked
                        showTimeSelectionModal(info.startStr, info.endStr, info.event.id);
                    },
                    events: function(fetchInfo, successCallback, failureCallback) {
                        // Convert selected dates to calendar events
                        var events = selectedDates.map(function(date, index) {
                            var start = date.start_date + 'T' + (date.start_time || '00:00:00');
                            var end = date.end_date + 'T' + (date.end_time || '23:59:59');
                            return {
                                id: index,
                                title: date.start_time ? date.start_time + ' - ' + date.end_time : 'All Day',
                                start: start,
                                end: end,
                                backgroundColor: '#007bff',
                                borderColor: '#0056b3',
                                textColor: '#fff'
                            };
                        });
                        successCallback(events);
                    }
                });
                calendar.render();
            }

            // ============================================
            // TIME SELECTION MODAL
            // ============================================
            function showTimeSelectionModal(startDate, endDate, eventId = null) {
                var isEdit = eventId !== null;
                var existingDate = isEdit ? selectedDates[eventId] : null;

                Swal.fire({
                    title: isEdit ? 'Edit Date & Time' : 'Select Time',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" id="swal-start-date" class="form-control" value="${existingDate ? existingDate.start_date : startDate.split('T')[0]}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" id="swal-end-date" class="form-control" value="${existingDate ? existingDate.end_date : endDate.split('T')[0]}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" id="swal-start-time" class="form-control" value="${existingDate ? existingDate.start_time : ''}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" id="swal-end-time" class="form-control" value="${existingDate ? existingDate.end_time : ''}">
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: isEdit ? 'Update' : 'Add',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    width: '500px',
                    didOpen: () => {
                        // Set min date to today
                        var today = new Date().toISOString().split('T')[0];
                        var startDateInput = document.getElementById('swal-start-date');
                        var endDateInput = document.getElementById('swal-end-date');
                        
                        if (startDateInput) {
                            startDateInput.setAttribute('min', today);
                            
                            // Set end date min based on start date initially
                            var initialStartDate = startDateInput.value || today;
                            if (endDateInput) {
                                endDateInput.setAttribute('min', initialStartDate);
                            }
                            
                            // Update end date min when start date changes
                            startDateInput.addEventListener('change', function() {
                                if (endDateInput) {
                                    endDateInput.setAttribute('min', this.value);
                                    // If current end date is before new min, update it to match start date
                                    if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                                        endDateInput.value = this.value;
                                    }
                                }
                            });
                        } else if (endDateInput) {
                            endDateInput.setAttribute('min', today);
                        }
                    },
                    preConfirm: () => {
                        var startDate = document.getElementById('swal-start-date').value;
                        var endDate = document.getElementById('swal-end-date').value;
                        var startTime = document.getElementById('swal-start-time').value;
                        var endTime = document.getElementById('swal-end-time').value;

                        if (!startDate || !endDate) {
                            Swal.showValidationMessage('Please select both start and end dates');
                            return false;
                        }

                        // Allow same day - only check if end date is before start date
                        if (new Date(endDate) < new Date(startDate)) {
                            Swal.showValidationMessage('End date must be on or after start date');
                            return false;
                        }

                        // If same day, validate times only if both are provided
                        if (startDate === endDate) {
                            // If both times are provided, end time must be after start time
                            if (startTime && endTime && endTime <= startTime) {
                                Swal.showValidationMessage('End time must be after start time when dates are the same');
                                return false;
                            }
                            // If only one time is provided, it's valid (same day events can have partial times)
                            // If no times provided, it's also valid (all day event on same day)
                        }

                        return {
                            start_date: startDate,
                            end_date: endDate,
                            start_time: startTime || null,
                            end_time: endTime || null
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        if (isEdit) {
                            selectedDates[eventId] = result.value;
                        } else {
                            selectedDates.push(result.value);
                            dateCounter++;
                        }
                        updateSelectedDatesList();
                        if (calendar) {
                            calendar.refetchEvents();
                        }
                    }
                });
            }

            // ============================================
            // UPDATE SELECTED DATES LIST
            // ============================================
            function updateSelectedDatesList() {
                var container = $('#selected-dates-list');
                container.empty();

                if (selectedDates.length === 0) {
                    $('#selected-dates-section').hide();
                    return;
                }

                $('#selected-dates-section').show();

                selectedDates.forEach(function(date, index) {
                    var dateItem = `
                        <div class="selected-date-item">
                            <div class="date-info">
                                <strong>${formatDate(date.start_date)} ${date.start_time ? 'at ' + date.start_time : ''}</strong>
                                <small>To: ${formatDate(date.end_date)} ${date.end_time ? 'at ' + date.end_time : ''}</small>
                            </div>
                            <button type="button" class="remove-date-btn" data-index="${index}">
                                <i class="ti ti-trash ti-xs"></i> Remove
                            </button>
                        </div>
                    `;
                    container.append(dateItem);
                });

                // Add remove functionality
                $('.remove-date-btn').off('click').on('click', function() {
                    var index = $(this).data('index');
                    selectedDates.splice(index, 1);
                    updateSelectedDatesList();
                    if (calendar) {
                        calendar.refetchEvents();
                    }
                });
            }

            // ============================================
            // FORMAT DATE FOR DISPLAY
            // ============================================
            function formatDate(dateString) {
                var date = new Date(dateString);
                return date.toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            }

            // ============================================
            // ADD MULTIPLE DATES BUTTON
            // ============================================
            $('#add-multiple-dates').on('click', function() {
                var today = new Date().toISOString().split('T')[0];
                showTimeSelectionModal(today, today);
            });

            // ============================================
            // STEP NAVIGATION
            // ============================================
            $('#next-to-details').on('click', function() {
                if (selectedDates.length === 0) {
                    Swal.fire({
                        title: 'No Dates Selected',
                        text: 'Please select at least one date and time for the event',
                        icon: 'warning',
                        confirmButtonColor: '#007bff'
                    });
                    return;
                }

                // Generate hidden inputs for dates
                generateDateInputs();
                
                // Switch to step 2
                $('#step1').removeClass('active').addClass('completed');
                $('#step2').addClass('active');
                $('#step1-section').removeClass('active');
                $('#step2-section').addClass('active');

                // Scroll to top of form
                $('html, body').animate({
                    scrollTop: $('.step-indicator').offset().top - 20
                }, 500);
            });

            $('#back-to-calendar').on('click', function() {
                $('#step1').addClass('active').removeClass('completed');
                $('#step2').removeClass('active');
                $('#step1-section').addClass('active');
                $('#step2-section').removeClass('active');

                // Scroll to top
                $('html, body').animate({
                    scrollTop: $('.step-indicator').offset().top - 20
                }, 500);
            });

            // ============================================
            // GENERATE HIDDEN INPUTS FOR DATES
            // ============================================
            function generateDateInputs() {
                var container = $('#dates-container');
                container.empty();

                selectedDates.forEach(function(date, index) {
                    container.append(`
                        <input type="hidden" name="start_date[]" value="${date.start_date}">
                        <input type="hidden" name="end_date[]" value="${date.end_date}">
                        <input type="hidden" name="start_time[]" value="${date.start_time || ''}">
                        <input type="hidden" name="end_time[]" value="${date.end_time || ''}">
                    `);
                });
            }

            // ============================================
            // DYNAMIC SUB CATEGORY LOADING
            // ============================================
            $('#category_id').on('change', function () {
                var categoryId = $(this).val();
                var subCategorySelect = $('#sub_category_id');
                
                if (categoryId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.get.sub.category') }}",
                        data: {
                            event_category_id: categoryId
                        },
                        success: function (res) {
                            subCategorySelect.empty().append('<option value="">Select Sub Category</option>');
                            if (res && res.length) {
                                $.each(res, function (key, value) {
                                    subCategorySelect.append(
                                        `<option value="${value.id}">${value.name}</option>`
                                    );
                                });
                            }
                        },
                        error: function () {
                            subCategorySelect.empty().append('<option value="">No Sub Categories</option>');
                        }
                    });
                } else {
                    subCategorySelect.empty().append('<option value="">Select Sub Category</option>');
                }
            });

            // ============================================
            // FORMAT TOGGLE (ONLINE/OFFLINE)
            // ============================================
            $('#format').on('change', function () {
                var format = $(this).val();

                // Toggle Online fields
                $('#link_meeting').toggle(format === '1');
                // Link meeting is now optional for online events

                // Toggle Offline fields
                $('#display_location').toggle(format === '0');
                $('#display_map_location').toggle(format === '0');
                $('#display_city').toggle(format === '0');
            });

            // Trigger on page load if format is already selected
            if ($('#format').val()) {
                $('#format').trigger('change');
            }

            // ============================================
            // EXCLUSIVE IMAGE TOGGLE
            // ============================================
            $('#is_exclusive').on('change', function () {
                var is_exclusive = $(this).val();
                $('#exclusive_image').toggle(is_exclusive === '1');
                
                if (is_exclusive === '1') {
                    $('#exclusive_image_input').prop('required', false); // Optional
                } else {
                    $('#exclusive_image_input').prop('required', false);
                }
            });

            // Trigger on page load if is_exclusive is already selected
            if ($('#is_exclusive').val()) {
                $('#is_exclusive').trigger('change');
            }

            // ============================================
            // ADD/REMOVE TICKET ENTRIES
            // ============================================
            $('#add-ticket').on('click', function () {
                var ticketHtml = `
                    <div class="row ticket-entry">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ticket_type_${ticketCounter}" class="form-label">Ticket Type</label>
                                <input type="text" class="form-control" name="ticket_type[]" id="ticket_type_${ticketCounter}" placeholder="Enter Ticket Type">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_${ticketCounter}" class="form-label">Ticket Price</label>
                                <input type="number" step="0.01" class="form-control" name="price[]" id="price_${ticketCounter}" placeholder="Enter Ticket Price">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity_${ticketCounter}" class="form-label">Ticket Quantity</label>
                                <input type="number" class="form-control" name="quantity[]" id="quantity_${ticketCounter}" placeholder="Enter Ticket Quantity">
                                <button type="button" class="btn btn-sm btn-danger remove-ticket mt-2">
                                    <i class="ti ti-trash ti-xs me-1"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#tickets-container').append(ticketHtml);
                ticketCounter++;
            });

            // Remove Ticket Entry
            $(document).on('click', '.remove-ticket', function () {
                if ($('.ticket-entry').length > 1) {
                    $(this).closest('.ticket-entry').remove();
                } else {
                    Swal.fire({
                        title: 'Cannot Remove',
                        text: 'You must have at least one ticket entry',
                        icon: 'warning',
                        confirmButtonColor: '#007bff'
                    });
                }
            });

            // ============================================
            // INITIALIZE SELECT2
            // ============================================
            $('#category_id').select2({
                placeholder: "Select Event Category",
                allowClear: true,
                width: '100%'
            });

            $('#sub_category_id').select2({
                placeholder: "Select Sub Category",
                allowClear: true,
                width: '100%'
            });

            $('#company_id').select2({
                placeholder: "Select Company",
                allowClear: true,
                width: '100%'
            });

            $('#city_id').select2({
                placeholder: "Select City",
                allowClear: true,
                width: '100%'
            });

            $('#facility').select2({
                placeholder: "Select Facilities",
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });

            // ============================================
            // INITIALIZE CKEDITOR
            // ============================================
            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('description', {
                    font_defaultLabel: 'Arial',
                    fontSize_defaultLabel: '16px',
                    allowedContent: true,
                    language: 'en',
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_P,
                    forcePasteAsPlainText: true,
                    pasteFromWordPromptCleanup: true,
                    pasteFromWordRemoveFontStyles: true,
                    pasteFromWordRemoveStyles: true,
                    removeFormatAttributes: 'style,class,lang,width,height,align,hspace,valign',
                    toolbar: [
                        { 
                            name: 'document', 
                            items: ['Source', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates'] 
                        },
                        { 
                            name: 'clipboard', 
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] 
                        },
                        { 
                            name: 'editing', 
                            items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] 
                        },
                        { 
                            name: 'forms', 
                            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] 
                        },
                        '/',
                        { 
                            name: 'basicstyles', 
                            items: ['Bold', 'Italic', 'Underline', 'Strikethrough', 'Subscript', 'Superscript', '-', 'RemoveFormat'] 
                        },
                        { 
                            name: 'paragraph', 
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] 
                        },
                        { 
                            name: 'links', 
                            items: ['Link', 'Unlink', 'Anchor'] 
                        },
                        { 
                            name: 'insert', 
                            items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] 
                        },
                        '/',
                        { 
                            name: 'styles', 
                            items: ['Styles', 'Format', 'Font', 'FontSize'] 
                        },
                        { 
                            name: 'colors', 
                            items: ['TextColor', 'BGColor'] 
                        },
                        { 
                            name: 'tools', 
                            items: ['Maximize', 'ShowBlocks', '-', 'About'] 
                        }
                    ]
                });

                CKEDITOR.replace('cancellation_policy', {
                    font_defaultLabel: 'Arial',
                    fontSize_defaultLabel: '16px',
                    allowedContent: true,
                    language: 'en',
                    enterMode: CKEDITOR.ENTER_BR, 
                    shiftEnterMode: CKEDITOR.ENTER_P,
                    forcePasteAsPlainText: true,
                    pasteFromWordPromptCleanup: true,
                    pasteFromWordRemoveFontStyles: true,
                    pasteFromWordRemoveStyles: true,
                    removeFormatAttributes: 'style,class,lang,width,height,align,hspace,valign',
                    toolbar: [
                        { 
                            name: 'document', 
                            items: ['Source', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates'] 
                        },
                        { 
                            name: 'clipboard', 
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] 
                        },
                        { 
                            name: 'editing', 
                            items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] 
                        },
                        { 
                            name: 'forms', 
                            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] 
                        },
                        '/',
                        { 
                            name: 'basicstyles', 
                            items: ['Bold', 'Italic', 'Underline', 'Strikethrough', 'Subscript', 'Superscript', '-', 'RemoveFormat'] 
                        },
                        { 
                            name: 'paragraph', 
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] 
                        },
                        { 
                            name: 'links', 
                            items: ['Link', 'Unlink', 'Anchor'] 
                        },
                        { 
                            name: 'insert', 
                            items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] 
                        },
                        '/',
                        { 
                            name: 'styles', 
                            items: ['Styles', 'Format', 'Font', 'FontSize'] 
                        },
                        { 
                            name: 'colors', 
                            items: ['TextColor', 'BGColor'] 
                        },
                        { 
                            name: 'tools', 
                            items: ['Maximize', 'ShowBlocks', '-', 'About'] 
                        }
                    ]
                });

                CKEDITOR.replace('meta_description', {
                    font_defaultLabel: 'Arial',
                    fontSize_defaultLabel: '16px',
                    allowedContent: true,
                    language: 'en',
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_P,
                    forcePasteAsPlainText: true,
                    pasteFromWordPromptCleanup: true,
                    pasteFromWordRemoveFontStyles: true,
                    pasteFromWordRemoveStyles: true,
                    removeFormatAttributes: 'style,class,lang,width,height,align,hspace,valign',
                    toolbar: [
                        { 
                            name: 'document', 
                            items: ['Source', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates'] 
                        },
                        { 
                            name: 'clipboard', 
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] 
                        },
                        { 
                            name: 'editing', 
                            items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] 
                        },
                        { 
                            name: 'forms', 
                            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] 
                        },
                        '/',
                        { 
                            name: 'basicstyles', 
                            items: ['Bold', 'Italic', 'Underline', 'Strikethrough', 'Subscript', 'Superscript', '-', 'RemoveFormat'] 
                        },
                        { 
                            name: 'paragraph', 
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] 
                        },
                        { 
                            name: 'links', 
                            items: ['Link', 'Unlink', 'Anchor'] 
                        },
                        { 
                            name: 'insert', 
                            items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] 
                        },
                        '/',
                        { 
                            name: 'styles', 
                            items: ['Styles', 'Format', 'Font', 'FontSize'] 
                        },
                        { 
                            name: 'colors', 
                            items: ['TextColor', 'BGColor'] 
                        },
                        { 
                            name: 'tools', 
                            items: ['Maximize', 'ShowBlocks', '-', 'About'] 
                        }
                    ]
                });

                CKEDITOR.replace('meta_keywords', {
                    font_defaultLabel: 'Arial',
                    fontSize_defaultLabel: '16px',
                    allowedContent: true,
                    language: 'en',
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_P,
                    forcePasteAsPlainText: true,
                    pasteFromWordPromptCleanup: true,
                    pasteFromWordRemoveFontStyles: true,
                    pasteFromWordRemoveStyles: true,
                    removeFormatAttributes: 'style,class,lang,width,height,align,hspace,valign',
                    toolbar: [
                        { 
                            name: 'document', 
                            items: ['Source', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates'] 
                        },
                        { 
                            name: 'clipboard', 
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] 
                        },
                        { 
                            name: 'editing', 
                            items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] 
                        },
                        { 
                            name: 'forms', 
                            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] 
                        },
                        '/',
                        { 
                            name: 'basicstyles', 
                            items: ['Bold', 'Italic', 'Underline', 'Strikethrough', 'Subscript', 'Superscript', '-', 'RemoveFormat'] 
                        },
                        { 
                            name: 'paragraph', 
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] 
                        },
                        { 
                            name: 'links', 
                            items: ['Link', 'Unlink', 'Anchor'] 
                        },
                        { 
                            name: 'insert', 
                            items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] 
                        },
                        '/',
                        { 
                            name: 'styles', 
                            items: ['Styles', 'Format', 'Font', 'FontSize'] 
                        },
                        { 
                            name: 'colors', 
                            items: ['TextColor', 'BGColor'] 
                        },
                        { 
                            name: 'tools', 
                            items: ['Maximize', 'ShowBlocks', '-', 'About'] 
                        }
                    ]
                });
            }

            // ============================================
            // FORM VALIDATION & SUBMISSION
            // ============================================
            $('#addEventForm').on('submit', function (e) {
                e.preventDefault();
                
                // Validate required fields
                var isValid = true;
                var errors = [];

                // Check required fields
                if (!$('#category_id').val()) {
                    errors.push('Category is required');
                    isValid = false;
                }
                if (!$('#currency_id').val()) {
                    errors.push('Currency is required');
                    isValid = false;
                }
                if (!$('#upload_by').val()) {
                    errors.push('Supplied By is required');
                    isValid = false;
                }
                if (!$('#name').val()) {
                    errors.push('Event Name is required');
                    isValid = false;
                }
                if (!$('#format').val()) {
                    errors.push('Format is required');
                    isValid = false;
                }
                if (!$('#is_exclusive').val()) {
                    errors.push('Is Exclusive is required');
                    isValid = false;
                }
                if (!$('#poster').val()) {
                    errors.push('Event Poster is required');
                    isValid = false;
                }
                if (!$('#banner').val()) {
                    errors.push('Event Banner is required');
                    isValid = false;
                }
                if (!$('#summary').val()) {
                    errors.push('Summary is required');
                    isValid = false;
                }
                // Check if description has content (from CKEditor)
                var descriptionContent = '';
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.description) {
                    descriptionContent = CKEDITOR.instances.description.getData();
                } else {
                    descriptionContent = $('#description').val();
                }
                // Remove HTML tags and whitespace to check if there's actual content
                var textContent = descriptionContent.replace(/<[^>]*>/g, '').trim();
                if (!textContent) {
                    errors.push('Description is required');
                    isValid = false;
                }
                // Cancellation Policy is optional - no validation needed

                if (!isValid) {
                    Swal.fire({
                        title: 'Validation Error',
                        html: '<ul style="text-align: left;"><li>' + errors.join('</li><li>') + '</li></ul>',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                // Show confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to add this event?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update CKEditor content before submit
                        if (typeof CKEDITOR !== 'undefined') {
                            for (var instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].updateElement();
                            }
                        }
                        this.submit();
                    }
                });
            });

            // ============================================
            // CSRF TOKEN SETUP
            // ============================================
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ============================================
            // FILE INPUT PREVIEW (Optional)
            // ============================================
            $('#poster, #banner, #exclusive_image_input').on('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // You can add image preview here if needed
                        console.log('File selected:', file.name);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // ============================================
        // GOOGLE MAPS INITIALIZATION
        // ============================================
        function initMap() {
            // Check if Google Maps API is loaded
            if (typeof google !== 'object' || typeof google.maps !== 'object') {
                console.error("❌ Google Maps API not loaded correctly.");
                return;
            }

            // Check if map element exists
            var mapElement = document.getElementById('map');
            if (!mapElement) {
                console.log("Map element not found, skipping map initialization");
                return;
            }

            // Default location (Cairo, Egypt)
            var defaultLocation = {
                lat: 30.0444,
                lng: 31.2357
            };

            // Initialize the map with default location
            var map = new google.maps.Map(mapElement, {
                zoom: 12,
                center: defaultLocation,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            // Create a draggable marker with default location
            var marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
                title: 'Event Location'
            });

            // Initialize autocomplete for the location input
            var input = document.getElementById('location');
            if (input) {
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);

                // Handle place selection from autocomplete
                autocomplete.addListener('place_changed', function () {
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        console.error("No geometry available for selected place.");
                        return;
                    }

                    // Update map and marker
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    // Update hidden latitude and longitude inputs
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
                });
            }

            // Update coordinates when marker is dragged
            google.maps.event.addListener(marker, 'dragend', function (event) {
                document.getElementById('latitude').value = event.latLng.lat();
                document.getElementById('longitude').value = event.latLng.lng();

                // Reverse geocode to update location input
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: event.latLng }, function(results, status) {
                    if (status === 'OK' && results[0] && input) {
                        input.value = results[0].formatted_address;
                    }
                });
            });

            // Restore old values if they exist (e.g., after form validation error)
            var oldLat = $('#latitude').val();
            var oldLng = $('#longitude').val();
            if (oldLat && oldLng) {
                var restoredLocation = {
                    lat: parseFloat(oldLat),
                    lng: parseFloat(oldLng)
                };
                map.setCenter(restoredLocation);
                map.setZoom(15);
                marker.setPosition(restoredLocation);
            }
        }
    </script>
@endsection