@extends('backend.partials.master')

@section('title', 'Order Details #' . $order->order_number)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Order Details #{{ $order->order_number }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Order Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9">
                <!-- Order Details Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Order Details</h5>
                             <div class="flex-shrink-0">
                                 @switch($order->status)
                                     @case('pending')
                                         <span class="badge badge-soft-warning fs-11">Pending</span>
                                         @break
                                     @case('checked')
                                         <span class="badge badge-soft-info fs-11">Checked In</span>
                                         @break
                                     @case('exited')
                                         <span class="badge badge-soft-success fs-11">Completed</span>
                                         @break
                                     @case('cancelled')
                                         <span class="badge badge-soft-danger fs-11">Cancelled</span>
                                         @break
                                 @endswitch
                             </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium" style="width: 200px;">Order Number:</td>
                                        <td>#{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Creation Date:</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Last Update:</td>
                                        <td>{{ $order->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                            <td class="fw-medium">Order Status:</td>
                                         <td>
                                             @switch($order->status)
                                                 @case('pending')
                                                     <span class="badge badge-soft-warning">Pending</span>
                                                     @break
                                                 @case('checked')
                                                     <span class="badge badge-soft-info">Checked In</span>
                                                     @break
                                                 @case('exited')
                                                     <span class="badge badge-soft-success">Completed</span>
                                                     @break
                                                 @case('cancelled')
                                                     <span class="badge badge-soft-danger">Cancelled</span>
                                                     @break
                                             @endswitch
                                         </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Quantity:</td>
                                        <td><span class="badge badge-soft-info">{{ $order->quantity }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Total Amount:</td>
                                        <td><span class="text-success fw-medium fs-15">{{ number_format($order->total, 2) }} {{ $order->payment_currency ?? 'Unknown' }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Event Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Event Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                @if($order->event->media->where('name', 'poster')->first())
                                    <img src="{{ asset('storage/' . $order->event->media->where('name', 'poster')->first()->path) }}" 
                                         alt="{{ $order->event->name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <i class="ri-image-line text-muted fs-24"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium" style="width: 150px;">Event Name:</td>
                                                <td>{{ $order->event->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Category:</td>
                                                <td>{{ $order->event->category->name ?? 'Not Specified' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">City:</td>
                                                <td>{{ $order->event->city->name ?? 'Not Specified' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Location:</td>
                                                <td>{{ $order->event->location ?? 'Not Specified' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Event Date:</td>
                                                <td>
                                                    @if($order->eventDate)
                                                        {{ $order->eventDate->start_date }} 
                                                        @if($order->eventDate->start_time)
                                                            {{ \Carbon\Carbon::parse($order->eventDate->start_time)->format('H:i') }}
                                                        @endif
                                                    @else
                                                        Not Specified
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">Ticket Type:</td>
                                                <td>
                                                    <span class="badge badge-soft-primary">{{ $order->ticket->type ?? 'Not Specified' }}</span>
                                                    <span class="text-muted ms-2">({{ number_format($order->ticket->price ?? 0, 2) }} {{ $order->payment_currency ?? 'Unknown' }} for each ticket)</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium" style="width: 200px;">Payment Status:</td>
                                        <td>
                                            @switch($order->payment_status)
                                                @case('pending')
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge badge-soft-success">Completed</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge badge-soft-danger">Failed</span>
                                                    @break
                                                @case('refunded')
                                                    <span class="badge badge-soft-info">Refunded</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Payment Method:</td>
                                        <td>
                                            @switch($order->payment_method)
                                                @case('cash')
                                                    <i class="ri-money-dollar-circle-line text-success me-1"></i> Cash
                                                    @break
                                                @case('credit_card')
                                                    <i class="ri-bank-card-line text-primary me-1"></i> Credit Card
                                                    @break
                                                @case('bank_transfer')
                                                    <i class="ri-bank-line text-info me-1"></i> Bank Transfer
                                                    @break
                                                @case('paypal')
                                                    <i class="ri-paypal-line text-primary me-1"></i> PayPal
                                                    @break
                                                @default
                                                    {{ $order->payment_method }}
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Payment Amount:</td>
                                        <td>{{ number_format($order->payment_amount, 2) }} {{ $order->payment_currency }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Payment Reference:</td>
                                        <td><code>{{ $order->payment_reference ?? 'Not Specified' }}</code></td>
                                    </tr>
                                    @if($order->payment_response)
                                        <tr>
                                            <td class="fw-medium">Notes:</td>
                                            <td>
                                                @php
                                                    $paymentData = json_decode($order->payment_response, true);
                                                    $notes = $paymentData['notes'] ?? null;
                                                @endphp
                                                @if($notes)
                                                    <div class="alert alert-light">{{ $notes }}</div>
                                                @else
                                                    <span class="text-muted">No Notes</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header">
                            <h5 class="card-title mb-0">Client Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <div class="avatar-title rounded-circle bg-light text-primary fs-24">
                                    {{ strtoupper(substr($order->user->first_name ?? $order->user->user_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $order->user->first_name }} {{ $order->user->last_name }}</h6>
                                <p class="text-muted mb-0">{{ $order->user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">Phone:</td>
                                        <td>{{ $order->user->phone ?? 'Not Specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Registration Date:</td>
                                        <td>{{ $order->user->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Status:</td>
                                        <td>
                                            @if($order->user->is_active)
                                                <span class="badge badge-soft-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-grid">
                            <a href="{{ route('admin.users.show', $order->user->id) }}" class="btn btn-soft-primary">
                                <i class="ri-user-line me-1"></i>
                                View Client Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @can('update orders')
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">
                                    <i class="ri-pencil-line me-1"></i>
                                    Edit Order
                                </a>
                            @endcan

                            <button type="button" class="btn btn-info" onclick="window.print()">
                                <i class="ri-printer-line me-1"></i>
                                Print
                            </button>

                             @can('delete orders')
                                 @if(!($order->status === 'exited' && $order->payment_status === 'completed'))
                                     <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" 
                                           onsubmit="return confirm('Are you sure you want to delete this order?')">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="btn btn-danger">
                                             <i class="ri-delete-bin-line me-1"></i>
                                             Delete Order
                                         </button>
                                     </form>
                                 @endif
                             @endcan

                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i>
                                Return to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        @media print {
            .card-header .badge,
            .btn,
            .breadcrumb,
            .page-title-box {
                display: none !important;
            }
            
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            
            .col-xl-3 {
                display: none !important;
            }
            
            .col-xl-9 {
                width: 100% !important;
            }
        }
    </style>
@endsection
