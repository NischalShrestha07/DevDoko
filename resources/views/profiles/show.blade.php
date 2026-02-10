@extends('layouts.app')

@section('title', '@' . $profile->username . ' - DevDoko')

@section('content')
<!-- Profile Header -->
<div class="card border-0 bg-white mb-5">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                @if($profile->avatar)
                <img src="{{ $profile->avatar_url }}" class="rounded-circle mb-3"
                    style="width:150px;height:150px;object-fit:cover;">
                @else
                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 150px; height: 150px;">
                    <i class="bi bi-person-fill text-white fs-1"></i>
                </div>
                @endif
            </div>
            <div class="col-md-9">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="mb-0 me-3">{{ $profile->username }}</h4>

                    @if(auth()->id() === $profile->user_id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm me-2">Edit Profile</a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-gear"></i>
                    </a>
                    @else
                    @if($isFollowing)
                    <form action="{{ route('users.unfollow', $profile->user) }}" method="POST" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Following</button>
                    </form>
                    @else
                    <form action="{{ route('users.follow', $profile->user) }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Follow</button>
                    </form>
                    @endif
                    <a href="#" class="btn btn-outline-secondary btn-sm me-2">Message</a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-person-plus"></i>
                    </a>
                    @endif
                </div>

                <div class="d-flex mb-3">
                    <div class="me-4">
                        {{-- <span class="fw-bold">{{ $posts->total() }}</span> posts --}}
                        <span class="stat-value">{{ auth()->user()->posts->count() ?? 0 }}</span> posts
                    </div>
                    <a href="{{ route('users.followers', $profile->user) }}"
                        class="text-decoration-none text-dark me-4">
                        {{-- <span class="fw-bold">{{ $profile->user->followers_count ?? 0 }}</span> followers --}}
                        <span class="stat-value">{{ auth()->user()->followers->count() ?? 0 }}</span> followers
                    </a>
                    <a href="{{ route('users.following', $profile->user) }}" class="text-decoration-none text-dark">
                        {{-- <span class="fw-bold">{{ $profile->user->following_count ?? 0 }}</span> following --}}
                        <span class="stat-value">{{ auth()->user()->following->count() ?? 0 }}</span> following
                    </a>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold mb-1">{{ $profile->user->name }}</h6>
                    @if($profile->bio)
                    <p class="mb-2">{{ $profile->bio }}</p>
                    @endif
                    @if($profile->github_link)
                    <div class="mb-1">
                        <i class="bi bi-github"></i>
                        <a href="{{ $profile->github_link }}" target="_blank" class="text-decoration-none">
                            {{ $profile->github_link }}
                        </a>
                    </div>
                    @endif
                    @if($profile->portfolio_link)
                    <div class="mb-1">
                        <i class="bi bi-link-45deg"></i>
                        <a href="{{ $profile->portfolio_link }}" target="_blank" class="text-decoration-none">
                            {{ $profile->portfolio_link }}
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Tech Stack -->
                @if($profile->techTags->count())
                <div class="mb-3">
                    <h6 class="fw-bold mb-2">Tech Stack</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($profile->techTags as $techTag)
                        <span class="badge bg-light text-dark border">
                            {{ $techTag->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Profile Tabs -->
<ul class="nav nav-tabs justify-content-center border-0 mb-4" id="profileTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active d-flex align-items-center" id="posts-tab" data-bs-toggle="tab" href="#posts"
            role="tab">
            <i class="bi bi-grid-3x3 me-1"></i> POSTS
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link d-flex align-items-center" id="saved-tab" data-bs-toggle="tab" href="#saved" role="tab">
            <i class="bi bi-bookmark me-1"></i> SAVED
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link d-flex align-items-center" id="tagged-tab" data-bs-toggle="tab" href="#tagged" role="tab">
            <i class="bi bi-tag me-1"></i> TAGGED
        </a>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="profileTabContent">
    <!-- Posts Tab -->
    <div class="tab-pane fade show active" id="posts" role="tabpanel">
        @if($posts->count())
        <div class="row">
            @foreach($posts as $post)
            <div class="col-md-4 mb-4">
                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                    @if($post->type === 'image' && $post->media->count())
                    <img src="{{ asset('storage/' . $post->media->first()->file_path) }}" class="img-fluid rounded"
                        style="width: 100%; height: 300px; object-fit: cover;">
                    @elseif($post->type === 'code')
                    <div class="bg-dark text-light p-4 rounded h-100">
                        <div class="text-center">
                            <i class="bi bi-code-slash fs-1"></i>
                            <div class="mt-2">Code Snippet</div>
                            <small class="text-muted">
                                {{ $post->code_language ?? '' }}
                            </small>
                        </div>
                    </div>
                    @endif
                </a>
            </div>
            @endforeach
        </div>
        {{ $posts->links() }}
        @else
        <div class="text-center py-5">
            <i class="bi bi-camera fs-1 text-muted"></i>
            <h5 class="mt-3">No Posts Yet</h5>
            @if(auth()->id() === $profile->user_id)
            <p class="text-muted">Share your first code or project!</p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">Share Your First Post</a>
            @endif
        </div>
        @endif
    </div>

    <!-- Saved Tab -->
    <div class="tab-pane fade" id="saved" role="tabpanel">
        <div class="text-center py-5">
            <i class="bi bi-bookmark fs-1 text-muted"></i>
            <h5 class="mt-3">No Saved Posts</h5>
            <p class="text-muted">Save posts you want to come back to later</p>
        </div>
    </div>

    <!-- Tagged Tab -->
    <div class="tab-pane fade" id="tagged" role="tabpanel">
        <div class="text-center py-5">
            <i class="bi bi-tag fs-1 text-muted"></i>
            <h5 class="mt-3">No Tagged Posts</h5>
            <p class="text-muted">Photos and videos you're tagged in will appear here</p>
        </div>
    </div>
</div>



<!-- GitHub Integration -->
@if($profile->github_link)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-github"></i> GitHub Activity
        </h6>
        <a href="{{ $profile->github_link }}" target="_blank" class="btn btn-sm btn-outline-dark">
            View Profile
        </a>
    </div>
    <div class="card-body">
        <!-- GitHub stats would go here via API -->
        <div class="text-center py-3">
            <i class="bi bi-github fs-1 text-muted"></i>
            <p class="text-muted mt-2">GitHub integration coming soon</p>
        </div>
    </div>
</div>
@endif


@endsection