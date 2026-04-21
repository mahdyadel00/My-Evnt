@extends('Frontend.layouts.master')
@section('title', 'Login')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
            @include('Frontend.layouts._message')
            <form action="{{ route('forgot.password') }}" method="POST">
                @csrf
                <h3>Forgot password ? </h3>
                <p>No worries, add your email address and we’ll send you reset instructions. </p>

                <div class="input_box">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter email address" required />
                </div>
                <button type="submit" class="subscribe-btn">Reset Password</button>
                <p class="sign_up" style="margin-top: 4px;">
                    Remembered your password? <a class="sign" href="{{ route('login') }}">Return to login</a>
                </p>
            </form>
        </div>
    </section>
@endsection
@push('js')
@endpush
