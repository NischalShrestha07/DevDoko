{{-- resources/views/posts/partials/card.blade.php --}}
{{-- Post Card Component --}}
<div class="post-card mb-4" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
    <!-- Post Header -->
    <div class="card-header bg-white border-0 p-4 pb-2">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <!-- User Avatar -->
                <a href="{{ route('profile.show', $post->user->profile->username ?? '') }}" class="text-decoration-none">
                    <img src="{{ $post->user->profile->avatar_url }}" alt="{{ $post->user->name }}"
                        class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover;">
                </a>

                <!-- User Info -->
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('profile.show', $post->user->profile->username ?? '') }}"
                            class="text-decoration-none text-dark fw-bold">
                            {{ $post->user->profile->username ?? '' }}
                        </a>

                        @if($post->user->is_verified)
                        <span class="badge bg-primary ms-2" style="font-size: 10px; padding: 2px 6px;">
                            <i class="bi bi-check-circle-fill"></i>
                        </span>
                        @endif

                        @if($post->visibility === 'private')
                        <span class="badge bg-secondary ms-2" style="font-size: 10px; padding: 2px 6px;">
                            <i class="bi bi-lock"></i> Private
                        </span>
                        @elseif($post->visibility === 'followers')
                        <span class="badge bg-info ms-2" style="font-size: 10px; padding: 2px 6px;">
                            <i class="bi bi-people"></i> Followers
                        </span>
                        @endif

                        @if($post->is_pinned)
                        <span class="badge bg-warning ms-2" style="font-size: 10px; padding: 2px 6px;">
                            <i class="bi bi-pin-angle"></i> Pinned
                        </span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center text-muted" style="font-size: 12px;">
                        <span class="me-2">{{ $post->created_at->diffForHumans() }}</span>
                        <i class="bi bi-dot"></i>
                        <span class="ms-2">
                            <i class="bi bi-{{ $post->type_icon }}"></i>
                            {{ $post->type_label }}
                        </span>
                        @if($post->type === 'article')
                        <i class="bi bi-dot"></i>
                        <span class="ms-2">{{ $post->formatted_reading_time }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Post Actions Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if($post->user_id === auth()->id())
                    <li>
                        <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                            <i class="bi bi-pencil me-2"></i> Edit Post
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('posts.pin', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-pin-angle me-2"></i>
                                {{ $post->is_pinned ? 'Unpin Post' : 'Pin Post' }}
                            </button>
                        </form>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-trash me-2"></i> Delete Post
                            </button>
                        </form>
                    </li>
                    @else
                    @auth
                    @if(auth()->user()->isFollowing($post->user))
                    <li>
                        <form action="{{ route('users.unfollow', $post->user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-person-dash me-2"></i> Unfollow
                            </button>
                        </form>
                    </li>
                    @else
                    <li>
                        <form action="{{ route('users.follow', $post->user) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-person-plus me-2"></i> Follow
                            </button>
                        </form>
                    </li>
                    @endif
                    @endauth
                    <li>
                        <button class="dropdown-item" onclick="copyToClipboard({{ json_encode($post->url) }})">
                            <i class="bi bi-link-45deg me-2"></i> Copy Link
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#shareModal-{{ $post->id }}">
                            <i class="bi bi-send me-2"></i> Share Post
                        </button>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                            data-bs-target="#reportModal-{{ $post->id }}">
                            <i class="bi bi-flag me-2"></i> Report Post
                        </button>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Post Content -->
    <div class="card-body px-0 py-2">
        <!-- Title -->
        @if($post->title)
        <div class="px-4 mb-2">
            <h5 class="fw-bold mb-1">{{ $post->title }}</h5>
        </div>
        @endif

        <!-- Content -->
        @if($post->content)
        <div class="px-4 mb-3">
            <div class="post-content">
                {!! Str::markdown(e($post->content)) !!}
            </div>
        </div>
        @endif

        <!-- Code Snippet -->
        @if($post->type === 'code' && $post->code_snippet)
        <div class="mb-3">
            <div class="bg-dark text-light rounded mx-4">
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom border-secondary">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-code-slash me-2"></i>
                        <span class="badge bg-primary">{{ $post->code_language ?? 'Code' }}</span>
                        @if($post->code_language)
                        <span class="ms-2 text-light-50" style="font-size: 12px;">
                            {{ $post->code_language }}
                        </span>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-light" onclick="copyCode('{{ $post->id }}', this)">
                            <i class="bi bi-clipboard me-1"></i> Copy
                        </button>
                    </div>
                </div>
                <pre class="mb-0 p-3" style="max-height: 400px; overflow: auto;">
                        <code id="code-{{ $post->id }}" class="language-{{ $post->code_language ?? 'plaintext' }}">{{ $post->code_snippet }}</code>
                    </pre>
            </div>
        </div>
        @endif

        <!-- Image -->
        @if($post->type === 'image' && $post->image_url)
        <div class="mb-3">
            <img src="{{ $post->image_url }}" alt="Post image" class="img-fluid w-100"
                style="max-height: 600px; object-fit: contain; cursor: pointer;"
                onclick="openImageModal({{ json_encode($post->image_url) }}, {{ json_encode($post->title) }})">
        </div>
        @endif

        <!-- Video -->
        @if($post->type === 'video' && $post->video_path)
        <div class="mb-3 px-4">
            <video controls class="w-100 rounded" style="max-height: 500px;">
                <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        @endif

        <!-- Link Preview -->
        @if($post->type === 'link' && $post->link_url)
        <div class="mb-3 px-4">
            <a href="{{ $post->link_url }}" target="_blank" class="text-decoration-none">
                <div class="card border">
                    <div class="row g-0">
                        @if($post->link_image)
                        <div class="col-md-4">
                            <img src="{{ $post->link_image }}" class="img-fluid rounded-start" alt="Link preview"
                                style="height: 150px; object-fit: cover;">
                        </div>
                        @endif
                        <div class="{{ $post->link_image ? 'col-md-8' : 'col-12' }}">
                            <div class="card-body">
                                <h6 class="card-title">{{ $post->link_title ?? parse_url($post->link_url, PHP_URL_HOST)
                                    }}</h6>
                                @if($post->link_description)
                                <p class="card-text text-muted small">{{ Str::limit($post->link_description, 150) }}</p>
                                @endif
                                <small class="text-muted">{{ parse_url($post->link_url, PHP_URL_HOST) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Tags -->
        @if($post->tags->count() > 0)
        <div class="px-4 mb-3">
            <div class="d-flex flex-wrap gap-1">
                @foreach($post->tags as $tag)
                <a href="{{ route('tags.show', $tag->slug) }}"
                    class="badge bg-light text-dark border text-decoration-none">
                    <i class="bi bi-hash"></i>{{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Post Stats -->
    <div class="card-footer bg-white border-0 pt-0">
        <!-- Stats Row -->
        <div class="px-4 pb-2 border-bottom">
            <div class="d-flex justify-content-between text-muted small">
                <div class="d-flex align-items-center">
                    <i class="bi bi-eye me-1"></i>
                    <span>{{ $post->views_count }} views</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-chat me-1"></i>
                    <span>{{ $post->comments_count }} comments</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-share me-1"></i>
                    <span>{{ $post->shares_count }} shares</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-bookmark me-1"></i>
                    <span>{{ $post->saves_count ?? 0 }} saves</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-4 py-2">
            <div class="d-flex justify-content-between">
                <!-- Like Button -->
                <form action="{{ route('posts.like.toggle', $post) }}" method="POST" class="like-form">
                    @csrf
                    <button type="submit" class="btn btn-link text-dark p-0 action-btn">
                        <i class="bi bi-heart{{ $post->is_liked ? '-fill text-danger' : '' }} fs-5"></i>
                        <span class="ms-1">{{ $post->likes_count }}</span>
                    </button>
                </form>

                <!-- Comment Button -->
                <button class="btn btn-link text-dark p-0 action-btn comment-toggle" data-post-id="{{ $post->id }}">
                    <i class="bi bi-chat fs-5"></i>
                    <span class="ms-1">Comment</span>
                </button>

                <!-- Share Button -->
                <button class="btn btn-link text-dark p-0 action-btn" data-bs-toggle="modal"
                    data-bs-target="#shareModal-{{ $post->id }}">
                    <i class="bi bi-send fs-5"></i>
                    <span class="ms-1">Share</span>
                </button>

                <!-- Save Button -->
                <form action="{{ route('posts.save', $post) }}" method="POST" class="save-form">
                    @csrf
                    @if($post->is_saved)
                    @method('DELETE')
                    <button type="submit" class="btn btn-link text-dark p-0 action-btn">
                        <i class="bi bi-bookmark-fill fs-5"></i>
                        <span class="ms-1">Saved</span>
                    </button>
                    @else
                    <button type="submit" class="btn btn-link text-dark p-0 action-btn">
                        <i class="bi bi-bookmark fs-5"></i>
                        <span class="ms-1">Save</span>
                    </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Comments Section (Collapsible) -->
        <div id="comments-{{ $post->id }}" class="collapse">
            <div class="px-4 py-3 border-top">
                <!-- Comments List -->
                <div id="comments-list-{{ $post->id }}">
                    @foreach($post->comments->take(3) as $comment)
                    @include('posts.partials.comment', ['comment' => $comment])
                    @endforeach

                    @if($post->comments_count > 3)
                    <div class="text-center mt-2">
                        <button class="btn btn-link text-primary" onclick="loadMoreComments({{ $post->id }})">
                            View all {{ $post->comments_count }} comments
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Add Comment Form -->
                <div class="mt-3">
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="comment-form"
                        id="comment-form-{{ $post->id }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control border-0" placeholder="Write a comment..."
                                name="content" id="comment-input-{{ $post->id }}">
                            <button class="btn btn-link text-primary text-decoration-none" type="submit">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                <div class="mb-3">
                    <label class="form-label">Share URL</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $post->url }}" readonly>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="copyToClipboard('{{ $post->url }}')">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Share with message</label>
                    <form action="{{ route('posts.share', $post) }}" method="POST">
                        @csrf
                        <textarea class="form-control mb-2" name="content" rows="3"
                            placeholder="Add a message (optional)"></textarea>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-1"></i> Share Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal-{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('posts.report', $post) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason for reporting</label>
                        <select class="form-select" name="reason" required>
                            <option value="">Select a reason</option>
                            <option value="spam">Spam</option>
                            <option value="harassment">Harassment or bullying</option>
                            <option value="hate_speech">Hate speech or symbols</option>
                            <option value="violence">Violence or dangerous organizations</option>
                            <option value="false_info">False information</option>
                            <option value="intellectual_property">Intellectual property violation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional details (optional)</label>
                        <textarea class="form-control" name="details" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<script>
if (!window._cardInit) {
    window._cardInit = true;

    // Handle like form submission (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.like-form');
        if (!form) return;
        e.preventDefault();

        const postId = form.closest('.post-card').dataset.postId;
        const likeButton = form.querySelector('button');
        const likeIcon = likeButton.querySelector('i');
        const likeCount = likeButton.querySelector('span');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.liked) {
                    likeIcon.classList.remove('bi-heart');
                    likeIcon.classList.add('bi-heart-fill', 'text-danger');
                } else {
                    likeIcon.classList.remove('bi-heart-fill', 'text-danger');
                    likeIcon.classList.add('bi-heart');
                }
                likeCount.textContent = data.likes_count;
                likeIcon.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    likeIcon.style.transform = 'scale(1)';
                }, 200);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Handle save form submission (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.save-form');
        if (!form) return;
        e.preventDefault();

        const saveButton = form.querySelector('button');
        const saveIcon = saveButton.querySelector('i');
        const saveText = saveButton.querySelector('span');

        try {
            const response = await fetch(form.action, {
                method: form.querySelector('[name="_method"]') ? 'DELETE' : 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.saved) {
                    saveIcon.classList.remove('bi-bookmark');
                    saveIcon.classList.add('bi-bookmark-fill');
                    saveText.textContent = 'Saved';
                } else {
                    saveIcon.classList.remove('bi-bookmark-fill');
                    saveIcon.classList.add('bi-bookmark');
                    saveText.textContent = 'Save';
                }
                saveIcon.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    saveIcon.style.transform = 'scale(1)';
                }, 200);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Toggle comments section (event delegation)
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.comment-toggle');
        if (!button) return;
        const postId = button.dataset.postId;
        const commentsSection = document.getElementById(`comments-${postId}`);
        if (!commentsSection) return;
        const bsCollapse = new bootstrap.Collapse(commentsSection, { toggle: true });
        commentsSection.addEventListener('shown.bs.collapse', function() {
            const input = document.getElementById(`comment-input-${postId}`);
            if (input) input.focus();
        }, { once: true });
    });

    // Handle comment form submission (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.comment-form');
        if (!form) return;
        e.preventDefault();

        const postId = form.id.split('-').pop();
        const commentInput = document.getElementById(`comment-input-${postId}`);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const commentsList = document.getElementById(`comments-list-${postId}`);
                if (commentsList) {
                    commentsList.insertAdjacentHTML('afterbegin', data.html);
                }
                if (commentInput) commentInput.value = '';
                const commentCount = document.querySelector(`#post-${postId} .bi-chat-text + span`);
                if (commentCount) {
                    const current = parseInt(commentCount.textContent) || 0;
                    commentCount.textContent = (current + 1) + ' comments';
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Toggle reply form (event delegation)
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.reply-toggle');
        if (!button) return;
        const commentId = button.dataset.commentId;
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        if (!replyForm) return;
        replyForm.classList.toggle('d-none');
        if (!replyForm.classList.contains('d-none')) {
            const input = replyForm.querySelector('input');
            if (input) input.focus();
        }
    });

    // Toggle replies view (event delegation)
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.view-replies-toggle');
        if (!button) return;
        const commentId = button.dataset.commentId;
        const repliesContainer = document.getElementById(`replies-${commentId}`);
        if (!repliesContainer) return;
        const icon = button.querySelector('i');
        repliesContainer.classList.toggle('d-none');
        if (repliesContainer.classList.contains('d-none')) {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        } else {
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        }
    });

    // Handle comment like form submission (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.like-comment-form');
        if (!form) return;
        e.preventDefault();

        const formData = new FormData(form);
        const likeButton = form.querySelector('button');
        const likeIcon = likeButton.querySelector('i');
        const likeCount = likeButton.querySelector('span');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.liked) {
                    likeIcon.classList.remove('bi-heart');
                    likeIcon.classList.add('bi-heart-fill', 'text-danger');
                } else {
                    likeIcon.classList.remove('bi-heart-fill', 'text-danger');
                    likeIcon.classList.add('bi-heart');
                }
                likeCount.textContent = data.likes_count;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Handle reply form submission (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.reply-form');
        if (!form) return;
        e.preventDefault();

        const formData = new FormData(form);
        const commentId = form.closest('.reply-form-container').id.split('-').pop();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const repliesContainer = document.getElementById(`replies-${commentId}`);
                if (repliesContainer) {
                    repliesContainer.insertAdjacentHTML('beforeend', data.html);
                    repliesContainer.classList.remove('d-none');
                }
                const viewRepliesBtn = document.querySelector(`.view-replies-toggle[data-comment-id="${commentId}"]`);
                if (viewRepliesBtn) {
                    const currentCount = parseInt(viewRepliesBtn.textContent.match(/\d+/)[0]);
                    viewRepliesBtn.innerHTML = `<i class="bi bi-chevron-down"></i> ${currentCount + 1} replies`;
                }
                form.querySelector('input').value = '';
                form.closest('.reply-form-container').classList.add('d-none');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Copy code to clipboard
    function copyCode(postId, button) {
        const codeElement = document.getElementById(`code-${postId}`);
        if (!codeElement) return;
        const code = codeElement.textContent;

        navigator.clipboard.writeText(code).then(() => {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i> Copied!';
            button.classList.add('btn-success');
            button.classList.remove('btn-outline-light');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-light');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Copy URL to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Open image in modal
    function openImageModal(imageUrl, title) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
        modalImage.alt = title || 'Image';

        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Load more comments
    async function loadMoreComments(postId) {
        try {
            const response = await fetch(`/posts/${postId}/comments?offset=3`);
            if (response.ok) {
                const data = await response.json();
                document.getElementById(`comments-list-${postId}`).innerHTML = data.html;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Lazy load images
    document.addEventListener('DOMContentLoaded', function() {
        const lazyImages = document.querySelectorAll('img[data-src]');

        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    });

    // Initialize Highlight.js for code blocks
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }
    });
}
</script>

<style>
    .post-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }

    .post-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-btn {
        transition: all 0.2s;
        border-radius: 8px;
        padding: 8px 12px;
    }

    .action-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .post-content {
        line-height: 1.6;
    }

    .post-content h1,
    .post-content h2,
    .post-content h3,
    .post-content h4,
    .post-content h5,
    .post-content h6 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .post-content p {
        margin-bottom: 1rem;
    }

    .post-content ul,
    .post-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .post-content code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .post-content pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin-bottom: 1rem;
    }

    .post-content blockquote {
        border-left: 4px solid #ddd;
        padding-left: 1rem;
        margin-left: 0;
        color: #666;
        font-style: italic;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    #imageModal .modal-content {
        background: rgba(0, 0, 0, 0.9);
    }

    #imageModal .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>
