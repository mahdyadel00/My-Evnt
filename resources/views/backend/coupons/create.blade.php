@extends('backend.partials.master')

@section('title' , 'Add Coupon')
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.coupons.index') }}">All Coupons</a>
                </li>
                <li class="breadcrumb-item active">Add Coupon</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <label for="event_id">Event</label>
                                    <select class="form-control select2" name="event_id" id="event_id">
                                        <option disabled selected>Select Event</option>
                                        @foreach ($events as $event)
                                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" id="code" class="form-control" placeholder="Code">
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <label for="type">Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="fixed">Fixed</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <label for="value">Value</label>
                                    <input type="text" name="value" id="value" class="form-control" placeholder="Value">
                                    @error('value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        placeholder="Start Date">
                                    @error('start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        placeholder="End Date">
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 col-12 mt-2 mb-2">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control"
                                        placeholder="Description"></textarea>
                                    @error('description')
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
        //when select type change
        $('#type').change(function() {
            var type = $(this).val();   
            if (type == 'fixed') {
                $('#value').attr('placeholder', 'Value');
            } else {
                $('#value').attr('placeholder', 'Value in Percent');
            }
        });
    </script>
@endsection
