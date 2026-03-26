{{-- resources/views/posts/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Feed - DevDoko')

@section('content')
<div class="container py-4">
    <!-- Feed Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-rss-fill me-2 text-primary"></i>
                Feed
            </h1>
            <p class="text-muted mb-0">Latest posts from developers you follow</p>
        </div>

        <!-- Create Post Button -->
        <div class="dropdown">
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="dropdown">
                <i class="bi bi-plus-lg me-2"></i>
                Create Post
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 240px;">
                <li>
                    <a class="dropdown-item rounded-3 py-2" href="{{ route('posts.create', ['type' => 'text']) }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle p-2">
                                <i class="bi bi-file-text text-primary"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Text Post</span>
                                <small class="text-muted">Share your thoughts</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item rounded-3 py-2" href="{{ route('posts.create', ['type' => 'code']) }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle p-2">
                                <i class="bi bi-code-slash text-success"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Code Snippet</span>
                                <small class="text-muted">Share code with syntax highlighting</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-3 py-2" href="{{ route('posts.create', ['type' => 'image']) }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle p-2">
                                <i class="bi bi-image text-info"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Image</span>
                                <small class="text-muted">Upload an image</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-3 py-2" href="{{ route('posts.create', ['type' => 'link']) }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle p-2">
                                <i class="bi bi-link-45deg text-warning"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Link</span>
                                <small class="text-muted">Share a URL</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item rounded-3 py-2" href="{{ route('posts.create', ['type' => 'question']) }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle p-2">
                                <i class="bi bi-question-circle text-danger"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">Question</span>
                                <small class="text-muted">Ask the community</small>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Feed Filters -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('feed') }}"
                class="btn btn-sm {{ request()->routeIs('feed') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-people me-2"></i>Following
            </a>
            <a href="{{ route('feed.popular') }}"
                class="btn btn-sm {{ request()->routeIs('feed.popular') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-fire me-2"></i>Popular
            </a>
            <a href="{{ route('feed.latest') }}"
                class="btn btn-sm {{ request()->routeIs('feed.latest') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-clock me-2"></i>Latest
            </a>
            <a href="{{ route('explore') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-compass me-2"></i>Explore
            </a>
        </div>
    </div>

    <!-- Main Feed -->
    <div class="row g-4">
        <!-- Posts Column -->
        <div class="col-lg-8">
            @forelse($posts as $post)
            @include('posts.partials.card', ['post' => $post, 'fullView' => false])
            @empty
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-newspaper text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No posts to show</h5>
                <p class="text-muted mb-4">Follow more developers to see their posts here</p>
                <a href="{{ route('developers.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-people me-2"></i>Find Developers
                </a>
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-4">
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Trending Tags -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                        Trending Tags
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @php
                        $trendingTags = App\Models\Tag::withCount('posts')
                        ->orderBy('posts_count', 'desc')
                        ->limit(10)
                        ->get();
                        @endphp
                        @foreach($trendingTags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}"
                            class="text-decoration-none px-3 py-2 bg-light rounded-pill text-dark small">
                            #{{ $tag->name }}
                            <span class="text-muted ms-1">({{ $tag->posts_count }})</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Suggested Developers -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-person-plus-fill text-success me-2"></i>
                        Suggested Developers
                    </h6>
                    <a href="{{ route('developers.index') }}" class="small text-decoration-none">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    @php
                    $suggestedUsers = App\Models\User::where('id', '!=', auth()->id())
                    ->whereDoesntHave('followers', function($q) {
                    $q->where('follower_id', auth()->id());
                    })
                    ->with('profile')
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
                    @endphp
                    @foreach($suggestedUsers as $suggestedUser)
                    <div class="list-group-item border-0 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $suggestedUser->avatar_url }}" class="rounded-circle border"
                                style="width: 48px; height: 48px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <a href="{{ route('profile.show', $suggestedUser->profile->username) }}"
                                    class="text-decoration-none text-dark fw-semibold d-block">
                                    {{ $suggestedUser->profile->username ?? $suggestedUser->name }}
                                </a>
                                <small class="text-muted">{{ $suggestedUser->profile->title ?? 'Developer' }}</small>
                            </div>
                            <form action="{{ route('users.follow', $suggestedUser) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    Follow
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection