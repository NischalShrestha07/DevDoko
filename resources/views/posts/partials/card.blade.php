{{-- resources/views/posts/partials/card.blade.php --}}
<div class="post-card" id="post-{{ $post->id }}">
    <!-- Post Header -->
    <div class="post-header">
        <div class="post-user">
            <a href="{{ route('profile.show', $post->user->profile->username) }}">
                <img src="{{ $post->user->profile->avatar_url }}" alt="{{ $post->user->name }}" class="post-avatar">
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user->profile->username) }}" class="post-username">
                    {{ $post->user->profile->username }}
                </a>
                @if($post->location)
                <div style="font-size: 12px; color: #8e8e8e;">{{ $post->location }}</div>
                @endif
            </div>
        </div>
        <button class="post-more" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-three-dots"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @if(auth()->id() === $post->user_id)
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>
            </li>
            <li>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger"
                        onclick="return confirm('Are you sure you want to delete this post?')">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </li>
            @else
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-flag me-2"></i> Report
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-slash-circle me-2"></i> Mute
                </a>
            </li>
            @endif
        </ul>
    </div>

    <!-- Post Content -->
    @if($post->type === 'image' && $post->media->count())
    @if($post->media->count() > 1)
    <div id="carousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($post->media as $index => $media)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $media->file_path) }}" class="post-image" alt="Post image">
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $post->id }}"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $post->id }}"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    @else
    <img src="{{ asset('storage/' . $post->media->first()->file_path) }}" class="post-image" alt="Post image">
    @endif
    @elseif($post->type === 'code' && $post->codeSnippet)
    <div style="background: #1e1e1e; color: #d4d4d4; padding: 24px; overflow-x: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <span style="background: #007acc; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px;">
                {{ $post->codeSnippet->language }}
            </span>
            <button onclick="copyCode('{{ $post->id }}')"
                style="background: #2d2d30; color: #d4d4d4; border: 1px solid #3e3e42; padding: 4px 12px; border-radius: 4px; font-size: 12px; cursor: pointer;">
                <i class="bi bi-clipboard"></i> Copy
            </button>
        </div>
        <pre
            style="margin: 0; font-family: 'Consolas', 'Monaco', monospace; font-size: 14px; line-height: 1.5;"><code id="code-{{ $post->id }}">{{ $post->codeSnippet->code }}</code></pre>
    </div>
    @endif

    <!-- Post Actions -->
    <div class="post-actions">
        <div class="action-left">
            <button class="post-action-btn like-btn" data-post-id="{{ $post->id }}">
                <i class="bi bi-heart{{ $post->likes->contains('user_id', auth()->id()) ? '-fill liked' : '' }}"></i>
            </button>
            <button class="post-action-btn" onclick="location.href='{{ route('posts.show', $post) }}'">
                <i class="bi bi-chat"></i>
            </button>
            <button class="post-action-btn">
                <i class="bi bi-send"></i>
            </button>
        </div>
        <button class="post-action-btn save-btn">
            <i class="bi bi-bookmark{{ $post->saves->contains('user_id', auth()->id()) ? '-fill' : '' }}"></i>
        </button>
    </div>

    <!-- Likes Count -->
    <div class="post-likes likes-count-{{ $post->id }}">
        {{ $post->likes->count() }} likes
    </div>

    <!-- Caption -->
    <div class="post-caption">
        <a href="{{ route('profile.show', $post->user->profile->username) }}" class="post-caption-user">
            {{ $post->user->profile->username }}
        </a>
        {{ $post->caption }}
    </div>

    <!-- Tags -->
    @if($post->tags->count())
    <div style="padding: 0 16px 8px;">
        @foreach($post->tags as $tag)
        <a href="{{ route('tags.show', $tag->name) }}"
            style="color: #00376b; text-decoration: none; font-size: 14px; margin-right: 8px;">
            #{{ $tag->name }}
        </a>
        @endforeach
    </div>
    @endif

    <!-- View Comments -->
    @if(!isset($fullView) && $post->comments->count() > 0)
    <div class="post-comments">
        <a href="{{ route('posts.show', $post) }}" style="color: #8e8e8e; text-decoration: none;">
            View all {{ $post->comments->count() }} comments
        </a>
    </div>
    @endif

    <!-- Timestamp -->
    <p class="post-timestamp">
        {{ $post->created_at->diffForHumans() }}
    </p>

    <!-- Add Comment (Only in feed view) -->
    @if(!isset($fullView))
    <div class="add-comment">
        <form class="comment-form" action="{{ route('comments.store', $post) }}" method="POST">
            @csrf
            <input type="text" class="comment-input" name="content" placeholder="Add a comment..." autocomplete="off">
            <button type="submit" class="comment-submit">Post</button>
        </form>
    </div>
    @endif
</div>