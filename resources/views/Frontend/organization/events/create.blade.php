@extends('Frontend.organization.events.inc.master')
@section('title', 'Create New Event')
@section('content')
    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px;">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="{{ route('home') }}">
                        <i class='bx bx-home-alt me-1'></i>Home
                    </a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">
                        <i class='bx bx-plus-circle me-1'></i>Create New Event
                    </a>
                </li>
            </ul>
            <h1><i class='bx bx-calendar-plus me-2'></i>Create New Event</h1>
        </div>
    </div>
    <!-- end breadcrumb  -->

    <!-- Progress Bar -->
    <div class="progress-container mb-4">
        <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
        <div class="progress-steps mt-2">
            <span class="step active">1. Event Info</span>
            <span class="step">2. Location</span>
            <span class="step">3. Tickets</span>
            <span class="step">4. Payment</span>
            <span class="step">5. Review</span>
        </div>
    </div>

    <!-- start form-data -->
    <div class="form-data">
        <div class="head">
            <h3><i class='bx bx-info-circle me-2'></i>Event Information</h3>
            <p class="text-muted">Step 1 of 5 - Basic event details</p>
        </div>

        <!-- Info Notice -->
        <div class="alert alert-info mb-4" role="alert">
            <i class='bx bx-info-circle me-2'></i>
            <strong>Getting Started:</strong> Fill in the basic information about your event. All fields marked with <span
                class="text-danger">*</span> are required.
        </div>

        <!-- form step 1 -->
        <form class="row g-3" method="post" action="{{ route('organization.events.store') }}" enctype="multipart/form-data"
            id="eventForm">
            @csrf

            <!-- Basic Information Section -->
            <div class="col-12">
                <h5 class="section-title"><i class='bx bx-edit me-2'></i>Basic Information</h5>
            </div>

            <!-- event name -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class='bx bx-text me-1'></i>Event Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" class="form-control p-2" placeholder="Enter event name" required
                    maxlength="255" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
                <div class="form-text">Choose a clear and descriptive name for your event</div>
            </div>

            <!-- Currency -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class='bx bx-dollar me-1'></i>Currency <span class="text-danger">*</span>
                </label>
                <select class="form-select p-2" name="currency_id" required>
                    <option disabled selected>Select Currency</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                            {{ $currency->code }} - {{ $currency->name ?? $currency->code }}
                        </option>
                    @endforeach
                </select>
                @error('currency_id')
                    <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Category -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class='bx bx-category me-1'></i>Category <span class="text-danger">*</span>
                </label>
                <select class="form-select p-2" name="category_id" required>
                    <option disabled selected>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Sub Category -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class='bx bx-subdirectory-right me-1'></i>Sub Category <span class="text-danger">*</span>
                </label>
                <select class="form-select p-2" name="sub_category_id" required disabled>
                    <option disabled selected>Select Sub Category</option>
                </select>
                @error('sub_category_id')
                    <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
                <div class="form-text">Please select a category first</div>
            </div>

            <!-- Description Section -->
            <div class="col-12 mt-4">
                <h5 class="section-title"><i class='bx bx-detail me-2'></i>Event Details</h5>
            </div>

            <!-- description -->
            <div class="col-12">
                <label for="description" class="form-label">
                    <i class='bx bx-text me-1'></i>Event Description <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="description" rows="4" name="description" required
                    placeholder="Describe your event in detail..." maxlength="2000">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <span id="descriptionCount">0</span>/2000 characters
                </div>
            </div>

            <!-- Cancellation Policy -->
            <div class="col-12">
                <label for="cancellation_policy" class="form-label">
                    <i class='bx bx-shield-x me-1'></i>Event Cancellation Policy <span class="text-muted">(Optional)</span>
                </label>
                <textarea class="form-control" id="cancellation_policy" rows="3" name="cancellation_policy"
                    placeholder="Specify your cancellation and refund policy..."
                    maxlength="1000">{{ old('cancellation_policy') }}</textarea>
                @error('cancellation_policy')
                    <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <span id="policyCount">0</span>/1000 characters
                </div>
            </div>

            <!-- Date & Time Section -->
            <div class="col-12 mt-4">
                <h5 class="section-title"><i class='bx bx-calendar me-2'></i>Event Schedule</h5>
                <div class="alert alert-warning" role="alert">
                    <i class='bx bx-time me-2'></i>
                    <strong>Note:</strong> You can add multiple dates for recurring events. Each date must have both start
                    and end times.
                </div>
            </div>

            <!-- Date Container -->
            <div id="calendar" class="row g-3">
                <div class="date-group border rounded p-3 mb-3" data-date-index="0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class='bx bx-calendar-event me-2'></i>Event Date #1</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class='bx bx-calendar-plus me-1'></i>Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control p-2" name="start_date[]" required
                                value="{{ old('start_date.0') }}">
                            @error('start_date.0')
                                <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class='bx bx-time me-1'></i>Start Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control p-2" name="start_time[]" required
                                value="{{ old('start_time.0') }}">
                            @error('start_time.0')
                                <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class='bx bx-calendar-minus me-1'></i>End Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control p-2" name="end_date[]" required
                                value="{{ old('end_date.0') }}">
                            @error('end_date.0')
                                <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class='bx bx-time me-1'></i>End Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control p-2" name="end_time[]" required
                                value="{{ old('end_time.0') }}">
                            @error('end_time.0')
                                <div class="text-danger mt-1"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-outline-primary" type="button" id="addDate">
                    <i class='bx bx-plus me-2'></i>Add Another Date
                </button>
                <div class="form-text">Maximum 5 dates allowed</div>
            </div>

            <!-- Event Format Section -->
            <div class="col-12 mt-4">
                <h5 class="section-title"><i class='bx bx-map me-2'></i>Event Format</h5>
                <div class="format-options mt-3">
                    <div class="form-check format-option">
                        <input class="form-check-input" type="radio" name="format" id="formatOnline" value="1" {{ old('format') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="formatOnline">
                            <div class="format-card">
                                <i class='bx bx-laptop'></i>
                                <div>
                                    <strong>Online Event</strong>
                                    <p class="mb-0">Virtual event accessible from anywhere</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="form-check format-option">
                        <input class="form-check-input" type="radio" name="format" id="formatOffline" value="0" {{ old('format') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="formatOffline">
                            <div class="format-card">
                                <i class='bx bx-map-pin'></i>
                                <div>
                                    <strong>Physical Event</strong>
                                    <p class="mb-0">In-person event at a specific location</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                @error('format')
                    <div class="text-danger mt-2"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                @enderror
            </div>

            <!-- Images Section -->
            <div class="col-12 mt-4">
                <h5 class="section-title"><i class='bx bx-image me-2'></i>Event Images</h5>
            </div>

            <div class="add-images-event">
                <!-- Event Poster -->
                <div class="col-md-6">
                    <div class="image-upload-section">
                        <h6><i class='bx bx-image-alt me-2'></i>Event Poster <span class="text-danger">*</span></h6>
                        <div class="notice-info mb-3">
                            <i class='bx bx-info-circle me-2'></i>
                            <span>JPG, PNG, or WEBP format. Max 5MB. Recommended: 300x300px</span>
                        </div>
                        <div class="image-upload-box" id="imageBox" onclick="document.getElementById('fileInput').click()">
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <i class='bx bx-cloud-upload'></i>
                                <p>Click to upload poster</p>
                                <small>Drag & drop or click to browse</small>
                            </div>
                            <img id="uploadedImage" src="" alt="" style="display: none;">
                            <div class="upload-overlay">
                                <i class='bx bx-edit'></i>
                                <span>Change Image</span>
                            </div>
                        </div>
                        <input type="file" id="fileInput" style="display: none;" name="poster" accept="image/*" required>
                        @error('poster')
                            <div class="text-danger mt-2"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Event Banner -->
                <div class="col-md-6">
                    <div class="image-upload-section">
                        <h6><i class='bx bx-image me-2'></i>Event Banner <span class="text-muted">(Optional)</span></h6>
                        <div class="notice-info mb-3">
                            <i class='bx bx-info-circle me-2'></i>
                            <span>Featured banner for landing pages. Recommended: 1200x400px</span>
                        </div>
                        <div class="image-upload-box" id="customImageBox"
                            onclick="document.getElementById('customFileInput').click()">
                            <div class="upload-placeholder" id="customUploadPlaceholder">
                                <i class='bx bx-cloud-upload'></i>
                                <p>Click to upload banner</p>
                                <small>Drag & drop or click to browse</small>
                            </div>
                            <img id="customUploadedImage" src="" alt="" style="display: none;">
                            <div class="upload-overlay">
                                <i class='bx bx-edit'></i>
                                <span>Change Image</span>
                            </div>
                        </div>
                        <input type="file" id="customFileInput" name="banner" style="display: none;" accept="image/*">
                        @error('banner')
                            <div class="text-danger mt-2"><i class='bx bx-error-circle me-1'></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="col-12 d-flex justify-content-between align-items-center mt-5 mb-3">
                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                    <i class='bx bx-arrow-back me-2'></i>Back
                </button>
                <button id="submit" type="submit" class="btn btn-primary" style="padding: 10px 30px;">
                    <i class='bx bx-right-arrow-alt me-2'></i>Next Step
                </button>
            </div>
        </form>
    </div>
    <!-- end form-data -->
@endsection

@push('inc_events_js')
    <script>
        $(document).ready(function () {
            let dateIndex = 1;
            const maxDates = 5;

            // Character counters
            function updateCharacterCount(textareaId, counterId, maxLength) {
                const textarea = document.getElementById(textareaId);
                const counter = document.getElementById(counterId);

                textarea.addEventListener('input', function () {
                    const currentLength = this.value.length;
                    counter.textContent = currentLength;

                    if (currentLength > maxLength * 0.9) {
                        counter.style.color = '#dc3545';
                    } else if (currentLength > maxLength * 0.7) {
                        counter.style.color = '#ffc107';
                    } else {
                        counter.style.color = '#6c757d';
                    }
                });

                // Initial count
                counter.textContent = textarea.value.length;
            }

            updateCharacterCount('description', 'descriptionCount', 2000);
            updateCharacterCount('cancellation_policy', 'policyCount', 1000);

            // Category change handler with loading state
            $('select[name="category_id"]').change(function () {
                const categoryId = $(this).val();
                const subCategorySelect = $('select[name="sub_category_id"]');

                if (!categoryId) return;

                // Show loading state
                subCategorySelect.prop('disabled', true)
                    .html('<option disabled selected>Loading...</option>');

                $.ajax({
                    url: "{{ route('organization.sub_categories') }}",
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function (data) {
                        subCategorySelect.empty()
                            .append('<option disabled selected>Select Sub Category</option>');

                        if (data && data.length > 0) {
                            $.each(data, function (key, value) {
                                const selected = "{{ old('sub_category_id') }}" == value.id ? 'selected' : '';
                                subCategorySelect.append(`<option value="${value.id}" ${selected}>${value.name}</option>`);
                            });
                        } else {
                            subCategorySelect.append('<option disabled>No sub categories available</option>');
                        }

                        subCategorySelect.prop('disabled', false);
                    },
                    error: function () {
                        subCategorySelect.html('<option disabled selected>Error loading sub categories</option>');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to load sub categories. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            // Trigger category change if old value exists
            const oldCategoryId = "{{ old('category_id') }}";
            if (oldCategoryId) {
                $('select[name="category_id"]').trigger('change');
            }

            // Add date functionality
            $('#addDate').click(function () {
                if (dateIndex >= maxDates) {
                    Swal.fire({
                        title: 'Maximum Reached',
                        text: `You can only add up to ${maxDates} dates.`,
                        icon: 'warning',
                        confirmButtonColor: '#ffc107'
                    });
                    return;
                }

                const html = `
                        <div class="date-group border rounded p-3 mb-3" data-date-index="${dateIndex}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class='bx bx-calendar-event me-2'></i>Event Date #${dateIndex + 1}</h6>
                                <button type="button" class="btn btn-outline-danger btn-sm delete-date">
                                    <i class='bx bx-trash me-1'></i>Remove
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-calendar-plus me-1'></i>Start Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control p-2" name="start_date[]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-time me-1'></i>Start Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control p-2" name="start_time[]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-calendar-minus me-1'></i>End Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control p-2" name="end_date[]" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class='bx bx-time me-1'></i>End Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control p-2" name="end_time[]" required>
                                </div>
                            </div>
                        </div>
                    `;

                $('#calendar').append(html);
                dateIndex++;

                // Scroll to new date group
                $('html, body').animate({
                    scrollTop: $('.date-group').last().offset().top - 100
                }, 500);

                // Update button state
                if (dateIndex >= maxDates) {
                    $('#addDate').prop('disabled', true).html('<i class="bx bx-check me-2"></i>Maximum dates added');
                }
            });

            // Delete date functionality
            $(document).on('click', '.delete-date', function () {
                const dateGroup = $(this).closest('.date-group');
                const dateNumber = dateGroup.find('h6').text();

                Swal.fire({
                    title: 'Remove Date?',
                    text: `Are you sure you want to remove ${dateNumber}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Remove',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        dateGroup.fadeOut(300, function () {
                            $(this).remove();
                            dateIndex--;

                            // Re-enable add button
                            $('#addDate').prop('disabled', false).html('<i class="bx bx-plus me-2"></i>Add Another Date');

                            // Renumber remaining date groups
                            $('.date-group').each(function (index) {
                                $(this).find('h6').html(`<i class='bx bx-calendar-event me-2'></i>Event Date #${index + 1}`);
                                $(this).attr('data-date-index', index);
                            });
                        });
                    }
                });
            });

            // Image upload handlers
            function setupImageUpload(inputId, imageId, placeholderId) {
                const input = document.getElementById(inputId);
                const image = document.getElementById(imageId);
                const placeholder = document.getElementById(placeholderId);

                input.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Validate file size (5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            Swal.fire({
                                title: 'File Too Large',
                                text: 'Please select an image smaller than 5MB.',
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                            input.value = '';
                            return;
                        }

                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                title: 'Invalid File Type',
                                text: 'Please select a JPG, PNG, or WEBP image.',
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                            input.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            image.src = e.target.result;
                            image.style.display = 'block';
                            placeholder.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            setupImageUpload('fileInput', 'uploadedImage', 'uploadPlaceholder');
            setupImageUpload('customFileInput', 'customUploadedImage', 'customUploadPlaceholder');

            // Form validation
            $('#eventForm').on('submit', function (e) {
                let isValid = true;
                const errors = [];

                // Validate dates
                $('.date-group').each(function (index) {
                    const startDate = $(this).find('input[name="start_date[]"]').val();
                    const endDate = $(this).find('input[name="end_date[]"]').val();
                    const startTime = $(this).find('input[name="start_time[]"]').val();
                    const endTime = $(this).find('input[name="end_time[]"]').val();

                    if (startDate && endDate) {
                        const startDateTime = new Date(startDate + ' ' + startTime);
                        const endDateTime = new Date(endDate + ' ' + endTime);

                        if (startDateTime >= endDateTime) {
                            errors.push(`Date #${index + 1}: End date/time must be after start date/time`);
                            isValid = false;
                        }

                        if (startDateTime < new Date()) {
                            errors.push(`Date #${index + 1}: Start date cannot be in the past`);
                            isValid = false;
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Validation Error',
                        html: errors.join('<br>'),
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                // Show loading state
                const submitBtn = $('#submit');
                submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Creating Event...');
            });

            // Auto-set minimum date to today
            $('input[type="date"]').attr('min', new Date().toISOString().split('T')[0]);
        });
    </script>

    <style>
        /* Progress Bar Styling */
        .progress-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .step {
            color: #6c757d;
            font-weight: 500;
        }

        .step.active {
            color: #1976d2;
            font-weight: 600;
        }

        /* Section Titles */
        .section-title {
            color: #1976d2;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e3f2fd;
        }

        /* Form Enhancements */
        .form-control:focus,
        .form-select:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }

        /* Date Groups */
        .date-group {
            background: #f8f9fa;
            border: 2px solid #e9ecef !important;
            transition: all 0.3s ease;
        }

        .date-group:hover {
            border-color: #1976d2 !important;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.1);
        }

        /* Format Options */
        .format-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .format-option {
            margin-bottom: 0;
        }

        .format-option .form-check-input {
            display: none;
        }

        .format-card {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .format-card i {
            font-size: 2rem;
            margin-right: 15px;
            color: #6c757d;
        }

        .format-card p {
            color: #6c757d;
            font-size: 14px;
        }

        .format-option input:checked+label .format-card {
            border-color: #1976d2;
            background: #e3f2fd;
        }

        .format-option input:checked+label .format-card i {
            color: #1976d2;
        }

        .format-card:hover {
            border-color: #1976d2;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.1);
        }

        /* Image Upload Styling */
        .image-upload-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .image-upload-section:hover {
            border-color: #1976d2;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.1);
        }

        .image-upload-box {
            position: relative;
            width: 100%;
            height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .image-upload-box:hover {
            border-color: #1976d2;
            background: #f8f9fa;
        }

        .upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            text-align: center;
        }

        .upload-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #1976d2;
        }

        .upload-placeholder p {
            margin: 5px 0;
            font-weight: 500;
        }

        .upload-placeholder small {
            color: #adb5bd;
        }

        .image-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(25, 118, 210, 0.8);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-upload-box:hover .upload-overlay {
            opacity: 1;
        }

        .upload-overlay i {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        /* Notice Info */
        .notice-info {
            background: #e3f2fd;
            border: 1px solid #1976d2;
            border-radius: 8px;
            padding: 10px 15px;
            color: #1976d2;
            font-size: 14px;
        }

        /* Button Enhancements */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Loading Animation */
        .bx-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .format-options {
                grid-template-columns: 1fr;
            }

            .progress-steps {
                font-size: 12px;
            }

            .step {
                text-align: center;
            }

            .add-images-event {
                display: block;
            }

            .add-images-event .col-md-6 {
                margin-bottom: 20px;
            }
        }

        /* Form Data Container */
        .form-data {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .head h3 {
            color: #1976d2;
            font-weight: 600;
        }

        /* Character Counter Styling */
        .form-text {
            font-size: 12px;
            margin-top: 5px;
        }

        /* Error Styling */
        .text-danger {
            font-size: 14px;
            font-weight: 500;
        }

        /* Success States */
        .is-valid {
            border-color: #4caf50 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endpush