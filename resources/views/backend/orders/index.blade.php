@extends('backend.partials.master')

@section('title', 'Orders Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="mb-2 text-white">
                                <i class="ti ti-shopping-cart me-2"></i>
                                Orders Management
                            </h2>
                            <p class="mb-0 text-white-50">
                                <i class="ti ti-calendar me-1"></i>
                                Total: {{ $stats['total'] ?? $orders->total() }} Orders
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-light me-2" onclick="window.location.reload()">
                                <i class="ti ti-refresh me-1"></i>Refresh
                            </button>
                            <button class="btn btn-light" onclick="exportOrders()">
                                <i class="ti ti-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted small">Pending Payment</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['pending'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                            <i class="ti ti-clock text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-label-warning">Awaiting Action</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted small">Completed</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['checked'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="ti ti-checks text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-label-success">Successful</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted small">Refunded</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['exited'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar" style="background: linear-gradient(135deg, #17a2b8 0%, #6610f2 100%);">
                            <i class="ti ti-receipt-refund text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-label-info">Refunded</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted small">Failed</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['cancelled'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
                            <i class="ti ti-x text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-label-danger">Cancelled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pb-0 pt-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Recent Orders</h5>
                    <p class="text-muted small mb-0">Manage and track all orders</p>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" id="searchOrders" placeholder="Search by order number, customer...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="ordersTable">
                    <thead>
                        <tr>
                            <th>
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Event</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <input class="form-check-input" type="checkbox">
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary fw-bold">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                <br>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        @if($order->user && $order->user->media->isNotEmpty())
                                            <img src="{{ asset('storage/' . $order->user->media->first()->path) }}" alt="Avatar" class="rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr(optional($order->user)->user_name ?? 'G', 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ optional($order->user)->user_name ?? 'Guest' }}</div>
                                        <small class="text-muted">{{ optional($order->user)->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ optional($order->event)->name ?? 'N/A' }}">
                                    {{ \Str::limit(optional($order->event)->name ?? 'N/A', 25) }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $paymentColors = [
                                        'cash' => 'success',
                                        'card' => 'primary',
                                        'online' => 'info',
                                        'wallet' => 'warning',
                                    ];
                                    $color = $paymentColors[strtolower($order->payment_method ?? 'cash')] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $color }}">
                                    <i class="ti ti-credit-card me-1"></i>
                                    {{ ucfirst($order->payment_method ?? 'Cash') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => 'warning', 'icon' => 'clock', 'text' => 'Pending'],
                                        'checked' => ['color' => 'success', 'icon' => 'check', 'text' => 'Completed'],
                                        'exited' => ['color' => 'info', 'icon' => 'arrow-back', 'text' => 'Exited'],
                                        'cancelled' => ['color' => 'danger', 'icon' => 'x', 'text' => 'Cancelled'],
                                    ];
                                    $status = $statusConfig[$order->status] ?? ['color' => 'secondary', 'icon' => 'question-mark', 'text' => ucfirst($order->status)];
                                @endphp
                                <span class="badge bg-label-{{ $status['color'] }}">
                                    <i class="ti ti-{{ $status['icon'] }} me-1"></i>
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">{{ $order->quantity }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-{{ $order->total == 0 ? 'success' : 'dark' }}">
                                    {{ $order->total == 0 ? 'FREE' : '$' . number_format($order->total, 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-label-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-label-info" onclick="printOrder({{ $order->id }})" title="Print">
                                        <i class="ti ti-printer"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-label-danger" onclick="deleteOrder({{ $order->id }})" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="ti ti-shopping-cart-off" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-2">No orders found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                <div>
                    <p class="text-muted small mb-0">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </p>
                </div>
                <nav aria-label="Orders pagination">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @elseif($orders->count() > 0)
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="text-muted small mb-0">
                        Showing {{ $orders->count() }} orders
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search functionality
    document.getElementById('searchOrders').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#ordersTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('#ordersTable tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Delete order function
    function deleteOrder(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add your delete logic here
                Swal.fire(
                    'Deleted!',
                    'Order has been deleted.',
                    'success'
                );
            }
        });
    }

    // Print order function
    function printOrder(orderId) {
        window.open(`/admin/orders/${orderId}/print`, '_blank');
    }

    // Export orders function
    function exportOrders() {
        Swal.fire({
            title: 'Export Orders',
            text: 'Choose export format',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
            cancelButtonText: '<i class="ti ti-file-text me-1"></i> PDF',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                // Export to Excel
                window.location.href = '/admin/orders/export/excel';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Export to PDF
                window.location.href = '/admin/orders/export/pdf';
            }
        });
    }

    // Row hover effect
    document.querySelectorAll('#ordersTable tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'all 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
</script>
@endpush

@push('css')
<style>
    .avatar {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
    
    .avatar-sm {
        width: 35px;
        height: 35px;
        font-size: 0.875rem;
    }
    
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-weight: 600;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #f0f0f0;
        padding: 1rem 0.75rem;
        background: #f8f9fa;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        font-weight: 500;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-label-primary {
        background-color: rgba(102, 126, 234, 0.1) !important;
        color: #667eea !important;
    }
    
    .bg-label-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
        color: #28a745 !important;
    }
    
    .bg-label-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
    }
    
    .bg-label-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
    }
    
    .bg-label-info {
        background-color: rgba(23, 162, 184, 0.1) !important;
        color: #17a2b8 !important;
    }
    
    .bg-label-secondary {
        background-color: rgba(108, 117, 125, 0.1) !important;
        color: #6c757d !important;
    }
    
    .input-group-text {
        background: #f8f9fa;
        border-color: #e9ecef;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
    
    /* Smooth animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        color: #667eea;
        border-color: #e9ecef;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    .pagination .page-link:hover {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
        transform: translateY(-2px);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #e9ecef;
        cursor: not-allowed;
    }
</style>
@endpush
