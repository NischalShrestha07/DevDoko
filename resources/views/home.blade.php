@extends('layouts.app')

@section('title', 'Home - DevDoko')

@section('content')
<div class="container-fluid px-0">
    <!-- Stories Section -->
    {{-- <div class="bg-white border-bottom py-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <div class="d-flex px-3" style="gap: 16px;">
            <!-- My Story -->
            <div class="text-center" style="width: 80px; flex-shrink: 0;">
                <div class="position-relative mx-auto mb-2">
                    <div class="rounded-circle border border-3 border-primary"
                        style="width: 64px; height: 64px; padding: 3px;">
                        <img src="{{ auth()->user()->profile?->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle w-100 h-100" style="object-fit: cover;">
                    </div>
                    <button
                        class="btn btn-primary btn-sm p-0 rounded-circle position-absolute bottom-0 end-0 border border-2 border-white"
                        style="width: 20px; height: 20px;">
                        <i class="bi bi-plus" style="font-size: 10px;"></i>
                    </button>
                </div>
                <small class="text-truncate d-block" style="font-size: 11px; max-width: 80px;">Your Story</small>
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="container py-3">
        <div class="row justify-content-center">
            <!-- Left Column - Main Feed -->
            <div class="col-lg-8">
                <!-- Create Post Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ auth()->user()->profile?->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="input-group">
                                    <input type="text" class="form-control border-0 bg-light"
                                        placeholder="What's on your mind, {{ auth()->user()->name }}?"
                                        style="border-radius: 20px;"
                                        onclick="location.href='{{ route('posts.create') }}'">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=image'">
                                <i class="bi bi-image-fill text-success fs-5 me-2"></i>
                                <span>Photo</span>
                            </button>
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=video'">
                                <i class="bi bi-camera-reels-fill text-danger fs-5 me-2"></i>
                                <span>Video</span>
                            </button>
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=code'">
                                <i class="bi bi-code-slash text-info fs-5 me-2"></i>
                                <span>Code</span>
                            </button>
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=article'">
                                <i class="bi bi-file-text-fill text-warning fs-5 me-2"></i>
                                <span>Article</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Feed Tabs -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-2">
                        <ul class="nav nav-pills nav-fill" style="gap: 2px;">
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == '' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('home') }}">
                                    <i class="bi bi-lightning me-1"></i> For You
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == 'following' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('feed.following') }}">
                                    <i class="bi bi-people me-1"></i> Following
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == 'popular' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('feed.popular') }}">
                                    <i class="bi bi-fire me-1"></i> Popular
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == 'latest' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('feed.latest') }}">
                                    <i class="bi bi-clock me-1"></i> Latest
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Posts Feed -->
                @forelse($posts as $post)
                    @include('posts.partials.card', ['post' => $post])
                @empty
                <div class="card border-0 shadow-sm text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">Your feed is empty</h4>
                        <p class="text-muted mb-4">
                            Follow other developers or create your first post to get started!
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('explore') }}" class="btn btn-primary">
                                <i class="bi bi-compass me-2"></i> Explore
                            </a>
                            <a href="{{ route('posts.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i> Create Post
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4 d-none d-lg-block">
                <!-- Current User Profile -->
                <div class="card mb-4 border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ auth()->user()->profile?->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="rounded-circle me-3" style="width: 56px; height: 56px; object-fit: cover;">
                            <div>
                                <a href="{{ route('profile.show', auth()->user()->profile?->username) }}"
                                    class="text-decoration-none text-dark fw-bold d-block">
                                    {{ auth()->user()->profile?->username }}
                                </a>
                                <small class="text-muted">{{ auth()->user()->name }}</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-around text-center border-top border-bottom py-3">
                            <div>
                                <div class="fw-bold">{{ auth()->user()->posts()->count() }}</div>
                                <small class="text-muted">Posts</small>
                            </div>
                            <div>
                                <div class="fw-bold">{{ auth()->user()->followers()->count() }}</div>
                                <small class="text-muted">Followers</small>
                            </div>
                            <div>
                                <div class="fw-bold">{{ auth()->user()->following()->count() }}</div>
                                <small class="text-muted">Following</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-pencil me-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Suggested Developers -->
                @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Suggested Developers</h6>
                            <a href="{{ route('explore') }}" class="text-decoration-none small">See All</a>
                        </div>

                        @foreach($suggestedUsers as $user)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $user->profile?->avatar_url }}" alt="{{ $user->name }}"
                                class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <a href="{{ route('profile.show', $user->profile?->username ?? $user->id) }}"
                                    class="text-decoration-none fw-bold text-dark d-block">
                                    {{ $user->profile?->username }}
                                </a>
                                <small class="text-muted">{{ $user->followers->count() ?? 0 }} followers</small>
                            </div>
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="follow-form">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" style="font-size: 12px;">
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
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Trending Topics</h6>
                            <a href="{{ route('tech.trending') }}" class="text-decoration-none small">See All</a>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($trendingTags as $tag)
                            <a href="{{ route('tags.show', $tag->slug) }}"
                                class="badge bg-light text-dark text-decoration-none border">
                                #{{ $tag->name }}
                                <span class="badge bg-secondary rounded-pill ms-1">{{ $tag->posts_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Platform Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">About DevDoko</h6>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a href="{{route('posts.index')}}" class="text-decoration-none small text-muted">Posts</a>
                            <a href="{{route('messages.index')}}"
                                class="text-decoration-none small text-muted">Messages</a>
                            <a href="{{route('notifications.index')}}"
                                class="text-decoration-none small text-muted">Notifications</a>
                            <a href="{{route('search')}}" class="text-decoration-none small text-muted">Search</a>
                            <a href="#" class="text-decoration-none small text-muted">Terms</a>
                        </div>
                        <div class="text-muted small">
                            © {{ date('Y') }} DevDoko
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
