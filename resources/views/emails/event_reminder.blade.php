<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Thanks for Registering</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', 'Roboto', sans-serif;
            line-height: 1.6;
            color: #2d3748;
            /* background: linear-gradient(135deg, #ed7326 0%, #f5a97f 100%); */
            padding: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #ed7326;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
        }

        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .email-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: #ed7326;
        }

        .header {
            background: #ed7326;
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .content {
            padding: 25px 20px;
            background: #fafafa;
        }

        .greeting {
            font-size: 18px;
            color: #ed7326;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .confirmation-message {
            background: #fff5f0;
            border: 1px solid #f8d7c8;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(237, 115, 38, 0.1);
            position: relative;
        }

        .confirmation-message::before {
            content: '✨';
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            opacity: 0.6;
        }

        .confirmation-message h3 {
            color: #ed7326;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .confirmation-message p {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.5;
        }

        .booking-details {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #f8d7c8;
        }

        .booking-details h3 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border-bottom: 2px solid #ed7326;
            padding-bottom: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            margin: 8px 0;
            background: #fff5f0;
            border-radius: 8px;
            border-left: 3px solid #ed7326;
            transition: all 0.3s ease;
        }

        .detail-row:hover {
            transform: translateX(3px);
            box-shadow: 0 5px 15px rgba(237, 115, 38, 0.1);
        }

        .detail-label {
            font-weight: 500;
            color: #4a5568;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .detail-label i {
            margin-right: 8px;
            color: #ed7326;
            font-size: 16px;
        }

        .detail-value {
            color: #2d3748;
            font-weight: 500;
            font-size: 14px;
        }

        .event-info {
            background: #fff5f0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(237, 115, 38, 0.1);
            border: 1px solid #f8d7c8;
        }

        .event-info h3 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }

        .next-steps {
            background: #fff5f0;
            border: 1px solid #f8d7c8;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(237, 115, 38, 0.1);
            position: relative;
        }

        /* .next-steps::before {
    content: '🚀';
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 20px;
    opacity: 0.7;
} */

        .next-steps h3 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }

        .next-steps ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .next-steps li {
            padding: 10px 0 10px 30px;
            position: relative;
            color: #4a5568;
            font-weight: 500;
            line-height: 1.5;
            margin: 5px 0;
        }

        .next-steps li:before {
            content: "✅";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #ed7326;
            font-size: 14px;
        }

        .contact-info {
            background: #fff5f0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 5px 15px rgba(237, 115, 38, 0.1);
            border: 1px solid #f8d7c8;
            position: relative;
        }

        .reminder-content {
            text-align: center;
            padding: 20px;
            font-size: 15px;
            font-weight: 400;
        }

        .contact-info h4 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 700;
        }

        .contact-info p {
            margin: 5px 0;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }

        .footer {
            background: #2d3748;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #ed7326;
        }

        .footer p {
            margin: 5px 0;
            opacity: 0.9;
            font-size: 12px;
        }

        .footer a {
            color: #ed7326;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: #f5a97f;
        }

        .social-links {
            margin: 15px 0;
            display: flex;
            justify-content: center !important;
            align-items: center !important;
            gap: 5px;
        }

        .social-links a {
            display: flex;
            align-items: center !important;
            justify-content: center !important;
            padding: 8px;
            background: #ed7326;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            width: 35px;
            height: 35px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-2px);
            background: #f5a97f;
        }

        @media (max-width: 650px) {
            .email-container {
                margin: 8px;
                border-radius: 12px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            }

            .header {
                padding: 20px 12px;
            }

            .header h1 {
                font-size: 22px;
                line-height: 1.3;
            }

            .header p {
                font-size: 13px;
                line-height: 1.4;
            }

            .content {
                padding: 15px 12px;
            }

            .greeting {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .confirmation-message,
            .booking-details,
            .event-info,
            .next-steps,
            .contact-info {
                padding: 5px;
                margin: 12px 0;
                border-radius: 8px;
            }

            .confirmation-message h3,
            .booking-details h3,
            .event-info h3,
            .next-steps h3 {
                font-size: 16px;
                line-height: 1.3;
            }

            .confirmation-message p,
            .next-steps li,
            .contact-info p {
                font-size: 13px;
                line-height: 1.5;
            }

            .detail-row {
                flex-direction: row;
                align-items: flex-start;
                gap: 8px;
                padding: 10px 12px;
                margin: 6px 0;
            }

            .detail-label,
            .detail-value {
                font-size: 13px;
            }

            .detail-label i {
                font-size: 14px;
            }

            .next-steps li {
                padding: 8px 0 8px 28px;
            }

            .next-steps li:before {
                font-size: 13px;
            }

            .contact-info h4 {
                font-size: 15px;
            }

            .footer {
                padding: 15px 12px;
            }

            .footer p {
                font-size: 11px;
                line-height: 1.4;
            }

            .social-links a {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .email-container {
                margin: 5px;
                border-radius: 10px;
            }

            .header h1 {
                font-size: 20px;
            }

            .greeting {
                font-size: 15px;
                margin-bottom: 12px;
            }

            .confirmation-message h3,
            .booking-details h3,
            .event-info h3,
            .next-steps h3 {
                font-size: 15px;
            }

            .confirmation-message p,
            .next-steps li,
            .contact-info p {
                font-size: 12px;
            }

            .detail-row {
                padding: 8px 10px;
                gap: 6px;
            }

            .detail-label,
            .detail-value {
                font-size: 12px;
            }

            .detail-label i {
                font-size: 13px;
            }

            .next-steps li {
                padding: 6px 0 6px 25px;
            }

            .next-steps li:before {
                font-size: 12px;
            }

            .contact-info h4 {
                font-size: 14px;
            }

            .footer p {
                font-size: 10px;
            }

            .social-links a {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    @php
        $settings = \App\Models\Setting::first();
    @endphp
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✨ Reminder: Your Webinar is Today ✨</h1>
            <p>We’re looking forward to having you with us.</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $user->first_name ?? 'there' }} {{ $user->last_name ?? 'there' }}
            </div>

            <div class="confirmation-message">
                <h3>🎉 Reminder for Event!</h3>
                <p>
                    Just a quick reminder that your event <strong>{{ $event->name ?? 'the selected event' }}</strong> is
                    happening today – {{ $eventDate }} at 07:00 PM.
                </p>
            </div>

            <div class="booking-details">
                <h3>💎 Event Details</h3>
                <div class="booking-details-content">
                    <a href="{{ $event->link_meeting }}" class="btn btn-primary" target="_blank">Join Meeting</a>
                </div>

                <div class="reminder-content">
                    <p> We’re excited to have you with us!🎉
                        Please join Now to ensure a smooth experience.
                    </p>
                </div>
                <!-- Contact Information -->
                <div class="contact-info">
                    <h4>📞 Need Help?</h4>
                    <p>If you have any questions or need to make changes to your Event,</p>
                    <p>please don't hesitate to contact us:</p>
                    <p><strong>Email:</strong> {{ $settings->email }}</p>
                    <p><strong>Phone:</strong> {{ $settings->phone }}</p>
                </div>

                <p style="color: #666; font-style: italic; text-align: center; margin-top: 15px; font-size: 12px;">
                    This is an automated message. Please do not reply directly to this email.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>{{ $settings->name }}</strong></p>
                <p>Your trusted partner for event management</p>
                <div class="social-links">
                    <a href="{{ $settings->facebook }}" title="Facebook">📘</a>
                    <a href="{{ $settings->instagram }}" title="Instagram">📸</a>
                    <a href="{{ $settings->whats_app }}" title="WhatsApp">📱</a>
                </div>
                <p>© {{ date('Y') }} {{ $settings->name }}. All rights reserved.</p>
                <p>
                    <a href="{{ route('privacy') }}">Privacy Policy</a> |
                    <a href="{{ route('terms') }}">Terms of Service</a> |
                    <a href="{{ route('contacts') }}">Contact Us</a>
                </p>
            </div>
        </div>
</body>

</html>