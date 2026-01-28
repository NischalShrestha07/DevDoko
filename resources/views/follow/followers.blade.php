{{-- resources/views/follow/followers.blade.php --}}
@extends('layouts.app')

@section('title', '@' . $user->profile->username . ' Followers - DevDoko')

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
                        <small class="text-muted">Followers</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @forelse($followers as $follower)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <a href="{{ route('profile.show', $follower->profile->username) }}" class="text-decoration-none">
                        <img src="{{ $follower->profile->avatar_url }}" alt="{{ $follower->name }}"
                            class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                    </a>
                    <div class="flex-grow-1">
                        <a href="{{ route('profile.show', $follower->profile->username) }}"
                            class="text-decoration-none text-dark fw-bold d-block">
                            {{ $follower->profile->username }}
                        </a>
                        <small class="text-muted">{{ $follower->name }}</small>
                    </div>
                    @if(auth()->id() !== $follower->id)
                    @if(auth()->user()->isFollowing($follower))
                    <form action="{{ route('users.unfollow', $follower) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            Following
                        </button>
                    </form>
                    @else
                    <form action="{{ route('users.follow', $follower) }}" method="POST">
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
                    <i class="bi bi-people display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No followers yet</h5>
                    <p class="text-muted">
                        @if(auth()->id() === $user->id)
                        Share your work to get followers!
                        @else
                        Be the first to follow
                        @endif
                    </p>
                </div>
                @endforelse

                @if($followers->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $followers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection