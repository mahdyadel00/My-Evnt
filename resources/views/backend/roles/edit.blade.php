@extends('backend.partials.master')

@section('title' , 'Edit Role')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.roles.index') }}">Manage Roles</a>
                </li>
                <li class="breadcrumb-item active">Edit Role ({{ $role->name }})</li>
            </ol>
        </nav>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <p class="card-title mb-2">Role Name</p>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Enter Role Name" value="{{ old('name', $role->name) }}">
                                        @error('name')
                                            <span class="text-danger d-block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <h4 class="card-title mb-2">Permissions</h4>
                                <p class="mb-3">Update Permissions for this Role</p>

                                <div class="d-flex mt-2">
                                    <div class="d-flex align-items-center justify-content-between flex-grow-1">
                                        <div class="me-1">
                                            <p class="fw-bolder mb-0">Select All</p>
                                        </div>
                                        <div class="mt-sm-0">
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" class="form-check-input" id="select-all-checkbox" />
                                                <label class="form-check-label" for="select-all-checkbox"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr style="margin: 10px 0;">

                                @if (isset($permissions) && !$permissions->isEmpty())
                                    @foreach ($permissions as $permission)
                                        <div class="d-flex flex-wrap mt-2 align-items-center">
                                            <div class="me-1 col-md-4 mt-2 mb-2">
                                                <p class="fw-bolder mb-0 text-capitalize">{{ $permission->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center flex-grow-1 col-md-6 col-12 mt-2 mb-2">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" name="permission[]" value="{{ $permission->id }}"
                                                        class="module-permission form-check-input"
                                                        id="permission_{{ $permission->id ?? $loop->index }}"
                                                        {{ old('permission', $rolePermissions ?? []) && in_array($permission->id, old('permission', $rolePermissions)) ? 'checked' : '' }} />
                                                    <label class="form-check-label"
                                                        for="permission_{{ $permission->id ?? $loop->index }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="margin: 10px 0;">
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <p>No permissions found.</p>
                                    </div>
                                @endif

                                @error('permission')
                                    <div class="col-12">
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 text-center mb-3">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                <i class="ti ti-device-floppy ti-xs me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const allPermissionCheckboxes = document.querySelectorAll('.module-permission');

            function selectAllPermissions(isChecked) {
                allPermissionCheckboxes.forEach(checkbox => checkbox.checked = isChecked);
            }

            function updateSelectAllState() {
                if (!allPermissionCheckboxes.length) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                    return;
                }

                const allChecked = Array.from(allPermissionCheckboxes).every(checkbox => checkbox.checked);
                const noneChecked = Array.from(allPermissionCheckboxes).every(checkbox => !checkbox.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    selectAllPermissions(this.checked);
                });
            }

            allPermissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllState);
            });

            updateSelectAllState();
        });
    </script>
@endsection
