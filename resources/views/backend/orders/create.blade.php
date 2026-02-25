@extends('backend.partials.master')

@section('title', 'Add New Order')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add New Order</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Add New Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">New Order Data</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.orders.store') }}">
                            @csrf
                            
                            <div class="row">
                                <!-- Customer Selection -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Client <span class="text-danger">*</span></label>
                                        <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id" required>
                                            <option value="">Select Client</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Event Selection -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="event_id" class="form-label">Event <span class="text-danger">*</span></label>
                                        <select class="form-select @error('event_id') is-invalid @enderror" name="event_id" id="event_id" required>
                                            <option value="">Select Event</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('event_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Event Date Selection -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="event_date_id" class="form-label">Event Date <span class="text-danger">*</span></label>
                                        <select class="form-select @error('event_date_id') is-invalid @enderror" name="event_date_id" id="event_date_id" required>
                                            <option value="">Select Event Date</option>
                                        </select>
                                        @error('event_date_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Ticket Selection -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_id" class="form-label">Ticket Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('ticket_id') is-invalid @enderror" name="ticket_id" id="ticket_id" required>
                                            <option value="">Select Ticket Type</option>
                                        </select>
                                        @error('ticket_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                               name="quantity" id="quantity" value="{{ old('quantity', 1) }}" 
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
                                            <span class="form-control" id="total_display">0.00</span>
                                            <span class="input-group-text">Currency</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" id="payment_method" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
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
                                             <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                             <option value="checked" {{ old('status') == 'checked' ? 'selected' : '' }}>Checked In</option>
                                             <option value="exited" {{ old('status') == 'exited' ? 'selected' : '' }}>Completed</option>
                                             <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="4" placeholder="Additional Notes (Optional)">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-save-line me-1"></i>
                                    Save Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventSelect = document.getElementById('event_id');
            const eventDateSelect = document.getElementById('event_date_id');
            const ticketSelect = document.getElementById('ticket_id');
            const quantityInput = document.getElementById('quantity');
            const totalDisplay = document.getElementById('total_display');

            const eventsData = @json($events);
            let currentTicketPrice = 0;

            // When event changes, update event dates and tickets
            eventSelect.addEventListener('change', function() {
                const eventId = this.value;
                
                // Clear dependent selects
                eventDateSelect.innerHTML = '<option value="">Select Event Date</option>';
                ticketSelect.innerHTML = '<option value="">Select Ticket Type</option>';
                updateTotal();

                if (eventId) {
                    const selectedEvent = eventsData.find(event => event.id == eventId);
                    
                    if (selectedEvent) {
                        // Populate event dates
                        selectedEvent.event_dates.forEach(eventDate => {
                            const option = document.createElement('option');
                            option.value = eventDate.id;
                            option.textContent = `${eventDate.start_date} ${eventDate.start_time || ''}`;
                            eventDateSelect.appendChild(option);
                        });

                        // Populate tickets
                        selectedEvent.tickets.forEach(ticket => {
                            const option = document.createElement('option');
                            option.value = ticket.id;
                            option.textContent = `${ticket.type} - ${ticket.price} {{ $event->currency?->code ?? 'EGP' }}`;
                            option.dataset.price = ticket.price;    
                            ticketSelect.appendChild(option);
                        });
                    }
                }
            });

            // When ticket changes, update price
            ticketSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                currentTicketPrice = selectedOption.dataset.price || 0;
                updateTotal();
            });

            // When quantity changes, update total
            quantityInput.addEventListener('input', updateTotal);

            function updateTotal() {
                const quantity = parseInt(quantityInput.value) || 0;
                const total = currentTicketPrice * quantity;
                totalDisplay.textContent = total.toFixed(2);
            }

            // Initialize Select2 for better UX
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('#user_id, #event_id').select2({
                    placeholder: function() {
                        return $(this).data('placeholder');
                    },
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>
@endsection
