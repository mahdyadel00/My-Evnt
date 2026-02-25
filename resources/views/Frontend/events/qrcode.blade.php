<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #ffffff;
            font-family: Arial, sans-serif;
        }
        .qr-container {
            text-align: center;
            padding: 20px;
        }
        .qr-code {
            display: inline-block;
        }
        svg {
            max-width: 50%;
            height: auto;
        }
        h3 {
            color: black;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="qr-container">
        @if(isset($qrCodeUrl) && !empty($qrCodeUrl))
            <div class="qr-code">
                @php
                    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(400)
                        ->color(0, 0, 0)
                        ->backgroundColor(255, 255, 255)
                        ->generate($qrCodeUrl);
                    echo $qr;
                @endphp
                <h3 style="color: black;">لنا في الخيال… حب</h3>
            </div>
        @else
            <h3 style="color: red;">QR Code is not available</h3>
        @endif
    </div>
</body>
</html>
