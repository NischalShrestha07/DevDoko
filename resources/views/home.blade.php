@extends('layouts.app')

@section('title', 'Home - DevDoko')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <!-- Left Column - Main Feed -->
        <div class="col-lg-8">
            <!-- Create Post Card -->
            <div class="card border-0 shadow-sm mb-4 home-create-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover;">
                        <a href="{{ route('posts.create') }}" data-composer-open="text"
                            class="flex-grow-1 text-decoration-none home-post-input rounded-pill px-4 py-2">
                            What's on your mind, {{ auth()->user()->profile->username ?? auth()->user()->name }}?
                        </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="{{ route('posts.create') }}?type=image" data-composer-open="image"
                            class="btn btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 home-action-btn text-decoration-none">
                            <i class="bi bi-image-fill" style="color: #22c55e;"></i>
                            <span class="d-none d-sm-inline">Photo</span>
                        </a>
                        <a href="{{ route('posts.create') }}?type=video" data-composer-open="video"
                            class="btn btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 home-action-btn text-decoration-none">
                            <i class="bi bi-camera-reels-fill" style="color: #ef4444;"></i>
                            <span class="d-none d-sm-inline">Video</span>
                        </a>
                        <a href="{{ route('posts.create') }}?type=code" data-composer-open="code"
                            class="btn btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 home-action-btn text-decoration-none">
                            <i class="bi bi-code-slash" style="color: #06b6d4;"></i>
                            <span class="d-none d-sm-inline">Code</span>
                        </a>
                        <a href="{{ route('posts.create') }}?type=article" data-composer-open="article"
                            class="btn btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 home-action-btn text-decoration-none">
                            <i class="bi bi-file-text-fill" style="color: #f59e0b;"></i>
                            <span class="d-none d-sm-inline">Article</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feed Tabs -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-2">
                    <ul class="nav nav-pills gap-1" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <a class="nav-link rounded-pill text-center {{ ($activeTab ?? '') == '' ? 'active' : '' }}"
                                href="{{ route('home') }}" role="tab">
                                <i class="bi bi-lightning-fill me-1"></i>For You
                            </a>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <a class="nav-link rounded-pill text-center {{ ($activeTab ?? '') == 'following' ? 'active' : '' }}"
                                href="{{ route('feed.following') }}" role="tab">
                                <i class="bi bi-people-fill me-1"></i>Following
                            </a>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <a class="nav-link rounded-pill text-center {{ ($activeTab ?? '') == 'popular' ? 'active' : '' }}"
                                href="{{ route('feed.popular') }}" role="tab">
                                <i class="bi bi-fire me-1"></i>Popular
                            </a>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <a class="nav-link rounded-pill text-center {{ ($activeTab ?? '') == 'latest' ? 'active' : '' }}"
                                href="{{ route('feed.latest') }}" role="tab">
                                <i class="bi bi-clock-fill me-1"></i>Latest
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Posts Feed -->
            <div id="feedItems">
                @forelse($posts as $post)
                    @include('posts.partials.card', ['post' => $post])
                @empty
            <div class="card border-0 shadow-sm text-center py-5 mb-4 home-empty-card">
                <div class="card-body px-4">
                    <div class="empty-feed-icon mx-auto mb-4">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Your feed is empty</h5>
                    <p class="app-text-muted mb-4 mx-auto" style="max-width: 320px;">
                        Follow other developers or share your first post to get your feed started.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('explore') }}" class="btn btn-primary px-4 rounded-pill">
                            <i class="bi bi-compass me-1"></i> Explore
                        </a>
                        <a href="{{ route('posts.create') }}" class="btn btn-outline-primary px-4 rounded-pill">
                            <i class="bi bi-plus-circle me-1"></i> Create Post
                        </a>
                    </div>
                </div>
            </div>
                @endforelse
            </div>

            {{-- Infinite scroll sentinel. Falls back to a plain link if JS is off. --}}
            @if($posts->hasMorePages())
                <div id="feedSentinel" data-next-page="2" class="py-4 text-center">
                    <div class="spinner-border spinner-border-sm app-text-muted d-none" id="feedSpinner" role="status">
                        <span class="visually-hidden">Loading more posts…</span>
                    </div>
                    <noscript>
                        <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-primary rounded-pill px-4">Load more posts</a>
                    </noscript>
                </div>
            @endif

            <div id="feedEnd" class="text-center app-text-muted small py-4 d-none">
                <i class="bi bi-check2-circle me-1"></i>You're all caught up
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4 d-none d-lg-block">
            <!-- Current User Profile -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden home-profile-card">
                <div class="home-profile-banner"></div>
                <div class="card-body text-center pt-0 pb-3">
                    <div class="home-profile-avatar mx-auto mb-2">
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle" style="width: 72px; height: 72px; object-fit: cover; border: 3px solid var(--card-bg, #fff);">
                    </div>
                    <a href="{{ route('profile.show', auth()->user()->profile->username ?? '') }}"
                        class="text-decoration-none fw-bold d-block fs-6 app-text-primary mb-0">
                        {{ auth()->user()->name }}
                    </a>
                    <small class="app-text-muted d-block mb-3">{{ '@' . (auth()->user()->profile->username ?? '') }}</small>
                    <div class="d-flex justify-content-around text-center border-top border-bottom py-3 mb-2">
                        <a href="{{ route('profile.show', auth()->user()->profile->username ?? '') }}"
                            class="text-decoration-none">
                            <div class="fw-bold app-text-primary">{{ auth()->user()->posts()->count() }}</div>
                            <small class="app-text-muted">Posts</small>
                        </a>
                        <div class="border-start border-end px-3">
                            <div class="fw-bold app-text-primary">{{ auth()->user()->followers()->count() }}</div>
                            <small class="app-text-muted">Followers</small>
                        </div>
                        <a href="{{ route('profile.show', auth()->user()->profile->username ?? '') }}"
                            class="text-decoration-none">
                            <div class="fw-bold app-text-primary">{{ auth()->user()->following()->count() }}</div>
                            <small class="app-text-muted">Following</small>
                        </a>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill mt-1">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Strength -->
            @php $completeness = auth()->user()->profile->completeness; @endphp
            @if($completeness < 100)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="position-relative flex-shrink-0" style="width: 44px; height: 44px;">
                            <svg width="44" height="44" viewBox="0 0 44 44">
                                <circle cx="22" cy="22" r="18" fill="none" stroke="#e9ecef" stroke-width="4"></circle>
                                <circle cx="22" cy="22" r="18" fill="none" stroke="var(--brand-purple)" stroke-width="4"
                                    stroke-dasharray="{{ round(2 * pi() * 18) }}"
                                    stroke-dashoffset="{{ round(2 * pi() * 18 * (1 - $completeness / 100)) }}"
                                    stroke-linecap="round" transform="rotate(-90 22 22)"></circle>
                            </svg>
                            <small class="position-absolute top-50 start-50 translate-middle fw-bold" style="font-size: 11px;">{{ $completeness }}%</small>
                        </div>
                        <div class="small app-app-text-muted">
                            Add a bio, avatar, tech tags, and a portfolio link to complete your profile.
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="small fw-semibold text-decoration-none" style="color: var(--brand-purple);">
                        Complete your profile &rarr;
                    </a>
                </div>
            </div>
            @endif

            <!-- Suggested Developers -->
            @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 app-text-primary">Suggested for you</h6>
                        <a href="{{ route('explore') }}" class="text-decoration-none small fw-semibold" style="color: var(--brand-purple);">See all</a>
                    </div>

                    @foreach($suggestedUsers as $user)
                    <div class="d-flex align-items-center {{ !$loop->last ? 'pb-3 mb-3 border-bottom' : '' }}">
                        <a href="{{ route('profile.show', $user->profile->username ?? $user->id) }}"
                            class="text-decoration-none flex-shrink-0 me-3">
                            <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}"
                                class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        </a>
                        <div class="flex-grow-1 min-width-0">
                            <a href="{{ route('profile.show', $user->profile->username ?? $user->id) }}"
                                class="text-decoration-none fw-semibold app-text-primary d-block text-truncate">
                                {{ $user->profile->username ?? '' }}
                            </a>
                            <small class="app-text-muted">{{ $user->followers->count() ?? 0 }} followers</small>
                        </div>
                        <form action="{{ route('users.follow', $user) }}" method="POST" class="follow-form ms-2">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm rounded-pill fw-semibold follow-btn"
                                style="font-size: 12px; padding: 4px 14px;">
                                Follow
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Trending Tags -->
            @if(isset($trendingTags) && $trendingTags->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 app-text-primary">Trending Topics</h6>
                        <a href="{{ route('tech.trending') }}" class="text-decoration-none small fw-semibold" style="color: var(--brand-purple);">See all</a>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($trendingTags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}"
                            class="badge text-decoration-none tag-badge">
                            #{{ $tag->name }}
                            <span class="tag-badge-count">{{ $tag->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Platform Info / Footer -->
            <div class="px-3 pb-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <a href="{{route('posts.index')}}" class="text-decoration-none small app-text-muted">Posts</a>
                    <a href="{{route('messages.index')}}" class="text-decoration-none small app-text-muted">Messages</a>
                    <a href="{{route('notifications.index')}}" class="text-decoration-none small app-text-muted">Notifications</a>
                    <a href="{{route('search')}}" class="text-decoration-none small app-text-muted">Search</a>
                    <a href="#" class="text-decoration-none small app-text-muted">About</a>
                    <a href="#" class="text-decoration-none small app-text-muted">Terms</a>
                </div>
                <small class="app-text-muted">&copy; {{ date('Y') }} DevDoko</small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sentinel = document.getElementById('feedSentinel');
    if (!sentinel) return;

    const items = document.getElementById('feedItems');
    const spinner = document.getElementById('feedSpinner');
    const endMarker = document.getElementById('feedEnd');
    let loading = false;

    async function loadMore() {
        const nextPage = sentinel.dataset.nextPage;
        if (loading || !nextPage) return;
        loading = true;
        spinner.classList.remove('d-none');

        try {
            const res = await fetch(`{{ route('home') }}?page=${nextPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            });
            if (!res.ok) throw new Error('Request failed');
            const data = await res.json();

            // Card partials each carry their own <script>; the handlers are
            // delegated on document and already registered, so strip the
            // duplicates instead of bloating the DOM with them.
            const frag = document.createElement('div');
            frag.innerHTML = data.html;
            frag.querySelectorAll('script').forEach(s => s.remove());
            while (frag.firstChild) items.appendChild(frag.firstChild);

            if (data.next_page) {
                sentinel.dataset.nextPage = data.next_page;
            } else {
                observer.disconnect();
                sentinel.remove();
                endMarker.classList.remove('d-none');
            }
        } catch (e) {
            window.DevDoko?.toast('Could not load more posts.', 'error');
        } finally {
            loading = false;
            spinner.classList.add('d-none');
        }
    }

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) loadMore();
    }, { rootMargin: '400px' });

    observer.observe(sentinel);
});
</script>
@endsection
