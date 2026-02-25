<!--write mail content here by using html-->
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Email</title>
</head>
<body>
    <p>DearMahdy,</p>
    <p>Thank you for registering with us. Please click on the below link to verify your email address</p>
    <a href="{{ route('confirmation_email', $code) }}">Verify Email</a>
    <p>Regards,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>


