@extends('Frontend.layouts.master')
@section('title', 'Reset Password')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
            @include('Frontend.layouts._message')
            <form action="{{ route('post_reset_password') }}" method="POST">
                @csrf
                <h3>Reset password ? </h3>
                <p>No worries, add your email address and we’ll send you reset instructions. </p>

                <div class="input_box">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" placeholder="Enter code" required />
                </div>
                <div class="input_box">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" required />
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                <div class="input_box">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Enter confirm password" required />
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                <button type="submit" class="subscribe-btn">Reset Password</button>
            </form>
        </div>
    </section>
@endsection
@push('js')
@endpush
