@extends('layouts.app')

@section('title', 'Explore - DevDoko')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <!-- Left Column - Main -->
        <div class="col-lg-8">
            <!-- Pill tab row -->
            <div class="d-flex align-items-center gap-2 overflow-auto pb-1 mb-4 stories-scroll">
                <a href="{{ route('explore') }}" class="btn btn-sm rounded-pill fw-semibold flex-shrink-0 explore-pill active">Explore</a>
                @foreach($techTopics as $topic)
                @if($topic['count'] > 0)
                <a href="{{ route('tags.show', $topic['slug']) }}" class="btn btn-sm rounded-pill flex-shrink-0 explore-pill">
                    {{ $topic['name'] }}
                </a>
                @endif
                @endforeach
            </div>

            @php $heroPost = $trendingPosts->first(); @endphp
            @if($heroPost)
            <a href="{{ route('posts.show', $heroPost) }}" class="text-decoration-none d-block mb-4 explore-hero {{ $heroPost->image_url ? '' : 'explore-hero-noimage' }}"
                @if($heroPost->image_url) style="background-image: linear-gradient(180deg, rgba(0,0,0,0.05) 40%, rgba(0,0,0,0.85) 100%), url('{{ $heroPost->image_url }}');" @endif>
                <div class="explore-hero-inner">
                    <span class="badge explore-hero-badge mb-2"><i class="bi bi-fire me-1"></i>Trending</span>
                    <h3 class="fw-bold text-white mb-1">{{ $heroPost->title ?? Str::limit(strip_tags($heroPost->content ?? ''), 90) }}</h3>
                    <div class="small text-white-50">
                        {{ $heroPost->user->profile->username ?? $heroPost->user->name }} &middot; {{ $heroPost->created_at->diffForHumans() }}
                    </div>
                </div>
            </a>
            @endif

            <!-- Headline rows -->
            @php $headlinePosts = $trendingPosts->skip(1)->count() ? $trendingPosts->skip(1) : $latestPosts; @endphp
            @if($headlinePosts->count())
            <div class="mb-5">
                @foreach($headlinePosts->take(8) as $post)
                <a href="{{ route('posts.show', $post) }}" class="d-flex align-items-center justify-content-between text-decoration-none py-3 explore-headline-row {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="min-width-0 me-3">
                        <div class="fw-bold app-text-primary text-truncate">{{ $post->title ?? Str::limit(strip_tags($post->content ?? ''), 70) }}</div>
                        <div class="small app-text-muted text-truncate">
                            {{ $post->user->profile->username ?? $post->user->name }} &middot; {{ Str::limit(strip_tags($post->content ?? ''), 80) }}
                        </div>
                    </div>
                    @if($post->image_url)
                    <img src="{{ $post->image_url }}" alt="" class="rounded-3 flex-shrink-0" style="width: 64px; height: 64px; object-fit: cover;">
                    @endif
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-compass display-1 app-text-muted mb-3"></i>
                <h5 class="app-text-muted mb-3">No posts to explore yet</h5>
                <p class="app-text-muted">Be the first to share something amazing!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-accent">
                    <i class="bi bi-plus-circle me-2"></i> Create Post
                </a>
            </div>
            @endif

            <!-- Horizontal scroll: popular developers -->
            @if($popularDevelopers->count())
            <div class="mb-5">
                <h6 class="fw-bold mb-3 app-text-primary">Popular Developers</h6>
                <div class="d-flex gap-3 overflow-auto pb-2 stories-scroll">
                    @foreach($popularDevelopers as $dev)
                    <a href="{{ route('profile.show', $dev->profile->username) }}" class="text-decoration-none text-center flex-shrink-0" style="width: 100px;">
                        <img src="{{ $dev->profile->avatar_url }}" alt="{{ $dev->name }}" class="rounded-circle mb-2" style="width: 72px; height: 72px; object-fit: cover;">
                        <div class="small fw-semibold app-text-primary text-truncate">{{ $dev->profile->username }}</div>
                        <div class="small app-text-muted">{{ number_format($dev->followers_count ?? 0) }} followers</div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4 d-none d-lg-block">
            @include('partials.right-sidebar', [
                'rightSidebarFollowing' => $rightSidebarFollowing,
                'rightSidebarHeading' => 'New & Trending',
                'rightSidebarPosts' => $latestPosts->take(5),
                'rightSidebarSeeAllUrl' => route('feed.latest'),
            ])
        </div>
    </div>
</div>

<style>
    .explore-pill {
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: var(--bs-body-color);
        white-space: nowrap;
        background: transparent;
    }

    [data-bs-theme="dark"] .explore-pill {
        border-color: rgba(255, 255, 255, 0.1);
    }

    .explore-pill.active,
    .explore-pill:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .explore-hero {
        position: relative;
        min-height: 320px;
        border-radius: 16px;
        overflow: hidden;
        background-color: #1a1a1a;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: flex-end;
    }

    .explore-hero-noimage {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
    }

    .explore-hero-inner {
        padding: 24px;
        width: 100%;
    }

    .explore-hero-badge {
        background: var(--accent);
        color: #fff;
        font-weight: 600;
    }

    .explore-headline-row {
        border-color: rgba(0, 0, 0, 0.08) !important;
    }

    [data-bs-theme="dark"] .explore-headline-row {
        border-color: rgba(255, 255, 255, 0.06) !important;
    }

    .explore-headline-row:hover .app-text-primary {
        color: var(--accent) !important;
    }
</style>
@endsection
