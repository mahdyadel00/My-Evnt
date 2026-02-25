@extends('backend.partials.master')

@section('title', 'Packages')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Packages</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
{{--                @can('create feature')--}}
                    <a href="{{ route('admin.packages.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add Package</i>
                    </a>
{{--                @endcan--}}
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Package Titile</th>
                            <th style="text-align: center">Description</th>
                            <th style="text-align: center">Price Monthly</th>
                            <th style="text-align: center">Price Yearly</th>
                            <th style="text-align: center">Discount</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($packages as $package)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $package->title }}</td>
                                <td>{!! $package->description !!}</td>
                                <td>{{ $package->price_monthly }}</td>
                                <td>{{ $package->price_yearly }}</td>
                                <td>{{ $package->discount }}</td>
                                <td>
                                    <div style="display: flex;">
{{--                                    @can('update feature')--}}
                                        <a href="{{ route('admin.packages.edit', $package->id) }}" style="margin: 0 5px"
                                            class="btn btn-xs btn-outline-info">
                                            <i class="ti ti-pencil ti-sm"></i>
                                        </a>
{{--                                     @endcan--}}
{{--                                     @can('delete feature')--}}
                                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                            style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-outline-danger" type="submit">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        </form>
                                    </div>
{{--                                    @endcan--}}
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
