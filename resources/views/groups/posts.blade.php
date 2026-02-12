{{-- resources/views/groups/post.blade.php --}}
@extends('layouts.app')

@section('title', $post->title . ' - ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Back to Group -->
            <div class="mb-3">
                <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to {{ $group->name }}
                </a>
            </div>

            <!-- Post Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    @include('groups.partials.post-card', ['post' => $post, 'fullView' => true])

                    <!-- Post Content (Full) -->
                    <div class="mt-4">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    @if($post->attachments)
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-2">Attachments</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($post->attachments as $attachment)
                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-paperclip"></i> {{ $attachment['name'] }}
                                <span class="text-muted ms-1">({{ round($attachment['size'] / 1024) }} KB)</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-chat-text me-2"></i>
                        Comments ({{ $post->comments->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Add Comment -->
                    @if($group->is_member)
                    <form action="{{ route('groups.posts.comments.store', [$group->slug, $post->id]) }}" method="POST"
                        class="mb-4">
                        @csrf
                        <div class="d-flex gap-2">
                            <img src="{{ auth()->user()->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <textarea name="content" class="form-control" rows="2" placeholder="Write a comment..."
                                    required></textarea>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-send"></i> Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <a href="{{ route('groups.join', $group->slug) }}" class="alert-link">Join the group</a> to
                        comment.
                    </div>
                    @endif

                    <!-- Comments List -->
                    @forelse($post->comments as $comment)
                    <div class="d-flex gap-2 mb-3">
                        <img src="{{ $comment->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                            class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="bg-light rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <a href="{{ route('profile.show', $comment->user->profile->username ?? $comment->user->name) }}"
                                            class="text-decoration-none fw-semibold">
                                            {{ $comment->user->profile->username ?? $comment->user->name }}
                                        </a>
                                        <span class="text-muted small ms-2">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if(auth()->id() === $comment->user_id || $group->canManage(auth()->user()))
                                    <form action="{{ route('groups.comments.destroy', [$group->slug, $comment->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 small"
                                            onclick="return confirm('Delete this comment?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                <p class="mb-1">{{ $comment->content }}</p>
                                <div class="d-flex gap-3 small">
                                    <form action="{{ route('groups.comments.like', [$group->slug, $comment->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none">
                                            <i
                                                class="bi bi-heart{{ $comment->is_liked ? '-fill text-danger' : '' }}"></i>
                                            <span class="ms-1">{{ $comment->likes_count }}</span>
                                        </button>
                                    </form>
                                    <button class="btn btn-link text-dark p-0 text-decoration-none reply-toggle"
                                        data-comment-id="{{ $comment->id }}">
                                        <i class="bi bi-reply"></i> Reply
                                    </button>
                                </div>
                            </div>

                            <!-- Reply Form -->
                            <div id="reply-form-{{ $comment->id }}" class="mt-2 ms-4" style="display: none;">
                                <form action="{{ route('groups.posts.comments.store', [$group->slug, $post->id]) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div class="d-flex gap-2">
                                        <img src="{{ auth()->user()->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                            class="rounded-circle"
                                            style="width: 28px; height: 28px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <input type="text" name="content" class="form-control form-control-sm"
                                                placeholder="Write a reply...">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                            <div class="mt-2 ms-4">
                                @foreach($comment->replies as $reply)
                                <div class="d-flex gap-2 mb-2">
                                    <img src="{{ $reply->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}"
                                        class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <div class="bg-light rounded-3 p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <a href="{{ route('profile.show', $reply->user->profile->username ?? $reply->user->name) }}"
                                                        class="text-decoration-none fw-semibold small">
                                                        {{ $reply->user->profile->username ?? $reply->user->name }}
                                                    </a>
                                                    <span class="text-muted small ms-2">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                @if(auth()->id() === $reply->user_id ||
                                                $group->canManage(auth()->user()))
                                                <form
                                                    action="{{ route('groups.comments.destroy', [$group->slug, $reply->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 small">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                            <p class="mb-0 small">{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-chat fs-1 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">No comments yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle reply forms
document.querySelectorAll('.reply-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
    });
});
</script>
@endsection