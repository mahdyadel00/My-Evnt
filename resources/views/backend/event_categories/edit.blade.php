@extends('backend.partials.master')

@section('title', 'Edit Category')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.event_categories.index') }}">Categories</a>
                </li>
                <li class="breadcrumb-item active">Edit Category</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Edit Category</h5> -->
                <a href="{{ route('admin.event_categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-arrow-left ti-xs me-1"></i> Back to Categories
                </a>
            </div>
            <div class="card-body">
                <form id="editEventCategoryForm" action="{{ route('admin.event_categories.update', $eventCategory->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $eventCategory->name) }}" placeholder="Enter Category Name"
                                    required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    @if ($eventCategory->media->isNotEmpty())
                                        @if ($eventCategory->media->first()->path)  
                                        <a href="{{ asset('storage/' . $eventCategory->media->first()->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $eventCategory->media->first()->path) }}" alt="{{ $eventCategory->name }}"
                                                class="category-image" width="50" height="50">
                                        </a>
                                    @else
                                        <img src="{{ asset('backend/assets/img/avatars/1.png') }}" alt="Default Image"
                                            class="category-image" width="50" height="50">
                                    @endif
                                @endif
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_parent" class="form-label">Category Type</label>
                                <select name="is_parent" id="is_parent" class="form-control" required>
                                    <option value="" {{ old('is_parent', $eventCategory->is_parent) == null ? 'selected' : '' }}>
                                        Select Category Type</option>
                                    <option value="1" {{ old('is_parent', $eventCategory->is_parent) == '1' ? 'selected' : '' }}>
                                        Parent</option>
                                    <option value="0" {{ old('is_parent', $eventCategory->is_parent) == '0' ? 'selected' : '' }}>
                                        Child</option>
                                </select>
                                @error('is_parent')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 parent_category">
                            <div class="form-group">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">Select Parent Category</option>
                                    @foreach ($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}"
                                            {{ old('parent_id', $eventCategory->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-label">Category Description</label>
                                <textarea class="form-control ckeditor" id="description" name="description"
                                    placeholder="Enter Category Description" rows="4">{{ old('description', $eventCategory->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy ti-xs me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Toggle Parent Category visibility based on is_parent selection
            $('#is_parent').on('change', function() {
                var isParent = $(this).val();
                $('.parent_category').toggle(isParent === '0');
            });

            // Trigger change on page load to handle current value or old input
            $('#is_parent').trigger('change');

            // SweetAlert2 for form submission confirmation
            $('#editEventCategoryForm').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to update this category?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Updated!',
                            'The category has been updated successfully.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
@endsection
