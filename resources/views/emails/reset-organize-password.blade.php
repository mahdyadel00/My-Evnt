
@component('mail::message')
    <h1>Reset Password</h1>
    <p>Your Reset Code is : {{ $code }}</p>
    <p>Thank you for using our application!</p>
    <a href="{{ route('organization_reset_password', $code) }}" class="btn btn-primary">Reset Password</a>
@endcomponent

