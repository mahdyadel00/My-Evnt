@extends('backend.partials.master')

@section('title', 'Blogs')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Blogs</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                @can('create blog')
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add Blog</i>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Company Name</th>
                            <th style="text-align: center">Title</th>
                            <th style="text-align: center">Content</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($blogs as $blog)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>
                                    @foreach($blog->media as $media)
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="" style="width: 50px; height: 50px;" class="image">
                                    @endforeach
                                </td>
                                <td>{{ $blog->company?->company_name }}</td>
                                <td>{{ $blog->title }}</td>
                                <td>{!! substr($blog->content, 0, 50) !!}...</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                        @can('update blog')
                                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" style="margin: 0 5px;"
                                                class="btn btn-xs btn-outline-info">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete blog')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $blog->id }}, '{{ $blog->title }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $blog->id }}"
                                                action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Offcanvas to add new user -->
        </div>
    </div>
    <!-- / Content -->
@endsection
@section('js')
    <!-- when click image show full image -->
    <script>
        $(document).ready(function() {
            $('.image').click(function() {
                window.open($(this).attr('src'), '_blank');
            });
        });
    </script>
@endsection