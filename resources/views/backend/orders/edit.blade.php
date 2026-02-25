@extends('backend.partials.master')

@section('title', 'Edit Order #' . $order->order_number)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Order #{{ $order->order_number }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Edit Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Order Data</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Order Information (Read Only) -->
                                <div class="col-lg-12">
                                    <div class="alert alert-info">
                                            <h6 class="alert-heading"><i class="ri-information-line me-2"></i>Order Information</h6>
                                        <p class="mb-1"><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                                        <p class="mb-1"><strong>Client:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }} ({{ $order->user->email }})</p>
                                        <p class="mb-1"><strong>Event:</strong> {{ $order->event->name }}</p>
                                        <p class="mb-0"><strong>Ticket Type:</strong> {{ $order->ticket->type }} ({{ number_format($order->ticket->price, 2) }} {{ $order->payment_currency ?? 'Unknown' }} per ticket)</p>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                               name="quantity" id="quantity" value="{{ old('quantity', $order->quantity) }}" 
                                               min="1" max="10" required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Total Price Display -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Amount</label>
                                        <div class="input-group">
                                            <span class="form-control" id="total_display">{{ number_format($order->total, 2) }}</span>
                                            <span class="input-group-text">{{ $order->payment_currency ?? 'Unknown' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" id="payment_method" required>
                                            <option value="cash" {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="credit_card" {{ old('payment_method', $order->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                            <option value="bank_transfer" {{ old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="paypal" {{ old('payment_method', $order->payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                 <!-- Order Status -->
                                 <div class="col-lg-6">
                                     <div class="mb-3">
                                         <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                                         <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                             <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                             <option value="checked" {{ old('status', $order->status) == 'checked' ? 'selected' : '' }}>Checked In</option>
                                             <option value="exited" {{ old('status', $order->status) == 'exited' ? 'selected' : '' }}>Completed</option>
                                             <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                         </select>
                                         @error('status')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                         @enderror
                                     </div>
                                 </div>

                                <!-- Payment Status -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                            <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_status') is-invalid @enderror" name="payment_status" id="payment_status" required>
                                            <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="completed" {{ old('payment_status', $order->payment_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Current Notes Display -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Current Notes</label>
                                        <div class="form-control bg-light" style="min-height: 80px;">
                                            @php
                                                $currentNotes = json_decode($order->payment_response, true)['notes'] ?? null;
                                            @endphp
                                            @if($currentNotes)
                                                {{ $currentNotes }}
                                            @else
                                                <span class="text-muted">No previous notes</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- New Notes -->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">New Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="4" placeholder="Add new notes (Optional)">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">The new notes will be added to the existing notes</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-save-line me-1"></i>
                                    Update Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">Order Number:</td>
                                        <td>#{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Creation Date:</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Last Update:</td>
                                        <td>{{ $order->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                            <td class="fw-medium">Ticket Price:</td>
                                        <td>{{ number_format($order->ticket->price, 2) }} {{ $order->payment_currency ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Current Quantity:</td>
                                        <td><span class="badge badge-soft-info">{{ $order->quantity }}</span></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-medium">Current Total:</td>
                                        <td><span class="text-success fw-medium">{{ number_format($order->total, 2) }} {{ $order->payment_currency ?? 'Unknown' }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <small>
                                <i class="ri-information-line me-1"></i>
                                Changing the quantity will affect the total amount of the order
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Event Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($order->event->media->where('name', 'poster')->first())
                                <img src="{{ asset('storage/' . $order->event->media->where('name', 'poster')->first()->path) }}" 
                                     alt="{{ $order->event->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ Str::limit($order->event->name, 30) }}</h6>
                                <small class="text-muted">{{ $order->event->category->name ?? 'Not Specified' }}</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">Date:</td>
                                        <td>{{ $order->eventDate->start_date ?? 'Not Specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Time:</td>
                                        <td>{{ $order->eventDate->start_time ? \Carbon\Carbon::parse($order->eventDate->start_time)->format('H:i') : 'Not Specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Location:</td>
                                            <td>{{ $order->event->location ?? 'Not Specified' }}</td>    
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const totalDisplay = document.getElementById('total_display');
            const ticketPrice = {{ $order->ticket->price }};

            // Update total when quantity changes
            quantityInput.addEventListener('input', function() {
                const quantity = parseInt(this.value) || 0;
                const total = ticketPrice * quantity;
                totalDisplay.textContent = total.toFixed(2);
            });

            // Status change confirmation
            const statusSelect = document.getElementById('status');
            const paymentStatusSelect = document.getElementById('payment_status');
            
             statusSelect.addEventListener('change', function() {
                 if (this.value === 'cancelled') {
                     if (!confirm('Are you sure you want to cancel this order?')) {
                         this.value = '{{ $order->status }}'; // Reset to original value
                     }
                 } else if (this.value === 'exited') {
                     if (!confirm('Are you sure you want to mark this order as completed?')) {
                         this.value = '{{ $order->status }}'; // Reset to original value
                     }
                 }
             });

            paymentStatusSelect.addEventListener('change', function() {
                if (this.value === 'refunded') {
                    if (!confirm('Are you sure you want to refund the amount?')) {
                        this.value = '{{ $order->payment_status }}'; // Reset to original value
                    }
                }
            });
        });
    </script>
@endsection
