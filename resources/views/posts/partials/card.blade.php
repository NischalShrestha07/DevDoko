{{-- resources/views/posts/partials/card.blade.php --}}
<div class="card post-card mb-4" id="post-{{ $post->id }}">
    <!-- Post Header -->
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ route('profile.show', $post->user->profile->username) }}" class="text-decoration-none">
                    <img src="{{ $post->user->profile->avatar_url }}" class="rounded-circle me-2" width="40" height="40"
                        style="object-fit: cover;">
                </a>
                <div>
                    <a href="{{ route('profile.show', $post->user->profile->username) }}"
                        class="text-decoration-none text-dark fw-bold d-block">
                        {{ $post->user->profile->username }}
                    </a>
                    @if($post->location)
                    <small class="text-muted">{{ $post->location }}</small>
                    @endif
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
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
                                onclick="return confirm('Are you sure?')">
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
        </div>
    </div>

    <!-- Post Content -->
    @if($post->type === 'image' && $post->media->count())
    @if($post->media->count() > 1)
    <div id="carousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($post->media as $index => $media)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $media->file_path) }}" class="d-block w-100" alt="Post image"
                    style="max-height: 600px; object-fit: contain; background-color: #f8f9fa;">
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
    <img src="{{ asset('storage/' . $post->media->first()->file_path) }}" class="card-img-top" alt="Post image"
        style="max-height: 600px; object-fit: contain; background-color: #f8f9fa;">
    @endif
    @elseif($post->type === 'video' && $post->media->count())
    <video class="card-img-top" controls style="max-height: 600px; background-color: #000;">
        <source src="{{ asset('storage/' . $post->media->first()->file_path) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    @elseif($post->type === 'code' && $post->codeSnippet)
    <div class="card-body border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-primary">{{ $post->codeSnippet->language }}</span>
            <button class="btn btn-sm btn-outline-secondary" onclick="copyCode('{{ $post->id }}')">
                <i class="bi bi-clipboard"></i> Copy
            </button>
        </div>
        <pre class="mb-0 bg-dark text-light p-3 rounded" style="max-height: 400px; overflow: auto;">
                <code id="code-{{ $post->id }}" class="language-{{ $post->codeSnippet->language }}">
{{ $post->codeSnippet->code }}
                </code>
            </pre>
    </div>
    @endif

    <!-- Post Actions -->
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center gap-3">
                <!-- Like Button -->
                <button class="btn btn-link text-dark p-0 like-btn" data-post-id="{{ $post->id }}"
                    data-liked="{{ $post->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}">
                    <i
                        class="bi bi-heart{{ $post->likes->contains('user_id', auth()->id()) ? '-fill text-danger' : '' }} fs-5"></i>
                </button>

                <!-- Comment Button -->
                <button class="btn btn-link text-dark p-0 comment-btn" data-post-id="{{ $post->id }}">
                    <i class="bi bi-chat fs-5"></i>
                </button>

                <!-- Share Button -->
                <button class="btn btn-link text-dark p-0" data-bs-toggle="modal"
                    data-bs-target="#shareModal-{{ $post->id }}">
                    <i class="bi bi-send fs-5"></i>
                </button>
            </div>

            <!-- Save Button -->
            <button class="btn btn-link text-dark p-0 save-btn" data-post-id="{{ $post->id }}"
                data-saved="{{ $post->saves->contains('user_id', auth()->id()) ? 'true' : 'false' }}">
                <i class="bi bi-bookmark{{ $post->saves->contains('user_id', auth()->id()) ? '-fill' : '' }} fs-5"></i>
            </button>
        </div>

        <!-- Likes Count -->
        <div class="mb-2">
            <span class="fw-bold likes-count-{{ $post->id }}">
                {{ $post->likes_count }} likes
            </span>
        </div>

        <!-- Caption -->
        <div class="mb-2">
            <a href="{{ route('profile.show', $post->user->profile->username) }}"
                class="text-decoration-none text-dark fw-bold me-1">
                {{ $post->user->profile->username }}
            </a>
            <span class="post-caption">{{ $post->caption }}</span>
        </div>

        <!-- Tags -->
        @if($post->tags->count())
        <div class="mb-2">
            @foreach($post->tags as $tag)
            <a href="{{ route('tags.show', $tag->name) }}" class="text-primary text-decoration-none me-2">
                #{{ $tag->name }}
            </a>
            @endforeach
        </div>
        @endif

        <!-- View Comments -->
        @if($post->comments_count > 0)
        <div class="mb-2">
            <button class="btn btn-link p-0 text-muted view-comments-btn" data-post-id="{{ $post->id }}">
                View all {{ $post->comments_count }} comments
            </button>
        </div>
        @endif

        <!-- Timestamp -->
        <div class="text-muted small">
            {{ $post->created_at->diffForHumans() }}
        </div>
    </div>

    <!-- Add Comment -->
    <div class="card-footer bg-white border-0 pt-0">
        <form class="comment-form" data-post-id="{{ $post->id }}">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control border-0 comment-input" placeholder="Add a comment..."
                    aria-label="Add a comment" style="font-size: 14px;">
                <button class="btn btn-link text-primary text-decoration-none" type="submit">
                    Post
                </button>
            </div>
        </form>
        <!-- Comments Container -->
        <div class="comments-container mt-2" id="comments-{{ $post->id }}" style="display: none;">
            <!-- Comments will be loaded here -->
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal-{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{ route('posts.show', $post) }}" readonly>
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="copyToClipboard('{{ route('posts.show', $post) }}')">
                        Copy
                    </button>
                </div>
                <div class="d-flex justify-content-around">
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-facebook"></i> Facebook
                    </button>
                    <button class="btn btn-outline-info">
                        <i class="bi bi-twitter"></i> Twitter
                    </button>
                    <button class="btn btn-outline-success">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Like/Unlike functionality
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', async function() {
        const postId = this.dataset.postId;
        const isLiked = this.dataset.liked === 'true';

        try {
            const response = await fetch(`/posts/${postId}/like/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Update button state
                const heartIcon = this.querySelector('i');
                if (data.liked) {
                    heartIcon.classList.remove('bi-heart');
                    heartIcon.classList.add('bi-heart-fill', 'text-danger');
                } else {
                    heartIcon.classList.remove('bi-heart-fill', 'text-danger');
                    heartIcon.classList.add('bi-heart');
                }
                this.dataset.liked = data.liked.toString();

                // Update likes count
                document.querySelector(`.likes-count-${postId}`).textContent = `${data.likes_count} likes`;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

// Comment submission
document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const postId = this.dataset.postId;
        const input = this.querySelector('.comment-input');
        const content = input.value.trim();

        if (!content) return;

        try {
            const response = await fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: content })
            });

            const data = await response.json();

            if (response.ok) {
                // Clear input
                input.value = '';

                // Add comment to container
                const commentsContainer = document.getElementById(`comments-${postId}`);
                commentsContainer.insertAdjacentHTML('afterbegin', data.html);

                // Update comments count
                const viewCommentsBtn = document.querySelector(`.view-comments-btn[data-post-id="${postId}"]`);
                if (viewCommentsBtn) {
                    const currentText = viewCommentsBtn.textContent;
                    const count = parseInt(currentText.match(/\d+/)[0]) + 1;
                    viewCommentsBtn.textContent = `View all ${count} comments`;
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

// View comments
document.querySelectorAll('.view-comments-btn').forEach(button => {
    button.addEventListener('click', async function() {
        const postId = this.dataset.postId;
        const commentsContainer = document.getElementById(`comments-${postId}`);

        if (commentsContainer.style.display === 'none') {
            // Load comments if not already loaded
            if (!commentsContainer.dataset.loaded) {
                try {
                    const response = await fetch(`/posts/${postId}/comments`);
                    const data = await response.json();

                    if (response.ok) {
                        commentsContainer.innerHTML = data.html;
                        commentsContainer.dataset.loaded = 'true';
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            commentsContainer.style.display = 'block';
        } else {
            commentsContainer.style.display = 'none';
        }
    });
});

// Copy code
function copyCode(postId) {
    const codeElement = document.getElementById(`code-${postId}`);
    const textArea = document.createElement('textarea');
    textArea.value = codeElement.textContent;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);

    // Show feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i> Copied!';
    button.disabled = true;

    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.disabled = false;
    }, 2000);
}

// Copy link to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endpush