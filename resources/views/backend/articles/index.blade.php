@extends('backend.partials.master')

@section('title', 'Articles')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active"> Articles</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                @can('create article')
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add Article</i>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Blog</th>
                            <th style="text-align: center">Title</th>
                            <th style="text-align: center">Description</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($articles as $article)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>
                                    @foreach($article->media as $media)
                                        <img src="{{ asset('storage/' . $media->path) }}" alt="" style="width: 50px; height: 50px;">
                                    @endforeach
                                </td>
                                    <td>{{ $article->blog->title }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{!! substr($article->description, 0, 50) !!}...</td>
                                <td>
                                    <div style="display: flex; justify-content: space-between;">
                                        @can('update article')
                                            <a href="{{ route('admin.articles.edit', $article->id) }}" style="margin: 0 5px"
                                                class="btn btn-xs btn-outline-info">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete article')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $article->id }}, '{{ $article->title }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $article->id }}"
                                                action="{{ route('admin.articles.destroy', $article->id) }}" method="POST"
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
    </div>
@endsection

@section('js')
    <!-- Page JS -->
@endsection