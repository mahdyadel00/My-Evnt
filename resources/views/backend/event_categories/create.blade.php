@extends('backend.partials.master')

@section('title', 'Add Category')

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
                <li class="breadcrumb-item active">Add Category</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <a href="{{ route('admin.event_categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-arrow-left ti-xs me-1"></i> Back to Categories
                </a>
            </div>
            <div class="card-body">
                <form id="addEventCategoryForm" action="{{ route('admin.event_categories.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Enter Category Name" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_parent" class="form-label">Category Type</label>
                                <select name="is_parent" id="is_parent" class="form-control" required>
                                    <option value="" {{ old('is_parent') === null ? 'selected' : '' }}>
                                        Select Category Type</option>
                                    <option value="1" {{ old('is_parent') === '1' ? 'selected' : '' }}>Parent</option>
                                    <option value="0" {{ old('is_parent') === '0' ? 'selected' : '' }}>Child</option>
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
                                    @foreach ($eventCategories as $eventCategory)
                                        <option value="{{ $eventCategory->id }}"
                                            {{ old('parent_id') == $eventCategory->id ? 'selected' : '' }}>
                                            {{ $eventCategory->name }}
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
                                    placeholder="Enter Category Description" rows="4">{{ old('description') }}</textarea>
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

            // Trigger change on page load to handle old input
            $('#is_parent').trigger('change');

            // SweetAlert2 for form submission confirmation
            $('#addEventCategoryForm').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to add this category?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Added!',
                            'The category has been added successfully.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
@endsection
