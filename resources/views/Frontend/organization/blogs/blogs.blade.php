@extends('Frontend.organization.layouts.master')
@section('title', 'Blogs')
@section('organization')
    <div class="hero-blog">
        <div class="hero-blog-text">
            <h1>Blog</h1>
            <p>Read our latest news and articles</p>
        </div>
        <div class="image-hero">
            <img src="{{ asset('Front') }}/img/blog.jpg" alt="">
        </div>
    </div>
    <!-- start section blogs -->
    <section id="blog">
        @foreach($blogs as $blog)
            <div class="blog-box">
            <div class="blog-img">
                @foreach($blog->media as $media)
                    <a href="{{ asset('storage/'.$media->path) }}" data-lightbox="image-1">
                        <img src="{{ asset('storage/'.$media->path) }}" alt="">
                    </a>
                @endforeach
            </div>
            <div class="blog-text">
                <span>{{ $blog->created_at->format('d M Y') }}</span>
                <h4>{{ $blog->title }}</h4>
                <p>{!! Str::limit($blog->content, 200) !!}</p>
                <a href="{{ route('blog' , $blog->id) }}" class="btn-outline-website">READ MORE </a>
            </div>
        </div>
        @endforeach
        <!-- Pagination -->
        <nav aria-label="Blog pagination" class="mt-4 mb-4">
            <ul class="pagination justify-content-center">
                @if($blogs->previousPageUrl())
                    <li class="page-item">
                        <a class="page-link" href="{{ $blogs->previousPageUrl() }}" tabindex="-1">Previous</a>
                    </li>
                @endif
                @for($i = 1; $i <= $blogs->lastPage(); $i++)
                    <li class="page-item {{ $blogs->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $blogs->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
                @if($blogs->nextPageUrl())
                    <li class="page-item">
                        <a class="page-link" href="{{ $blogs->nextPageUrl() }}">Next</a>
                    </li>
                @endif
            </ul>
        </nav>
    </section>
    <!-- end section blogs -->
@endsection
