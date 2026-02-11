@extends('layouts.app', ['hideSidebar' => true])

@section('title', '@' . $post->user->profile->username . ' - ' . Str::limit($post->title, 50) . ' | DevDoko')

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
            class="text-decoration-none text-dark d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back</span>
        </a>

        <!-- Post Actions -->
        <div class="d-flex gap-2">
            @auth
            @if(auth()->id() === $post->user_id)
            <a href="{{ route('posts.edit', $post) }}"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-pencil"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </a>

            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1"
                    onclick="confirmDelete(this)">
                    <i class="bi bi-trash"></i>
                    <span class="d-none d-sm-inline">Delete</span>
                </button>
            </form>
            @endif
            @endauth
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Post -->
        <div class="col-lg-8">
            <!-- Post Card -->
            <div class="card border-0">
                @include('posts.partials.card', [
                'post' => $post,
                'fullView' => true,
                'showActions' => true
                ])

            </div>

            <!-- Author Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <a href="{{ route('profile.show', $post->user->profile->username) }}">
                            <img src="{{ $post->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&size=64' }}"
                                alt="{{ $post->user->name }}" class="rounded-circle border"
                                style="width: 64px; height: 64px; object-fit: cover;" loading="lazy">
                        </a>

                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <a href="{{ route('profile.show', $post->user->profile->username) }}"
                                        class="text-decoration-none text-dark fw-semibold fs-5">
                                        {{ $post->user->profile->username }}
                                    </a>
                                    <div class="d-flex gap-3 mt-1">
                                        <small class="text-muted">
                                            <i class="bi bi-file-text me-1"></i>
                                            {{ $post->user->posts_count ?? $post->user->posts()->count() }} posts
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-people me-1"></i>
                                            {{ $post->user->followers_count ?? 0 }} followers
                                        </small>
                                    </div>
                                </div>
                            </div>

                            @if($post->user->profile->bio)
                            <p class="mt-3 mb-0 text-secondary small">
                                {{ $post->user->profile->bio }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Comments & Related -->
        <div class="col-lg-4">
            <!-- Comments Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-chat-text me-2"></i>
                            Comments
                        </h5>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ $post->comments->count() }}
                        </span>
                    </div>
                </div>

                <!-- Comments List -->
                <div class="card-body p-0">
                    <div style="max-height: 500px; overflow-y: auto;">
                        @forelse($post->comments as $comment)
                        <div class="p-3 border-bottom">
                            <div class="d-flex gap-2">
                                <a href="{{ route('profile.show', $comment->user->profile->username) }}">
                                    <img src="{{ $comment->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&size=32' }}"
                                        alt="{{ $comment->user->name }}" class="rounded-circle"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                </a>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <a href="{{ route('profile.show', $comment->user->profile->username) }}"
                                                class="text-decoration-none text-dark fw-semibold small">
                                                {{ $comment->user->profile->username }}
                                            </a>
                                            <span class="text-secondary small ms-2">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        @if(auth()->id() === $comment->user_id)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0 small"
                                                onclick="return confirm('Delete this comment?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    <p class="mb-0 small mt-1">
                                        {{ $comment->content }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 px-3">
                            <i class="bi bi-chat-square text-muted" style="font-size: 48px;"></i>
                            <p class="fw-semibold mb-1 mt-3">No comments yet</p>
                            <small class="text-muted">Be the first to share your thoughts!</small>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Add Comment Form -->
                @auth
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                    <form action="{{ route('comments.store', $post) }}" method="POST" id="commentForm">
                        @csrf
                        <div class="d-flex gap-2 align-items-start">
                            <img src="{{ auth()->user()->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=36' }}"
                                alt="{{ auth()->user()->name }}" class="rounded-circle"
                                style="width: 36px; height: 36px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="input-group">
                                    <input type="text" name="content"
                                        class="form-control form-control-sm bg-light border-0"
                                        placeholder="Write a comment..." id="commentInput" maxlength="500"
                                        style="border-radius: 20px; padding: 10px 16px;">
                                    <button type="submit" class="btn btn-primary btn-sm ms-2" id="commentSubmit"
                                        disabled style="border-radius: 20px; padding: 8px 20px;">
                                        Post
                                    </button>
                                </div>
                                <small class="text-muted d-block text-end mt-1" id="charCount">0/500</small>
                            </div>
                        </div>
                    </form>
                </div>
                @else
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4"
                        style="border-radius: 20px;">
                        Log in to comment
                    </a>
                </div>
                @endauth
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                        More from {{ $post->user->profile->username }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-1">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-6">
                            <a href="{{ route('posts.show', $relatedPost) }}"
                                class="text-decoration-none d-block position-relative">
                                <div style="aspect-ratio: 1; background-color: #f8f9fa;">
                                    @if($relatedPost->type === 'image' && $relatedPost->media->count())
                                    <img src="{{ asset('storage/' . ($relatedPost->media->first()->thumbnail_url ?? $relatedPost->media->first()->file_path)) }}"
                                        alt="Post" class="w-100 h-100" style="object-fit: cover;" loading="lazy">
                                    @elseif($relatedPost->type === 'code')
                                    <div
                                        class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-white">
                                        <i class="bi bi-code-slash" style="font-size: 24px;"></i>
                                    </div>
                                    @else
                                    <div
                                        class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-secondary">
                                        <i class="bi bi-file-text" style="font-size: 24px;"></i>
                                    </div>
                                    @endif

                                    @if($relatedPost->media->count() > 1)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">
                                        <i class="bi bi-collection"></i>
                                    </span>
                                    @endif

                                    <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white"
                                        style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-around small">
                                            <span><i class="bi bi-heart-fill me-1"></i>{{ $relatedPost->likes_count ?? 0
                                                }}</span>
                                            <span><i class="bi bi-chat-fill me-1"></i>{{ $relatedPost->comments_count ??
                                                0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Tags Section -->
            @if($post->tags->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-tags me-2"></i>
                        Tags
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}"
                            class="text-decoration-none px-3 py-2 bg-light rounded-pill text-dark small">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-share me-2"></i>
                    Share Post
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0" value="{{ url()->current() }}" readonly
                        id="shareLink">
                    <button class="btn btn-primary" type="button" onclick="copyShareLink()">
                        <i class="bi bi-clipboard me-1"></i>
                        Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Comment form validation
    const commentInput = document.getElementById('commentInput');
    const commentSubmit = document.getElementById('commentSubmit');
    const charCount = document.getElementById('charCount');

    if (commentInput) {
        commentInput.addEventListener('input', function() {
            const length = this.value.trim().length;
            charCount.textContent = `${length}/500`;
            commentSubmit.disabled = length === 0 || length > 500;
            charCount.style.color = length > 450 ? '#dc3545' : '#6c757d';
        });
    }
});

// Share functionality
function sharePost() {
    const shareData = {
        title: document.title,
        text: 'Check out this post on DevDoko',
        url: window.location.href
    };

    if (navigator.share && navigator.canShare(shareData)) {
        navigator.share(shareData).catch(console.error);
    } else {
        const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
        shareModal.show();
    }
}

function copyShareLink() {
    const shareLink = document.getElementById('shareLink');
    shareLink.select();
    shareLink.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(shareLink.value).then(() => {
        const toast = new bootstrap.Toast(document.createElement('div'));
        toast.show();
        alert('Link copied to clipboard!');
    }).catch(() => {
        alert('Failed to copy link');
    });
}

function confirmDelete(button) {
    if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        button.closest('form').submit();
    }
}

// Handle comment form submission
document.getElementById('commentForm')?.addEventListener('submit', function(e) {
    const input = document.getElementById('commentInput');
    if (!input.value.trim()) {
        e.preventDefault();
        input.focus();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const commentForm = document.getElementById('commentForm');
        const commentInput = document.getElementById('commentInput');
        if (commentForm && document.activeElement === commentInput) {
            commentForm.submit();
        }
    }
});
</script>