@extends('Frontend.organization.account.inc.master')
@section('title', 'Orders')
@section('css')
    <style>
        .orders-filters-form {
            margin-bottom: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            background: #151922;
            /* نفس ألوان الداشبورد */
            border: 1px solid #262a35;
        }

        .filters-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px 25px;
            align-items: flex-end;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            min-width: 160px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 500;
            color: #b0b3c1;
            margin-bottom: 4px;
        }

        .filter-select,
        .filter-input {
            background-color: #1f2430;
            border: 1px solid #343846;
            color: #fff;
            height: 38px;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 13px;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.4);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            margin-left: auto;
            /* يزق الأزرار لليمين */
        }

        .orders-filter-btn,
        .orders-export-btn,
        .orders-clear-btn {
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .orders-filter-btn {
            background: #4f46e5;
            color: #fff;
        }

        .orders-filter-btn:hover {
            background: #4338ca;
        }

        .orders-clear-btn {
            background: transparent;
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .orders-clear-btn:hover {
            background: #ef4444;
            color: #fff;
        }

        .orders-export-btn {
            background: transparent;
            color: #9ca3af;
            border: 1px solid #343846;
        }

        .orders-export-btn:hover {
            color: #e5e7eb;
            border-color: #4f46e5;
        }

        /* موبايل */
        @media (max-width: 768px) {
            .filters-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-actions {
                margin-left: 0;
                justify-content: flex-start;
            }
        }
    </style>
@endsection
@section('content')
    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px; ">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="{{ route('home') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">Orders </a>
                </li>
            </ul>
            <h1>Orders</h1>
        </div>
    </div>
    @include('Frontend.organization.layouts._message')
    <!-- end breadcrumb  -->

    <!-- Orders Table Container -->
    <div class="orders-table-wrapper">
        <div class="orders-table-container">

            <!-- Filters & Export -->
            <form method="GET" action="{{ route('organization.orders') }}" class="orders-filters-form">
                <div class="filters-row">
                    <div class="filter-item">
                        <label class="filter-label">Ticket Type</label>
                        <select name="ticket_type" class="filter-select">
                            <option value="">All</option>
                            <option value="attendee" {{ request('ticket_type') === 'attendee' ? 'selected' : '' }}>Attendee
                            </option>
                            <option value="startups" {{ request('ticket_type') === 'startups' ? 'selected' : '' }}>Startups
                            </option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">Status</label>
                        <select name="status" class="filter-select">
                            <option value="">All</option>
                            @isset($availableStatuses)
                                @foreach($availableStatuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">From</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="filter-input">
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="filter-input">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="orders-filter-btn">Filter</button>
                        <button type="button" class="orders-clear-btn" id="clearFiltersBtn">Clear Filter</button>
                        <a href="{{ route('organization.orders.export', request()->query()) }}"
                            class="orders-export-btn">Export CSV</a>
                    </div>
                </div>
            </form>

            <table class="orders-table-main">
                <thead class="orders-table-header">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Ticket Types</th>
                        <th>Status</th>
                        <th>Order Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="orders-table-body">
                    @forelse($orders as $order)
                                    <tr id="order-row-{{ $order->id }}">
                                        <td data-label="ID">{{ $order->id }}</td>
                                        <td data-label="Name">{{ $order->first_name ?? 'N/A' }} {{ $order->last_name ?? '' }}
                                        </td>
                                        <td data-label="Email">{{ $order->email ?? 'N/A' }}</td>
                                        <td data-label="Ticket Types">
                                            @php
                                                $ticketDisplay = 'N/A';
                                                if ($order->ticket_type === 'attendee' && $order->attendee_type) {
                                                    $ticketDisplay = ucfirst($order->attendee_type);
                                                }
                                                if ($order->attendee_type === 'mentorship' && $order->mentorship_track) {
                                                    $ticketDisplay = $order->mentorship_track;
                                                }
                                                if ($order->ticket_type === 'startups') {
                                                    $ticketDisplay = 'Startups';
                                                }
                                            @endphp
                                            <span class="ticket-badge-primary">
                                                {{ $ticketDisplay }}
                                            </span>
                                        </td>
                                        <td data-label="Status">
                                            @php
                                                $statusMap = [
                                                    'pending' => 'Pending',
                                                    'checked' => 'Checked In',
                                                    'exited' => 'Completed',
                                                    'cancelled' => 'Cancelled',
                                                ];

                                                $statusLabel = $statusMap[$order->status] ?? ucfirst($order->status);
                                            @endphp

                                            <span class="status-badge-completed">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td data-label="Order Number">
                                            #{{ $orderNumbersMap[$order->email] ?? 'N/A' }}
                                        </td>
                                        <td data-label="Action" class="orders-action-cell">
                                            <button type="button" class="orders-view-btn" data-bs-toggle="modal"
                                                data-bs-target="#orderDetailsModal" data-order-id="{{ $order->id }}" data-order-data="{{ json_encode([
                            'id' => $order->id,
                            'customer_name' => trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')),
                            'order_number' => $orderNumbersMap[$order->email] ?? 'N/A',
                            'email' => $order->email ?? 'N/A',
                            'status' => $order->status ?? 'Completed',
                            'status_label' => $order->status ?? 'Completed',
                            'ticket_type' => $order->ticket_type,
                            'attendee_type' => $order->attendee_type,
                            'mentorship_track' => $order->mentorship_track,
                            'startup_file_url' => $order->startup_file ? asset('storage/' . $order->startup_file) : null,
                            'quantity' => $order->quantity ?? 1,
                            'order_date' => $order->created_at?->format('Y-m-d h:i A') ?? 'N/A',
                            'event_name' => $order->event?->name ?? 'N/A',
                        ]) }}" aria-label="View order details">
                                                <i class="bx bx-show"></i>
                                            </button>
                                            <button type="button" class="orders-delete-btn" data-order-id="{{ $order->id }}"
                                                onclick="deleteOrder({{ $order->id }})" aria-label="Delete order">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- pagination -->
            @if($orders->hasPages())
                <div class="pagination-container-orders">
                    @if(!$orders->onFirstPage())
                        <div class="pagination-item-orders-first">
                            <a href="{{ $orders->previousPageUrl() }}">Previous</a>
                        </div>
                    @endif

                    @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                        <div class="pagination-item-orders-{{ $page === $orders->currentPage() ? 'first' : 'last' }}">
                            <a class="{{ $page === $orders->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                        </div>
                    @endforeach

                    @if($orders->hasMorePages())
                        <div class="pagination-item-orders-last">
                            <a href="{{ $orders->nextPageUrl() }}">Next</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content orders-modal-content">
                <div class="modal-header orders-modal-header">
                    <h5 class="modal-title orders-modal-title" id="orderDetailsModalLabel">
                        Order Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body orders-modal-body">
                    <div class="orders-detail-section" id="orderDetailsContent">
                        <!-- Content will be filled by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer orders-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('inc_js')
    <script>
        // Handle clear filters button
        document.addEventListener('DOMContentLoaded', function () {
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function () {
                    // Reset all filter inputs
                    const form = document.querySelector('.orders-filters-form');
                    if (form) {
                        // Reset selects to "All"
                        const selects = form.querySelectorAll('.filter-select');
                        selects.forEach(select => {
                            select.value = '';
                        });

                        // Reset date inputs
                        const dateInputs = form.querySelectorAll('.filter-input');
                        dateInputs.forEach(input => {
                            input.value = '';
                        });

                        // Redirect to orders page without query parameters
                        window.location.href = '{{ route("organization.orders") }}';
                    }
                });
            }
        });

        // Handle order details modal
        document.addEventListener('DOMContentLoaded', function () {
            const orderDetailsModal = document.getElementById('orderDetailsModal');
            const orderDetailsContent = document.getElementById('orderDetailsContent');

            if (!orderDetailsModal || !orderDetailsContent) {
                return;
            }

            orderDetailsModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const orderData = JSON.parse(button.getAttribute('data-order-data'));

                // Build status badge class
                let statusBadgeClass = 'status-badge-completed';

                // Build ticket type display
                let ticketTypeDisplay = 'N/A';
                if (orderData.ticket_type === 'attendee' && orderData.attendee_type) {
                    ticketTypeDisplay = orderData.attendee_type;
                    if (orderData.attendee_type === 'mentorship' && orderData.mentorship_track) {
                        ticketTypeDisplay = orderData.mentorship_track;
                    }
                } else if (orderData.ticket_type === 'startups') {
                    ticketTypeDisplay = 'Startups';
                } else if (orderData.ticket_type) {
                    ticketTypeDisplay = orderData.ticket_type;
                }

                // Build content HTML
                let html = `
                            <!-- customer name -->
                            <div class="orders-detail-row col-5">
                                <div class="orders-detail-label">
                                    Customer Name
                                </div>
                                <div class="orders-detail-value">
                                    ${orderData.customer_name || 'N/A'}
                                </div>
                            </div>
                            <!-- order number -->
                            <div class="orders-detail-row col-5">
                                <div class="orders-detail-label">
                                    Order Number
                                </div>
                                <div class="orders-detail-value">
                                    #${orderData.order_number || 'N/A'}
                                </div>
                            </div>
                            <!-- email -->
                            <div class="orders-detail-row col-12">
                                <div class="orders-detail-label">
                                    Email
                                </div>
                                <div class="orders-detail-value">
                                    ${orderData.email || 'N/A'}
                                </div>
                            </div>
                            <!-- event name -->
                            <div class="orders-detail-row col-12">
                                <div class="orders-detail-label">
                                    Event Name
                                </div>
                                <div class="orders-detail-value">
                                    ${orderData.event_name || 'N/A'}
                                </div>
                            </div>
                            <!-- ticket type -->
                            <div class="orders-detail-row col-5">
                                <div class="orders-detail-label">
                                    Ticket Type
                                </div>
                                <div class="orders-detail-value">
                                    <span class="ticket-badge-primary">${ticketTypeDisplay}</span>
                                </div>
                            </div>
                            <!-- status -->
                            <div class="orders-detail-row col-5">
                                <div class="orders-detail-label">
                                    Status
                                </div>
                                <div class="orders-detail-value">
                                    <span class="${statusBadgeClass}">${orderData.status_label || orderData.status || 'Completed'}</span>
                                </div>
                            </div>
                            <!-- order date -->
                            <div class="orders-detail-row col-5">
                                <div class="orders-detail-label">
                                    Order Date
                                </div>
                                <div class="orders-detail-value">
                                    ${orderData.order_date || 'N/A'}
                                </div>
                            </div>
                        `;

                // If startups ticket with file, show file link
                if (orderData.ticket_type === 'startups' && orderData.startup_file_url) {
                    html += `
                                <!-- startup file -->
                                <div class="orders-detail-row col-12">
                                    <div class="orders-detail-label">
                                        Startup File
                        </div>
                                    <div class="orders-detail-value">
                                        <a href="${orderData.startup_file_url}" target="_blank" class="orders-file-link">
                                            View / Download File
                                        </a>
                    </div>
                </div>
                            `;
                }

                orderDetailsContent.innerHTML = html;
            });
        });

        // Direct delete function (called from onclick) - Make it global
        window.deleteOrder = function (orderId) {
            console.log('deleteOrder function called with ID:', orderId);

            if (!orderId) {
                console.error('Order ID is required');
                return;
            }

            // Check if SweetAlert is available
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded');
                alert('Please wait, page is still loading...');
                return;
            }

            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    performDelete(orderId);
                }
            });
        }

        // Perform the actual delete operation
        function performDelete(orderId) {
            console.log('Performing delete for order:', orderId);

            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                Swal.fire({
                    title: 'Error!',
                    text: 'CSRF token not found',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Delete request
            fetch(`/organization/orders/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    console.log('Delete response status:', response.status);
                    // Check if response is ok
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    // Try to parse as JSON
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        // If not JSON, return text
                        return response.text().then(text => {
                            throw new Error('Server response is not JSON: ' + text);
                        });
                    }
                })
                .then(data => {
                    console.log('Delete response data:', data);
                    if (data && data.success) {
                        // Remove row with animation
                        const row = document.getElementById(`order-row-${orderId}`);
                        if (row) {
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();

                                // Check if table is empty
                                const tbody = document.querySelector('.orders-table-body');
                                if (tbody && tbody.children.length === 0) {
                                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No orders found</td></tr>';
                                }
                            }, 300);
                        }

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Order has been deleted successfully',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: (data && data.message) ? data.message : 'An error occurred while deleting the order',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Delete Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'An error occurred while deleting the order. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }

        // Handle delete order using event delegation (backup method)
        console.log('Orders page script loaded');

        // Wait for DOM and SweetAlert to be ready
        function initDeleteHandler() {
            // Check if SweetAlert is loaded
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 is not loaded yet, retrying...');
                setTimeout(initDeleteHandler, 100);
                return;
            }

            console.log('Delete handler initialized - SweetAlert2 is ready');
        }

        // Use event delegation to handle clicks on delete buttons
        document.addEventListener('click', function (e) {
            console.log('Click event detected on:', e.target);
            console.log('Clicked element classes:', e.target.classList ? Array.from(e.target.classList) : 'No classes');

            // Check if the clicked element is a delete button or inside one (icon)
            let deleteBtn = e.target;

            // If clicked on icon, get parent button
            if (deleteBtn.classList && (deleteBtn.classList.contains('bx-trash') || deleteBtn.classList.contains('bx'))) {
                console.log('Clicked on icon, finding parent button...');
                deleteBtn = deleteBtn.closest('.orders-delete-btn');
                console.log('Found parent button:', deleteBtn);
            } else if (!deleteBtn.classList || !deleteBtn.classList.contains('orders-delete-btn')) {
                console.log('Not a delete button, checking closest...');
                deleteBtn = deleteBtn.closest('.orders-delete-btn');
                console.log('Closest delete button:', deleteBtn);
            }

            if (!deleteBtn || !deleteBtn.classList || !deleteBtn.classList.contains('orders-delete-btn')) {
                console.log('Not a delete button, ignoring click');
                return;
            }

            console.log('Delete button found!', deleteBtn);
            e.preventDefault();
            e.stopPropagation();

            const orderId = deleteBtn.getAttribute('data-order-id');
            console.log('Order ID from button:', orderId);

            if (!orderId) {
                console.error('Order ID not found on button');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Order ID not found',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
                return;
            }

            console.log('Delete button clicked for order:', orderId);

            // Check if SweetAlert is available
            if (typeof Swal === 'undefined') {
                alert('Please wait, page is still loading...');
                return;
            }

            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'CSRF token not found',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Delete request
                    fetch(`/organization/orders/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            console.log('Delete response status:', response.status);
                            // Check if response is ok
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            // Try to parse as JSON
                            const contentType = response.headers.get("content-type");
                            if (contentType && contentType.indexOf("application/json") !== -1) {
                                return response.json();
                            } else {
                                // If not JSON, return text
                                return response.text().then(text => {
                                    throw new Error('Server response is not JSON: ' + text);
                                });
                            }
                        })
                        .then(data => {
                            console.log('Delete response data:', data);
                            if (data && data.success) {
                                // Remove row with animation
                                const row = document.getElementById(`order-row-${orderId}`);
                                if (row) {
                                    row.style.transition = 'opacity 0.3s ease';
                                    row.style.opacity = '0';
                                    setTimeout(() => {
                                        row.remove();

                                        // Check if table is empty
                                        const tbody = document.querySelector('.orders-table-body');
                                        if (tbody && tbody.children.length === 0) {
                                            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No orders found</td></tr>';
                                        }
                                    }, 300);
                                }

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Order has been deleted successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: (data && data.message) ? data.message : 'An error occurred while deleting the order',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Delete Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'An error occurred while deleting the order. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        });

        // Initialize delete handler when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                initDeleteHandler();
                // Test: Check if delete buttons exist
                const deleteButtons = document.querySelectorAll('.orders-delete-btn');
                console.log('Found delete buttons:', deleteButtons.length);
                deleteButtons.forEach((btn, index) => {
                    console.log(`Delete button ${index}:`, btn, 'Order ID:', btn.getAttribute('data-order-id'));
                });
            });
        } else {
            initDeleteHandler();
            // Test: Check if delete buttons exist
            const deleteButtons = document.querySelectorAll('.orders-delete-btn');
            console.log('Found delete buttons:', deleteButtons.length);
            deleteButtons.forEach((btn, index) => {
                console.log(`Delete button ${index}:`, btn, 'Order ID:', btn.getAttribute('data-order-id'));
            });
        }
    </script>
@endpush