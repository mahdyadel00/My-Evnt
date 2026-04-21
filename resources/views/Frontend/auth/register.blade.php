@extends('Frontend.layouts.master')
@section('title', 'Register')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
    <style>
        .input_box {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 44% !important;
            right: 10px;
            cursor: pointer;
            color: #666;
        }

        .toggle-password:hover {
            color: #333;
        }

        /* Password rules as a tooltip/notification (not under the input) */
        .input_box.has-hint .input-hint {
            position: absolute;
            right: 0;
            top: 0;
            transform: translateY(-110%);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
            background: rgba(6, 69, 84, 0.96);
            color: #fff;
            padding: 10px 12px;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
            width: min(320px, 92vw);
            font-size: .78rem;
            line-height: 1.35;
            z-index: 5;
        }

        .input_box.has-hint .input-hint strong {
            display: block;
            font-size: .8rem;
            margin-bottom: 4px;
        }

        .input_box.has-hint .input-hint::after {
            content: "";
            position: absolute;
            right: 14px;
            bottom: -7px;
            width: 14px;
            height: 14px;
            background: rgba(6, 69, 84, 0.96);
            transform: rotate(45deg);
            border-radius: 3px;
        }

        .input_box.has-hint:focus-within .input-hint {
            opacity: 1;
            visibility: visible;
            transform: translateY(-120%);
        }

        @media (max-width: 768px) {
            .input_box.has-hint .input-hint {
                left: 0;
                right: 0;
                margin: 0 auto;
                transform: translateY(-105%);
                width: min(360px, 92vw);
            }

            .input_box.has-hint .input-hint::after {
                left: 22px;
                right: auto;
            }

            .input_box.has-hint:focus-within .input-hint {
                transform: translateY(-112%);
            }
        }
    </style>
@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
            @include('Frontend.layouts._message')
            <form id="registerForm" action="{{ route('register') }}" method="POST">
                @csrf
                <h3>Create Account </h3>
                <p>Please enter your details for buying tickets.</p>
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
                    <input type="email" id="email" name="email" placeholder="Enter email address" required />
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box has-hint">
                    <div class="password_title">
                        <label for="password">Password</label>
                    </div>
                    <input class="mb-0" type="password" id="password" name="password" placeholder="Enter your password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required />
                    <div class="input-hint" role="note" aria-live="polite">
                        <strong>Password rules</strong>
                        At least 8 characters, includes 1 uppercase letter, 1 lowercase letter, and 1 number.
                    </div>
                    <!-- <i class="fas fa-eye toggle-password"></i> -->
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box">
                    <div class="password_title mt-1">
                        <label for="Confirm-password">Confirm Password</label>
                    </div>
                    <input class="mb-0" type="password" id="Confirm-password" name="password_confirmation" placeholder="Enter your password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required />
                    <!-- <i class="fas fa-eye toggle-password"></i> -->
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box">
                    <label for="category_id mt-1">Category</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input_box">
                    <label for="sub_category_id mt-1">Sub Category</label>
                    <select name="sub_category_id" id="sub_category_id" required>
                        <option value="">Select Sub Category</option>
                    </select>
                    @error('sub_category_id')
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
                <p class="sign_up">Already have an account? <a class="sign" href="{{ route('login') }}">Log in</a></p>
            </form>
        </div>
    </section>
@endsection
@push('js')
    <script>
    //when select event category then get sub category
    $('#category_id').change(function() {
        var category_id = $(this).val();
        if (category_id) {
            $.ajax({
                type: "GET",
                url: "{{ url('get-sub-category-list') }}?category_id=" + category_id,
                success: function(res) {
                    if (res) {
                        $("#sub_category_id").empty();
                        $("#sub_category_id").append('<option value="">Select Sub Category</option>');
                        $.each(res, function(key, value) {
                            $("#sub_category_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    } else {
                        $("#sub_category_id").empty();
                    }
                }
            });
        } else {
            $("#sub_category_id").empty();
        }
    });

    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.add('fa-eye-slash');
                this.classList.remove('fa-eye');
            } else {
                input.type = 'password';
                this.classList.add('fa-eye');
                this.classList.remove('fa-eye-slash');
            }
        });
    });

    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('Confirm-password');
    confirmPassword.addEventListener('input', () => {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match");
        } else {
            confirmPassword.setCustomValidity('');
        }
    });


    </script>
@endpush
