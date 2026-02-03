@extends('layouts.app')

@section('title', '@' . $user->profile->username . ' Following - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <a href="{{ route('profile.show', $user->profile->username) }}"
                        class="text-decoration-none text-dark me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $user->profile->username }}</h5>
                        <small class="text-muted">Following</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @forelse($following as $followedUser)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <a href="{{ route('profile.show', $followedUser->profile->username) }}"
                        class="text-decoration-none">
                        <img src="{{ $followedUser->profile->avatar_url }}" alt="{{ $followedUser->name }}"
                            class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                    </a>
                    <div class="flex-grow-1">
                        <a href="{{ route('profile.show', $followedUser->profile->username) }}"
                            class="text-decoration-none text-dark fw-bold d-block">
                            {{ $followedUser->profile->username }}
                        </a>
                        <small class="text-muted">{{ $followedUser->name }}</small>
                    </div>
                    @if(auth()->id() === $user->id)
                    <form action="{{ route('users.unfollow', $followedUser) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            Unfollow
                        </button>
                    </form>
                    @elseif(auth()->id() !== $followedUser->id)
                    @if(auth()->user()->isFollowing($followedUser))
                    <form action="{{ route('users.unfollow', $followedUser) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            Following
                        </button>
                    </form>
                    @else
                    <form action="{{ route('users.follow', $followedUser) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            Follow
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-person-plus display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Not following anyone yet</h5>
                    <p class="text-muted">
                        @if(auth()->id() === $user->id)
                        Discover and follow other developers!
                        @else
                        This user isn't following anyone yet
                        @endif
                    </p>
                    @if(auth()->id() === $user->id)
                    <a href="{{ route('explore') }}" class="btn btn-primary">
                        Explore Developers
                    </a>
                    @endif
                </div>
                @endforelse

                @if($following->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $following->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection