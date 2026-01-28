@extends('layouts.app')

@section('title', '@' . $post->user->profile->username . ' on DevDoko')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <!-- Back Button -->
    <div style="padding: 16px 0; margin-bottom: 16px;">
        <a href="{{ url()->previous() }}"
            style="color: var(--text-color); text-decoration: none; font-size: 14px; display: inline-flex; align-items: center;">
            <i class="bi bi-arrow-left" style="margin-right: 8px;"></i> Back
        </a>
    </div>

    <!-- Single Post -->
    @include('posts.partials.card', ['post' => $post, 'fullView' => true])

    <!-- Comments Section -->
    <div class="post-card" style="margin-top: 16px;">
        <div style="padding: 16px; border-bottom: 1px solid var(--border-color);">
            <h6 style="margin: 0; font-weight: 600;">Comments ({{ $post->comments->count() }})</h6>
        </div>

        <!-- Comments List -->
        <div style="max-height: 400px; overflow-y: auto;">
            @forelse($post->comments as $comment)
            <div style="padding: 12px 16px; border-bottom: 1px solid #f0f0f0;">
                <div style="display: flex;">
                    <a href="{{ route('profile.show', $comment->user->profile->username) }}"
                        style="margin-right: 12px;">
                        <img src="{{ $comment->user->profile->avatar_url }}" alt="{{ $comment->user->name }}"
                            style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                    </a>
                    <div style="flex: 1;">
                        <div style="margin-bottom: 4px;">
                            <a href="{{ route('profile.show', $comment->user->profile->username) }}"
                                style="font-weight: 600; font-size: 14px; color: var(--text-color); text-decoration: none;">
                                {{ $comment->user->profile->username }}
                            </a>
                            <span style="font-size: 14px; color: var(--text-color);">{{ $comment->content }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <span style="font-size: 12px; color: #8e8e8e;">{{ $comment->created_at->diffForHumans()
                                }}</span>
                            @if(auth()->id() === $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background: none; border: none; color: #ed4956; font-size: 12px; cursor: pointer;">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding: 40px 16px; text-align: center;">
                <i class="bi bi-chat" style="font-size: 32px; color: var(--border-color); margin-bottom: 16px;"></i>
                <p style="color: #8e8e8e; margin: 0;">No comments yet. Be the first to comment!</p>
            </div>
            @endforelse
        </div>

        <!-- Add Comment Form -->
        <div class="add-comment">
            <form class="comment-form" action="{{ route('comments.store', $post) }}" method="POST">
                @csrf
                <input type="text" class="comment-input" name="content" placeholder="Add a comment..."
                    autocomplete="off">
                <button type="submit" class="comment-submit">Post</button>
            </form>
        </div>
    </div>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
    <div style="margin-top: 32px;">
        <h6 style="font-weight: 600; margin-bottom: 16px;">More from {{ $post->user->profile->username }}</h6>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
            @foreach($relatedPosts as $relatedPost)
            <a href="{{ route('posts.show', $relatedPost) }}"
                style="display: block; aspect-ratio: 1; overflow: hidden; border-radius: 4px;">
                @if($relatedPost->type === 'image' && $relatedPost->media->count())
                <img src="{{ asset('storage/' . $relatedPost->media->first()->file_path) }}" alt="Post image"
                    style="width: 100%; height: 100%; object-fit: cover;">
                @elseif($relatedPost->type === 'code')
                <div
                    style="background: #2d3748; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-code-slash" style="font-size: 24px;"></i>
                </div>
                @else
                <div
                    style="background: #f8f9fa; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-file-text" style="font-size: 24px; color: #6c757d;"></i>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Comment form validation
    const commentInput = document.querySelector('.comment-input');
    const commentSubmit = document.querySelector('.comment-submit');

    if (commentInput && commentSubmit) {
        commentInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                commentSubmit.classList.add('active');
                commentSubmit.disabled = false;
            } else {
                commentSubmit.classList.remove('active');
                commentSubmit.disabled = true;
            }
        });

        // Initialize state
        commentInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush
@endsection