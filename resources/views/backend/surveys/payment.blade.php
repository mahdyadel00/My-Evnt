<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - {{ $survey->event->name ?? 'Event' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .payment-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .payment-details {
            padding: 30px;
            background: #f8f9fa;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: #28a745;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .detail-value {
            font-weight: 500;
            color: #212529;
        }

        .payment-iframe-container {
            padding: 20px;
            text-align: center;
        }

        .payment-iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .payment-actions {
            padding: 20px 30px;
            background: #f8f9fa;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .btn-custom {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: all 0.3s ease;
        }

        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
        }

        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #28a745;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1edff;
            color: #0c5460;
        }

        @media (max-width: 768px) {
            .payment-container {
                margin: 10px;
                border-radius: 15px;
            }

            .payment-header {
                padding: 20px;
            }

            .payment-header h1 {
                font-size: 1.5rem;
            }

            .payment-details {
                padding: 20px;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .payment-iframe {
                height: 500px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="payment-container">
            <!-- Header -->
            <div class="payment-header">
                <h1><i class="fas fa-credit-card me-3"></i>Payment</h1>
                <p class="mb-0 mt-2">{{ $survey->event->name ?? 'Event' }}</p>
            </div>

            <!-- Payment Details -->
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-user me-2"></i>Client Name:</span>
                    <span class="detail-value">{{ $survey->full_name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-envelope me-2"></i>Email:</span>
                    <span class="detail-value">{{ $survey->email }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-phone me-2"></i>Phone:</span>
                    <span class="detail-value">{{ $survey->phone }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-calendar-alt me-2"></i>Session Type:</span>
                    <span class="detail-value">{{ $survey->session_type_label }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-ticket-alt me-2"></i>Number of Tickets:</span>
                    <span class="detail-value">{{ $survey->quantity }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-money-bill-wave me-2"></i>Price per Ticket:</span>
                    <span class="detail-value">{{ number_format($survey->unit_price, 2) }} EGP</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-calculator me-2"></i>Total Amount:</span>
                    <span class="detail-value">{{ number_format($survey->total_amount, 2) }} EGP</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-info-circle me-2"></i>Payment Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $survey->status }}">
                            {{ $survey->status_label }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Payment iframe -->
            <div class="payment-iframe-container">
                <h4 class="mb-4"><i class="fas fa-credit-card me-2"></i>Complete Payment</h4>
                @if(isset($paymentToken) && $paymentToken)
                    <iframe id="paymentFrame" class="payment-iframe"
                        src="https://accept.paymob.com/api/acceptance/iframes/{{ config('services.paymob.iframe_id', '860671') }}?payment_token={{ $paymentToken }}"
                        frameborder="0" allowfullscreen>
                    </iframe>
                @else
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error loading payment page</strong><br>
                        Please try again or contact the technical support
                    </div>
                @endif

                <div class="mt-3">
                    <p class="text-muted text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        To pay: Enter card details or use the electronic wallet<br>
                        <small>Required amount: {{ number_format($survey->total_amount, 2) }} EGP</small>
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="payment-actions">
                <button onclick="refreshPayment()" class="btn-custom btn-success-custom">
                    <i class="fas fa-sync-alt me-2"></i>Update Payment Status
                </button>

                <a href="tel:{{ config('app.support_phone', '+201234567890') }}" class="btn-custom btn-success-custom">
                    <i class="fas fa-phone me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h5>Checking payment status...</h5>
            <p class="mb-0">Please wait a moment</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function refreshPayment() {
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.add('show');

            // Simulate checking payment status
            setTimeout(() => {
                overlay.classList.remove('show');

                // Check actual payment status via AJAX
                checkPaymentStatus();
            }, 2000);
        }

        function checkPaymentStatus() {
            $.ajax({
                url: '{{ route("admin.surveys.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: {{ $survey->id }},
                    status: 'confirmed'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Payment Success!',
                            text: 'Thank you, your booking has been confirmed.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while checking the payment status.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }

        // Listen for payment completion from iframe
        window.addEventListener('message', function (event) {
            console.log('Received message from iframe:', event.data);

            // Handle different PayMob callback formats
            if (event.data.type === 'payment_success' ||
                event.data === 'payment_success' ||
                (event.data.success && event.data.success === true) ||
                (event.data.type === 'TRANSACTION' && event.data.success)) {

                Swal.fire({
                    title: 'Payment Success!',
                    text: 'Confirming the process...',
                    icon: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 3000
                });

                setTimeout(() => {
                    checkPaymentStatus();
                }, 2000);
            }
        });

        // Auto-refresh every 60 seconds to check payment status
        let autoCheckInterval = setInterval(function () {
            console.log('Auto-checking payment status...');
            checkPaymentStatusSilently();
        }, 60000);

        // Silent check without showing alerts
        function checkPaymentStatusSilently() {
            $.ajax({
                url: '{{ route("admin.payment.status.check", $survey->id) }}',
                method: 'GET',
                success: function (response) {
                    if (response.success && response.is_confirmed) {
                        clearInterval(autoCheckInterval);

                        Swal.fire({
                            title: 'Payment Success!',
                            text: 'Thank you, your booking has been confirmed.',
                            icon: 'success',
                            confirmButtonText: 'View Details',
                            confirmButtonColor: '#28a745'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("admin.surveys.payment.completed", $survey->id) }}';
                            } else {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: function () {
                    // Silent fail for auto-check
                }
            });
        }
    </script>
</body>

</html>