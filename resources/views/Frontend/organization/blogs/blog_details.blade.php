@extends('Frontend.organization.layouts.master')
@section('title', 'Blog Details')
@section('organization')
    <!-- start post section -->
    <section class="blog_post">
        @if($article)
        <div class="container my-5">
            @foreach($article->media as $media)
                    <img class="w-100 my-3" style="border-radius: 7px" src="{{ asset('storage/'.$media->path) }}" />
            @endforeach
            <!-- start post content -->
            <div style="max-width: 800px; " class="mx-auto text-secondary position-relative">
                <h3 class="text-dark text-center mt-3 mb-3">{{ $article->title }}</h3>
                <p class="my-2" style="line-height: 2;">{!! $article->description !!}</p>
                <br>
                <div class="my-3">
                    <small>
                        <p> {{ $article->created_at->format('d M Y') }} </p>
                    </small>
                </div>
            </div>
            <!-- end post content -->
        </div>
        @else
        <div class="container my-5">
            <h3 class="text-dark text-center mt-3 mb-3">No article found</h3>
        </div>
        @endif
    </section>
    <!-- end post section -->
@endsection
