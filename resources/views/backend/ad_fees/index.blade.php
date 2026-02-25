@extends('backend.partials.master')

@section('title', 'Ad Fees')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Ad Fees</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
{{--                @can('create ad_fees')--}}
                    <a href="{{ route('admin.ad_fees.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add Ad Fees</i>
                    </a>
{{--                @endcan--}}
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Price</th>
                            <th style="text-align: center">Duration</th>
                            <th style="text-align: center">Description</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($ad_fees as $ad_fee)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $ad_fee->name }}</td>
                                <td>{{ $ad_fee->price }}</td>
                                <td>{{ $ad_fee->duration }}</td>
                                <td>{!! $ad_fee->description !!}</td>
                                <td>
                                    <div style="display: flex">
{{--                                    @can('update ad_fees')--}}
                                        <a href="{{ route('admin.ad_fees.edit', $ad_fee->id) }}" style="margin: 0 5px;"
                                            class="btn btn-xs btn-outline-info">
                                            <i class="ti ti-pencil ti-sm"></i>
                                        </a>
{{--                                     @endcan--}}
{{--                                     @can('delete ad_fees')--}}
                                        <form action="{{ route('admin.ad_fees.destroy', $ad_fee->id) }}" method="POST"
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
