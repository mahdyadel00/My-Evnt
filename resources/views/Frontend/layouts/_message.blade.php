<div class="col-lg-12">
    @if(session('success'))
        <div class="alert alert-dismissible fade show" role="alert"
            style="background-color: #fff8f5; border-color: #FF6B35; color: #CC4E1F;">
            <i class="fas fa-check-circle me-2" style="color: #FF6B35;"></i>
            <strong>Success!</strong> {{ session('success') }}

            @if(session('survey_details'))
                <div class="mt-3 p-3 bg-white border-start border-5" style="border-color: #FF6B35 !important;">
                    <h6 class="mb-2" style="color: #FF6B35;"><i class="fas fa-info-circle me-2"></i>Submission Details:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Session Type:</strong> {{ session('survey_details.session_type') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Quantity:</strong> {{ session('survey_details.quantity') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Amount:</strong> {{ session('survey_details.total_amount') }} EGP</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Reference ID:</strong> #{{ session('survey_details.reference_id') }}</p>
                        </div>
                        @if(session('survey_details.selected_date'))
                            <div class="col-md-6">
                                <p><strong>Preferred Date:</strong>
                                    {{ \Carbon\Carbon::parse(session('survey_details.selected_date'))->format('l, F j, Y') }}</p>
                            </div>
                        @endif
                        @if(session('survey_details.selected_time'))
                            <div class="col-md-6">
                                <p><strong>Preferred Time:</strong> {{ session('survey_details.selected_time') }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2 p-2 rounded" style="background-color: #fff8f5; border: 1px solid #FFE4D6;">
                        <small style="color: #CC4E1F;">
                            <i class="fas fa-phone me-1" style="color: #FF6B35;"></i>Please save your reference ID for future
                            communication.
                        </small>
                    </div>
                </div>
            @endif

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Warning!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Info!</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>