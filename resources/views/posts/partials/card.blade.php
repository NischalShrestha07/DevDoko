{{-- resources/views/posts/partials/card.blade.php --}}
{{-- Post Card Component --}}
<div class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
    <!-- Post Header -->
    <div class="card-header bg-transparent border-0 px-0 pt-3 pb-2">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center min-width-0">
                <!-- User Avatar -->
                <a href="{{ route('profile.show', $post->user->profile->username ?? '') }}" class="text-decoration-none flex-shrink-0">
                    <img src="{{ $post->user->profile->avatar_url }}" alt="{{ $post->user->name }}"
                        class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                </a>

                <!-- User Info -->
                <div class="ms-2 min-width-0">
                    <div class="d-flex align-items-center flex-wrap">
                        <a href="{{ route('profile.show', $post->user->profile->username ?? '') }}"
                            class="text-decoration-none app-text-primary fw-bold" style="font-size: 14px;">
                            {{ $post->user->profile->username ?? '' }}
                        </a>

                        @if($post->user->profile->is_verified ?? false)
                        <i class="bi bi-patch-check-fill ms-1" style="font-size: 12px; color: var(--accent);" title="Verified"></i>
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

                    <div class="d-flex align-items-center app-text-muted" style="font-size: 12px;">
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

            <!-- Post Actions -->
            <div class="d-flex align-items-center flex-shrink-0 ms-2">
                @auth
                @if($post->user_id !== auth()->id() && ! auth()->user()->isFollowing($post->user))
                <form action="{{ route('users.follow', $post->user) }}" method="POST" class="follow-form me-1">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link fw-semibold text-decoration-none p-0" style="font-size: 13px; color: var(--accent);">
                        Follow
                    </button>
                </form>
                @elseif($post->user_id !== auth()->id())
                <form action="{{ route('posts.hide', $post) }}" method="POST" class="hide-post-form me-1">
                    @csrf
                    <button type="submit" class="btn btn-link text-body-secondary p-0 action-btn-icon" title="Not interested">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </form>
                @endif
                @endauth

            <!-- Post Actions Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link app-text-muted p-0 action-btn-icon" type="button" data-bs-toggle="dropdown">
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
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="delete-post-form">
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
                        <form action="{{ route('posts.hide', $post) }}" method="POST" class="hide-post-form">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-eye-slash me-2"></i> Not Interested
                            </button>
                        </form>
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
    </div>

    <!-- Post Content -->
    <div class="card-body px-0 py-2">
        <!-- Title -->
        @if($post->title)
        <div class="px-0 mb-2">
            <h5 class="fw-bold mb-1 {{ $post->type !== 'article' ? 'fst-italic' : '' }}">{{ $post->title }}</h5>
        </div>
        @endif

        <!-- Content -->
        @if($post->content)
        <div class="px-0 mb-3">
            <div class="post-content post-content-truncate" id="post-content-{{ $post->id }}">
                @rich($post->content)
            </div>
            @if(strlen($post->content) > 300)
            <button class="btn btn-link p-0 mt-1 post-read-more" style="color: var(--accent);" data-post-id="{{ $post->id }}" onclick="toggleReadMore({{ $post->id }})">
                Read more <i class="bi bi-chevron-down small"></i>
            </button>
            @endif
        </div>
        @endif

        <!-- Code Snippet -->
        @if($post->type === 'code' && $post->code_snippet)
        <div class="mb-3">
            <div class="bg-dark text-light rounded mx-0">
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
                <pre class="mb-0 p-3 code-block-feed">
                        <code id="code-{{ $post->id }}" class="language-{{ $post->code_language ?? 'plaintext' }}">{{ $post->code_snippet }}</code>
                    </pre>
            </div>
        </div>
        @endif

        <!-- Image / Gallery -->
        @if($post->type === 'image' && ($post->image_url || $post->media->count()))
        @php
            $galleryImages = collect([$post->image_url])->merge($post->media->pluck('url'))->filter()->unique()->values();
        @endphp
        <div class="mb-3">
            @if($galleryImages->count() > 1)
            <div id="carousel-{{ $post->id }}" class="carousel slide post-image-container position-relative" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($galleryImages as $i => $img)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <img src="{{ $img }}" alt="Post image" class="post-image d-block mx-auto"
                            data-image-url="{{ $img }}" data-image-title="{{ $post->title }}">
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                <div class="carousel-indicators position-relative gallery-dots" style="height: auto;">
                    @foreach($galleryImages as $i => $img)
                    <button type="button" data-bs-target="#carousel-{{ $post->id }}" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
                <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2 gallery-counter">1/{{ $galleryImages->count() }}</span>
            </div>
            @else
            <div class="post-image-container">
                <img src="{{ $galleryImages->first() }}" alt="Post image" class="post-image"
                    data-image-url="{{ $galleryImages->first() }}" data-image-title="{{ $post->title }}">
            </div>
            @endif
        </div>
        @endif

        <!-- Video -->
        @if($post->type === 'video' && $post->video_path)
        <div class="mb-3 px-0">
            <video controls class="w-100 rounded" style="max-height: 500px;">
                <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        @endif

        <!-- Link Preview -->
        @if($post->type === 'link' && $post->link_url)
        <div class="mb-3 px-0">
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
        <div class="px-0 mb-3">
            <div class="d-flex flex-wrap gap-1">
                @foreach($post->tags as $tag)
                <a href="{{ route('tags.show', $tag->slug) }}"
                    class="badge tag-badge text-decoration-none">
                    <i class="bi bi-hash"></i>{{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Post Stats -->
    <div class="card-footer bg-transparent border-0 px-0 pt-0 pb-3">
        <!-- Action Buttons -->
        <div class="px-0 pt-2">
            <div class="d-flex align-items-center gap-4">
                <!-- Like / Reaction Button -->
                @php $reactionEmoji = ['like' => '<i class="bi bi-heart-fill text-danger"></i>', 'love' => '❤️', 'haha' => '😂', 'wow' => '😮', 'sad' => '😢', 'angry' => '😡']; @endphp
                <div class="position-relative reaction-wrap d-flex align-items-center gap-1" data-reacted="{{ $post->reaction_type ? '1' : '0' }}">
                    <form action="{{ route('posts.like.toggle', $post) }}" method="POST" class="like-form">
                        @csrf
                        <input type="hidden" name="type" value="{{ $post->reaction_type ?? 'like' }}" class="reaction-type-input">
                        <button type="submit" class="btn btn-link app-text-muted p-0 action-btn action-btn-icon">
                            <span class="reaction-icon fs-5">
                                @if($post->reaction_type)
                                    {!! $reactionEmoji[$post->reaction_type] ?? $reactionEmoji['like'] !!}
                                @else
                                    <i class="bi bi-heart"></i>
                                @endif
                            </span>
                        </button>
                    </form>
                    <span class="small app-text-muted likes-count-wrap {{ $post->likes_count > 0 ? '' : 'd-none' }}">
                        <span class="likes-count-text">{{ number_format($post->likes_count) }} {{ Str::plural('like', $post->likes_count) }}</span>
                    </span>
                    <div class="reaction-picker">
                        @foreach($reactionEmoji as $key => $emoji)
                        <button type="button" class="reaction-option" data-type="{{ $key }}" title="{{ ucfirst($key) }}">{!! $emoji !!}</button>
                        @endforeach
                    </div>
                </div>

                <!-- Comment Button -->
                <button class="btn btn-link app-text-muted p-0 d-flex align-items-center gap-1 action-btn action-btn-icon comment-toggle" data-post-id="{{ $post->id }}">
                    <i class="bi bi-chat fs-5"></i>
                    @if($post->comments_count > 0)<span class="small">{{ $post->comments_count }}</span>@endif
                </button>

                <!-- Share / Repost Button -->
                <button class="btn btn-link app-text-muted p-0 d-flex align-items-center gap-1 action-btn action-btn-icon" data-bs-toggle="modal"
                    data-bs-target="#shareModal-{{ $post->id }}">
                    <i class="bi bi-repeat fs-5"></i>
                    @if($post->shares_count > 0)<span class="small">{{ $post->shares_count }}</span>@endif
                </button>

                <!-- Save Button -->
                <form action="{{ route('posts.save', $post) }}" method="POST" class="save-form ms-auto">
                    @csrf
                    @if($post->is_saved)
                    @method('DELETE')
                    <button type="submit" class="btn btn-link app-text-muted p-0 action-btn action-btn-icon">
                        <i class="bi bi-bookmark-fill fs-5"></i>
                    </button>
                    @else
                    <button type="submit" class="btn btn-link app-text-muted p-0 action-btn action-btn-icon">
                        <i class="bi bi-bookmark fs-5"></i>
                    </button>
                    @endif
                </form>
            </div>
        </div>

        @if($post->views_count > 0)
        <div class="px-0 pt-1">
            <span class="small app-text-muted"><i class="bi bi-eye me-1"></i>{{ number_format($post->views_count) }} views</span>
        </div>
        @endif

        <!-- Comments Section (Collapsible) -->
        <div id="comments-{{ $post->id }}" class="collapse">
            <div class="px-0 py-3 border-top">
                <!-- Comments List -->
                <div id="comments-list-{{ $post->id }}">
                    @foreach($post->comments->take(3) as $comment)
                    @include('posts.partials.comment', ['comment' => $comment])
                    @endforeach

                    @if($post->comments_count > 3)
                    <div class="text-center mt-2">
                        <button class="btn btn-link" style="color: var(--accent);" onclick="loadMoreComments({{ $post->id }})">
                            View all {{ $post->comments_count }} comments
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Add Comment Form -->
                <div class="mt-3">
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="comment-form d-flex align-items-center gap-2"
                        id="comment-form-{{ $post->id }}">
                        @csrf
                        @auth
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                        @endauth
                        <input type="text" class="form-control border-0 bg-transparent flex-grow-1" placeholder="Add a comment..."
                            name="content" id="comment-input-{{ $post->id }}" maxlength="500">
                        <button class="btn btn-link p-0 fw-semibold text-decoration-none comment-post-btn" type="submit" disabled>
                            Post
                        </button>
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
                        <button type="submit" class="btn btn-accent w-100">
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

    // Handle like/reaction form submission (event delegation)
    const REACTION_EMOJI = { like: '<i class="bi bi-heart-fill text-danger"></i>', love: '❤️', haha: '😂', wow: '😮', sad: '😢', angry: '😡' };

    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.like-form');
        if (!form) return;
        e.preventDefault();

        const wrap = form.closest('.reaction-wrap');
        const reactionIcon = wrap.querySelector('.reaction-icon');
        const card = form.closest('.post-card');
        const likesWrap = card.querySelector('.likes-count-wrap');
        const likesText = card.querySelector('.likes-count-text');

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
                reactionIcon.innerHTML = data.type ? REACTION_EMOJI[data.type] : '<i class="bi bi-heart"></i>';
                form.querySelector('.reaction-type-input').value = data.type || 'like';
                wrap.dataset.reacted = data.type ? '1' : '0';
                if (likesWrap && likesText) {
                    likesText.textContent = `${data.likes_count} ${data.likes_count === 1 ? 'like' : 'likes'}`;
                    likesWrap.classList.toggle('d-none', data.likes_count === 0);
                }
                reactionIcon.style.transform = 'scale(1.25)';
                setTimeout(() => { reactionIcon.style.transform = 'scale(1)'; }, 200);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Reaction picker: pick a specific reaction, then submit the like-form
    document.addEventListener('click', function(e) {
        const option = e.target.closest('.reaction-option');
        if (!option) return;
        const wrap = option.closest('.reaction-wrap');
        const form = wrap.querySelector('.like-form');
        form.querySelector('.reaction-type-input').value = option.dataset.type;
        form.requestSubmit();
        wrap.classList.remove('picker-open');
    });

    // Long-press (touch) reveals the picker without submitting the default reaction
    document.querySelectorAll('.reaction-wrap').forEach(function(wrap) {
        let holdTimer = null;
        const trigger = wrap.querySelector('.like-form button');
        trigger.addEventListener('touchstart', function() {
            holdTimer = setTimeout(() => wrap.classList.add('picker-open'), 350);
        }, { passive: true });
        trigger.addEventListener('touchend', function(e) {
            if (wrap.classList.contains('picker-open')) e.preventDefault();
            clearTimeout(holdTimer);
        });
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.reaction-wrap')) {
            document.querySelectorAll('.reaction-wrap.picker-open').forEach(w => w.classList.remove('picker-open'));
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

    // Enable the "Post" button only once the comment box has text (event delegation)
    document.addEventListener('input', function(e) {
        if (!e.target.id || !e.target.id.startsWith('comment-input-')) return;
        const postBtn = e.target.closest('.comment-form')?.querySelector('.comment-post-btn');
        if (postBtn) postBtn.disabled = e.target.value.trim().length === 0;
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
                const postBtn = form.querySelector('.comment-post-btn');
                if (postBtn) postBtn.disabled = true;
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

    // Toggle read more/less for long content
    window.toggleReadMore = function(postId) {
        const content = document.getElementById(`post-content-${postId}`);
        const btn = document.querySelector(`.post-read-more[data-post-id="${postId}"]`);
        if (!content) return;
        content.classList.toggle('expanded');
        if (btn) {
            const isExpanded = content.classList.contains('expanded');
            btn.innerHTML = isExpanded
                ? 'Show less <i class="bi bi-chevron-up small"></i>'
                : 'Read more <i class="bi bi-chevron-down small"></i>';
        }
    };

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
            window.DevDoko?.toast('Link copied to clipboard!', 'success');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            window.DevDoko?.toast('Failed to copy link', 'error');
        });
    }

    // "Not interested" — hide the post and remove its card from the DOM (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.hide-post-form');
        if (!form) return;
        e.preventDefault();

        const card = form.closest('.post-card');

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
                window.DevDoko?.toast("You won't see this post again.", 'success');
                card?.remove();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Delete-post confirmation (event delegation)
    document.addEventListener('submit', async function(e) {
        const form = e.target.closest('.delete-post-form');
        if (!form || form.dataset.confirmed) return;
        e.preventDefault();
        const confirmed = window.DevDoko?.confirm
            ? await window.DevDoko.confirm('Are you sure you want to delete this post?')
            : confirm('Are you sure you want to delete this post?');
        if (confirmed) {
            form.dataset.confirmed = '1';
            form.submit();
        }
    });

    // Open image in modal
    function openImageModal(imageUrl, title) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
        modalImage.alt = title || 'Image';

        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Single click opens the image modal; a double-click (within 250ms) instead
    // likes the post with a heart-burst, Instagram-style — the delay is what lets
    // us tell the two apart before the first click's action fires.
    let postImageClickTimer = null;
    document.addEventListener('click', function(e) {
        const img = e.target.closest('.post-image');
        if (!img) return;
        if (postImageClickTimer) {
            clearTimeout(postImageClickTimer);
            postImageClickTimer = null;
            return;
        }
        postImageClickTimer = setTimeout(() => {
            postImageClickTimer = null;
            openImageModal(img.dataset.imageUrl, img.dataset.imageTitle);
        }, 250);
    });

    document.addEventListener('dblclick', function(e) {
        const img = e.target.closest('.post-image');
        if (!img) return;
        e.preventDefault();

        const container = img.closest('.post-image-container');
        if (container) {
            const burst = document.createElement('i');
            burst.className = 'bi bi-heart-fill heart-burst';
            container.appendChild(burst);
            burst.addEventListener('animationend', () => burst.remove());
        }

        const wrap = img.closest('.post-card')?.querySelector('.reaction-wrap');
        if (wrap && wrap.dataset.reacted !== '1') {
            wrap.querySelector('.like-form').requestSubmit();
        }
    });

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

    // Keep the "N/total" gallery badge in sync with the active carousel slide
    document.addEventListener('slide.bs.carousel', function(e) {
        const counter = e.target.querySelector('.gallery-counter');
        if (!counter) return;
        const total = e.target.querySelectorAll('.carousel-item').length;
        counter.textContent = `${e.to + 1}/${total}`;
    });

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
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
    }

    [data-bs-theme="dark"] .post-card {
        border-bottom-color: rgba(255, 255, 255, 0.06);
    }

    .post-card:last-child {
        border-bottom: none;
    }

    .action-btn {
        transition: color 0.15s ease, transform 0.15s ease;
        border-radius: 8px;
        padding: 4px 6px;
    }

    .action-btn-icon {
        padding: 4px 6px;
        line-height: 1;
    }

    .comment-post-btn:disabled {
        color: #a3d2ff !important;
        opacity: 1;
    }

    .heart-burst {
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 80px;
        color: #fff;
        text-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
        transform: translate(-50%, -50%) scale(0);
        pointer-events: none;
        z-index: 5;
        animation: heartBurst 0.7s ease-out forwards;
    }

    @keyframes heartBurst {
        0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
        15% { transform: translate(-50%, -50%) scale(1.15); opacity: 1; }
        30% { transform: translate(-50%, -50%) scale(0.95); }
        100% { transform: translate(-50%, -50%) scale(1.05); opacity: 0; }
    }

    .action-btn:hover {
        color: var(--accent);
    }

    .post-content {
        line-height: 1.6;
    }

    .post-content-truncate {
        max-height: 150px;
        overflow: hidden;
        position: relative;
    }

    .post-content-truncate.expanded {
        max-height: none;
        -webkit-mask-image: none;
        mask-image: none;
    }

    /* Fade with a mask, not a coloured overlay — the old
       linear-gradient(transparent, white) showed as a white smear in dark mode
       and over code blocks. A mask fades to transparent in any theme. */
    .post-content-truncate:not(.expanded) {
        -webkit-mask-image: linear-gradient(to bottom, #000 calc(100% - 40px), transparent);
        mask-image: linear-gradient(to bottom, #000 calc(100% - 40px), transparent);
    }

    /* Size to the image instead of forcing a 16:9 box. The old
       padding-top + object-fit:cover combination cropped the top and
       bottom off any portrait image. */
    .post-image-container {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8f9fa;
    }

    .post-image {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 600px;
        object-fit: contain;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .post-image:hover {
        transform: scale(1.02);
    }

    .code-block-feed {
        max-height: 200px;
        overflow: auto;
        position: relative;
    }

    .code-block-feed::after {
        content: '';
        position: sticky;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: linear-gradient(transparent, #1a1a2e);
        display: block;
        pointer-events: none;
        margin: 0 -1rem -1rem;
        padding: 0 1rem;
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

    .reaction-picker {
        position: absolute;
        bottom: 100%;
        left: -8px;
        z-index: 20;
        display: flex;
        gap: 2px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 24px;
        padding: 4px;
        margin-bottom: 6px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        opacity: 0;
        pointer-events: none;
        transform: translateY(4px) scale(0.9);
        transform-origin: bottom left;
        transition: opacity 0.12s ease, transform 0.12s ease;
    }

    [data-bs-theme="dark"] .reaction-picker {
        background: #161b22;
        border-color: #30363d;
    }

    .reaction-wrap:hover .reaction-picker,
    .reaction-wrap.picker-open .reaction-picker {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0) scale(1);
    }

    .reaction-option {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: transparent;
        font-size: 19px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.12s ease;
    }

    .reaction-option:hover {
        transform: scale(1.3) translateY(-3px);
    }

    .gallery-dots {
        margin: 0 !important;
        padding-top: 8px;
        bottom: -4px;
    }

    .gallery-dots [data-bs-target] {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #adb5bd;
        opacity: 1;
    }

    .gallery-dots [data-bs-target].active {
        background-color: var(--accent);
    }
</style>
