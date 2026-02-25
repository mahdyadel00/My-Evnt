@extends('backend.partials.master')

@section('title', 'Contact List')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Contact List</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Phone</th>
                            <th style="text-align: center">Subject</th>
                            <th style="text-align: center">Message</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->subject }}</td>
                                <td>{!! substr($contact->message, 0, 50) !!}...</td>
                                <td>
{{--                                     @can('delete features')--}}
                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
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
                <div class="d-flex justify-content-center mt-5">
                    {{ $contacts->links() }}
                </div>
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
                    search: "Search contacts:",
                    emptyTable: "No contacts available",
                }
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
