<script>
        $(document).ready(function () {
            // Initialize DataTables for all event tables
            const tableIds = ['eventsTable', 'newEventsTable', 'upcomingEventsTable', 'pastEventsTable', 'weeklyEventsTable', 'monthlyEventsTable'];
            const tables = {};

            tableIds.forEach(function (tableId) {
                if ($('#' + tableId).length > 0) {
                    tables[tableId] = $('#' + tableId).DataTable({
                        responsive: true,
                        lengthChange: true,
                        buttons: ['copy', 'excel', 'pdf', 'print'],
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50],
                        // order: [[1, 'asc']], // Sort by Event Name,
                        // dom: 'Bfrtip',
                        paging: true,
                        info: true,
                        language: {
                            search: "Search Events:",
                            emptyTable: "No events available",
                            info: "Showing _START_ to _END_ of _TOTAL_ events",
                        }
                    });

                    // Add buttons to each table
                    tables[tableId].buttons().container()
                        .appendTo('#' + tableId + '_wrapper .col-md-6:eq(0)');
                }
            });

            // Handle status card clicks
            $('.status-card').on('click', function () {
                const filter = $(this).data('filter');

                // Update active card
                $('.status-card').removeClass('active');
                $(this).addClass('active');

                // Hide all sections
                $('.events-section').hide();

                // Show selected section
                $('#' + filter + '-events').show();

                // Redraw table for the active section
                const tableId = filter + 'EventsTable';
                if (tables[tableId]) {
                    setTimeout(() => {
                        tables[tableId].columns.adjust().responsive.recalc();
                    }, 100);
                }
            });

            // SweetAlert2 for delete confirmation - using event delegation
            $(document).on('submit', '.delete-form', function (e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this event! This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the event.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            });

            // Gallery upload form submission
            $('.gallery-upload-form').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                var eventId = $(this).data('event-id');
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.events.gallery.upload') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        $('#uploadStatus-' + eventId).html(
                            '<p class="text-info">Uploading...</p>');
                    },
                    success: function (response) {
                        $('#uploadStatus-' + eventId).html(
                            '<p class="text-success">' + response.message + '</p>');
                        $(form).find('input[type="file"]').val('');
                        setTimeout(() => {
                            $('#galleryModal-' + eventId).modal('hide');
                            $('#uploadStatus-' + eventId).empty();
                        }, 2000);
                    },
                    error: function (xhr) {
                        let errorMsg = xhr.responseJSON?.error || 'Error uploading images!';
                        $('#uploadStatus-' + eventId).html(
                            '<p class="text-danger">' + errorMsg + '</p>');
                    }
                });
            });
        });

        // Toggle Format - using event delegation for dynamic content
        $(document).on('click', '.toggle-format', function (e) {
            e.preventDefault();
            var eventId = $(this).data('id');
            var icon = $(this).find('i');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to change the event format!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.events.toggle.format') }}",
                        method: 'POST',
                        data: {
                            id: eventId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            // Update the icon based on the format state
                            if (response.format === 'online' || response.format == 1) {
                                icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                            } else {
                                icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message || 'Event format updated successfully.',
                                timer: 2000,
                                timerProgressBar: true
                            });
                        },
                        error: function (xhr, status, error) {
                            console.error('Format toggle error:', xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong while updating format.'
                            });
                        }
                    });
                }
            });
        });

        // Toggle Active Status - using event delegation for dynamic content
        $(document).on('click', '.toggle-active', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const icon = $('#active-icon-' + id);

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to change the activation status!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.events.toggleActive') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            icon.removeClass('fa-toggle-on text-success fa-toggle-off text-danger');
                            if (response.is_active) {
                                icon.addClass('fa-toggle-on text-success');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: 'Event is now active.',
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            } else {
                                icon.addClass('fa-toggle-off text-danger');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: 'Event is now inactive.',
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Toggle error:', xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong while updating activation status.'
                            });
                        }
                    });
                }
            });
        });

        $(document).ready(function () {
            // Handling Export with AJAX
            $('#export-form').on('submit', function (e) {
                e.preventDefault();

                // Get the date range
                var fromDate = $('#from').val();
                var toDate = $('#to').val();

                // Check if dates are provided
                if (!fromDate || !toDate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Dates',
                        text: 'Please provide both start and end dates to export.',
                    });
                    return;
                }

                // Sending the AJAX request for export
                $.ajax({
                    url: "{{ route('admin.events.export') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        from: fromDate,
                        to: toDate,
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Export Successful',
                                text: 'Your events have been exported as PDF.',
                            });
                            // Optionally, handle PDF download link if available
                            window.location.href = response.download_url;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Export Failed',
                                text: 'An error occurred while exporting the events.',
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong while exporting.',
                        });
                    }
                });
            });
        });

        // Gallery Management Functions
        let reorderMode = {};

        // Load gallery when modal opens
        $('.add-gallery-btn').on('click', function () {
            const eventId = $(this).data('event-id');
            console.log('Gallery button clicked for event:', eventId);
            // Load gallery immediately when button is clicked
            loadGallery(eventId);
        });

        // Also load gallery when modal is shown
        $('[id^="galleryModal-"]').on('shown.bs.modal', function () {
            const eventId = $(this).attr('id').split('-')[1];
            console.log('Modal shown for event:', eventId);
            loadGallery(eventId);
        });

        // Handle edit modal close - reopen gallery modal
        $('#editImageModal').on('hidden.bs.modal', function () {
            const eventId = $('#editEventId').val();
            if (eventId) {
                $('#galleryModal-' + eventId).modal('show');
            }
        });

        // Handle new image preview in edit modal
        $(document).on('change', '#editNewImage', function () {
            const file = this.files[0];
            const previewContainer = $('#newImagePreviewContainer');
            const previewImg = $('#newImagePreview');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.attr('src', e.target.result);
                    previewContainer.show();
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.hide();
            }
        });

        // Preview selected images
        $(document).on('change', 'input[type="file"][name="images[]"]', function () {
            const eventId = $(this).attr('id').split('-')[1];
            const files = this.files;
            const previewContainer = $(`#previewContainer-${eventId}`);
            const previewDiv = $(`#imagePreview-${eventId}`);

            // Clear previous previews
            previewContainer.empty();

            if (files.length > 0) {
                previewDiv.show();

                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewHtml = `
                                        <div class="col-3">
                                            <div class="card">
                                                <img src="${e.target.result}" class="card-img-top" style="height: 80px; object-fit: cover;" alt="${file.name}">
                                                <div class="card-body p-1">
                                                    <small class="text-truncate d-block" title="${file.name}">${file.name}</small>
                                                    <small class="text-muted">${formatFileSize(file.size)}</small>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                            previewContainer.append(previewHtml);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewDiv.hide();
            }
        });

        // Load gallery images
        function loadGallery(eventId) {
            console.log('Loading gallery for event:', eventId);
            $.ajax({
                url: `{{ url('admin/events') }}/${eventId}/gallery`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log('Gallery response:', response);
                    if (response.success) {
                        displayGallery(eventId, response.data);
                    } else {
                        showGalleryError(eventId, response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Gallery load error:', xhr.responseText);
                    showGalleryError(eventId, 'Failed to load gallery images: ' + error);
                }
            });
        }

        // Display gallery images
        function displayGallery(eventId, images) {
            console.log('Displaying gallery for event:', eventId, 'Images:', images);
            const container = $(`#galleryContainer-${eventId}`);

            if (!images || images.length === 0) {
                container.html(`
                            <div class="col-12 text-center py-5">
                                <i class="ti ti-photo-off ti-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No images in gallery</h5>
                                <p class="text-muted">Upload some images to get started</p>
                            </div>
                        `);
                return;
            }

            let html = '';
            images.forEach((image, index) => {
                console.log('Processing image:', image.id, 'is_main:', image.is_main, 'type:', typeof image.is_main);
                const isMain = Boolean(image.is_main);
                const mainBadge = isMain ? '<span class="badge bg-success position-absolute top-0 start-0 m-2">Main</span>' : '';
                const mainButtonDisabled = isMain ? 'disabled' : '';

                html += `
                            <div class="col-md-4 col-lg-3" data-image-id="${image.id}" data-order="${image.order}">
                                <div class="card gallery-image-card ${isMain ? 'border-success' : ''}" style="position: relative;">
                                    ${mainBadge}
                                    <img src="${image.url}" class="card-img-top image-preview" alt="${image.name}" onerror="this.src='{{ asset('backend/assets/img/avatars/1.png') }}'">
                                    <div class="card-body p-2">
                                        <h6 class="card-title text-truncate" title="${image.name}">${image.name}</h6>
                                        <p class="card-text small text-muted">${formatFileSize(image.size || 0)}</p>
                                        <div class="btn-group w-100" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewImage(${eventId}, ${image.id})" title="View Details">
                                                <i class="ti ti-eye ti-sm"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="editImage(${eventId}, ${image.id})" title="Edit Image">
                                                <i class="ti ti-edit ti-sm"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="setMainImage(${eventId}, ${image.id})" title="Set as Main" ${mainButtonDisabled}>
                                                <i class="ti ti-star ti-sm"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteImage(${eventId}, ${image.id})" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
            });

            container.html(html);
        }

        // Show gallery error
        function showGalleryError(eventId, message) {
            const container = $(`#galleryContainer-${eventId}`);
            container.html(`
                        <div class="col-12 text-center py-5">
                            <i class="ti ti-alert-circle ti-3x text-danger mb-3"></i>
                            <h5 class="text-danger">Error loading gallery</h5>
                            <p class="text-muted">${message}</p>
                            <button class="btn btn-primary" onclick="loadGallery(${eventId})">
                                <i class="ti ti-refresh ti-sm me-1"></i>
                                Try Again
                            </button>
                        </div>
                    `);
        }

        // Upload form submission
        $('.gallery-upload-form').on('submit', function (e) {
            e.preventDefault();
            const eventId = $(this).data('event-id');
            const formData = new FormData(this);
            const progressDiv = $(`#uploadProgress-${eventId}`);

            console.log('Uploading images for event:', eventId);
            progressDiv.show();

            $.ajax({
                url: `{{ url('admin/events') }}/${eventId}/gallery`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = evt.loaded / evt.total * 100;
                            progressDiv.find('.progress-bar').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function (response) {
                    progressDiv.hide();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message
                        });
                        loadGallery(eventId);
                        $(`#images-${eventId}`).val('');
                        $(`#imagePreview-${eventId}`).hide();
                        $(`#previewContainer-${eventId}`).empty();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: response.message
                        });
                    }
                },
                error: function (xhr) {
                    progressDiv.hide();
                    let message = 'Upload failed';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: message
                    });
                }
            });
        });

        // View image details (renamed from editImage)
        function viewImage(eventId, mediaId) {
            console.log('Viewing image details:', eventId, mediaId);
            // Get current image data
            $.ajax({
                url: `{{ url('admin/events') }}/${eventId}/gallery/${mediaId}`,
                type: 'GET',
                success: function (response) {
                    console.log('Image details response:', response);
                    if (response.success) {
                        showViewModal(eventId, mediaId, response.data);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Image details error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load image data: ' + error
                    });
                }
            });
        }

        // Edit image details
        function editImage(eventId, mediaId) {
            console.log('Editing image details:', eventId, mediaId);
            // Get current image data
            $.ajax({
                url: `{{ url('admin/events') }}/${eventId}/gallery/${mediaId}`,
                type: 'GET',
                success: function (response) {
                    console.log('Image details response:', response);
                    if (response.success) {
                        showEditModal(eventId, mediaId, response.data);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Image details error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load image data: ' + error
                    });
                }
            });
        }

        // Show view modal - Only for viewing image details
        function showViewModal(eventId, mediaId, imageData) {
            // Close the main gallery modal first
            $('#galleryModal-' + eventId).modal('hide');

            // Wait a bit for the modal to close, then show SweetAlert
            setTimeout(() => {
                Swal.fire({
                    title: 'Image Details',
                    html: `
                                <div class="text-start">
                                    <div class="text-center mb-3">
                                        <img src="${imageData.url}" class="img-fluid rounded" style="max-height: 200px;" alt="${imageData.name}">
                                    </div>
                                    <div class="mb-2">
                                        <strong>Name:</strong> ${imageData.name}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Size:</strong> ${formatFileSize(imageData.size)}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Order:</strong> ${imageData.order}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Main Image:</strong> ${Boolean(imageData.is_main) ? 'Yes' : 'No'}
                                    </div>
                                    ${imageData.description ? `<div class="mb-2"><strong>Description:</strong> ${imageData.description}</div>` : ''}
                                </div>
                            `,
                    showCancelButton: true,
                    confirmButtonText: 'Set as Main',
                    cancelButtonText: 'Close',
                    allowOutsideClick: false,
                    backdrop: true,
                    showDenyButton: true,
                    denyButtonText: 'Delete Image',
                    denyButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set as main image
                        setMainImage(eventId, mediaId);
                    } else if (result.isDenied) {
                        // Delete image
                        deleteImage(eventId, mediaId);
                    } else {
                        // Reopen the gallery modal if cancelled
                        $('#galleryModal-' + eventId).modal('show');
                    }
                });
            }, 300);
        }

        // Show edit modal for editing image details
        function showEditModal(eventId, mediaId, imageData) {
            // Close the main gallery modal first
            $('#galleryModal-' + eventId).modal('hide');

            // Wait a bit for the modal to close, then show edit modal
            setTimeout(() => {
                // Fill the edit form with current data
                $('#editEventId').val(eventId);
                $('#editMediaId').val(mediaId);
                $('#editImageName').val(imageData.name);
                $('#editImageDescription').val(imageData.description || '');
                $('#editImageOrder').val(imageData.order);
                $('#editIsMain').prop('checked', Boolean(imageData.is_main));

                // Set current image preview
                $('#currentImagePreview').attr('src', imageData.url);

                // Clear new image input and preview
                $('#editNewImage').val('');
                $('#newImagePreviewContainer').hide();

                // Show the edit modal
                $('#editImageModal').modal('show');
            }, 300);
        }

        // Save image edit
        function saveImageEdit() {
            const eventId = $('#editEventId').val();
            const mediaId = $('#editMediaId').val();
            const newImageFile = $('#editNewImage')[0].files[0];

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('name', $('#editImageName').val());
            formData.append('description', $('#editImageDescription').val());
            formData.append('order', parseInt($('#editImageOrder').val()));
            formData.append('is_main', $('#editIsMain').is(':checked') ? '1' : '0');
            formData.append('_token', '{{ csrf_token() }}');

            // Add new image file if selected
            if (newImageFile) {
                formData.append('new_image', newImageFile);
            }

            console.log('Saving image edit:', eventId, mediaId, 'Has new image:', !!newImageFile);

            $.ajax({
                url: `{{ url('admin/events') }}/${eventId}/gallery/${mediaId}`,
                type: 'POST', // Use POST for file upload
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log('Edit response:', response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message
                        }).then(() => {
                            $('#editImageModal').modal('hide');
                            loadGallery(eventId);
                            $('#galleryModal-' + eventId).modal('show');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: response.message
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Edit error:', xhr.responseText);
                    let message = 'Something went wrong';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: message
                    });
                }
            });
        }


        // Set main image
        function setMainImage(eventId, mediaId) {
            console.log('Setting main image:', eventId, mediaId);
            Swal.fire({
                title: 'Set as Main Image?',
                text: 'This will replace the current main image',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, set as main',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('admin/events') }}/${eventId}/gallery/${mediaId}/set-main`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            console.log('Set main response:', response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message
                                }).then(() => {
                                    loadGallery(eventId);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Set main error:', xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: 'Something went wrong: ' + error
                            });
                        }
                    });
                }
            });
        }

        // Delete image
        function deleteImage(eventId, mediaId) {
            console.log('Deleting image:', eventId, mediaId);
            Swal.fire({
                title: 'Delete Image?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('admin/events') }}/${eventId}/gallery/${mediaId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            console.log('Delete response:', response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message
                                }).then(() => {
                                    loadGallery(eventId);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Delete Failed',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Delete error:', xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete Failed',
                                text: 'Something went wrong: ' + error
                            });
                        }
                    });
                }
            });
        }

        // Refresh gallery
        function refreshGallery(eventId) {
            loadGallery(eventId);
        }

        // Toggle reorder mode
        function toggleReorderMode(eventId) {
            const container = $(`#galleryContainer-${eventId}`);
            const isReorderMode = reorderMode[eventId] || false;

            if (isReorderMode) {
                // Exit reorder mode
                container.find('.gallery-image-card').removeClass('border-primary');
                container.find('.reorder-handle').remove();
                reorderMode[eventId] = false;
            } else {
                // Enter reorder mode
                container.find('.gallery-image-card').addClass('border-primary');
                container.find('.gallery-image-card').prepend('<div class="reorder-handle position-absolute top-0 end-0 m-2"><i class="ti ti-arrows-sort text-white bg-primary rounded p-1"></i></div>');
                reorderMode[eventId] = true;
            }
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        $('#filter-btn').on('click', function (e) {
            e.preventDefault();

            let from = $('#from').val();
            let to = $('#to').val();

            $.ajax({
                url: "{{ route('admin.events.filter') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    from: from,
                    to: to,
                },
                success: function (response) {
                    if (response.success) {
                        console.log(response.html);
                        $('#eventsTable tbody').html(response.html);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function (xhr) {
                    console.error('Filter error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while filtering.'
                    });
                }
            });
        });

        $('#reset-btn').on('click', function (e) {
            e.preventDefault();
            $('#from').val('');
            $('#to').val('');
            $('#eventsTable tbody').html('');
            $('#eventsTable').DataTable().destroy();
            $('#eventsTable').DataTable();
        });
    </script>