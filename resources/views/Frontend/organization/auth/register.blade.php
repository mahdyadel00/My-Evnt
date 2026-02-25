@extends('Frontend.layouts.master')
@section('title', 'Register')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />

@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
            @include('Frontend.layouts._message')
            <form id="registerForm" action="{{ route('organization_register') }}" method="POST">
                @csrf
                <h3>Create Account </h3>
                <p>Please enter your details for buying tickets.</p>
                <p class="separator"><span>or</span></p>
                <div class="input_box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email address" required />
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box">
                    <div class="password_title">
                        <label for="password">Password</label>
                    </div>
                    <!--write note for password-->
                    <p id="password-message" style="color: rgb(245, 33, 33); display: none;text-align: left;">The password must
                        contain at least 8 characters, including one letter and one number.</p>
                    <!--write note for password-->
                    <p id="password-message" style="color: rgb(245, 33, 33); display: none;text-align: left;">The password must
                        contain at least 8 characters, including one letter and one number.</p>
                    <!--write note for password-->
                    <input type="password" id="password" name="password" placeholder="Enter your password" required />
                    <!--عندما يبدا المستخدم فى كتابة الباسوورد اظهر له رساله عن طرق ال cssتوضح كيفية كتاية الباسوورد عن طري الvalidation -->
                    <p id="password-message" style="color: rgb(245, 33, 33); display: none;text-align: left;">The password must
                        contain at least 8 characters, including one letter and one number.</p>

                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box">
                    <div class="password_title">
                        <label for="Confirm-password">Confirm Password</label>
                    </div>
                    <input type="password" id="Confirm-password" name="password_confirmation" placeholder="Enter your password" required />
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <p id="error-message" style="color: rgb(245, 33, 33); display: none;text-align: left;">The password fields must
                    match .</p>

                <div class="col-12">
                    <div class="form-check pb-2">
                        <input class="form-check-input" type="checkbox" required>
                        <label class="form-check-label">
                            Agree to terms and conditions
                        </label>
                    </div>
                </div>
                <button type="submit" class="subscribe-btn">Create Account</button>
                <p class="sign_up">Already have an account? <a class="sign" href="{{ route('organization_login') }}">Log in</a></p>
            </form>
        </div>
    </section>
@endsection
@push('js')
    <script>
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const passwordMessage = document.getElementById('password-message');
            if (password.length < 8) {
                passwordMessage.style.display = 'block';
            } else {
                passwordMessage.style.display = 'none';
            }
        });
    </script>
@endpush
