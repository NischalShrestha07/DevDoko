{{-- resources/views/posts/partials/comment.blade.php --}}
<div class="comment-item mb-3" id="comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}">
    <div class="d-flex">
        <!-- Commenter Avatar -->
        <div class="flex-shrink-0 me-3">
            <a href="{{ route('profile.show', $comment->user->profile->username) }}" class="text-decoration-none">
                <img src="{{ $comment->user->profile->avatar_url }}" alt="{{ $comment->user->name }}"
                    class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
            </a>
        </div>

        <!-- Comment Content -->
        <div class="flex-grow-1">
            <div class="card bg-light border-0">
                <div class="card-body py-2 px-3">
                    <!-- Comment Header -->
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <a href="{{ route('profile.show', $comment->user->profile->username) }}"
                                class="text-decoration-none fw-bold text-dark">
                                {{ $comment->user->profile->username }}
                            </a>
                            @if($comment->user->is_verified)
                            <span class="badge bg-primary ms-1" style="font-size: 8px; padding: 1px 4px;">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                            @endif
                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>

                        <!-- Comment Actions Dropdown -->
                        @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->is_admin))
                        <div class="dropdown">
                            <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown"
                                style="font-size: 12px;">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->id() === $comment->user_id)
                                <li>
                                    <button class="dropdown-item" onclick="editComment({{ $comment->id }})">
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </button>
                                </li>
                                @endif
                                <li>
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                        onsubmit="return confirm('Delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    <!-- Comment Body -->
                    <div class="comment-content" id="comment-content-{{ $comment->id }}">
                        <p class="mb-0">{{ $comment->content }}</p>
                    </div>

                    <!-- Comment Edit Form (Hidden) -->
                    <form action="{{ route('comments.update', $comment) }}" method="POST"
                        class="comment-edit-form d-none" id="comment-edit-form-{{ $comment->id }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <textarea class="form-control form-control-sm" name="content"
                                rows="2">{{ $comment->content }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                onclick="cancelEditComment({{ $comment->id }})">Cancel</button>
                        </div>
                    </form>

                    <!-- Comment Actions -->
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <!-- Like Button -->
                        <form action="{{ route('comments.like', $comment) }}" method="POST" class="like-comment-form">
                            @csrf
                            <button type="submit" class="btn btn-link text-dark p-0" style="font-size: 12px;">
                                <i class="bi bi-heart{{ $comment->is_liked ? '-fill text-danger' : '' }}"></i>
                                <span class="ms-1">{{ $comment->likes_count }}</span>
                            </button>
                        </form>

                        <!-- Reply Button -->
                        <button class="btn btn-link text-dark p-0 reply-toggle" style="font-size: 12px;"
                            data-comment-id="{{ $comment->id }}">
                            <i class="bi bi-reply"></i> Reply
                        </button>

                        <!-- View Replies (if any) -->
                        @if($comment->replies_count > 0)
                        <button class="btn btn-link text-dark p-0 view-replies-toggle" style="font-size: 12px;"
                            data-comment-id="{{ $comment->id }}">
                            <i class="bi bi-chevron-down"></i>
                            {{ $comment->replies_count }} {{ Str::plural('reply', $comment->replies_count) }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reply Form (Hidden) -->
            <div class="reply-form-container d-none mt-2" id="reply-form-{{ $comment->id }}">
                <form action="{{ route('comments.reply', $comment) }}" method="POST" class="reply-form">
                    @csrf
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-2">
                            <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <input type="text" class="form-control form-control-sm" name="content"
                                placeholder="Write a reply...">
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Replies Container -->
            <div class="replies-container mt-2 ms-4 d-none" id="replies-{{ $comment->id }}">
                @foreach($comment->replies as $reply)
                @include('posts.partials.comment', ['comment' => $reply])
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Edit comment
    function editComment(commentId) {
        const contentDiv = document.getElementById(`comment-content-${commentId}`);
        const editForm = document.getElementById(`comment-edit-form-${commentId}`);

        contentDiv.classList.add('d-none');
        editForm.classList.remove('d-none');
    }

    function cancelEditComment(commentId) {
        const contentDiv = document.getElementById(`comment-content-${commentId}`);
        const editForm = document.getElementById(`comment-edit-form-${commentId}`);

        contentDiv.classList.remove('d-none');
        editForm.classList.add('d-none');
    }

    // Toggle reply form
    document.querySelectorAll('.reply-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.classList.toggle('d-none');

            // Focus on input
            if (!replyForm.classList.contains('d-none')) {
                replyForm.querySelector('input').focus();
            }
        });
    });

    // Toggle replies view
    document.querySelectorAll('.view-replies-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const repliesContainer = document.getElementById(`replies-${commentId}`);
            const icon = this.querySelector('i');

            repliesContainer.classList.toggle('d-none');

            if (repliesContainer.classList.contains('d-none')) {
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            } else {
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            }
        });
    });

    // Handle comment like form submission
    document.querySelectorAll('.like-comment-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const likeButton = this.querySelector('button');
            const likeIcon = likeButton.querySelector('i');
            const likeCount = likeButton.querySelector('span');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    // Update UI
                    if (data.liked) {
                        likeIcon.classList.remove('bi-heart');
                        likeIcon.classList.add('bi-heart-fill', 'text-danger');
                    } else {
                        likeIcon.classList.remove('bi-heart-fill', 'text-danger');
                        likeIcon.classList.add('bi-heart');
                    }

                    // Update count
                    likeCount.textContent = data.likes_count;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Handle reply form submission
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const commentId = this.closest('.reply-form-container').id.split('-').pop();

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    // Add reply to replies container
                    const repliesContainer = document.getElementById(`replies-${commentId}`);
                    if (repliesContainer) {
                        repliesContainer.insertAdjacentHTML('beforeend', data.html);
                        repliesContainer.classList.remove('d-none');
                    }

                    // Update reply count
                    const viewRepliesBtn = document.querySelector(`.view-replies-toggle[data-comment-id="${commentId}"]`);
                    if (viewRepliesBtn) {
                        const currentCount = parseInt(viewRepliesBtn.textContent.match(/\d+/)[0]);
                        viewRepliesBtn.innerHTML = `<i class="bi bi-chevron-down"></i> ${currentCount + 1} replies`;
                    }

                    // Clear form
                    this.querySelector('input').value = '';
                    this.closest('.reply-form-container').classList.add('d-none');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
</script>

<style>
    .comment-item {
        position: relative;
    }

    .comment-item::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 40px;
        bottom: -16px;
        width: 2px;
        background-color: #e9ecef;
        z-index: 0;
    }

    .comment-item:last-child::before {
        display: none;
    }

    .replies-container {
        border-left: 2px solid #e9ecef;
        padding-left: 20px;
    }

    .reply-form-container {
        animation: slideDown 0.2s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card.bg-light {
        background-color: #f8f9fa !important;
        border-radius: 18px !important;
        border-top-left-radius: 4px !important;
    }
</style>
@endpush