@extends('backend.partials.master')

@section('title', 'Send WhatsApp Invitation')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.events.index') }}">Events</a>
                </li>
                <li class="breadcrumb-item active">Send WhatsApp Invitation</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <i class="ti ti-brand-whatsapp ti-xs me-2"></i>
                    Send WhatsApp Invitation
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form id="sendSmsForm">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <!-- Event Info -->
                            <div class="mb-4">
                                <h6 class="mb-3">Event Information</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-2"><strong>Event Name:</strong> {{ $event->name }}</p>
                                        @if($event->eventDates->first())
                                            @php
                                                $eventDate = $event->eventDates->first();
                                                if ($eventDate->start_date) {
                                                    $date = \Carbon\Carbon::parse($eventDate->start_date);
                                                    $time = $eventDate->start_time ? \Carbon\Carbon::parse($eventDate->start_time)->format('g:i A') : '';
                                                } else {
                                                    $date = null;
                                                    $time = '';
                                                }
                                            @endphp
                                            @if($date)
                                                <p class="mb-2"><strong>Date:</strong> {{ $date->format('Y-m-d') }}</p>
                                            @endif
                                            @if($time)
                                                <p class="mb-2"><strong>Time:</strong> {{ $time }}</p>
                                            @endif
                                        @endif
                                        @if($event->location)
                                            <p class="mb-0"><strong>Location:</strong> {{ $event->location }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number Input -->
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">+2</span>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="phone_number" 
                                        name="phone_number" 
                                        placeholder="01012345678"
                                        required
                                        pattern="[0-9]{10,11}"
                                    >
                                </div>
                                <div class="form-text">
                                    Enter phone number without country code (e.g., 01012345678)
                                </div>
                            </div>

                            <!-- Preview Message -->
                            <div class="mb-3">
                                <label class="form-label">Message Preview</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <pre id="messagePreview" class="mb-0" style="white-space: pre-wrap; font-family: Arial, sans-serif;">Loading...</pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success" id="sendBtn">
                                    <i class="ti ti-brand-whatsapp ti-xs me-1"></i>
                                    Send WhatsApp
                                </button>
                                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left ti-xs me-1"></i>
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- QR Code Preview -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">QR Code Preview</h6>
                            </div>
                            <div class="card-body text-center">
                                @php
                                    $qrCodeUrl = route('event.qrcode', ['event' => $event->uuid]);
                                @endphp
                                <div class="mb-3 p-4 border rounded d-flex align-items-center justify-content-center" style="min-height: 220px; background: #ffffff;">
                                    @php
                                        // Generate QR Code with black and white colors
                                        $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
                                            ->color(0, 0, 0)  // Black foreground
                                            ->backgroundColor(255, 255, 255)  // White background
                                            ->generate($qrCodeUrl);
                                        echo $qr;
                                    @endphp
                                </div>
                                <p class="text-muted small mb-0">This QR Code will be included in the WhatsApp message</p>
                                <a href="{{ $qrCodeUrl }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="ti ti-external-link ti-xs me-1"></i>
                                    Open QR Code Page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Update message preview
            function updateMessagePreview() {
                const eventName = "{{ $event->name }}";
                const eventDate = @if($event->eventDates->first() && $event->eventDates->first()->start_date)
                    "{{ \Carbon\Carbon::parse($event->eventDates->first()->start_date)->locale('ar')->translatedFormat('l j F Y') }}"
                @else
                    ""
                @endif;
                const eventTime = @if($event->eventDates->first() && $event->eventDates->first()->start_time)
                    "{{ \Carbon\Carbon::parse($event->eventDates->first()->start_time)->format('g:i A') }}"
                @else
                    "9 مساءً"
                @endif;
                const eventLocation = "{{ $event->location ?: 'سينما أركان بلازا' }}";
                const qrCodeUrl = "{{ route('event.qrcode', ['event' => $event->uuid]) }}";

                let message = "تدعوكم Red Star Films و Film Square لحضور العرض الخاص\n\n";
                message += "لفيلم \"لنا في الخيال… حب\"\n\n";
                
                if (eventDate) {
                    message += "وذلك يوم " + eventDate;
                } else {
                    message += "وذلك يوم";
                }
                
                message += " في " + eventLocation;
                message += "، الساعة " + eventTime;
                message += ".\n\n";
                message += "ليلة مليانة خيال وحب… نشارككم فيها مشاهدة الفيلم مع أبطاله وصُنّاعه.\n\n";
                message += "يرجى تأكيد الحضور — Kindly confirm your attendance.\n\n";
                message += qrCodeUrl;

                $('#messagePreview').text(message);
            }

            updateMessagePreview();

            // Handle form submission
            $('#sendSmsForm').on('submit', function(e) {
                e.preventDefault();

                const phoneNumber = $('#phone_number').val();
                const eventId = $('input[name="event_id"]').val();

                // Validate phone number
                if (!phoneNumber || phoneNumber.length < 10) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Phone Number',
                        text: 'Please enter a valid phone number (10-11 digits)'
                    });
                    return;
                }

                // Show loading
                Swal.fire({
                    title: 'Sending SMS...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.events.send-sms-invitation') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        event_id: eventId,
                        phone_number: phoneNumber
                    },
                           success: function(response) {
                               Swal.fire({
                                   icon: 'success',
                                   title: 'نجح الإرسال!',
                                   text: 'تم إرسال رسالة WhatsApp بنجاح ✅',
                                   confirmButtonText: 'OK'
                               }).then(() => {
                                   $('#phone_number').val('');
                               });
                           },
                    error: function(xhr) {
                        let errorMessage = 'فشل إرسال رسالة WhatsApp';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endsection

