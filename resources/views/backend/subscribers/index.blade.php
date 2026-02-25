@extends('backend.partials.master')

@section('title', 'All Subscribers')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">All Subscribers</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header border-bottom">
                <a href="{{ route('admin.create.email') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-plus ti-xs"> Send Email</i>
                </a>
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Subscriber Email</th>
                            <th style="text-align: center">Created At</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscribers as $subscriber)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $subscriber->email }}</td>
                                <td>{{ $subscriber->created_at->format('d-m-Y') }}</td>
                                {{--                                     @can('delete slider')--}}
                                <td>
                                <form action="{{ route('admin.unsubscribe', $subscriber->id) }}" method="POST"
                                      style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger" type="submit">
                                        <i class="ti ti-trash ti-sm"></i>
                                    </button>
                                </form>
                                </td>
                                {{--                                    @endcan--}}
                            @empty
                                <td colspan="4">No Subscribers Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-5">
                    {{ $subscribers->links() }}
                </div>
            </div>
            <!-- Offcanvas to add new user -->
        </div>
    </div>
    <!-- / Content -->
@endsection

@section('js')
    <!-- Page JS -->
    <script src="{{ asset('backend') }}/assets/js/app-user-list.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                responsive: true,
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                paging: false,
                info: false,
                language: {
                    search: "Search subscribers:",
                    emptyTable: "No subscribers available",
                }
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
