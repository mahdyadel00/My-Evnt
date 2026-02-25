@extends('Frontend.layouts.master')
@section('title', 'Confirmation Email')
@push('css')
    <link rel="stylesheet" href="{{ asset('Front') }}/css/login.css" />
@endpush
@section('content')
    <section class="page-form">
        <div class="login_form">
            @include('Frontend.layouts._message')
            <form action="{{ route('post_confirmation_email') }}" method="POST">
                @csrf
                <h3>Confirm Email</h3>
                <p>No worries, add your email address and we’ll send you reset instructions. </p>

                <div class="input_box">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" placeholder="Enter code" required />
                </div>
                <button type="submit" class="subscribe-btn">Confirm Email</button>
            </form>
        </div>
    </section>
@endsection
@push('js')
@endpush
