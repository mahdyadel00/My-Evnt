<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Completed - {{ $survey->event->name ?? 'Event' }}</title>

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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .success-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }

        .success-header {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 40px 30px;
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .success-content {
            padding: 40px 30px;
        }

        .detail-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: right;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .contact-info {
            background: #e7f3ff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .btn-custom {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="success-container">
            <!-- Success Header -->
            <div class="success-header">
                <i class="fas fa-check-circle success-icon"></i>
                <h1>Payment Completed!</h1>
                <p class="mb-0">Thank you, your booking has been confirmed in the event</p>
            </div>

            <!-- Success Content -->
            <div class="success-content">
                <h3 class="text-success mb-4">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Your booking has been confirmed
                </h3>

                <div class="detail-box">
                    <div class="detail-row">
                        <strong>Event Name:</strong>
                        <span>{{ $survey->event->name ?? 'Event' }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Client Name:</strong>
                        <span>{{ $survey->full_name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Session Type:</strong>
                        <span>{{ $survey->session_type_label }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Number of Tickets:</strong>
                        <span>{{ $survey->quantity }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Paid Amount:</strong>
                        <span class="text-success fw-bold">{{ number_format($survey->total_amount, 2) }} EGP</span>
                    </div>
                </div>

                <div class="contact-info">
                    <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                    <p class="mb-2">✅ Your payment has been confirmed and your booking has been confirmed</p>
                    <p class="mb-2">📧 The booking details will be sent to your email</p>
                    <p class="mb-0">📞 In case of any questions, please contact us</p>
                </div>

                <div class="text-center">
                    <a href="tel:{{ config('app.support_phone', '+201234567890') }}"
                        class="btn-custom btn-success-custom">
                        <i class="fas fa-phone me-2"></i>Contact Us
                    </a>

                    <a href="mailto:{{ config('app.support_email', 'support@example.com') }}"
                        class="btn-custom btn-success-custom">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </div>

                <div class="mt-4">
                    <small class="text-muted">
                        Paid at: {{ now()->format('Y-m-d H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>