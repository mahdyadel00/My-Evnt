@extends('backend.partials.master')

@section('title', 'Survey Forms')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .dt-buttons .dt-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            margin: 4px 2px;
            border-radius: 4px;
            transition: opacity 0.3s;
        }

        .dt-buttons .dt-button.buttons-excel {
            background-color: #28a745;
        }

        .dt-buttons .dt-button.buttons-csv {
            background-color: #17a2b8;
        }

        .dt-buttons .dt-button:hover {
            opacity: 0.8;
        }

        .export-form {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        #export {
            padding: 0.5rem 2.25rem;
            font-size: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Survey Forms</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Survey Forms List</h5>
                <form id="export-form" class="export-form" method="GET" action="{{ route('admin.surveys.export') }}">
                    @csrf
                    <input type="hidden" name="type" id="export-type" value="xlsx">
                    <input type="date" class="form-control" name="from" value="{{ request('from') }}" placeholder="From">
                    <input type="date" class="form-control" name="to" value="{{ request('to') }}" placeholder="To">
                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="$('#export-type').val('xlsx')">
                        <i class="ti ti-download ti-xs me-1"></i> Export Excel
                    </button>
                    <button type="submit" class="btn btn-sm btn-outline-info" onclick="$('#export-type').val('csv')">
                        <i class="ti ti-download ti-xs me-1"></i> Export CSV
                    </button>
                </form>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, email, phone, event..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="session_type" class="form-select">
                            <option value="">All Sessions</option>
                            @foreach($sessionTypes as $key => $type)
                                <option value="{{ $key }}" {{ request('session_type') == $key ? 'selected' : '' }}>
                                    {{ $type['title'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}"
                            placeholder="From">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="To">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="surveysTable" class="table border-top table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Name</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Session Type</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveys as $survey)
                                <tr>
                                    <td>{{ $loop->iteration + ($surveys->currentPage() - 1) * $surveys->perPage() }}</td>
                                    <td>{{ $survey->event?->name ?? '-' }}</td>
                                    <td>{{ $survey->full_name }}</td>
                                    <td>{{ $survey->email }}</td>
                                    <td>{{ $survey->phone }}</td>
                                    <td>{{ $survey->session_type_label }}</td>
                                    <td>{{ $survey->quantity }}</td>
                                    <td>{{ number_format($survey->total_amount, 2) }}</td>
                                    <td>{{ $survey->payment_method == 'default' ? 'Credit Card' : $survey->payment_method }}</td>
                                    <td><span
                                            class="badge bg-{{ $survey->status == 'confirmed' ? 'success' : ($survey->status == 'pending' ? 'warning' : 'danger') }}">{{ $survey->status_label }}</span>
                                    </td>
                                    <td>{{ $survey->date ? $survey->date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $survey->time }}</td>
                                    <td>
                                        @php
                                            $phone = preg_replace('/[^0-9]/', '', $survey->phone);
                                            if (Str::startsWith($phone, '0')) {
                                                $phone = '2' . $phone;
                                            }
                                            if (!Str::startsWith($phone, '+')) {
                                                $phone = '+' . $phone;
                                            }

                                            $paymentUrl = route('admin.surveys.payment', $survey->id);

                                            $whatsappMessage = "Hello " . $survey->full_name . ",\n\n";
                                            $whatsappMessage .= "You have a booking with us for: " . ($survey->event->name ?? 'the event') . "\n\n";
                                            $whatsappMessage .= "Booking details:\n";
                                            $whatsappMessage .= "• Session type: " . $survey->session_type_label . "\n";
                                            $whatsappMessage .= "• Number of tickets: " . $survey->quantity . "\n";
                                            $whatsappMessage .= "• Total amount: " . number_format($survey->total_amount, 2) . " EGP\n\n";
                                            $whatsappMessage .= "💳 DIRECT PAYMENT LINK (No extra pages):\n";
                                            $whatsappMessage .= $paymentUrl . "\n\n";
                                            $whatsappMessage .= "✅ Click to pay instantly!\n";
                                            $whatsappMessage .= "✅ Opens payment gateway directly\n";
                                            $whatsappMessage .= "✅ Secure & immediate confirmation\n\n";
                                            $whatsappMessage .= "In case of any questions, please do not hesitate to contact us.\n\n";
                                            $whatsappMessage .= "Thank you for choosing our events! 🎉";

                                            $waLink = 'https://wa.me/' . ltrim($phone, '+') . '?text=' . urlencode($whatsappMessage);
                                        @endphp
                                        <a href="{{ $waLink }}" target="_blank" class="btn btn-success btn-sm"
                                            title="Send WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        {{-- @can('update survey') --}}
                                        <select
                                            class="form-select form-select-sm d-inline-block w-auto ms-2 survey-status-select"
                                            data-id="{{ $survey->id }}">
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ $survey->status == $key ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <span class="spinner-border spinner-border-sm text-primary d-none" role="status"
                                            aria-hidden="true"></span>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No survey forms found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $surveys->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script>
        $(function () {
            $('#surveysTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                responsive: true,
                autoWidth: false,
            });
            $('.survey-status-select').on('change', function () {
                var select = $(this);
                var id = select.data('id');
                var status = select.val();
                var spinner = select.siblings('.spinner-border');
                spinner.removeClass('d-none');
                $.ajax({
                    url: '{{ route('admin.surveys.updateStatus') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status
                    },
                    success: function (res) {
                        spinner.addClass('d-none');
                        if (res.success) {
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message || 'Failed to update status.');
                        }
                    },
                    error: function (xhr) {
                        spinner.addClass('d-none');
                        toastr.error(xhr.responseJSON?.message || 'Failed to update status.');
                    }
                });
            });
        });
    </script>
@endsection
