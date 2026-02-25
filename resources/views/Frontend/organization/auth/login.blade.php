@extends('Frontend.organization.layouts.master')
@section('title', 'Login')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
@endpush
@section('organization')
    <section class="page-form">
        <div class="login_form">
        @include('Frontend.organization.layouts._message')
            <form action="{{ route('post_organization_login') }}" method="POST">
                @csrf
                <h3>Welcome As Organization</h3>
                <p>Please enter your details for buying tickets! </p>
                <p class="separator">
                    <span>or</span>
                </p>
                <div class="input_box">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email address" required />
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box">
                    <div class="password_title">
                        <label for="password">Password</label>
                    </div>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required />
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <i class="fas fa-eye toggle-password"></i>
                </div>

                <div class="password_title" style="padding: 10px 0;">
                    <a class="forget" href="{{ route('organization_forgot_password') }}">Forgot Password?</a>
                </div>
                <!-- Login button -->
                <button type="submit" class="subscribe-btn">Log In</button>
                <p class="sign_up">Don't have an account? <a class="sign" href="{{ route('organization_register') }}">Sign Up</a></p>
            </form>
        </div>
    </section>
@endsection
@push('js')
    <script>
        //validation for password
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endpush
