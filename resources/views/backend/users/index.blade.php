@extends('backend.partials.master')

@section('title', 'All Users')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">All Users</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        @if (Session::has('send_details'))
            @php
                $sendDetails = Session::get('send_details');
                $firstDetail = $sendDetails[0] ?? [];
                $isEmail = isset($firstDetail['email']);
                $isMessage = isset($firstDetail['phone']) || isset($firstDetail['response_id']);
            @endphp
            <div class="alert alert-info alert-dismissible" role="alert">
                <h6 class="alert-heading fw-bold mb-2">
                    <i class="ti ti-info-circle ti-xs me-1"></i>
                    Details of the sent {{ $isEmail ? 'email' : 'message' }}
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>{{ $isEmail ? 'Email' : 'Phone' }}</th>
                                @if ($isMessage)
                                    <th>Response ID</th>
                                @endif
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sendDetails as $detail)
                                <tr>
                                    <td>{{ $detail['user'] ?? 'N/A' }}</td>
                                    <td>{{ $detail['phone'] ?? ($detail['email'] ?? 'N/A') }}</td>
                                    @if ($isMessage)
                                        <td>
                                            <code>{{ $detail['response_id'] ?? 'N/A' }}</code>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="ti ti-check ti-xs me-1"></i>
                                            {{ $detail['message'] ?? 'Sent successfully' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="ti ti-info-circle ti-xs me-1"></i>
                        You can check the Log files in <code>storage/logs/laravel.log</code> for more details
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('info'))
            <div class="alert alert-info alert-dismissible" role="alert">
                <h6 class="alert-heading fw-bold mb-2">
                    <i class="ti ti-info-circle ti-xs me-1"></i>
                    Information
                </h6>
                <p class="mb-0">{{ Session::get('info') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('send_errors'))
            <div class="alert alert-warning alert-dismissible" role="alert">
                <h6 class="alert-heading fw-bold mb-2">
                    <i class="ti ti-alert-triangle ti-xs me-1"></i>
                    Sending Errors
                </h6>
                <ul class="mb-0">
                    @foreach (Session::get('send_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        @can('create user') 
                            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-plus ti-xs me-1"></i> Create New User
                            </a>
                        @endcan 
                        <a href="{{ route('admin.user.import') }}" class="btn btn-sm btn-outline-success">
                            <i class="ti ti-file-import ti-xs me-1"></i> Import Users from Excel
                        </a>
                    </div>
                    <div>
                        <button type="button" id="selectAllBtn" class="btn btn-sm btn-outline-info">
                            <i class="ti ti-checkbox ti-xs me-1"></i> Select All Users
                        </button>
                        <button type="button" id="deselectAllBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-square ti-xs me-1"></i> Deselect All Users
                        </button>
                        <button type="button" id="sendMessageBtn" class="btn btn-sm btn-outline-warning" disabled>
                            <i class="ti ti-brand-whatsapp ti-xs me-1"></i> Send Message
                        </button>
                        <!--send email button -->
                        <button type="button" id="sendEmailBtn" class="btn btn-sm btn-outline-info" disabled>
                            <i class="ti ti-brand-email ti-xs me-1"></i> Send Email
                        </button>
                        <!-- Export to Excel button -->
                        <a href="{{ route('admin.users.export') }}" class="btn btn-sm btn-outline-success">
                            <i class="ti ti-file-export ti-xs me-1"></i> Export to Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <form id="usersForm" action="{{ route('admin.users.send-message') }}" method="GET">
                    <table id="example2" class="table border-top table-striped text-center">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll" title="Select All">
                                </th>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Role</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                            class="user-checkbox" 
                                            data-phone="{{ $user->phone ?? '' }}"
                                            data-email="{{ $user->email ?? '' }}">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($user->media->isNotEmpty())
                                        <img src="{{ asset('storage/' . $user->media->first()->path) }}"
                                            alt="{{ $user->user_name }}" class="user-image" style="border-radius: 50%;" width="50" height="50">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $user->user_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>{{ $user->city?->name ?? 'N/A' }}</td>
                                <td>{{ $user->roles->first()?->name ?? 'N/A' }}</td>
                                <td>{{ $user->type ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('view user')
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-xs btn-outline-warning" title="View">
                                                <i class="ti ti-eye ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('update user')
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-xs btn-outline-info" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete user')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->user_name }}')"
                                                title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </form>
                <form id="usersEmailForm" action="{{ route('admin.users.send-email') }}" method="GET" style="display: none;">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Select All functionality
        $('#selectAll').on('change', function() {
            $('.user-checkbox').prop('checked', this.checked);
            updateSendButton();
        });

        $('#selectAllBtn').on('click', function() {
            $('.user-checkbox').prop('checked', true);
            $('#selectAll').prop('checked', true);
            updateSendButton();
        });

        $('#deselectAllBtn').on('click', function() {
            $('.user-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateSendButton();
        });

        // Individual checkbox change
        $(document).on('change', '.user-checkbox', function() {
            updateSelectAll();
            updateSendButton();
        });

        // Update Select All checkbox
        function updateSelectAll() {
            const total = $('.user-checkbox').length;
            const checked = $('.user-checkbox:checked').length;
            $('#selectAll').prop('checked', total === checked && total > 0);
        }

        // Update Send Message and Send Email button states
        function updateSendButton() {
            const checked = $('.user-checkbox:checked').length;

            // Update Send Message button
            if (checked > 0) {
                $('#sendMessageBtn').prop('disabled', false);
            } else {
                $('#sendMessageBtn').prop('disabled', true);
            }

            // Update Send Email button
            if (checked > 0) {
                $('#sendEmailBtn').prop('disabled', false);
            } else {
                $('#sendEmailBtn').prop('disabled', true);
            }
        }

        // Send Message button click
        $('#sendMessageBtn').on('click', function() {
            const checked = $('.user-checkbox:checked');
            const hasPhone = checked.filter(function() {
                return $(this).data('phone') && $(this).data('phone').trim() !== '';
            });

            if (hasPhone.length === 0) {
                alert('Please select users with phone numbers');
                return;
            }

            $('#usersForm').submit();
        });

        // Send Email button click
        $('#sendEmailBtn').on('click', function() {
            const checked = $('.user-checkbox:checked');
            const hasEmail = checked.filter(function() {
                return $(this).data('email') && $(this).data('email').trim() !== '';
            });

            if (hasEmail.length === 0) {
                alert('Please select users with email addresses');
                return;
            }

            // Copy checked user IDs to email form
            const checkedIds = checked.map(function() {
                return $(this).val();
            }).get();
            
            $('#usersEmailForm').empty();
            checkedIds.forEach(function(id) {
                $('#usersEmailForm').append('<input type="hidden" name="user_ids[]" value="' + id + '">');
            });

            $('#usersEmailForm').submit();
        });

        // Initial check
        updateSendButton();
    });
</script>
@endsection