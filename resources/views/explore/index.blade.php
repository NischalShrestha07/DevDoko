@extends('layouts.app')

@section('title', 'Explore - DevDoko')

@section('content')
<div class="container-fluid px-0">
    <!-- Explore Header -->
    <div class="bg-white border-bottom">
        <div class="container py-3">
            <h4 class="fw-bold mb-0">Explore</h4>
            <p class="text-muted mb-0">Discover amazing content, developers, and topics</p>
        </div>
    </div>

    <!-- Explore Tabs -->
    <div class="bg-white border-bottom">
        <div class="container">
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item ">
                    <a class="nav-link {{ $type == 'trending' ? 'active' : '' }}"
                        href="{{ route('explore') }}?type=trending">
                        <i class="bi bi-fire me-1"></i> Trending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'latest' ? 'active' : '' }}"
                        href="{{ route('explore') }}?type=latest">
                        <i class="bi bi-clock me-1"></i> Latest
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'developers' ? 'active' : '' }}"
                        href="{{ route('explore') }}?type=developers">
                        <i class="bi bi-people me-1"></i> Developers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'tags' ? 'active' : '' }}" href="{{ route('explore') }}?type=tags">
                        <i class="bi bi-tags me-1"></i> Topics
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        @if($type == 'trending' || $type == 'latest')
        <!-- Trending/Latest Posts -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    @if($type == 'trending')
                    <i class="bi bi-fire text-danger me-2"></i>Trending Now
                    @else
                    <i class="bi bi-clock text-primary me-2"></i>Latest Posts
                    @endif
                </h5>
                <a href="{{ route('feed.latest') }}" class="text-decoration-none small">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach(($type == 'trending' ? $trendingPosts : $latestPosts) as $post)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <!-- Post Image/Preview -->
                        @if($post->type === 'image' && $post->image_url)
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            <img src="{{ $post->image_url }}" class="card-img-top"
                                style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;"
                                alt="{{ $post->title }}">
                        </a>
                        @elseif($post->type === 'video' && $post->video_path)
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            <div class="position-relative"
                                style="height: 200px; background-color: #000; border-radius: 8px 8px 0 0;">
                                <div class="ratio ratio-16x9 h-100">
                                    <video class="w-100 h-100" style="object-fit: cover;">
                                        <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                                    </video>
                                </div>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="bi bi-play-circle-fill text-white fs-1"></i>
                                </div>
                            </div>
                        </a>
                        @elseif($post->type === 'code')
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            <div class="bg-dark text-light" style="height: 200px; border-radius: 8px 8px 0 0;">
                                <div class="p-3 border-bottom border-secondary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-code-slash me-2"></i>
                                            <span class="badge bg-primary">{{ $post->code_language ?? 'Code' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3" style="height: calc(200px - 60px); overflow: hidden;">
                                    <pre class="mb-0"
                                        style="background: transparent; border: none; color: #d4d4d4; font-size: 12px;">
                                        <code>{{ Str::limit($post->code_snippet, 150) }}</code>
                                    </pre>
                                </div>
                            </div>
                        </a>
                        @else
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            <div class="card-body" style="height: 200px; border-radius: 8px 8px 0 0;">
                                @if($post->title)
                                <h6 class="card-title fw-bold mb-2">{{ Str::limit($post->title, 50) }}</h6>
                                @endif
                                @if($post->content)
                                <div class="post-content text-muted" style="font-size: 14px;">
                                    {!! Str::markdown(Str::limit($post->content, 120)) !!}
                                </div>
                                @endif
                            </div>
                        </a>
                        @endif

                        <!-- Post Footer -->
                        <div class="card-body py-2">
                            <!-- User Info -->
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('profile.show', $post->user->profile->username) }}"
                                    class="text-decoration-none d-flex align-items-center">
                                    <img src="{{ $post->user->profile->avatar_url }}" class="rounded-circle me-2"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                    <span class="fw-bold text-dark">{{ $post->user->profile->username }}</span>
                                </a>
                                <span class="badge bg-light text-dark ms-auto">
                                    <i class="bi bi-{{ $post->type_icon }} me-1"></i>
                                </span>
                            </div>

                            <!-- Stats -->
                            <div class="d-flex justify-content-between text-muted small">
                                <div class="d-flex gap-3">
                                    <span>
                                        <i class="bi bi-heart me-1"></i>{{ $post->likes_count }}
                                    </span>
                                    <span>
                                        <i class="bi bi-chat me-1"></i>{{ $post->comments_count }}
                                    </span>
                                </div>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if(($type == 'trending' ? $trendingPosts->isEmpty() : $latestPosts->isEmpty()))
            <div class="text-center py-5">
                <i class="bi bi-compass display-1 text-muted mb-3"></i>
                <h5 class="text-muted mb-3">No posts to explore yet</h5>
                <p class="text-muted">Be the first to share something amazing!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Create Post
                </a>
            </div>
            @endif
        </div>
        @endif

        @if($type == 'developers')
        <!-- Popular Developers -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-people text-primary me-2"></i>Popular Developers
                </h5>
                <a href="{{ route('explore') }}?type=trending" class="text-decoration-none small">
                    View trending posts <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
                @foreach($popularDevelopers as $user)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <a href="{{ route('profile.show', $user->profile->username) }}"
                                class="text-decoration-none">
                                <div class="position-relative mx-auto mb-3" style="width: 80px;">
                                    <img src="{{ $user->profile->avatar_url }}" class="rounded-circle border border-2"
                                        style="width: 80px; height: 80px; object-fit: cover; border-color: #dbdbdb !important;">
                                    @if($user->followers_count > 100)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge bg-warning border border-2 border-white rounded-circle p-0"
                                        style="width: 20px; height: 20px;">
                                        <i class="bi bi-star-fill text-white" style="font-size: 10px;"></i>
                                    </span>
                                    @endif
                                </div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $user->profile->username }}</h6>
                                <small class="text-muted d-block mb-2">{{ $user->name }}</small>

                                <div class="d-flex justify-content-center gap-3 small mb-3">
                                    <div>
                                        <div class="fw-bold">{{ $user->posts_count ?? 0 }}</div>
                                        <small class="text-muted">Posts</small>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->followers_count ?? 0 }}</div>
                                        <small class="text-muted">Followers</small>
                                    </div>
                                </div>

                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.follow', $user) }}" method="POST" class="follow-form">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        @if(auth()->user()->isFollowing($user))
                                        Following
                                        @else
                                        <i class="bi bi-person-plus me-1"></i> Follow
                                        @endif
                                    </button>
                                </form>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($type == 'tags')
        <!-- Popular Tags & Topics -->
        <div class="row">
            <!-- Tech Topics -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-code-slash text-primary me-2"></i>Popular Tech Topics
                        </h5>

                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                            @foreach($techTopics as $topic)
                            <div class="col">
                                <a href="{{ route('tags.show', strtolower($topic['name'])) }}"
                                    class="text-decoration-none">
                                    <div class="card border-0 shadow-sm h-100"
                                        style="border-left: 4px solid {{ $topic['color'] }} !important;">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="bi {{ $topic['icon'] }} fs-2"
                                                    style="color: {{ $topic['color'] }};"></i>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $topic['name'] }}</h6>
                                            <small class="text-muted">{{ $topic['count'] }} posts</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Tags -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-tags text-primary me-2"></i>All Topics
                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                            <a href="{{ route('tags.show', $tag->name) }}"
                                class="badge bg-light text-dark text-decoration-none border px-3 py-2">
                                #{{ $tag->name }}
                                <span class="badge bg-secondary rounded-pill ms-1">{{ $tag->posts_count }}</span>
                            </a>
                            @endforeach
                        </div>

                        @if($popularTags->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-tag display-1 text-muted mb-3"></i>
                            <p class="text-muted">No topics yet</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize highlight.js for code snippets
        if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }

        // Follow form submission
        document.querySelectorAll('.follow-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const button = this.querySelector('button');
                const originalText = button.textContent;

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        if (data.following) {
                            button.textContent = 'Following';
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-outline-secondary');
                        } else {
                            button.textContent = 'Follow';
                            button.classList.remove('btn-outline-secondary');
                            button.classList.add('btn-primary');
                        }

                        // Animation
                        button.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            button.style.transform = 'scale(1)';
                        }, 200);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    button.textContent = originalText;
                }
            });
        });

        // Card hover effects
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                this.style.transition = 'all 0.2s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
            });
        });
    });
