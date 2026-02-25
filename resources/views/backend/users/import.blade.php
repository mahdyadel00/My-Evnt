@extends('backend.partials.master')

@section('title', 'Import Users from Excel')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}">All Users</a>
                </li>
                <li class="breadcrumb-item active">Import Users</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-file-import ti-xs me-2"></i>
                            Import Users from Excel
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading fw-bold mb-1">
                                <i class="ti ti-info-circle ti-xs me-1"></i>
                                Instructions
                            </h6>
                            <ul class="mb-0 ps-3">
                                <li>Upload an Excel file (.xlsx, .xls, or .csv) with user data</li>
                                <li>Maximum file size: 10MB</li>
                                <li>The first row should contain column headers</li>
                                <li>Required columns: <strong>first_name</strong>, <strong>last_name</strong></li>
                                <li>Optional columns: <strong>middle_name</strong>, <strong>email</strong>, <strong>phone</strong>, 
                                    <strong>user_name</strong>, <strong>password</strong>, <strong>country</strong>, <strong>city</strong>, 
                                    <strong>about</strong>, <strong>address</strong>, <strong>birth_date</strong>, <strong>is_active</strong>
                                </li>
                                <li>If password is not provided, default password will be: <strong>password123</strong></li>
                                <li>If email is provided, username will be generated from email automatically</li>
                                <li>Country and City should match existing records in the database</li>
                            </ul>
                        </div>

                        <form action="{{ route('admin.users.import.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="file" class="form-label">
                                    Excel File <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="file" 
                                    name="file" 
                                    id="file" 
                                    class="form-control @error('file') is-invalid @enderror" 
                                    accept=".xlsx,.xls,.csv"
                                    required
                                >
                                @error('file')
                                    <div class="invalid-feedback d-block">
                                        <i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Supported formats: .xlsx, .xls, .csv (Max: 10MB)
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="ti ti-upload ti-xs me-1"></i>
                                    Import Users
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left ti-xs me-1"></i>
                                    Cancel
                                </a>
                            </div>
                        </form>

                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Sample Excel Format:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>first_name</th>
                                            <th>middle_name</th>
                                            <th>last_name</th>
                                            <th>email</th>
                                            <th>phone</th>
                                            <th>user_name</th>
                                            <th>password</th>
                                            <th>country</th>
                                            <th>city</th>
                                            <th>about</th>
                                            <th>address</th>
                                            <th>birth_date</th>
                                            <th>is_active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>John</td>
                                            <td>Michael</td>
                                            <td>Doe</td>
                                            <td>john.doe@example.com</td>
                                            <td>01234567890</td>
                                            <td>johndoe</td>
                                            <td>SecurePass123</td>
                                            <td>Egypt</td>
                                            <td>Cairo</td>
                                            <td>Software Developer</td>
                                            <td>123 Main St</td>
                                            <td>1990-01-15</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>Jane</td>
                                            <td></td>
                                            <td>Smith</td>
                                            <td>jane.smith@example.com</td>
                                            <td>01234567891</td>
                                            <td></td>
                                            <td></td>
                                            <td>Egypt</td>
                                            <td>Alexandria</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

