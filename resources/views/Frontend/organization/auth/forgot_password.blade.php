@extends('Frontend.layouts.master')
@section('title', 'Login')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
            @include('Frontend.layouts._message')
            <form action="{{ route('organization_forgot_password') }}" method="POST">
                @csrf
                <h3>Forgot password ? </h3>
                <p>No worries, add your email address and we’ll send you reset instructions. </p>

                <div class="input_box">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter email address" required />
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="subscribe-btn">Reset Password</button>
            </form>
        </div>
    </section>
@endsection
@push('js')
@endpush
