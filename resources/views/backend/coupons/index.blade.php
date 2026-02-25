@extends('backend.partials.master')

@section('title', 'All Coupons')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">All Coupons</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
{{--                @can('create city')--}}
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add New Coupon</i>
                    </a>
{{--                @endcan--}}
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Event</th>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">Type</th>
                            <th style="text-align: center">Value</th>
                            <th style="text-align: center">Start Date</th>
                            <th style="text-align: center">End Date</th>
                            <th style="text-align: center">Description</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $coupon->event?->name }}</td>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ $coupon->type }}</td>
                                <td>{{ $coupon->value }}</td>
                                <td>{{ date('d-m-Y', strtotime($coupon->start_date)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($coupon->end_date)) }}</td>
                                <td>{{ $coupon->description }}</td>
                                <td>
                                    <div style="display: flex">
{{--                                    @can('update city')--}}
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                            class="btn btn-xs btn-outline-info" style="margin: 0 5px">
                                            <i class="ti ti-pencil ti-sm"></i>
                                        </a>
{{--                                     @endcan--}}
{{--                                     @can('delete city')--}}
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                                            style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-outline-danger" type="submit">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        </form>
{{--                                    @endcan--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
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
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
