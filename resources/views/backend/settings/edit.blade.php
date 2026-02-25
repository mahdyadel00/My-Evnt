@extends('backend.partials.master')

@section('title', 'Edit Setting')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.settings.edit') }}">Settings</a>
                </li>
                <li class="breadcrumb-item active">Edit Setting</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        @include('backend.partials._message')

        <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Site Name</p>
                                            <input type="text" class="form-control" name="name" placeholder="Category Name"
                                                   value="{{ $setting->name }}">
                                            @if ($errors->has('title'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Email</p>
                                            <input type="email" class="form-control" name="email" placeholder="Email"
                                                   value="{{ $setting->email }}">
                                            @if ($errors->has('email'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Phone</p>
                                            <input type="text" class="form-control" name="phone" placeholder="Phone"
                                                   value="{{ $setting->phone }}">
                                            @if ($errors->has('phone'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Mobile</p>
                                            <input type="text" class="form-control" name="phone_2" placeholder="Mobile"
                                                   value="{{ $setting->phone_2 }}">
                                            @if ($errors->has('phone_2'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone_2') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Description</p>
                                            <textarea class="form-control" name="description"
                                                      placeholder="Description">{{ $setting->description }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('description') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Facebook</p>
                                            <input type="text" class="form-control" name="facebook" placeholder="Facebook"
                                                   value="{{ $setting->facebook }}">
                                            @if ($errors->has('facebook'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('facebook') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Twitter</p>
                                            <input type="text" class="form-control" name="twitter" placeholder="Twitter"
                                                   value="{{ $setting->twitter }}">
                                            @if ($errors->has('twitter'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('twitter') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Instagram</p>
                                            <input type="text" class="form-control" name="instagram" placeholder="Instagram"
                                                   value="{{ $setting->instagram }}">
                                            @if ($errors->has('instagram'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('instagram') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Youtube</p>
                                            <input type="text" class="form-control" name="youtube" placeholder="Youtube"
                                                   value="{{ $setting->youtube }}">
                                            @if ($errors->has('youtube'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('youtube') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Linkedin</p>
                                            <input type="text" class="form-control" name="linkedin" placeholder="Linkedin"
                                                   value="{{ $setting->linkedin }}">
                                            @if ($errors->has('linkedin'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('linkedin') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75">Header Logo</p>
                                            <input type="file" name="header_logo" class="form-control">
                                           @error('header_logo')
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            @foreach($setting->media as $media)
                                                @if($media->name == 'header_logo')
                                                    <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 50px; height: 50px;">
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Footer Logo</p>
                                        <input type="file" name="footer_logo" class="form-control">
                                        @error('footer_logo')
                                        <span class="text-danger invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @foreach($setting->media as $media)
                                            @if($media->name == 'footer_logo')
                                                <img src="{{ asset('storage/'.$media->path) }}" alt=""
                                                     style="width: 50px; height: 50px;">
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <p class="card-title mb-75"> Favicon</p>
                                            <input type="file" name="favicon" class="form-control">
                                            @if ($errors->has('favicon'))
                                                <span class="text-danger invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('favicon') }}</strong>
                                                </span>
                                            @endif
                                            @foreach($setting->media as $media)
                                                @if($media->name == 'favicon')
                                                    <img src="{{ asset('storage/'.$media->path) }}" alt=""
                                                         style="width: 50px; height: 50px;">
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- SEO Section -->
                            <div class="main-content-label mg-b-5 mt-4">
                                <h5 class="card-title mb-3"><i class="ti ti-search ti-xs me-2"></i>SEO Settings</h5>
                                <p class="text-muted mb-4">Configure search engine optimization settings for your website</p>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-12 mt-2 mb-2">
                                    <div class="form-group">
                                        <p class="card-title mb-75">Site Name <small class="text-muted">(for SEO)</small></p>
                                        <input type="text" class="form-control" name="site_name" placeholder="Site Name"
                                               value="{{ $setting->site_name }}">
                                        <small class="form-text text-muted">This will be used as the default site name in meta tags</small>
                                        @if ($errors->has('site_name'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('site_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-md-12 mt-2 mb-2">
                                    <div class="form-group">
                                        <p class="card-title mb-75">Meta Title <small class="text-muted">(Recommended: 50-60 characters)</small></p>
                                        <input type="text" class="form-control" name="meta_title" placeholder="Meta Title"
                                               value="{{ $setting->meta_title }}" maxlength="60">
                                        <small class="form-text text-muted">The title that appears in search engine results</small>
                                        @if ($errors->has('meta_title'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('meta_title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-md-12 mt-2 mb-2">
                                    <div class="form-group">
                                        <p class="card-title mb-75">Meta Description <small class="text-muted">(Recommended: 150-160 characters)</small></p>
                                        <textarea class="form-control" name="meta_description" placeholder="Meta Description" rows="3"
                                                  maxlength="160">{{ $setting->meta_description }}</textarea>
                                        <small class="form-text text-muted">A brief description that appears in search engine results</small>
                                        @if ($errors->has('meta_description'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('meta_description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-md-12 mt-2 mb-2">
                                    <div class="form-group">
                                        <p class="card-title mb-75">Meta Keywords <small class="text-muted">(Comma-separated)</small></p>
                                        <textarea class="form-control" name="meta_keywords" placeholder="keyword1, keyword2, keyword3" rows="2">{{ $setting->meta_keywords }}</textarea>
                                        <small class="form-text text-muted">Enter keywords separated by commas (e.g., events, tickets, online events)</small>
                                        @if ($errors->has('meta_keywords'))
                                            <span class="text-danger invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('meta_keywords') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End SEO Section -->
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                                    <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                        <i class="ti ti-device-floppy ti-xs">Edit</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- / Content -->
    </div>
@endsection
