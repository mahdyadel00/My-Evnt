@extends('backend.partials.master')

@section('title', 'Edit Social Gallery')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <style>
        .social-image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .image-upload-container {
            border: 2px dashed #e3e6f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .image-upload-container:hover {
            border-color: #696cff;
            background-color: #f8f8ff;
        }

        .upload-text {
            color: #8a8d93;
            font-size: 14px;
            margin-top: 10px;
        }

        .card {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1rem;
        }

        .card .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <!-- Social Gallery Settings Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-brand-instagram me-2"></i>
                            Social Gallery Settings
                        </h5>
                        <small class="text-muted float-end">Manage your social media gallery</small>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.social_galleries.update', $socialGallery->id) }}" method="POST"
                            enctype="multipart/form-data" id="socialGalleryForm">
                            @csrf
                            @method('PUT')

                            <!-- General Settings -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="title">Gallery Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                        name="title" value="{{ old('title', $socialGallery->title) }}"
                                        placeholder="Follow us" required />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="instagram_handle">Instagram Handle</label>
                                    <input type="text" class="form-control @error('instagram_handle') is-invalid @enderror"
                                        id="instagram_handle" name="instagram_handle"
                                        value="{{ old('instagram_handle', $socialGallery->instagram_handle) }}"
                                        placeholder="@Beautics lab" required />
                                    @error('instagram_handle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label" for="instagram_link">Instagram Link</label>
                                    <input type="url" class="form-control @error('instagram_link') is-invalid @enderror"
                                        id="instagram_link" name="instagram_link"
                                        value="{{ old('instagram_link', $socialGallery->instagram_link) }}"
                                        placeholder="https://www.instagram.com" required />
                                    @error('instagram_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                            {{ old('status', $socialGallery->status) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Images Section -->
                            <div class="mb-4">
                                <h6 class="mb-3">
                                    <i class="ti ti-photo me-2"></i>
                                    Gallery Images (6 images recommended)
                                </h6>

                                <!-- Current Images -->
                                @if($socialGallery->media && $socialGallery->media->count() > 0)
                                    <div class="mb-3">
                                        <label class="form-label">Current Images</label>
                                        <div class="row" id="currentImagesContainer">
                                            @foreach($socialGallery->media as $media)
                                                <div class="col-md-4 mb-4" id="media-container-{{ $media->id }}" data-media-id="{{ $media->id }}">
                                                    <div class="card">
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/' . $media->path) }}" alt="Social Image"
                                                                class="social-image-preview img-fluid card-img-top">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                                onclick="deleteMedia({{ $media->id }})"
                                                                style="transform: translate(50%, -50%);">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        </div>
                                                        <div class="card-body">
                                                            <label class="form-label" for="post_url_{{ $media->id }}">
                                                                <i class="ti ti-link me-1"></i>Post Link
                                                            </label>
                                                            <input type="url" class="form-control form-control-sm"
                                                                id="post_url_{{ $media->id }}"
                                                                name="existing_post_urls[{{ $media->id }}]"
                                                                value="{{ old('existing_post_urls.' . $media->id, $media->post_url) }}"
                                                                placeholder="https://www.instagram.com/p/..." />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Upload New Images -->
                                <div class="mb-3">
                                    <label class="form-label" for="images">Upload New Images</label>
                                    <div class="image-upload-container">
                                        <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                            id="images" name="images[]" multiple accept="image/*"
                                            onchange="previewImagesWithLinks(this)" />
                                        <div class="upload-text">
                                            <i class="ti ti-cloud-upload fs-4 d-block mb-2"></i>
                                            Choose up to 6 images (JPEG, PNG, GIF, WebP - Max 2MB each)
                                        </div>
                                    </div>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preview New Images with Post URL Inputs -->
                                <div id="imagePreview" class="row" style="display: none;"></div>
                                <!-- Preview Message  For Max 6 Images  and 2MB each-->
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Maximum 6 images allowed. You currently have
                                    {{ $socialGallery->media ? $socialGallery->media->count() : 0 }} images. Each image
                                    should not exceed 2MB.
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Update Social Gallery
                                    </button>
                                    <a href="{{ route('admin.home') }}" class="btn btn-label-secondary">
                                        <i class="ti ti-arrow-left me-1"></i>
                                        Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script>
        // Preview uploaded images with post URL inputs
        function previewImagesWithLinks(input) {
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';

            if (input.files && input.files.length > 0) {
                previewContainer.style.display = 'block';

                Array.from(input.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 mb-4';

                            col.innerHTML = `
                                    <div class="card">
                                        <div class="position-relative">
                                            <img src="${e.target.result}"
                                                class="social-image-preview img-fluid card-img-top"
                                                alt="Preview ${index + 1}">
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                                                New Image ${index + 1}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <label class="form-label" for="post_url_new_${index}">
                                                <i class="ti ti-link me-1"></i>Post Link
                                            </label>
                                            <input type="url" 
                                                class="form-control form-control-sm" 
                                                id="post_url_new_${index}"
                                                name="post_urls[${index}]"
                                                placeholder="https://www.instagram.com/p/..." />
                                            <small class="text-muted">Add the Instagram post URL for this image</small>
                                        </div>
                                    </div>
                                `;

                            previewContainer.appendChild(col);
                        };

                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewContainer.style.display = 'none';
            }
        }

        // Delete media function with SweetAlert2
        function deleteMedia(mediaId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This image will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/admin/social_galleries/media/${mediaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const contentType = response.headers.get("content-type");
                            if (contentType && contentType.indexOf("application/json") !== -1) {
                                return response.json();
                            } else {
                                throw new Error("Server response is not valid JSON");
                            }
                        })
                        .then(data => {
                            console.log('Delete response:', data);
                            if (data.success) {
                                // Find the media container by ID (most reliable method)
                                const containerElement = document.getElementById(`media-container-${mediaId}`);
                                
                                if (containerElement) {
                                    // Add fade out animation
                                    containerElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                    containerElement.style.opacity = '0';
                                    containerElement.style.transform = 'scale(0.9)';
                                    
                                    // Remove after animation
                                    setTimeout(() => {
                                        containerElement.remove();
                                        // Update image counter after removal
                                        updateImageCounter();
                                    }, 300);
                                } else {
                                    // Fallback: try to find by button onclick attribute
                                    const imageElement = document.querySelector(`button[onclick="deleteMedia(${mediaId})"]`);
                                    if (imageElement) {
                                        const fallbackContainer = imageElement.closest('.col-md-4');
                                        if (fallbackContainer) {
                                            fallbackContainer.style.transition = 'opacity 0.3s ease';
                                            fallbackContainer.style.opacity = '0';
                                            setTimeout(() => {
                                                fallbackContainer.remove();
                                                updateImageCounter();
                                            }, 300);
                                        } else {
                                            updateImageCounter();
                                        }
                                    } else {
                                        // If nothing found, update counter anyway
                                        updateImageCounter();
                                    }
                                }

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Image has been deleted successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'An error occurred while deleting the image',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while deleting the image',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }

        // Update image counter
        function updateImageCounter(forcedCount = null) {
            const currentImagesCount = forcedCount !== null
                ? forcedCount
                : document.querySelectorAll('.social-image-preview').length;
            const alertElement = document.querySelector('.alert-info');
            if (alertElement) {
                alertElement.innerHTML = `
                                                                                            <i class="ti ti-info-circle me-1"></i>
                                                                                            Maximum 6 images allowed. You currently have ${currentImagesCount} images. Each image should not exceed 2MB.
                                                                                        `;
            }
        }

        // Form submission with validation (maximum 6 images total) + AJAX (بدون ريلود)
        document.getElementById('socialGalleryForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const fileInput = document.getElementById('images');
            const currentImages = {{ $socialGallery->media ? $socialGallery->media->count() : 0 }};
            const newImages = fileInput.files.length;
            const maxImages = 6;
            const totalImages = currentImages + newImages;

            // لا يوجد أي صورة جديدة → هنرسل بقية الداتا عادي
            if (newImages === 0) {
                await submitSocialGalleryAjax(form);
                return;
            }

            // لو لسه في مساحة أقل من 6 صور، هنقبل عدد الصور المسموح فقط
            if (totalImages > maxImages) {
                const allowedNew = Math.max(0, maxImages - currentImages);

                // لو مفيش مكان لأي صورة جديدة بالفعل، امنع وخلي اليوزر يحذف صور قديمة
                if (allowedNew === 0) {
                    Swal.fire({
                        title: 'Maximum Images Exceeded!',
                        text: `Maximum ${maxImages} images allowed. You currently have ${currentImages} images. Please delete one or more images before adding new ones.`,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // لو في مساحة لبعض الصور الجديدة، نخلي أول allowedNew بس ونتجاهل الباقي
                const dt = new DataTransfer();
                for (let i = 0; i < allowedNew; i++) {
                    dt.items.add(fileInput.files[i]);
                }
                fileInput.files = dt.files;

                Swal.fire({
                    title: 'Maximum Images Adjusted',
                    text: `Only the first ${allowedNew} image(s) were added to keep the total at ${maxImages} images.`,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }

            await submitSocialGalleryAjax(form);
        });

        async function submitSocialGalleryAjax(form) {
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // تحديث عدد الصور من الريسبونس
                    const mediaCount = data.data && data.data.media ? data.data.media.length : null;
                    if (mediaCount !== null) {
                        updateImageCounter(mediaCount);
                    } else {
                        updateImageCounter();
            }

                    // إعادة رسم الصور الحالية من الريسبونس عشان تظهر الصور الجديدة فوراً
                    const currentImagesContainer = document.getElementById('currentImagesContainer');
                    if (currentImagesContainer && data.data && Array.isArray(data.data.media)) {
                        const storageBase = '{{ asset('storage') }}';
                        currentImagesContainer.innerHTML = '';

                        data.data.media.forEach(function (media) {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 mb-4';
                            col.id = `media-container-${media.id}`;
                            col.setAttribute('data-media-id', media.id);

                            col.innerHTML = `
                                <div class="card">
                                    <div class="position-relative">
                                        <img src="${storageBase}/${media.path}" alt="Social Image"
                                            class="social-image-preview img-fluid card-img-top">
                                        <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                            onclick="deleteMedia(${media.id})"
                                            style="transform: translate(50%, -50%);">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label" for="post_url_${media.id}">
                                            <i class="ti ti-link me-1"></i>Post Link
                                        </label>
                                        <input type="url"
                                            class="form-control form-control-sm"
                                            id="post_url_${media.id}"
                                            name="existing_post_urls[${media.id}]"
                                            value="${media.post_url ?? ''}"
                                            placeholder="https://www.instagram.com/p/..." />
                                    </div>
                                </div>
                            `;

                            currentImagesContainer.appendChild(col);
                        });
                    }

                    // تفريغ اختيار الصور الجديدة و الـ preview
                    const fileInput = document.getElementById('images');
                    const previewContainer = document.getElementById('imagePreview');
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                        previewContainer.style.display = 'none';
                    }

                    Swal.fire({
                        title: 'Social Gallery updated successfully',
                        text: 'Social Gallery updated successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'An error occurred while updating the Social Gallery, please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: 'Error in connection',
                    text: 'An error occurred while connecting to the server, please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            updateImageCounter();
        });
    </script>
@endsection