@extends('layouts.app')

@section('title', 'Home - DevDoko')

@section('content')
<div class="row">
    <!-- Main Feed -->
    <div class="col-lg-8">
        <!-- Create Post Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <a href="{{ route('posts.create') }}" class="btn btn-light w-100 text-start text-muted"
                            style="border: 1px solid #dee2e6; border-radius: 20px;">
                            What's on your mind, {{ auth()->user()->name }}?
                        </a>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('posts.create') }}?type=image" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-image text-success"></i> Photo
                            </a>
                            <a href="{{ route('posts.create') }}?type=video" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-play-btn text-info"></i> Video
                            </a>
                            <a href="{{ route('posts.create') }}?type=code" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-code-slash text-warning"></i> Code
                            </a>
                            <a href="{{ route('posts.create') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-file-text text-primary"></i> Article
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Feed -->
        @forelse($posts as $post)
        @include('posts.partials.card', ['post' => $post])
        @empty
        <!-- No Posts State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                <h4 class="text-muted">Your feed is empty</h4>
                <p class="text-muted mb-4">
                    Follow other developers or create your first post to get started!
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('explore') }}" class="btn btn-primary">
                        <i class="bi bi-compass"></i> Explore Developers
                    </a>
                    <a href="{{ route('posts.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Create First Post
                    </a>
                </div>
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

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- Current User Profile Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                    <div>
                        <h6 class="mb-0 fw-bold">
                            <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                                class="text-decoration-none text-dark">
                                {{ auth()->user()->profile->username }}
                            </a>
                        </h6>
                        <small class="text-muted">{{ auth()->user()->name }}</small>
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-4">
                        <div class="fw-bold">{{ auth()->user()->posts_count ?? 0 }}</div>
                        <small class="text-muted">Posts</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold">{{ auth()->user()->followers_count ?? 0 }}</div>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold">{{ auth()->user()->following_count ?? 0 }}</div>
                        <small class="text-muted">Following</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggested Developers -->
        @if($suggestedUsers->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Suggested Developers</h6>
            </div>
            <div class="card-body">
                @foreach($suggestedUsers as $user)
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3"
                        width="40" height="40" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">
                            <a href="{{ route('profile.show', $user->profile->username) }}"
                                class="text-decoration-none text-dark">
                                {{ $user->profile->username }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            {{ $user->posts->count() }} posts
                        </small>
                    </div>
                    <form action="{{ route('users.follow', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            Follow
                        </button>
                    </form>
                </div>
                @endforeach
                <div class="text-center">
                    <a href="{{ route('explore') }}" class="text-decoration-none">
                        See more suggestions
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Trending Tags -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Trending Tags</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #laravel
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #react
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #javascript
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #webdev
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #python
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #php
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #vuejs
                    </a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">
                        #nodejs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection