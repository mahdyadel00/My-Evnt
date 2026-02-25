@extends('Frontend.layouts.master')
@section('title', 'Login')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
        @include('Frontend.layouts._message')
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <h3>Welcome Back! </h3>
                <p>Please enter your details for buying tickets! </p>
                <div class="login_option">
                    <div class="option">
                        <a href="{{ route('google.redirect') }}" class="btn btn-primary">
                            <img src="{{ asset('Front') }}/img/logos/google.png" alt="Google" />
                            <span>Login With Google</span>
                        </a>
                    </div>
                    <div class="option">
                        <a href="{{ route('facebook.redirect') }}" class="facebook">
                            <img src="{{ asset('Front') }}/img/logos/facebook.png" alt="Facebook">
                            <span>Login With Facebook</span>
                        </a>
                    </div>
                </div>
                <p class="separator">
                    <span>or</span>
                </p>
                <div class="input_box">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email address" required />
                </div>
                <div class="input_box">
                    <div class="password_title">
                        <label for="password">Password</label>
                    </div>
                    <input class="mb-0" type="password" id="password" name="password" placeholder="Enter your password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required />
                    <i class="fas fa-eye toggle-password"></i>
                </div>

                <div class="password_title" style="padding: 10px 0;">
                    <a class="forget" href="{{ route('forget_password') }}">Forgot Password?</a>
                </div>
                <!-- Login button -->
                <button type="submit" class="subscribe-btn">Log In</button>
                <p class="sign_up">Don't have an account? <a class="sign" href="{{ route('register') }}">Sign Up</a></p>
            </form>
        </div>
    </section>
@endsection
