<div class="head-title" style="margin-bottom: 20px;">
    <div class="left">
        <ul class="breadcrumb" style="margin-bottom: 20px;">
            <li><a class="active" href="{{ route('home') }}">Home</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a href="{{ route('profile') }}">Profile</a></li>
        </ul>
        <h1>Profile</h1>
        <p>Add information to your profile to make ticket purchases easier and faster </p>
    </div>
</div>
@include('Frontend.layouts._message')
<!-- start profile data -->
<section class="py-xl-8">
    <h3 class="p-1" style="padding: 0 10px;">Account Settings</h3>
    <div class="container">
        <div class="row gy-4 gy-lg-0">
            <div class="col-12 col-lg-12 col-xl-12">
                <div class="card widget-card border-light shadow-sm">
                    <div class="card-body p-3">
                        <ul class="nav nav-tabs" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-tab-pane" type="button" role="tab" aria-controls="overview-tab-pane" aria-selected="true">General Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane" aria-selected="false">Change Password</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete-tab-pane" type="button" role="tab" aria-controls="delete-tab-pane" aria-selected="false" style="color: red;">Delete Account</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-4" id="profileTabContent">
                            <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
                                <form action="{{ route('update_profile') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <h5 class="pb-2">Personal Information</h5>
                                    <div class="row gy-3 gy-xxl-4">
                                        <div class="col-12 col-md-6">
                                            <!-- <label for="firstName" class="form-label">First Name</label> -->
                                            <input type="text" class="form-control" id="first_name" placeholder="First Name" value="{{ auth()->guard('company')->user()->first_name }}" name="first_name">
                                            @error('first_name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control" id="last_name" placeholder="Last Name" value="{{ auth()->guard('company')->user()->last_name }}" name="last_name">
                                            @error('last_name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="email" class="form-control" id="email" placeholder="example@gmail.com" value="{{ auth()->guard('company')->user()->email }}" name="email">
                                            @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="tel" class="form-control" id="phone" placeholder="Phone Number" value="{{ auth()->guard('company')->user()->phone }}" name="phone">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control" id="address" placeholder="Address" value="{{ auth()->guard('company')->user()->address }}" name="address">
                                            @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="date" class="form-control" id="birth_date" placeholder="Birth Date" value="{{ auth()->guard('company')->user()->birth_date }}" name="birth_date">

                                        </div>
                                    </div>
                                    <h5 class="mt-4 pb-2 pt-2">Corporate Information <span style="color: #999;">(Optional)</span>
                                    </h5>
                                    <div class="row gy-3 gy-xxl-4">
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control" id="company_name" placeholder="Company Name" name="company_name" value="{{ auth()->guard('company')->user()->company?->company_name }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="email" class="form-control" id="company_email" placeholder="Company Email" name="company_email" value="{{ auth()->guard('company')->user()->company?->email }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control" id="company_address" placeholder="Company Address" name="company_address" value="{{ auth()->guard('company')->user()->company?->address }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control" id="company_website" placeholder="Company website" name="company_website" value="{{ auth()->guard('company')->user()->company?->website }}">
                                        </div>
                                    </div>
                                    <div class="row gy-3 gy-xxl-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab"
                                 tabindex="0">
                                <form action="{{ route('change_password') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row gy-3 gy-xxl-4">
                                        <div class="col-12 col-md-6">
                                            <input type="password" class="form-control" id="currentPassword" name="current_password" placeholder="Current Password">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="password" class="form-control" id="newPassword" name="password" placeholder="New Password">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password">
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="delete-tab-pane" role="tabpanel" aria-labelledby="delete-tab"
                                 tabindex="0">
                                <form action="{{ route('delete_account') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="row gy-3 gy-xxl-4">
                                        <div class="col-12 text-center">
                                            <p>Are you sure you want to delete your account? <br> This action cannot be undone.</p>
                                            <button type="submit" class="btn btn-danger">Delete Account</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end profile data -->
