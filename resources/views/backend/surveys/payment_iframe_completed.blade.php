<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Completed - {{ $survey->event->name ?? 'Event' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #28a745, #20c997);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .success-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            max-width: 500px;
            width: 90%;
        }

        .success-icon {
            font-size: 5rem;
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

        .btn {
            background: white;
            color: #28a745;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            display: inline-block;
            margin: 10px;
            transition: transform 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .details {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .detail-row:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1>Payment Already Completed!</h1>
        <p style="font-size: 1.2rem; margin: 20px 0;">Your booking has been confirmed successfully.</p>

        <div class="details">
            <div class="detail-row">
                <span>Event:</span>
                <span>{{ $survey->event->name ?? 'Event' }}</span>
            </div>
            <div class="detail-row">
                <span>Booking ID:</span>
                <span>{{ $survey->id }}</span>
            </div>
            <div class="detail-row">
                <span>Amount:</span>
                <span>{{ number_format($survey->total_amount, 2) }} EGP</span>
            </div>
            <div class="detail-row">
                <span>Status:</span>
                <span>✅ Confirmed</span>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="tel:{{ config('app.support_phone', '+201234567890') }}" class="btn">
                📞 Contact Support
            </a>
            <a href="mailto:{{ config('app.support_email', 'support@example.com') }}" class="btn">
                📧 Email Us
            </a>
        </div>

        <p style="margin-top: 20px; font-size: 0.9rem; opacity: 0.8;">
            Thank you for choosing our services! 🎉
        </p>
    </div>
</body>

</html>