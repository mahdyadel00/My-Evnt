<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #dc3545, #c82333);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .error-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            max-width: 500px;
            width: 90%;
        }

        .error-icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }

        .btn {
            background: white;
            color: #dc3545;
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

        .error-details {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1>Payment Error</h1>
        <p style="font-size: 1.2rem; margin: 20px 0;">We couldn't load the payment gateway.</p>

        <div class="error-details">
            <h3>What can you do?</h3>
            <ul style="margin: 15px 0; padding-left: 20px;">
                <li>Try refreshing the page</li>
                <li>Check your internet connection</li>
                <li>Contact our support team</li>
            </ul>
        </div>

        <div style="margin-top: 30px;">
            <a href="javascript:window.location.reload()" class="btn">
                🔄 Refresh Page
            </a>
            <a href="tel:{{ config('app.support_phone', '+201234567890') }}" class="btn">
                📞 Contact Support
            </a>
        </div>

        <p style="margin-top: 20px; font-size: 0.9rem; opacity: 0.8;">
            We apologize for the inconvenience. Our team is here to help!
        </p>
    </div>
</body>

</html>