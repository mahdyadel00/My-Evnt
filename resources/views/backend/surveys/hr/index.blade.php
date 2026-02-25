@extends('backend.partials.master')

@section('title', 'Survey Forms HR')

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

        .email-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .bulk-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .selected-count {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .checkbox-cell {
            width: 40px;
            text-align: center;
        }

        .email-form {
            display: none;
            margin-top: 15px;
        }

        .email-form.show {
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Survey Forms HR</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Survey Forms HR List</h5>
                <form id="export-form" class="export-form" method="GET" action="{{ route('admin.surveys.hr.export') }}">
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

                <!-- Email Section -->
                <div class="email-section">
                    <div class="bulk-actions">
                        <span id="selected-count" class="selected-count" style="display: none;">0 selected</span>
                        <button type="button" id="toggle-email-form" class="btn btn-sm btn-primary" disabled>
                            <i class="ti ti-mail ti-xs me-1"></i> Send Email
                        </button>
                        <button type="button" id="select-all-surveys" class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-checks ti-xs me-1"></i> Select All
                        </button>
                        <button type="button" id="deselect-all-surveys" class="btn btn-sm btn-outline-secondary" style="display: none;">
                            <i class="ti ti-x ti-xs me-1"></i> Deselect All
                        </button>
                    </div>

                    <!-- Email Form -->
                    <div id="email-form" class="email-form">
                        <form id="bulk-email-form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email-subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email-subject" name="subject"
                                           placeholder="Enter email subject..." required>
                                </div>
                                <div class="col-md-12">
                                    <label for="email-message" class="form-label">Email Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="email-message" name="message" rows="4"
                                              placeholder="Enter your message..." required></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ti ti-send ti-xs me-1"></i> Send Email to Selected
                                    </button>
                                    <button type="button" id="cancel-email" class="btn btn-secondary ms-2">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="surveysTable" class="table border-top table-striped">
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                                </th>
                                <th>#</th>
                                <th>Event Name</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company Name</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveys as $survey)
                                <tr>
                                    <td class="checkbox-cell">
                                        <input type="checkbox" class="form-check-input survey-checkbox"
                                               value="{{ $survey->id }}" data-email="{{ $survey->email }}"
                                               data-name="{{ $survey->first_name }} {{ $survey->last_name }}">
                                    </td>
                                    <td>{{ $loop->iteration + ($surveys->currentPage() - 1) * $surveys->perPage() }}</td>
                                    <td>{{ $survey->event?->name ?? '-' }}</td>
                                    <td>{{ $survey->first_name }} {{ $survey->last_name }}</td>
                                    <td>{{ $survey->email }}</td>
                                    <td>{{ $survey->phone }}</td>
                                    <td>{{ $survey->company_name }}</td>
                                    <td>{{ $survey->position }}</td>
                                    <td><span
                                            class="badge bg-{{ $survey->status == 'approved' ? 'success' : ($survey->status == 'pending' ? 'rejected' : 'danger') }}">{{ $survey->status }}</span>
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($survey->created_at)) }}</td>
                                    <td>
                                        @php
                                            $phone = preg_replace('/[^0-9]/', '', $survey->phone);
                                            if (Str::startsWith($phone, '0')) {
                                                $phone = '2' . $phone;
                                            }
                                            if (!Str::startsWith($phone, '+')) {
                                                $phone = '+' . $phone;
                                            }
                                            $waLink = 'https://wa.me/' . ltrim($phone, '+') . '?text=' . urlencode('السلام عليكم، معك إدارة الفعاليات بخصوص استبيانك.');
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
                                    <td colspan="11">No survey forms found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $surveys->links('pagination::bootstrap-5') }}
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

            // Survey status update functionality
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

            // Email functionality
            var selectedSurveys = [];

            // Update selected count and button states
            function updateSelectionUI() {
                var count = selectedSurveys.length;
                var countElement = $('#selected-count');
                var emailButton = $('#toggle-email-form');
                var selectAllBtn = $('#select-all-surveys');
                var deselectAllBtn = $('#deselect-all-surveys');

                if (count > 0) {
                    countElement.text(count + ' selected').show();
                    emailButton.prop('disabled', false);
                    selectAllBtn.hide();
                    deselectAllBtn.show();
                } else {
                    countElement.hide();
                    emailButton.prop('disabled', true);
                    selectAllBtn.show();
                    deselectAllBtn.hide();
                    $('#email-form').removeClass('show');
                }
            }

            // Individual checkbox change
            $(document).on('change', '.survey-checkbox', function () {
                var surveyId = $(this).val();
                var email = $(this).data('email');
                var name = $(this).data('name');

                if ($(this).is(':checked')) {
                    if (!selectedSurveys.find(s => s.id === surveyId)) {
                        selectedSurveys.push({
                            id: surveyId,
                            email: email,
                            name: name
                        });
                    }
                } else {
                    selectedSurveys = selectedSurveys.filter(s => s.id !== surveyId);
                }

                updateSelectionUI();
                updateSelectAllCheckbox();
            });

            // Select all checkbox in header
            $('#select-all-checkbox').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('.survey-checkbox').prop('checked', isChecked).trigger('change');
            });

            // Select all button
            $('#select-all-surveys').on('click', function () {
                $('.survey-checkbox').prop('checked', true).trigger('change');
                $('#select-all-checkbox').prop('checked', true);
            });

            // Deselect all button
            $('#deselect-all-surveys').on('click', function () {
                $('.survey-checkbox').prop('checked', false);
                $('#select-all-checkbox').prop('checked', false);
                selectedSurveys = [];
                updateSelectionUI();
            });

            // Update select all checkbox state
            function updateSelectAllCheckbox() {
                var totalCheckboxes = $('.survey-checkbox').length;
                var checkedCheckboxes = $('.survey-checkbox:checked').length;
                var selectAllCheckbox = $('#select-all-checkbox');

                if (checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0) {
                    selectAllCheckbox.prop('checked', true).prop('indeterminate', false);
                } else if (checkedCheckboxes > 0) {
                    selectAllCheckbox.prop('checked', false).prop('indeterminate', true);
                } else {
                    selectAllCheckbox.prop('checked', false).prop('indeterminate', false);
                }
            }

            // Toggle email form
            $('#toggle-email-form').on('click', function () {
                $('#email-form').toggleClass('show');
            });

            // Cancel email
            $('#cancel-email').on('click', function () {
                $('#email-form').removeClass('show');
                $('#bulk-email-form')[0].reset();
            });

            // Submit email form
            $('#bulk-email-form').on('submit', function (e) {
                e.preventDefault();

                if (selectedSurveys.length === 0) {
                    toastr.error('Please select at least one survey to send email.');
                    return;
                }

                var formData = {
                    _token: '{{ csrf_token() }}',
                    subject: $('#email-subject').val(),
                    message: $('#email-message').val(),
                    survey_ids: selectedSurveys.map(s => s.id)
                };

                var submitButton = $(this).find('button[type="submit"]');
                var originalText = submitButton.html();
                submitButton.html('<i class="spinner-border spinner-border-sm me-1"></i> Sending...').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.surveys.hr.sendEmail') }}',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#email-form').removeClass('show');
                            $('#bulk-email-form')[0].reset();

                            // Show detailed results if there were any failures
                            if (response.details && response.details.failed > 0) {
                                console.log('Email sending details:', response.details);
                            }
                        } else {
                            toastr.error(response.message || 'Failed to send emails.');
                        }
                    },
                    error: function (xhr) {
                        console.error('Email sending error:', xhr);
                        var errorMessage = 'Failed to send emails.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    },
                    complete: function () {
                        submitButton.html(originalText).prop('disabled', false);
                    }
                });
            });

            // Initialize
            updateSelectionUI();
        });
    </script>
@endsection
