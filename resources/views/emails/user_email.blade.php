<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Event Reminder</title>
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
            background: linear-gradient(135deg, #ed7326 0%, #f5a97f 100%);
            padding: 10px 0;
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

        /* .contact-info::before {
    content: '📞';
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 20px;
    opacity: 0.7;
} */

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
        // Extract Zoom link from message content if exists
        $zoomLink = null;
        $messageText = $messageContent;
        
        // Try to find Zoom link in the message
        if (preg_match('/(https?:\/\/[^\s]+(?:zoom|meet)[^\s]*)/i', $messageContent, $matches)) {
            $zoomLink = $matches[1];
        } elseif (preg_match('/(https?:\/\/[^\s]+)/i', $messageContent, $matches)) {
            $zoomLink = $matches[1];
        }
    @endphp
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-bell"></i> Event Reminder 🔔</h1>
            <p>Don't forget about your upcoming event!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello There!👋
            </div>

            <!-- Message Content -->
            <div class="booking-details">
                <h3>📋 Event Details</h3>
                <div class="confirmation-message" style="margin-top: 15px;">
                    <div style="color: #4a5568; font-size: 14px; line-height: 1.8;">
                        {!! nl2br(e($messageText)) !!}
                    </div>
                </div>

                @if($zoomLink)
                <!-- Zoom Link Section -->
                <div class="event-info" style="margin-top: 20px; background: #e8f4f8; border: 2px solid #2d8fc7;">
                    <h3 style="color: #2d8fc7;">🔗 Join Zoom Meeting</h3>
                    <div style="text-align: center; padding: 25px; background: #ffffff; border-radius: 10px; margin: 15px 0; border: 1px solid #e0e0e0;">
                        <p style="margin-bottom: 20px; color: #4a5568; font-size: 14px; font-weight: 500;">
                            Click the button below to join the meeting:
                        </p>
                        <a href="{{ $zoomLink }}" 
                           target="_blank" 
                           style="display: inline-block; padding: 18px 35px; background: #2d8fc7; color: white; text-decoration: none; border-radius: 10px; font-weight: 700; font-size: 18px; transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(45, 143, 199, 0.4); margin-bottom: 15px;">
                            <i class="fas fa-video" style="margin-right: 10px;"></i>
                            Join Zoom Meeting Now
                        </a>
                        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #2d8fc7;">
                            <p style="margin: 0 0 10px 0; color: #2d3748; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-link" style="margin-right: 5px; color: #2d8fc7;"></i>
                                Meeting Link:
                            </p>
                            <p style="margin: 0; color: #4a5568; font-size: 12px; word-break: break-all; line-height: 1.6;">
                                <a href="{{ $zoomLink }}" target="_blank" style="color: #2d8fc7; text-decoration: underline; font-weight: 500;">
                                    {{ $zoomLink }}
                                </a>
                            </p>
                        </div>
                        <p style="margin-top: 15px; color: #666; font-size: 12px; font-style: italic;">
                            <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                            Make sure you have Zoom installed on your device before joining.
                        </p>
                    </div>
                </div>
                @endif

                <!-- Next Steps -->
                <div class="next-steps">
                    <h3>📝 What to Do Next?</h3>
                    <ul>
                        @if($zoomLink)
                            <li>Click the "Join Zoom Meeting" button above to join the event</li>
                            <li>Make sure you have Zoom installed on your device</li>
                            <li>Join a few minutes early to test your audio and video</li>
                        @else
                            <li>Check your calendar for the event date and time</li>
                            <li>Make sure you have all the necessary information</li>
                            <li>Contact us if you have any questions</li>
                        @endif
                        <li>We look forward to seeing you there!</li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h4>📞 Need Help?</h4>
                    <p>If you have any questions or need assistance,</p>
                    <p>please don't hesitate to contact us:</p>
                    <p><strong>Email:</strong> {{ $settings->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $settings->phone ?? 'N/A' }}</p>
                </div>

                <p style="color: #666; font-style: italic; text-align: center; margin-top: 15px; font-size: 12px;">
                    This is an automated reminder. Please do not reply directly to this email.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>{{ $settings->name }}</strong></p>
                <p>Your trusted partner for event management</p>
                <div class="social-links">
                    <a href="{{ $settings->facebook }}" title="Facebook">📘</a>
                    <a href="{{ $settings->instagram }}" title="Instagram">📸</a>
                    <a href="https://wa.me/{{ '+2' . str_replace(['+', ' ', '-'], '', $settings->whats_app ?? '') }}" target="_blank" title="WhatsApp">📱</a>

                </div>
                <p>© {{ date('Y') }} {{ $settings->name }}. All rights reserved.</p>
                <p class="text-center">
                    <a href="{{ route('privacy') }}">Privacy Policy</a> |
                    <a href="{{ route('terms') }}">Terms of Service</a> |
                    <a href="{{ route('contacts') }}">Contact Us</a>
                </p>
            </div>
        </div>
</body>

</html>