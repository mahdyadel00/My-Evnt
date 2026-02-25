@extends('backend.partials.master')

@section('title', 'Edit Company')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.companies.index') }}">Manage Companies</a>
                </li>
                <li class="breadcrumb-item active">Edit Company</li>
            </ol>
        </nav>

        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Edit Company</h5>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-arrow-left ti-xs me-1"></i> Back to Companies
                </a>
            </div>
            <div class="card-body">
                <form id="edit" action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $company->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="{{ $errors->has('first_name') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="first_name" name="first_name" value="{{ old('first_name', $company->first_name) }}" placeholder="Enter First Name">
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="{{ $errors->has('last_name') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="last_name" name="last_name" value="{{ old('last_name', $company->last_name) }}" placeholder="Enter Last Name">
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_name" class="form-label">User Name</label>
                                <input type="text" class="{{ $errors->has('user_name') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="user_name" name="user_name" value="{{ old('user_name', $company->user_name) }}" placeholder="Enter User Name">
                                @error('user_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="{{ $errors->has('company_name') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" placeholder="Enter Company Name">
                                @error('company_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Company Email</label>
                                <input type="email" class="{{ $errors->has('email') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="email" name="email" value="{{ old('email', $company->email) }}" placeholder="Enter Company Email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Company Phone</label>
                                <input type="text" class="{{ $errors->has('phone') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="phone" name="phone" value="{{ old('phone', $company->phone) }}" placeholder="Enter Company Phone">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whats_app" class="form-label">Company WhatsApp</label>
                                <input type="text" class="{{ $errors->has('whats_app') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="whats_app" name="whats_app" value="{{ old('whats_app', $company->whats_app) }}" placeholder="Enter Company WhatsApp">
                                @error('whats_app')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website" class="form-label">Company Website</label>
                                <input type="text" class="{{ $errors->has('website') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="website" name="website" value="{{ old('website', $company->website) }}" placeholder="Enter Company Website">
                                @error('website')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="text" class="{{ $errors->has('facebook') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="facebook" name="facebook" value="{{ old('facebook', $company->facebook) }}" placeholder="Enter Facebook URL">
                                @error('facebook')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="text" class="{{ $errors->has('twitter') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="twitter" name="twitter" value="{{ old('twitter', $company->twitter) }}" placeholder="Enter Twitter URL">
                                @error('twitter')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="{{ $errors->has('instagram') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="instagram" name="instagram" value="{{ old('instagram', $company->instagram) }}" placeholder="Enter Instagram URL">
                                @error('instagram')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="text" class="{{ $errors->has('linkedin') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="linkedin" name="linkedin" value="{{ old('linkedin', $company->linkedin) }}" placeholder="Enter LinkedIn URL">
                                @error('linkedin')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="youtube" class="form-label">YouTube</label>
                                <input type="text" class="{{ $errors->has('youtube') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="youtube" name="youtube" value="{{ old('youtube', $company->youtube) }}" placeholder="Enter YouTube URL">
                                @error('youtube')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="snapchat" class="form-label">Snapchat</label>
                                <input type="text" class="{{ $errors->has('snapchat') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="snapchat" name="snapchat" value="{{ old('snapchat', $company->snapchat) }}" placeholder="Enter Snapchat URL">
                                @error('snapchat')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tiktok" class="form-label">TikTok</label>
                                    <input type="text" class="{{ $errors->has('tiktok') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="tiktok" name="tiktok" value="{{ old('tiktok', $company->tiktok) }}" placeholder="Enter TikTok URL">
                                @error('tiktok')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="form-label">Company Address</label>
                                <textarea class="{{ $errors->has('address') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="address" name="address" placeholder="Enter Company Address">{{ old('address', $company->address) }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description" class="form-label">Company Description</label>
                                <textarea class="{{ $errors->has('description') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="description" name="description" placeholder="Enter Company Description">{{ old('description', $company->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Company Status</label>
                                <select class="{{ $errors->has('status') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="status" name="status">
                                    <option value="1" {{ old('status', $company->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $company->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logo" class="form-label">Company Logo</label>
                                <input type="file" class="{{ $errors->has('logo') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="logo" name="logo">
                                @error('logo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @php
                                    $logo = $company->media->firstWhere('name', 'logo');
                                @endphp
                                @if ($logo)
                                    <div class="media-preview">
                                        <a href="{{ asset('storage/' . $logo->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $logo->path) }}" alt="Company Logo" width="50" height="50">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="commercial_register_image" class="form-label">Commercial Register Image</label>
                                <input type="file" class="{{ $errors->has('commercial_register_image') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="commercial_register_image" name="commercial_register_image">
                                @error('commercial_register_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @php
                                    $commercialRegister = $company->media->firstWhere('name', 'commercial_register_image');
                                @endphp
                                @if ($commercialRegister)
                                    <div class="media-preview">
                                        <a href="{{ asset('storage/' . $commercialRegister->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $commercialRegister->path) }}" alt="Commercial Register Image" width="50" height="50">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_card" class="form-label">Tax Card Image</label>
                                <input type="file" class="{{ $errors->has('tax_card') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="tax_card" name="tax_card">
                                @error('tax_card')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @php
                                    $taxCard = $company->media->firstWhere('name', 'tax_card');
                                @endphp
                                @if ($taxCard)
                                    <div class="media-preview">
                                        <a href="{{ asset('storage/' . $taxCard->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $taxCard->path) }}" alt="Tax Card Image" width="50" height="50">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="commercial_record" class="form-label">Commercial Record</label>
                                <input type="file" class="{{ $errors->has('commercial_record') ? 'form-control is-invalid' : 'form-control' }}"
                                 id="commercial_record" name="commercial_record">
                                @error('commercial_record')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @php
                                    $commercialRecord = $company->media->firstWhere('name', 'commercial_record');
                                @endphp
                                @if ($commercialRecord)
                                    <div class="media-preview">
                                        <a href="{{ asset('storage/' . $commercialRecord->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $commercialRecord->path) }}" alt="Commercial Record" width="50" height="50">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy ti-xs me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

