@extends('backend.partials.master')

@section('title', 'Reports')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Reports</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Reports</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>       
        <!-- Search and Filter Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">Search and Filter</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collapsetest" id="searchCollapse">
                        <div class="card-body border-top">
                            <!-- <form method="GET" action="{{ route('admin.reports.filter') }}"> -->
                                <div class="row g-3">                                   
                                    <div class="col-xl-2 col-sm-6">
                                        <select class="form-select select2 company_id" name="company_id">
                                            <option disabled selected>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-sm-6">
                                        <select class="form-select select2 category_id" name="category_id">
                                            <option disabled selected>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-sm-6">
                                        <select class="form-select select2 city_id" name="city_id">
                                            <option disabled selected>Select City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-sm-4">
                                        <input type="date" name="date_from" class="form-control date_from" 
                                               value="{{ request('date_from') }}" placeholder="From Date">
                                    </div>
                                    <div class="col-xl-2 col-sm-4">
                                        <input type="date" name="date_to" class="form-control date_to" 
                                               value="{{ request('date_to') }}" placeholder="To Date">
                                    </div>
                                    <div class="col-xl-1 col-sm-4">
                                        <div class="dropdown w-100">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                            <a class="dropdown-item waves-effect export-button" id="export-btn"><i class="ti ti-pencil me-2"></i> Export</a>
                                            <a class="dropdown-item waves-effect print-button"><i class="ti ti-printer me-2"></i> Print</a>
                                            <a class="dropdown-item waves-effect reset-button" id="reset-btn""><i class="ti ti-refresh me-2"></i> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Events List {{ $events->count() }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-nowrap align-middle mb-0" id="example3">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Poster</th>     
                                        <th scope="col">Poster</th>     
                                        <th scope="col">Company </th>
                                        <th scope="col">Category</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Event Status</th>
                                        <th scope="col">Event Format</th>
                                        <th scope="col">Exclusive</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events as $event)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @foreach ($event->media as $media)
                                                        @if ($media->name == 'poster')
                                                            <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $event->name }}" class="avatar-img image-link" width="50" height="50">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                {{ $event->name }}
                                            </td>
                                            <td>
                                                    {{ $event->company?->company_name }}
                                            </td>
                                            <td>{{ $event->category?->name }}</td>
                                            <td>
                                                {{ $event->city?->name }}
                                            </td>
                                            <td>
                                                @if($event->is_active)
                                                    <span class="badge bg-label-success me-1">Active</span>
                                                @else
                                                    <span class="badge bg-label-danger me-1">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($event->format)
                                                    <span class="badge bg-label-success me-1">Online</span>
                                                @else
                                                    <span class="badge bg-label-danger me-1">Offline</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($event->is_exclusive)
                                                    <span class="badge bg-label-success me-1">Yes</span>
                                                @else
                                                    <span class="badge bg-label-danger me-1">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-xs btn-outline-warning" title="View">
                                                        <i class="ti ti-eye ti-sm"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="ri-shopping-cart-line" style="font-size: 48px; color: #ccc;"></i>
                                                        <h5 class="mt-3">No events found</h5>
                                                    <p class="text-muted">No events found matching the search</p>
                                                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                                                        Add New Event
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('backend') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         $(document).ready(function() {
            var table = $('#example3').DataTable({
                responsive: true,
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[1, 'asc']],
                paging: true,
                info: true,
                language: {
                    search: "Search events:",
                    emptyTable: "No events available",
                    info: "Showing _START_ to _END_ of _TOTAL_ events"
                }
            });
            table.buttons().container()
                .appendTo('#example3_wrapper .col-md-6:eq(0)');

                 // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).attr('data-placeholder') || 'Select an option';
                },
                allowClear: true
            });
        });
        //when click image
        $(document).ready(function() {
            $('.image-link').on('click', function() {
                window.open($(this).attr('src'), '_blank');
            });
        });

        $(document).ready(function () {
            function fetchReports() {
                var company_id = $('.company_id').val();
                var category_id = $('.category_id').val();
                var city_id = $('.city_id').val();
                var date_from = $('.date_from').val();
                var date_to = $('.date_to').val();

                $.ajax({
                    url: '{{ route('admin.reports.filter') }}',
                    type: 'GET',
                    data: { 
                        company_id, 
                        category_id, 
                        city_id, 
                        date_from, 
                        date_to, 
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function (response) {
                        $('#example3 tbody').html(response.html);
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong while fetching data'
                        });
                    }
                });
            }

            // Filters: company, category, city, date range
            $('.company_id, .category_id, .city_id, .date_from, .date_to').on('change', function () {
                fetchReports();
            });
            $(document).ready(function () {
                $('.export-button').on('click', function () {   
                var company_id = $('.company_id').val();
                var category_id = $('.category_id').val();
                var city_id = $('.city_id').val();
                var date_from = $('.date_from').val();
                var date_to = $('.date_to').val();

                $.ajax({
                    url: '{{ route('admin.reports.export') }}',
                    type: 'GET',
                    data: { company_id, category_id, city_id, date_from, date_to, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Export Successful',
                                text: 'Your events have been exported as PDF.',
                            });
                            window.open(response.download_url, '_blank');
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong while exporting'
                        });
                    }
                    });
                });
            });
        });

        $('#reset-btn').on('click', function () {
            $('.company_id, .category_id, .city_id, .date_from, .date_to').val('');
            fetchReports();
        });
        
       </script>
@endsection
