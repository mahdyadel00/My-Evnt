@extends('Frontend.layouts.master')
@section('title', 'Reset Password')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
    <style>
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
            <form action="{{ route('post_organization_reset_password') }}" method="POST">
                @csrf
                <h3>Reset password ? </h3>
                <p>No worries, add your email address and we’ll send you reset instructions. </p>

                <div class="input_box">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" placeholder="Enter code" required />
                </div>
                <div class="input_box has-hint">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" required />
                    <div class="input-hint" role="note" aria-live="polite">
                        <strong>Password rules</strong>
                        At least 8 characters, includes 1 uppercase letter, 1 lowercase letter, and 1 number.
                    </div>
                    <!-- <i class="fas fa-eye toggle-password"></i> -->
                </div>
                <div class="input_box">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Enter confirm password" required />
                    <!-- <i class="fas fa-eye toggle-password"></i> -->
                </div>
                <button type="submit" class="subscribe-btn">Reset Password</button>
            </form>
        </div>
    </section>
@endsection
@push('js')
@endpush
