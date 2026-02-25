@extends('backend.partials.master')

@section('title', 'Edit Article')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.articles.index') }}">Articles</a>
                </li>
                <li class="breadcrumb-item active">Edit Article</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        <form action="{{ route('admin.articles.update' , $article->id) }}" method="POST" enctype="multipart/form-data">
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
                                        <p class="card-title mb-75">Blog</p>
                                        <select name="blog_id" class="form-control select2">
                                            <option disabled selected>Select Blog</option>
                                            @foreach ($blogs as $blog)
                                                <option value="{{ $blog->id }}" {{ $article->blog_id == $blog->id ? 'selected' : '' }}>{{ $blog->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('blog_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Title</p>
                                        <input type="text" name="title" class="form-control" value="{{ $article->title }}" maxlength="255">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Description</p>
                                        <textarea name="description" class="form-control" rows="4" maxlength="1000">{!! $article->description !!}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Image</p>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @foreach($article->media as $media)
                                            <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 50px; height: 50px;">
                                        @endforeach
                                    </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                                <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                    <i class="ti ti-device-floppy ti-xs">Edit</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
        <!-- / Content -->
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection