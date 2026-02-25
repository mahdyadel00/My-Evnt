@extends('backend.partials.master')

@section('title' , 'Send Email')
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.subscriptions') }}">Subscribers</a>
                </li>
                <li class="breadcrumb-item active">Add Email</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.send.email') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75"> Subject</p>
                                    <input type="text" class="form-control" name="subject" placeholder="Subject" >
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75"> Message</p>
                                    <textarea type="text" name="message" class="form-control" placeholder="Message" ></textarea>
                                </div>

                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <p class="card-title mb-75"> Subscribers</p>
                                    <select class="form-control" name="subscribers[]" multiple>
                                        @foreach($subscribers as $subscriber)
                                            <option value="{{ $subscriber->email }}">{{ $subscriber->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                        <button class="btn btn-primary btn-sm pd-x-20" type="submit"><i class="ti ti-device-floppy ti-xs"> Send</i>
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