</script>
@endpush

<style>
    /* Explore specific styles */
    .nav-pills .nav-link {
        border-radius: 0;
        border-bottom: 3px solid transparent;
        color: #666;
        padding: 12px 0;
        margin: 0 15px;
    }

    .nav-pills .nav-link.active {
        background-color: transparent;
        color: #0095f6;
        border-bottom-color: #0095f6;
        font-weight: 600;
    }

    .nav-pills .nav-link:hover:not(.active) {
        color: #0095f6;
        border-bottom-color: #dee2e6;
    }

    /* Card styling */
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Badge styling */
    .badge.bg-light {
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Topic cards */
    .topic-card {
        border-left: 4px solid;
        transition: all 0.2s;
    }

    .topic-card:hover {
        transform: translateX(5px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .nav-pills .nav-link {
            margin: 0 8px;
            font-size: 14px;
        }

        .card-body {
            padding: 1rem !important;
        }
    }

    /* Video placeholder */
    .video-placeholder {
        background: linear-gradient(45deg, #667eea, #764ba2);
    }

    /* Code snippet styling */
    pre {
        background: #1e1e1e !important;
        color: #d4d4d4;
        padding: 1rem;
        border-radius: 4px;
        overflow-x: auto;
        font-size: 12px;
        margin: 0;
    }

    code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>
@endsection