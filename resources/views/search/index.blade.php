{{-- resources/views/search/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Search - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Search Box -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('search') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="{{ $query }}"
                            placeholder="Search developers, posts, tags...">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($query)
        <!-- Users Results -->
        @if($users->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Developers</h6>
            </div>
            <div class="card-body">
                @foreach($users as $user)
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('profile.show', $user->profile->username) }}" class="text-decoration-none">
                        <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3"
                            width="50" height="50" style="object-fit: cover;">
                    </a>
                    <div class="flex-grow-1">
                        <a href="{{ route('profile.show', $user->profile->username) }}"
                            class="text-decoration-none text-dark fw-bold d-block">
                            {{ $user->profile->username }}
                        </a>
                        <small class="text-muted">{{ $user->name }}</small>
                    </div>
                    @if(auth()->id() !== $user->id)
                    @if(auth()->user()->isFollowing($user))
                    <form action="{{ route('users.unfollow', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            Following
                        </button>
                    </form>
                    @else
                    <form action="{{ route('users.follow', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            Follow
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
                @endforeach
                {{ $users->links() }}
            </div>
        </div>
        @endif

        <!-- Posts Results -->
        @if($posts->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Posts</h6>
            </div>
            <div class="card-body">
                @foreach($posts as $post)
                @include('posts.partials.card', ['post' => $post])
                @endforeach
                {{ $posts->links() }}
            </div>
        </div>
        @endif

        <!-- Tags Results -->
        @if($tags->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Tags</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                    <a href="{{ route('tags.show', $tag->name) }}" class="btn btn-outline-primary btn-sm">
                        #{{ $tag->name }}
                        <span class="badge bg-light text-dark ms-1">{{ $tag->posts_count }}</span>
                    </a>
                    @endforeach
                </div>
                {{ $tags->links() }}
            </div>
        </div>
        @endif

        <!-- No Results -->
        @if($users->count() === 0 && $posts->count() === 0 && $tags->count() === 0)
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h5 class="text-muted">No results found</h5>
            <p class="text-muted">Try different keywords or explore trending content</p>
            <a href="{{ route('explore') }}" class="btn btn-primary">
                Explore Trending
            </a>
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h5 class="text-muted">Search DevDoko</h5>
            <p class="text-muted mb-4">
                Find developers, posts, and tags
            </p>
        </div>
        @endif
    </div>
</div>
@endsection