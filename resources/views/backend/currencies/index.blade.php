@extends('backend.partials.master')

@section('title', 'Currencies')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Currencies</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
{{--                @can('create slider')--}}
                    <a href="{{ route('admin.currencies.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add Currency</i>
                    </a>
{{--                @endcan--}}
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($currencies as $currency)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>
                                    @foreach($currency->media as $media)
                                        <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 50px; height: 50px;">
                                    @endforeach
                                </td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->code }}</td>
                                <td>
                                    @if ($currency->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
{{--                                    @can('update slider')--}}
                                        <a href="{{ route('admin.currencies.edit', $currency->id) }}"
                                            class="btn btn-xs btn-outline-info">
                                            <i class="ti ti-pencil ti-sm"></i>
                                        </a>
{{--                                     @endcan--}}
{{--                                     @can('delete slider')--}}
                                        <form action="{{ route('admin.currencies.destroy', $currency->id) }}" method="POST"
                                            style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-outline-danger" type="submit">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        </form>
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
