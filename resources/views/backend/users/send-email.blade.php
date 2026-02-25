@extends('backend.partials.master')

@section('title', 'Send Email to Users')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}">All Users</a>
                </li>
                <li class="breadcrumb-item active">Send Email</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-brand-email ti-xs me-2"></i>
                            Send Email to Selected Users
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading fw-bold mb-2">
                                <i class="ti ti-info-circle ti-xs me-1"></i>
                                Selected Users ({{ $users->count() }})
                            </h6>
                            <div class="row">
                                @foreach ($users as $user)
                                    <div class="col-md-4 mb-2">
                                        <span class="badge bg-primary">
                                            {{ $user->user_name }} - {{ $user->email }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <form action="{{ route('admin.users.send-email.store') }}" method="POST">
                            @csrf

                            @foreach ($users as $user)
                                <input type="hidden" name="user_ids[]" value="{{ $user->id }}">
                            @endforeach

                            <div class="mb-3">
                                <label for="subject" class="form-label">
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="subject" 
                                    id="subject" 
                                    class="form-control @error('subject') is-invalid @enderror" 
                                    placeholder="Enter email subject..."
                                    maxlength="255"
                                    value="{{ old('subject') }}"
                                    required
                                >
                                @error('subject')
                                    <div class="invalid-feedback d-block">
                                        <i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">
                                    Message <span class="text-danger">*</span>
                                </label>
                                <textarea 
                                    name="message" 
                                    id="message" 
                                    class="form-control @error('message') is-invalid @enderror" 
                                    rows="10"
                                    placeholder="Enter your email message here..."
                                    required
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback d-block">
                                        <i class="ti ti-alert-circle ti-xs me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="ti ti-send ti-xs me-1"></i>
                                    Send Email
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left ti-xs me-1"></i>
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Optional: Add any JavaScript functionality here if needed
    });
</script>
@endsection

