{{-- resources/views/posts/feed.blade.php --}}
@extends('layouts.app')

@section('title', 'Feed - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-semibold mb-0">
            <i class="bi bi-rss-fill me-2 text-primary"></i>
            Feed
        </h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Create Post
        </a>
    </div>

    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('feed') }}" class="btn btn-sm btn-primary rounded-pill px-4">
                <i class="bi bi-people me-2"></i>Following
            </a>
            <a href="{{ route('feed.popular') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-fire me-2"></i>Popular
            </a>
            <a href="{{ route('feed.latest') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-clock me-2"></i>Latest
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8 mx-auto">
            @forelse($posts as $post)
            @include('posts.partials.card', ['post' => $post, 'fullView' => false])
            @empty
            <div class="text-center py-5">
                <div class="app-bg-secondary rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-newspaper text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No posts to show</h5>
                <p class="app-text-muted mb-4">Follow more developers to see their posts here</p>
                <a href="{{ route('developers.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-people me-2"></i>Find Developers
                </a>
            </div>
            @endforelse

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
