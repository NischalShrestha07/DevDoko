{{-- resources/views/profiles/show.blade.php --}}
@extends('layouts.app')

@section('title', '@' . $profile->username . ' - DevDoko')

@section('content')
<div class="container py-4">
    <!-- Profile Header - Enhanced Original Layout -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-4">
            <div class="row">
                <!-- Avatar Section -->
                <div class="col-md-3 text-center">
                    <div class="position-relative d-inline-block">
                        @if($profile->avatar)
                        <img src="{{ $profile->avatar_url }}"
                            class="rounded-circle border border-3 border-primary mb-3 shadow-sm"
                            style="width: 160px; height: 160px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-gradient-primary d-inline-flex align-items-center justify-content-center mb-3 shadow"
                            style="width: 160px; height: 160px;">
                            <span class="display-3 fw-bold text-white">{{ substr($profile->username, 0, 1) }}</span>
                        </div>
                        @endif

                        @if(auth()->id() === $profile->user_id)
                        <a href="{{ route('profile.edit') }}"
                            class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm"
                            style="transform: translateY(-10px);" data-bs-toggle="tooltip" title="Edit Profile">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Profile Info Section -->
                <div class="col-md-9">
                    <!-- Username and Actions -->
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center">
                            <h4 class="fw-bold mb-0 me-2">{{ '@' . $profile->username }}</h4>
                            @if($profile->is_verified ?? false)
                            <i class="bi bi-patch-check-fill text-primary fs-5" data-bs-toggle="tooltip"
                                title="Verified Developer"></i>
                            @endif
                        </div>

                        @if(auth()->id() === $profile->user_id)
                        <div class="d-flex gap-2">
                            <a href="{{ route('profile.edit') }}"
                                class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-pencil me-1"></i> Edit Profile
                            </a>
                            <a href="{{ route('profile.edit') }}#settings"
                                class="btn btn-sm btn-outline-secondary rounded-circle">
                                <i class="bi bi-gear"></i>
                            </a>
                        </div>
                        @else
                        <div class="d-flex gap-2">
                            @if($isFollowing)
                            <form action="{{ route('users.unfollow', $profile->user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                                    <i class="bi bi-person-check-fill me-1"></i> Following
                                </button>
                            </form>
                            @else
                            <form action="{{ route('users.follow', $profile->user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4">
                                    <i class="bi bi-person-plus me-1"></i> Follow
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('messages.show', $profile->user) }}"
                                class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-chat me-1"></i> Message
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary rounded-circle"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi bi-flag me-2"></i> Report User
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="bi bi-block me-2"></i> Block
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Stats Row - Enhanced Cards -->
                    <div class="d-flex flex-wrap gap-4 mb-3">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold fs-5 me-2">{{ number_format($postsCount) }}</span>
                            <span class="text-muted">posts</span>
                        </div>
                        <a href="{{ route('users.followers', $profile->user) }}"
                            class="text-decoration-none d-flex align-items-center">
                            <span class="fw-bold fs-5 me-2 text-dark">{{ number_format($followersCount) }}</span>
                            <span class="text-muted">followers</span>
                        </a>
                        <a href="{{ route('users.following', $profile->user) }}"
                            class="text-decoration-none d-flex align-items-center">
                            <span class="fw-bold fs-5 me-2 text-dark">{{ number_format($followingCount) }}</span>
                            <span class="text-muted">following</span>
                        </a>
                    </div>

                    <!-- Name and Bio -->
                    <div class="mb-3">
                        <h5 class="fw-bold mb-1">{{ $profile->user->name }}</h5>
                        @if($profile->title)
                        <p class="text-primary mb-2">{{ $profile->title }}</p>
                        @endif
                        @if($profile->bio)
                        <p class="mb-2" style="white-space: pre-wrap; max-width: 600px;">{{ $profile->bio }}</p>
                        @endif

                        <!-- Social/Links Grid -->
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            @if($profile->github_link)
                            <a href="{{ $profile->github_link }}" target="_blank"
                                class="text-decoration-none text-dark d-flex align-items-center small">
                                <i class="bi bi-github me-1 fs-5"></i>
                                <span>{{ Str::after($profile->github_link, 'github.com/') }}</span>
                            </a>
                            @endif

                            @if($profile->twitter_link)
                            <a href="{{ $profile->twitter_link }}" target="_blank"
                                class="text-decoration-none text-dark d-flex align-items-center small">
                                <i class="bi bi-twitter me-1 fs-5 text-info"></i>
                                <span>{{ Str::after($profile->twitter_link, 'twitter.com/') }}</span>
                            </a>
                            @endif

                            @if($profile->linkedin_link)
                            <a href="{{ $profile->linkedin_link }}" target="_blank"
                                class="text-decoration-none text-dark d-flex align-items-center small">
                                <i class="bi bi-linkedin me-1 fs-5 text-primary"></i>
                                <span>{{ Str::after($profile->linkedin_link, 'linkedin.com/in/') }}</span>
                            </a>
                            @endif

                            @if($profile->portfolio_link)
                            <a href="{{ $profile->portfolio_link }}" target="_blank"
                                class="text-decoration-none text-dark d-flex align-items-center small">
                                <i class="bi bi-link-45deg me-1 fs-5 text-success"></i>
                                <span>Portfolio</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Tech Stack - Enhanced -->
                    @if($profile->techTags->count())
                    <div class="mt-3">
                        <h6 class="fw-semibold mb-2 d-flex align-items-center">
                            <i class="bi bi-code-square me-2 text-primary"></i>
                            Tech Stack
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($profile->techTags as $techTag)
                            <a href="{{ route('tags.show', $techTag->slug) }}"
                                class="text-decoration-none badge bg-light text-dark border px-3 py-2 rounded-pill hover-bg-primary transition">
                                <i class="bi bi-hash"></i> {{ $techTag->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Joined Date -->
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>
                        Joined {{ $profile->user->created_at->format('F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Tabs - Enhanced -->
    <ul class="nav nav-tabs justify-content-center border-0 mb-4" id="profileTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center px-4 py-3" id="posts-tab" data-bs-toggle="tab"
                data-bs-target="#posts" type="button" role="tab">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                POSTS
                <span class="badge bg-light text-dark ms-2">{{ $postsCount }}</span>
            </button>
        </li>
        @if(auth()->id() === $profile->user_id)
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center px-4 py-3" id="saved-tab" data-bs-toggle="tab"
                data-bs-target="#saved" type="button" role="tab">
                <i class="bi bi-bookmark-fill me-2"></i>
                SAVED
                <span class="badge bg-light text-dark ms-2">{{ $savedPosts->total() ?? 0 }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center px-4 py-3" id="tagged-tab" data-bs-toggle="tab"
                data-bs-target="#tagged" type="button" role="tab">
                <i class="bi bi-tag-fill me-2"></i>
                TAGGED
            </button>
        </li>
        @endif
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="profileTabContent">
        <!-- Posts Tab - Enhanced Grid -->
        <div class="tab-pane fade show active" id="posts" role="tabpanel">
            @if($posts->count())
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            <div class="position-relative" style="padding-bottom: 100%;">
                                @if($post->type === 'image' && $post->media->count())
                                <img src="{{ asset('storage/' . $post->media->first()->file_path) }}"
                                    class="position-absolute w-100 h-100 rounded-top" style="object-fit: cover;">

                                @elseif($post->type === 'code')
                                <div
                                    class="position-absolute w-100 h-100 bg-dark rounded-top d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-code-slash text-white fs-1"></i>
                                    <span class="text-white-50 mt-2 small">{{ $post->code_language ?? 'Code' }}</span>
                                </div>

                                @elseif($post->type === 'video')
                                <div
                                    class="position-absolute w-100 h-100 bg-dark rounded-top d-flex align-items-center justify-content-center">
                                    <i class="bi bi-play-circle-fill text-white fs-1"></i>
                                </div>

                                @else
                                <div
                                    class="position-absolute w-100 h-100 bg-light rounded-top d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-file-text text-secondary fs-1"></i>
                                    <span class="text-muted mt-2 small">{{ $post->type ?? 'Post' }}</span>
                                </div>
                                @endif

                                @if($post->media->count() > 1)
                                <span
                                    class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 rounded-pill">
                                    <i class="bi bi-collection"></i> {{ $post->media->count() }}
                                </span>
                                @endif

                                <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white rounded-bottom"
                                    style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                    <div class="d-flex justify-content-around small">
                                        <span><i class="bi bi-heart-fill me-1"></i> {{ $post->likes_count ?? 0 }}</span>
                                        <span><i class="bi bi-chat-fill me-1"></i> {{ $post->comments_count ?? 0
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-grid-3x3-gap-fill text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No Posts Yet</h5>
                @if(auth()->id() === $profile->user_id)
                <p class="text-muted mb-4">Share your first code snippet or project with the community!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary px-4">
                    <i class="bi bi-plus-lg me-2"></i> Create Post
                </a>
                @else
                <p class="text-muted mb-0">This user hasn't posted anything yet.</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Saved Tab - Enhanced -->
        @if(auth()->id() === $profile->user_id)
        <div class="tab-pane fade" id="saved" role="tabpanel">
            @if($savedPosts->count())
            <div class="row g-4">
                @foreach($savedPosts as $post)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-lift position-relative">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            <div class="position-relative" style="padding-bottom: 100%;">
                                @if($post->type === 'image' && $post->media->count())
                                <img src="{{ asset('storage/' . $post->media->first()->file_path) }}"
                                    class="position-absolute w-100 h-100 rounded-top" style="object-fit: cover;">
                                @elseif($post->type === 'code')
                                <div
                                    class="position-absolute w-100 h-100 bg-dark rounded-top d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-code-slash text-white fs-1"></i>
                                    <span class="text-white-50 mt-2 small">{{ $post->code_language ?? 'Code' }}</span>
                                </div>
                                @else
                                <div
                                    class="position-absolute w-100 h-100 bg-light rounded-top d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-file-text text-secondary fs-1"></i>
                                    <span class="text-muted mt-2 small">Post</span>
                                </div>
                                @endif

                                <!-- Unsaved Button -->
                                <form action="{{ route('posts.unsave', $post) }}" method="POST"
                                    class="position-absolute top-0 end-0 m-2 z-index-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm bg-white bg-opacity-90 rounded-circle p-2 border-0 shadow-sm"
                                        data-bs-toggle="tooltip" title="Remove from saved"
                                        onclick="return confirm('Remove this post from your saved items?')">
                                        <i class="bi bi-bookmark-fill text-primary"></i>
                                    </button>
                                </form>

                                <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white rounded-bottom"
                                    style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                    <div class="d-flex justify-content-around small">
                                        <span><i class="bi bi-heart-fill me-1"></i> {{ $post->likes_count ?? 0 }}</span>
                                        <span><i class="bi bi-chat-fill me-1"></i> {{ $post->comments_count ?? 0
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-2 py-2">
                                <div class="d-flex align-items-center mb-1">
                                    <img src="{{ $post->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                                        class="rounded-circle me-2"
                                        style="width: 20px; height: 20px; object-fit: cover;">
                                    <small class="text-muted">{{ '@' . ($post->user->profile->username ??
                                        $post->user->name) }}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $savedPosts->appends(['saved_page' => $savedPosts->currentPage()])->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-bookmark text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No Saved Posts</h5>
                <p class="text-muted mb-4">Save interesting posts to come back to them later</p>
                <a href="{{ route('explore') }}" class="btn btn-outline-primary px-4">
                    <i class="bi bi-compass me-2"></i> Explore Posts
                </a>
            </div>
            @endif
        </div>
        @endif

        <!-- Tagged Tab -->
        @if(auth()->id() === $profile->user_id)
        <div class="tab-pane fade" id="tagged" role="tabpanel">
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-tag text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No Tagged Posts</h5>
                <p class="text-muted mb-0">When other developers tag you in posts, they'll appear here</p>
            </div>
        </div>
        @endif
    </div>

    <!-- GitHub Integration - Enhanced Card -->
    @if($profile->github_link)
    <div class="card border-0 shadow-sm mt-5">
        <div class="card-body p-4">
            <div class="d-flex align-items-start gap-4">
                <div class="bg-dark text-white rounded-circle p-3 d-flex align-items-center justify-content-center"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-github fs-2"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">GitHub Profile</h6>
                        <a href="{{ $profile->github_link }}" target="_blank"
                            class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i> View
                        </a>
                    </div>
                    <p class="mb-1 fw-semibold">{{ '@' . basename($profile->github_link) }}</p>

                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }

    .hover-bg-primary:hover {
        background-color: var(--bs-primary) !important;
        color: white !important;
        border-color: var(--bs-primary) !important;
    }

    .transition {
        transition: all 0.2s ease;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link:hover {
        border: none;
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        background: transparent;
    }

    .nav-tabs .nav-link.active {
        border: none;
        color: #0d6efd;
        font-weight: 600;
        background: transparent;
        border-bottom: 3px solid #0d6efd;
    }

    .z-index-1 {
        z-index: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
