@extends('Frontend.organization.account.inc.master')
@section('title', 'Event Details')
@section('css')
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
@endsection
@section('content')
    <!-- start breadcrumb -->
    <div class="head-title">
        <div class="left">
            <ul class="breadcrumb">
                <li>
                    <a class="active" href="{{ route('home') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="{{ route('organization.event_details' , $event->id) }}">Event Details</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">Upload Images </a>
                </li>
            </ul>
            <h1>Upload Images </h1>
        </div>
    </div>
    @include('Frontend.organization.layouts._message')
    <!-- end breadcrumb -->
    <div class="row">
        <div class="col-12 col-md-8 col-lg-8">
            <div class="col-md-12">
                <!-- upload cv -->
                <section class="module-section">
                    <div class="upload-cv mt-2 mb-2">
                        <div class="upload">
                            <div class="wrapper-file">
                                <div class="form">
                                    <input type="file" name="gallery[]" class="file-input" hidden multiple>
                                    <i class="fa-solid fa-images"></i>
                                    <p>Browse Files to Upload</p>
                                </div>
                                <small>Upload Image event </small>
                                <section class="progress-area"></section>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4 p-3">
            <div class="team-members">
                <small class="mb-4">Tips</small>
                <br>
                <small>Use common job titles for searchability
                    Advertise for just one job eg: 'Nurse', not 'nurses'
                    No general opportunities or events</small>
            </div>
        </div>
    </div>
</main>
@endsection
@push('inc_js')
<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
<script src="{{ asset('Front') }}/EventDash/js/script.js"></script>
<script>
    const form = document.querySelector(".form");
    const fileInput = document.querySelector(".file-input");
    const progressArea = document.querySelector(".progress-area");

    // Load existing files from local storage
    let uploadedFiles = JSON.parse(localStorage.getItem("uploadedFiles")) || [];

    // Function to save files to local storage
    function saveFilesToLocalStorage() {
        localStorage.setItem("uploadedFiles", JSON.stringify(uploadedFiles));
    }

    // Function to display uploaded files
    function displayUploadedFiles() {
        progressArea.innerHTML = "";
        uploadedFiles.forEach((file, index) => {
            const progressHTML = `
      <li class="row-progress" data-index="${index}">
        <i class="fa-solid fa-image" style="color: #6a7381;"></i>
        <div class="progress-content">
          <div class="details">
            <span class="name" style="color: #6a7381;">${file.name} Uploaded</span>
            <div class="gallery" id="gallery"></div>
            <span><div class="percent" style="color: #6a7381;">${file.size}</div></span>
          </div>
          <div class="progress-bar">
            <div class="progress-part" style="width: 100%;"></div>
          </div>
          <div class="actions">
            <a href="#" class="delete-image">
              <i class="fas fa-trash-alt delete"></i>
            </a>
          </div>
        </div>
      </li>
    `;
            progressArea.innerHTML += progressHTML;
        });
    }

    // Display existing files on page load
    displayUploadedFiles();

    form.addEventListener("click", () => {
        fileInput.click();
    });

    fileInput.addEventListener("change", (event) => {
        const files = event.target.files;

        Array.from(files).forEach((file) => {
            const fileName = file.name;
            const fileSize = (file.size / 1024).toFixed(1) + " kB";

            // Add file to uploadedFiles array
            uploadedFiles.push({ name: fileName, size: fileSize });

            // Save to local storage
            saveFilesToLocalStorage();

            // Display updated list of files
            displayUploadedFiles();

            // Simulate upload progress
            const progressParts = document.querySelectorAll(".progress-part");
            const nameSpans = document.querySelectorAll(".name");
            let width = 0;
            const progressInterval = setInterval(() => {
                width += 10;
                const currentProgressPart = progressParts[progressParts.length - 1];
                const currentNameSpan = nameSpans[nameSpans.length - 1];
                currentProgressPart.style.width = width + "%";
                if (width >= 100) {
                    clearInterval(progressInterval);
                    currentNameSpan.textContent = `${fileName} Uploaded`;
                }
            }, 500);
        });
    });

    // Event delegation for view and delete buttons
    progressArea.addEventListener("click", (event) => {
        if (event.target.classList.contains("view-btn")) {
            const index = event.target.closest(".row-progress").dataset.index;
            alert(`Viewing file: ${uploadedFiles[index].name}`);
            // Implement your view functionality here
        } else if (event.target.classList.contains("delete")) {
            const index = event.target.closest(".row-progress").dataset.index;
            uploadedFiles.splice(index, 1);
            saveFilesToLocalStorage();
            displayUploadedFiles();
        }
    });

</script>
<script>
    $(document).ready(function () {
        $('.file-input').on('change', function () {
            var files = $(this)[0].files;
            if (files.length >= 1) {
                var formData = new FormData();
                for (var i = 0; i < files.length; i++) {
                    formData.append('gallery[]', files[i]); // إضافة الملفات إلى FormData
                }
                formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // إضافة CSRF token

                $.ajax({
                    url: "{{ route('store_gallery', $event->id) }}", // رابط تحميل الصور
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.success) {
                            var gallery = data.gallery; // البيانات المرجعة من السيرفر

                            // التأكد من أن gallery يحتوي على بيانات صحيحة
                            if (Array.isArray(gallery) && gallery.length > 0) {
                                var galleryHtml = '';
                                gallery.forEach(function (image) {
                                    galleryHtml += '<div class="col-6 col-md-4 col-lg-3 mb-3">';
                                    galleryHtml += '<img src="' + "{{ asset('storage') }}" + '/' + image.path + '" class="img-fluid" alt="">';
                                    galleryHtml += '</div>';
                                });
                                $('#gallery').html(galleryHtml); // عرض الصور في المعرض
                            } else {
                                console.error('gallery is empty or invalid:', gallery);
                                $('#gallery').html('<p class="text-center">No images found in the gallery.</p>');
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });</script>

@endpush
