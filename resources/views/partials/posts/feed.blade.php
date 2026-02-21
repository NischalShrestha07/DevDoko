{{-- resources/partials/posts/feed --}}
@forelse($posts as $post)
<div class="card post-card mb-4 border-0 shadow-sm">
    <!-- Post Header -->
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ route('profile.show', $post->user->profile->username) }}" class="text-decoration-none me-3">
                    <img src="{{ $post->user->profile->avatar_url }}" alt="{{ $post->user->name }}"
                        class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                </a>
                <div>
                    <a href="{{ route('profile.show', $post->user->profile->username) }}"
                        class="text-decoration-none text-dark fw-bold d-block">
                        {{ $post->user->profile->username }}
                    </a>
                    <small class="text-muted">{{ $post->time_ago }}</small>
                </div>
            </div>

            @if(auth()->id() == $post->user_id || auth()->user()->is_admin)
            <div class="dropdown">
                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    @if(auth()->id() == $post->user_id)
                    <li>
                        <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                            <i class="bi bi-pencil me-2"></i> Edit
                        </a>
                    </li>
                    @endif
                    <li>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger"
                                onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash me-2"></i> Delete
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Post Body -->
    <div class="card-body p-0">
        <!-- Title -->
        @if($post->title)
        <div class="px-4 pb-2">
            <h5 class="fw-bold mb-0">{{ $post->title }}</h5>
        </div>
        @endif

        <!-- Content -->
        @if($post->content && $post->type != 'code')
        <div class="px-4 py-2">
            <div class="post-content">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
        @endif

        <!-- Code Snippet -->
        @if($post->type == 'code' && $post->code_snippet)
        <div class="px-0">
            <div class="bg-dark text-light">
                <div class="d-flex justify-content-between align-items-center px-4 py-2">
                    <div>
                        @if($post->code_language)
                        <span class="badge bg-primary">{{ $post->code_language }}</span>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-light copy-code-btn"
                            data-code="{{ htmlspecialchars($post->code_snippet) }}">
                            <i class="bi bi-clipboard me-1"></i> Copy
                        </button>
                    </div>
                </div>
                <pre class="m-0 p-4 bg-dark text-light overflow-auto" style="max-height: 400px;">
                    <code class="language-{{ strtolower($post->code_language ?? 'plaintext') }}">
{{ $post->code_snippet }}
                    </code>
                </pre>
            </div>
        </div>
        @endif

        <!-- Image -->
        @if($post->image_path)
        <div class="px-0 py-2">
            <img src="{{ Storage::url($post->image_path) }}" alt="Post image" class="img-fluid w-100"
                style="max-height: 500px; object-fit: contain;">
        </div>
        @endif

        <!-- Tags -->
        @if($post->tags->count() > 0)
        <div class="px-4 py-2">
            <div class="d-flex flex-wrap gap-1">
                @foreach($post->tags as $tag)
                <a href="{{ route('tags.show', $tag->name) }}"
                    class="badge bg-light text-dark text-decoration-none border">
                    #{{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Post Stats -->
    <div class="card-footer bg-white border-0 border-top px-4 py-3">
        <div class="d-flex justify-content-between mb-2">
            <div class="d-flex gap-3">
                <!-- Like Button -->
                <form action="{{ route('posts.like.toggle', $post) }}" method="POST" class="d-inline like-form">
                    @csrf
                    <button type="submit" class="btn btn-link text-dark p-0">
                        <i class="bi bi-heart{{ $post->is_liked ? '-fill text-danger' : '' }} fs-5"></i>
                    </button>
                </form>

                <!-- Comment Button -->
                <a href="{{ route('posts.show', $post) }}" class="btn btn-link text-dark p-0">
                    <i class="bi bi-chat fs-5"></i>
                </a>

                <!-- Share Button -->
                <button class="btn btn-link text-dark p-0 share-btn" data-post-id="{{ $post->id }}">
                    <i class="bi bi-send fs-5"></i>
                </button>
            </div>

            <!-- Save Button -->
            <form action="{{ route('posts.save', $post) }}" method="POST" class="d-inline save-form">
                @csrf
                @if($post->is_saved)
                @method('DELETE')
                <button type="submit" class="btn btn-link text-dark p-0">
                    <i class="bi bi-bookmark-fill fs-5 text-warning"></i>
                </button>
                @else
                <button type="submit" class="btn btn-link text-dark p-0">
                    <i class="bi bi-bookmark fs-5"></i>
                </button>
                @endif
            </form>
        </div>

        <!-- Stats -->
        @if($post->likes_count > 0 || $post->comments_count > 0)
        <div class="mb-3">
            @if($post->likes_count > 0)
            <span class="fw-bold">{{ $post->likes_count }} likes</span>
            @endif

            @if($post->comments_count > 0)
            <span class="fw-bold ms-3">{{ $post->comments_count }} comments</span>
            @endif
        </div>
        @endif

        <!-- Add Comment -->
        <form action="{{ route('comments.store', $post) }}" method="POST" class="comment-form">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control border-0 bg-light rounded-pill" placeholder="Add a comment..."
                    name="content" required>
                <button class="btn btn-link text-primary text-decoration-none" type="submit">
                    Post
                </button>
            </div>
        </form>
    </div>
</div>
@empty
<div class="text-center py-5">
    <div class="mb-4">
        <i class="bi bi-newspaper display-1 text-muted"></i>
    </div>
    <h4 class="text-muted mb-3">No posts yet</h4>
    <p class="text-muted mb-4">
        Be the first to share something amazing!
    </p>
    <a href="{{ route('posts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Create First Post
    </a>
</div>
@endforelse

@if($posts->hasMorePages())
<div class="text-center mt-4">
    <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-primary">
        Load More Posts
    </a>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Like form submission
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                location.reload();
            }
        });
    });

    // Save form submission
    document.querySelectorAll('.save-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: this.querySelector('[name="_method"]') ? 'DELETE' : 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                location.reload();
            }
        });
    });

    // Copy code button
    document.querySelectorAll('.copy-code-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.dataset.code;
            navigator.clipboard.writeText(code).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';
                this.classList.add('btn-success');
                this.classList.remove('btn-outline-light');

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-light');
                }, 2000);
            });
        });
    });

    // Share button
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const postUrl = window.location.origin + '/posts/' + postId;

            // Copy link to clipboard
            navigator.clipboard.writeText(postUrl).then(() => {
                alert('Post link copied to clipboard!');
            });
        });
    });
});
</script>
