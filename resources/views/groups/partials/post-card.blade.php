{{-- resources/views/groups/partials/post-card.blade.php --}}
<div class="d-flex">
    <div class="flex-shrink-0 me-3">
        <a href="{{ route('profile.show', $post->user->profile->username ?? $post->user->name) }}">
            <img src="{{ $post->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
        </a>
    </div>
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <a href="{{ route('profile.show', $post->user->profile->username ?? $post->user->name) }}"
                    class="text-decoration-none text-dark fw-semibold">
                    {{ $post->user->profile->username ?? $post->user->name }}
                </a>
                <span class="text-muted mx-2">·</span>
                <span class="text-muted small">{{ $post->formatted_date }}</span>
                @if($post->is_pinned)
                <span class="badge bg-warning bg-opacity-10 text-warning ms-2">
                    <i class="bi bi-pin-angle-fill"></i> Pinned
                </span>
                @endif
                @if($post->type === 'announcement')
                <span class="badge bg-primary ms-2">📢 Announcement</span>
                @endif
            </div>
        </div>

        <h6 class="fw-semibold mb-2">
            <a href="{{ route('groups.post', [$post->group->slug, $post->id]) }}"
                class="text-dark text-decoration-none">
                {{ $post->title }}
            </a>
        </h6>

        <p class="text-muted mb-2">{{ Str::limit($post->content, 200) }}</p>

        @if($post->attachments)
        <div class="mb-2">
            @foreach($post->attachments as $attachment)
            <span class="badge bg-light text-dark me-1">
                <i class="bi bi-paperclip"></i> {{ $attachment['name'] }}
            </span>
            @endforeach
        </div>
        @endif

        <div class="d-flex gap-3">
            <form action="{{ route('groups.posts.like', [$post->group->slug, $post->id]) }}" method="POST"
                class="like-form">
                @csrf
                <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                    <i class="bi bi-heart{{ $post->is_liked ? '-fill text-danger' : '' }}"></i>
                    <span class="ms-1">{{ $post->likes_count }}</span>
                </button>
            </form>

            <a href="{{ route('groups.post', [$post->group->slug, $post->id]) }}#comments"
                class="btn btn-link text-dark p-0 text-decoration-none small">
                <i class="bi bi-chat"></i>
                <span class="ms-1">{{ $post->comments_count }}</span>
            </a>

            @if($post->group->canManage(auth()->user()) && !$post->is_pinned)
            <form action="{{ route('groups.posts.pin', [$post->group->slug, $post->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                    <i class="bi bi-pin-angle"></i> Pin
                </button>
            </form>
            @endif

            @if($post->group->canManage(auth()->user()) && $post->is_pinned)
            <form action="{{ route('groups.posts.unpin', [$post->group->slug, $post->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                    <i class="bi bi-pin-angle"></i> Unpin
                </button>
            </form>
            @endif
        </div>
    </div>
</div>