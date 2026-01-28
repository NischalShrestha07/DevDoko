{{-- resources/views/tags/show.blade.php --}}
@extends('layouts.app')

@section('title', '#' . $tag->name . ' - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Tag Header -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h1 class="display-4 mb-3">#{{ $tag->name }}</h1>
                <p class="text-muted">
                    {{ $posts->total() }} posts tagged with #{{ $tag->name }}
                </p>
                @if(auth()->check())
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Post with #{{ $tag->name }}
                </a>
                @endif
            </div>
        </div>

        <!-- Posts -->
        @forelse($posts as $post)
        @include('posts.partials.card', ['post' => $post])
        @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-tag display-1 text-muted mb-3"></i>
                <h5 class="text-muted">No posts with this tag yet</h5>
                <p class="text-muted">Be the first to post with #{{ $tag->name }}</p>
                @if(auth()->check())
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    Create First Post
                </a>
                @endif
            </div>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection