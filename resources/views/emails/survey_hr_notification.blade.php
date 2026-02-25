<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>{{ $emailSubject }}</title>
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

        .message-content {
            background: #fff5f0;
            border: 1px solid #f8d7c8;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(237, 115, 38, 0.1);
            position: relative;
        }

        .message-content h3 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .message-content p {
            color: #2d3748;
            font-size: 16px;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        .survey-details {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .survey-details h3 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #f7fafc;
            padding-bottom: 10px;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            border-bottom: 1px solid #f7fafc;
            padding-bottom: 8px;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 140px;
            font-size: 14px;
        }

        .detail-value {
            color: #2d3748;
            flex: 1;
            font-size: 14px;
        }

        .qr-code {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .qr-code h3 {
            color: #ed7326;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .qr-code p {
            color: #2d3748;
            font-size: 16px;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        .qr-code img {
            max-width: 100%;
            height: auto;
        }


        .footer {
            background: #2d3748;
            color: white;
            padding: 25px 20px;
            text-align: center;
        }

        .footer h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .footer p {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 15px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ed7326;
            text-decoration: none;
            font-size: 14px;
            transition: opacity 0.3s;
        }

        .contact-item:hover {
            opacity: 0.8;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 10px;
            }

            .header,
            .content,
            .footer {
                padding: 20px 15px;
            }

            .detail-row {
                flex-direction: column;
                gap: 5px;
            }

            .detail-label {
                min-width: auto;
                font-weight: 700;
            }

            .contact-info {
                flex-direction: column;
                gap: 10px;
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
            <h1>📧 {{ $emailSubject }}</h1>
            <p>Message from MyEvnt Team</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $survey->first_name }} {{ $survey->last_name }},
            </div>

            <div class="message-content">
                <h3>📋 Message from MyEvnt</h3>
                <p>{{ $emailMessage }}</p>
            </div>

            <!--Qr Code-->
            @if($survey->event && $survey->event->tickets && $survey->event->tickets->first() && $survey->event->tickets->first()->qr_code)
                <div class="qr-code">
                    <h3>QR Code</h3>
                    <p>Scan the QR code to view your booking details</p>
                    {!! QrCode::size(150)->generate($survey->event->tickets->first()->qr_code) !!}
                </div>
            @endif
            <!-- Survey Details -->
            <div class="survey-details">
                <h3>👤 Your Survey Information</h3>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-user"></i> Full Name :</div>
                    <div class="detail-value">{{ $survey->first_name }} {{ $survey->last_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-envelope"></i> Email :</div>
                    <div class="detail-value">{{ $survey->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-phone"></i> Phone :</div>
                    <div class="detail-value">{{ $survey->phone }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-building"></i> Company :</div>
                    <div class="detail-value">{{ $survey->company_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-briefcase"></i> Position :</div>
                    <div class="detail-value">{{ $survey->position }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-calendar"></i> Submission Date :</div>
                    <div class="detail-value">{{ $survey->created_at->format('F d, Y - g:i A') }}</div>
                </div>
                @if($event)
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-calendar-alt"></i> Event :</div>
                        <div class="detail-value">{{ $event->name }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h3>🤝 Contact Us</h3>
            <p>If you have any questions or need assistance, feel free to reach out to us:</p>

            <div class="contact-info">
                @if($settings && $settings->phone)
                    <a href="tel:{{ $settings->phone }}" class="contact-item">
                        <i class="fas fa-phone"></i>
                        {{ $settings->phone }}
                    </a>
                @endif
                @if($settings && $settings->email)
                    <a href="mailto:{{ $settings->email }}" class="contact-item">
                        <i class="fas fa-envelope"></i>
                        {{ $settings->email }}
                    </a>
                @endif
                @if($settings && $settings->website)
                    <a href="{{ $settings->website }}" class="contact-item" target="_blank">
                        <i class="fas fa-globe"></i>
                        Visit Website
                    </a>
                @endif
            </div>

            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} MyEvnt. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>