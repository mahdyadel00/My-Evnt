@extends('Frontend.organization.account.inc.master')
@section('title', 'Profile')
@section('content')

    <div class="head-title" style="margin-bottom: 20px;">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li><a class="active" href="{{ route('home') }}">Home</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="#">Profile</a></li>
            </ul>
            <h1>Profile</h1>
        </div>
    </div>
    @include('Frontend.organization.layouts._message')
    <!-- start profile data -->
    <section class="py-xl-8">
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
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane" aria-selected="false">Change Password </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link " id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete-tab-pane" type="button" role="tab" aria-controls="delete-tab-pane" aria-selected="false" style="color: red;">Delete Account </button>
                                </li>
                            </ul>
                            <div class="tab-content pt-4" id="profileTabContent">
                                <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
                                    <form action="{{ route('organization.profile.update') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <h5 class="pb-2">General Information </h5>
                                        <div class="row gy-3 gy-xxl-4">
                                            <div class="col-12 col-md-6">
                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ auth()->guard('company')->user()->first_name  }}">
                                                @error('first_name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ auth()->guard('company')->user()->last_name }}">
                                                @error('last_name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <select class="form-select" name="city_id">
                                                    <option disabled selected> Choose City</option>
                                                    @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{ auth()->guard('company')->user()->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('city_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class=" col-12 col-md-6">
                                                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" value="{{ auth()->guard('company')->user()->email }}">
                                                @error('email')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="tel" name="phone" class="form-control" placeholder="Phone Number" value="{{ auth()->guard('company')->user()->phone }}">
                                                @error('phone')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="text" name="user_name" class="form-control" placeholder="Username" value="{{ auth()->guard('company')->user()->user_name }}">
                                                @error('user_name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="text" name="company_name" class="form-control" placeholder="Company Name" value="{{ auth()->guard('company')->user()->company_name }}">
                                                @error('company_name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="text" name="website" class="form-control" placeholder="Site Url" value="{{ auth()->guard('company')->user()->website }}">
                                                @error('website')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- select Company Or User -->
                                            <div class="col-12 col-md-12">
                                                <select class="form-select" name="type">
                                                    <option disabled selected> Choose Type</option>
                                                    <option value="company" {{ auth()->guard('company')->user()->type == 'company' ? 'selected' : '' }}>Company</option>
                                                    <option value="user" {{ auth()->guard('company')->user()->type == 'user' ? 'selected' : '' }}>User</option>
                                                </select>
                                                @error('type')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- Upload Identity Image -->
                                            <div class="col-12 col-md-6" style="display: {{ auth()->guard('company')->user()->type == 'user' ? 'block' : 'none' }}">
                                                <label for="identity_image">Identity Image</label>
                                                <input type="file" name="identity_image" class="form-control" placeholder="Identity Image">
                                                @foreach(auth()->guard('company')->user()->media as $media)
                                                <a href="{{ asset('storage/'.$media->path) }}" target="_blank">
                                                    @if($media->name == 'identity_image')
                                                    <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 100px; height: 100px;border-radius: 50%;">
                                                    @endif
                                                </a>
                                                @endforeach
                                                @error('identity_image')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6" style="display: {{ auth()->guard('company')->user()->type == 'company' ? 'block' : 'none' }}">
                                                <label for="commercial_register_image">Commercial Register Image</label>
                                                <input type="file" name="commercial_register_image" class="form-control" placeholder="Commercial Register Image">
                                                @foreach(auth()->guard('company')->user()->media as $media)
                                                <a href="{{ asset('storage/'.$media->path) }}" target="_blank">
                                                    @if($media->name == 'commercial_register_image')
                                                    <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 100px; height: 100px;border-radius: 50%;">
                                                    @endif
                                                </a>
                                                @endforeach
                                                @error('commercial_register_image')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6" style="display: {{ auth()->guard('company')->user()->type == 'company' ? 'block' : 'none' }}">
                                                <label for="tax_card_image">Tax Card Image</label>
                                                <input type="file" name="tax_card" class="form-control" placeholder="Tax Card">
                                                @foreach(auth()->guard('company')->user()->media as $media)
                                                <a href="{{ asset('storage/'.$media->path) }}" target="_blank">
                                                    @if($media->name == 'tax_card')
                                                    <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 100px; height: 100px;border-radius: 50%;">
                                                    @endif
                                                </a>
                                                @endforeach
                                                @error('tax_card')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6" style="display: {{ auth()->guard('company')->user()->type == 'company' ? 'block' : 'none' }}">
                                                <label for="commercial_record">Commercial Record</label>
                                                <input type="file" name="commercial_record" class="form-control" placeholder="Commercial Record">
                                                @foreach(auth()->guard('company')->user()->media as $media)
                                                <a href="{{ asset('storage/'.$media->path) }}" target="_blank">
                                                    @if($media->name == 'commercial_record')
                                                    <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 100px; height: 100px;border-radius: 50%;">
                                                    @endif
                                                </a>
                                                @endforeach
                                                @error('commercial_record')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-12">
                                                <textarea name="description" class="form-control" placeholder="Description" rows="3">{!! auth()->guard('company')->user()->description !!}</textarea>
                                                @error('description')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-12">
                                                <textarea name="address" class="form-control" placeholder="Address" rows="3">{!! auth()->guard('company')->user()->address !!}</textarea>
                                                @error('address')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mt-3">
                                                <div> <h4 style="font-weight: bold;">Company image </h4>
                                                    <div class="notice-info col-md-4 d-flex justify-content-center align-items-center">
                                                        <span> Event poster image must be in JPG, PNG, or WEBP format. The file should not exceed 5 MB. Recommended image size: 300px * 300px.</span>
                                                    </div>
                                                </div>
                                                <div class="image-box" id="imageBox"
                                                     onclick="document.getElementById('fileInput').click()">
                                                    @foreach(auth()->guard('company')->user()->media as $media)
                                                    @if($media->name == 'logo')
                                                    <img id="uploadedImage" src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 100%; height: 100%;">
                                                    @endif
                                                    @endforeach
                                                </div>
                                                <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="loadFile(event)" name="logo">
                                            </div>
                                        </div>
                                        <div class="row gy-3 gy-xxl-4">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab" tabindex="0">
                                    <form action="{{ route('organization_change_password') }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row gy-3 gy-xxl-4">
                                            <div class="col-12 col-md-6">
                                                <input type="password" class="form-control" name="current_password"
                                                       id="newPassword" placeholder="Current Password">
                                                @error('current_password')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="password" name="password" class="form-control"
                                                       id="newPassword" placeholder="New Password">
                                                @error('password')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm New Password">
                                                @error('password_confirmation')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="delete-tab-pane" role="tabpanel" aria-labelledby="delete-tab" tabindex="0">
                                    <form action="{{ route('organization_delete_account') }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <div class="row gy-3 gy-xxl-4">
                                            <div class="col-12 text-center">
                                                <p>When you delete your account, your profile, events, archived <br/> events and statistics will be permanently removed. </p>
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
@endsection
@push('inc_js')
    <script>
        //when select type user show identity image else hide and when select company show all image
        $(document).ready(function(){
            $('select[name="type"]').on('change', function() {
                var type = $(this).val();
                if(type == 'user'){
                    $('input[name="identity_image"]').parent().show();
                    $('input[name="commercial_register_image"]').parent().hide();
                    $('input[name="tax_card"]').parent().hide();
                    $('input[name="commercial_record"]').parent().hide();
                }else if(type == 'company'){
                    $('input[name="identity_image"]').parent().hide();
                    $('input[name="commercial_register_image"]').parent().show();
                    $('input[name="tax_card"]').parent().show();
                    $('input[name="commercial_record"]').parent().show();
                }
            });
        });
    </script>
@endpush
