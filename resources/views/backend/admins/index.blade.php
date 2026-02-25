@extends('backend.partials.master')

@section('title')
    Admins
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet"
        href="{{ asset('backend') }}/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <style>
        /* تطبيق التصميم على أزرار DataTables */
        .dt-buttons .dt-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 4px;
        }

        /* تصميم خاص لكل زر */
        .dt-buttons .dt-button.buttons-copy {
            background-color: #acacac;
        }

        .dt-buttons .dt-button.buttons-excel {
            background-color: #acacac;
        }

        .dt-buttons .dt-button.buttons-pdf {
            background-color: #acacac;
        }

        .dt-buttons .dt-button.buttons-print {
            background-color: #acacac;
        }

        /* التصميم عند تحريك المؤشر */
        .dt-buttons .dt-button:hover {
            opacity: 0.8;
        }
    </style>
@endsection

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Session</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">21,459</h4>
                                    <span class="text-success">(+29%)</span>
                                </div>
                                <span>Total Users</span>
                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="ti ti-user ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Paid Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">4,567</h4>
                                    <span class="text-success">(+18%)</span>
                                </div>
                                <span>Last week analytics </span>
                            </div>
                            <span class="badge bg-label-danger rounded p-2">
                                <i class="ti ti-user-plus ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Active Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">19,860</h4>
                                    <span class="text-danger">(-14%)</span>
                                </div>
                                <span>Last week analytics</span>
                            </div>
                            <span class="badge bg-label-success rounded p-2">
                                <i class="ti ti-user-check ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Pending Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">237</h4>
                                    <span class="text-success">(+42%)</span>
                                </div>
                                <span>Last week analytics</span>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                                <i class="ti ti-user-exclamation ti-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            {{-- <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_plan"></div>
                    <div class="col-md-4 user_status"></div>
                </div>
            </div> --}}
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>E-mail</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($admins as $admin) --}}
                        <tr>
                            {{-- <td>{{ $admin->id }}</td> --}}
                            <td>1</td>
                            {{-- <td>{{ $admin->name }}</td> --}}
                            <td>Abdo</td>
                            {{-- <td>{{ $admin->email }}</td> --}}
                            <td>abdo</td>
                            <td>
                                {{-- @if ($admin->status == 1) --}}
                                <span class="badge bg-label-success">Active</span>
                                {{-- @else --}}
                                <span class="badge bg-label-danger">Inactive</span>
                                {{-- @endif --}}
                            </td>
                            <td>Admin</td>
                            {{-- <td>{{ $admin->role }}</td> --}}
                            <td>
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-warning">
                                    <i class="ti ti-eye ti-sm"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-info">
                                    <i class="ti ti-pencil ti-sm"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger">
                                    <i class="ti ti-trash ti-sm"></i>
                                </a>
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
            <!-- Offcanvas to add new user -->
        </div>
    </div>
    <!-- / Content -->


    </div>
@endsection

@section('js')
    <!-- Page JS -->
    <script src="{{ asset('backend') }}/assets/js/app-user-list.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#example2').DataTable({
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection