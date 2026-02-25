@extends('backend.partials.master')

@section('title' , 'Add Package')
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.packages.index') }}">Packages</a>
                </li>
                <li class="breadcrumb-item active">Add Package</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75"> Package Title</p>
                                        <input type="text" class="form-control" name="title" placeholder="Package Title" required>
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75"> Package Description</p>
                                        <textarea name="description" class="form-control" placeholder="Package Description" required></textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Price Monthly</p>
                                        <input type="text" class="form-control" name="price_monthly" placeholder="Price Monthly" required>
                                        @error('price_monthly')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Price Yearly</p>
                                        <input type="text" class="form-control" name="price_yearly" placeholder="Price Yearly" required>
                                        @error('price_yearly')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Discount</p>
                                        <input type="text" class="form-control" name="discount" placeholder="Discount" required>
                                        @error('discount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-xs-5 col-sm-5 col-md-5">
                                        <p class="card-title mb-75">Percentage</p>
                                        <input type="text" class="form-control" name="percentage" placeholder="Percentage" value="%" required>
                                        @error('percentage')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                            <button class="btn btn-primary btn-sm pd-x-20" type="submit"><i class="ti ti-device-floppy ti-xs"> Add</i></button>
                        </div>
                    </div>
                </div>
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
        //calculate price in percentage and native value in price yearly and price monthly
        $(document).ready(function() {
            $('input[name="percentage"]').on('keyup', function() {
                var percentage = $(this).val();
                var price_monthly   = $('input[name="price_monthly"]').val();
                var price_yearly    = $('input[name="price_yearly"]').val();
                var discount        = $('input[name="discount"]').val();
                if (percentage != '' && price_monthly != '' && price_yearly != '' && discount == ''){
                    var price_monthly   = parseFloat(price_monthly);
                    var price_yearly    = parseFloat(price_yearly);
                    var percentage      = parseFloat(percentage);
                    var price_monthly   = price_monthly - (price_monthly * percentage / 100);
                    var price_yearly    = price_yearly - (price_yearly * percentage / 100);
                    console.log(price_monthly , price_yearly , percentage);
                    $('input[name="price_monthly"]').val(price_monthly);
                    $('input[name="price_yearly"]').val(price_yearly);
                }else if (discount != '' && price_monthly != '' && price_yearly != '' && percentage == ''){
                    var price_monthly   = parseFloat(price_monthly);
                    var price_yearly    = parseFloat(price_yearly);
                    var discount        = parseFloat(discount);
                    var price_monthly   = price_monthly - discount;
                    var price_yearly    = price_yearly - discount;
                    console.log(price_monthly , price_yearly , discount);
                    $('input[name="price_monthly"]').val(price_monthly);
                    $('input[name="price_yearly"]').val(price_yearly);
                }
            });
        });



    </script>
@endsection
