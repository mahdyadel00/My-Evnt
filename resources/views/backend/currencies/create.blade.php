@extends('backend.partials.master')

@section('title' , 'Add Currency')
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.currencies.index') }}">Currencies</a>
                </li>
                <li class="breadcrumb-item active">Add Currency</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.currencies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75"> Currency Name</p>
                                    <input type="text" class="form-control" name="name" placeholder="Currency Name" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75"> Currency Code</p>
                                    <input type="text" class="form-control" name="code" placeholder="Currency Code" required>
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75"> Currency Status</p>
                                    <select name="status" class="form-control" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75">Currency Image</p>
                                    <input type="file" name="image" class="form-control" required>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                        <button class="btn btn-primary btn-sm pd-x-20" type="submit"><i class="ti ti-device-floppy ti-xs"> Add</i>
                        </button>
                    </div>
                </div>
            </div>

            <!--/ form -->
            <!-- connection -->
        </div>
        </form>

    </div>
    <!-- / Content -->
@endsection

@section('js')
    <!-- Page JS -->
    <script src="{{ asset('backend') }}/assets/js/app-user-list.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        function selectAllPermissions() {
            var selectAllCheckbox = document.getElementById("select-checkbox");
            var modulePermissions = document.getElementsByClassName("module");

            for (var i = 0; i < modulePermissions.length; i++) {
                modulePermissions[i].checked = selectAllCheckbox.checked;
            }
        }

        document.getElementById('select-all-checkbox').addEventListener('change', function() {
            var modulePermissions = document.getElementsByClassName('module-permission');

            // تحديد أو إلغاء تحديد جميع صناديق الاختيار ذات الصلاحيات المرتبطة
            for (var i = 0; i < modulePermissions.length; i++) {
                modulePermissions[i].checked = this.checked;
            }
        });
    </script>
@endsection
