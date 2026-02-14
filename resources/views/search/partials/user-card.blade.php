{{-- resources/views/search/partials/user-card.blade.php --}}
<div class="d-flex align-items-center p-3 border-bottom">
    <a href="{{ route('profile.show', $user->profile->username) }}" class="text-decoration-none">
        <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="60"
            height="60" style="object-fit: cover;">
    </a>
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('profile.show', $user->profile->username) }}"
                    class="text-decoration-none text-dark fw-bold d-block">
                    {{ $user->profile->username }}
                    @if($user->profile->is_verified ?? false)
                    <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size: 14px;"></i>
                    @endif
                </a>
                <small class="text-muted d-block">{{ $user->name }}</small>
                @if($user->profile->title)
                <small class="text-muted">{{ $user->profile->title }}</small>
                @endif
            </div>
            <div class="d-flex gap-3 text-muted small">
                <span><i class="bi bi-people"></i> {{ $user->followers_count ?? $user->followers()->count() }}</span>
                <span><i class="bi bi-file-text"></i> {{ $user->posts_count ?? $user->posts()->count() }}</span>
            </div>
        </div>

        @if($user->profile->bio)
        <p class="small text-muted mt-2 mb-0">{{ Str::limit($user->profile->bio, 100) }}</p>
        @endif

        @if($user->profile->tech_stack)
        <div class="mt-2">
            @foreach(explode(',', $user->profile->tech_stack) as $tech)
            <span class="badge bg-light text-dark me-1">#{{ trim($tech) }}</span>
            @endforeach
        </div>
        @endif
    </div>
    @if(auth()->check() && auth()->id() !== $user->id)
    <div class="ms-3">
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
    </div>
    @endif
</div>