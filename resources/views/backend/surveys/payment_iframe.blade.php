<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - {{ $survey->event->name ?? 'Event' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .iframe-container {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        .payment-iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
        }

        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #28a745;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .error-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #f5c6cb;
            max-width: 500px;
            width: 90%;
        }

        .info-bar {
            background: #28a745;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .iframe-wrapper {
            margin-top: 40px;
            /* للتعويض عن الـ info bar */
            height: calc(100vh - 40px);
        }

        @media (max-width: 768px) {
            .info-bar {
                font-size: 12px;
                padding: 8px;
            }

            .iframe-wrapper {
                margin-top: 35px;
                height: calc(100vh - 35px);
            }
        }
    </style>
</head>

<body>
    <!-- Info Bar -->
    <div class="info-bar">
        💳 Payment for {{ $survey->event->name ?? 'Event' }} - Amount: {{ number_format($survey->total_amount, 2) }} EGP
    </div>

    <div class="iframe-wrapper">
        @if(isset($paymentToken) && $paymentToken)
            <!-- Loading Screen -->
            <div class="loading" id="loadingScreen">
                <div class="spinner"></div>
                <h4>Loading Payment Gateway...</h4>
                <p>Please wait while we prepare your payment</p>
            </div>

            <!-- Payment iframe -->
            <div class="iframe-container">
                <iframe id="paymentFrame" class="payment-iframe"
                    src="https://accept.paymob.com/api/acceptance/iframes/{{ config('services.paymob.iframe_id', '860671') }}?payment_token={{ $paymentToken }}"
                    frameborder="0" allowfullscreen onload="hideLoading()">
                </iframe>
            </div>
        @else
            <div class="error-message">
                <h3>❌ Payment Error</h3>
                <p>Unable to load payment gateway. Please contact support.</p>
                <br>
                <a href="tel:{{ config('app.support_phone', '+201234567890') }}"
                    style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                    📞 Contact Support
                </a>
            </div>
        @endif
    </div>

    <script>
        function hideLoading() {
            const loading = document.getElementById('loadingScreen');
            if (loading) {
                loading.style.display = 'none';
            }
        }

        // Hide loading after 3 seconds even if iframe doesn't load
        setTimeout(hideLoading, 3000);

        // Listen for payment completion from iframe
        window.addEventListener('message', function (event) {
            console.log('Payment iframe message:', event.data);

            // Handle different PayMob callback formats
            if (event.data.type === 'payment_success' ||
                event.data === 'payment_success' ||
                (event.data.success && event.data.success === true) ||
                (event.data.type === 'TRANSACTION' && event.data.success)) {

                // Show success message
                document.body.innerHTML = `
                    <div style="
                        position: fixed;
                        top: 0; left: 0; right: 0; bottom: 0;
                        background: linear-gradient(135deg, #28a745, #20c997);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        text-align: center;
                        font-family: Arial, sans-serif;
                    ">
                        <div>
                            <div style="font-size: 4rem; margin-bottom: 20px;">✅</div>
                            <h1>Payment Successful!</h1>
                            <p style="font-size: 1.2rem; margin: 20px 0;">Thank you! Your payment has been completed.</p>
                            <p>Booking ID: {{ $survey->id }}</p>
                            <p style="margin-top: 30px;">
                                <a href="tel:{{ config('app.support_phone', '+201234567890') }}"
                                   style="background: white; color: #28a745; padding: 12px 25px;
                                          text-decoration: none; border-radius: 25px; font-weight: bold;">
                                   📞 Contact Us
                                </a>
                            </p>
                        </div>
                    </div>
                `;

                // Update payment status
                setTimeout(() => {
                    fetch('{{ route("payment.status.check", $survey->id) }}')
                        .then(response => response.json())
                        .then(data => {
                            console.log('Payment status updated:', data);
                        })
                        .catch(error => console.error('Error:', error));
                }, 2000);
            }
        });

        // Auto-check payment status every 30 seconds
        setInterval(function () {
            fetch('{{ route("payment.status.check", $survey->id) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.is_confirmed) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error checking status:', error));
        }, 30000);
    </script>
</body>

</html>