@extends('Frontend.layouts.master')
@section('title', 'Event Confirmation')

@section('content')
<div class="container mt-5">
    <div class="alert alert-success">
        <h4>Thank you for registering!</h4>
        <p>You have successfully registered for the event: <strong>{{ $event->name }}</strong>.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
    </div>
</div>
@endsection
