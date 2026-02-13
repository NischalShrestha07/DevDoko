{{-- resources/views/groups/post.blade.php --}}
@extends('layouts.app')

@section('title', $post->title . ' - ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-12">
            <!-- Back to Group -->
            <div class="mb-3">
                <a href="{{ route('groups.show', $group->slug) }}"
                    class="text-decoration-none d-inline-flex align-items-center text-secondary hover-text-primary">
                    <i class="bi bi-arrow-left-circle-fill me-2"></i>
                    Back to {{ $group->name }}
                </a>
            </div>

            <!-- Post Card -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <!-- Post Header with Actions -->
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('profile.show', $post->user->profile->username ?? $post->user->name) }}">
                            <img src="{{ $post->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                                class="rounded-circle border" style="width: 48px; height: 48px; object-fit: cover;">
                        </a>
                        <div class="ms-3">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('profile.show', $post->user->profile->username ?? $post->user->name) }}"
                                    class="text-decoration-none text-dark fw-semibold">
                                    {{ $post->user->profile->username ?? $post->user->name }}
                                </a>
                                @if($post->user->is_verified ?? false)
                                <i class="bi bi-patch-check-fill text-primary ms-2" style="font-size: 14px;"></i>
                                @endif
                                @if($post->user_id === $group->owner_id)
                                <span class="badge bg-warning bg-opacity-10 text-warning ms-2">👑 Owner</span>
                                @elseif($post->user->pivot->role ?? '' === 'admin')
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-2">🛡️ Admin</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <span>{{ $post->formatted_date ?? $post->created_at->diffForHumans() }}</span>
                                <span class="mx-2">•</span>
                                <span class="badge bg-light text-dark">
                                    @if($post->type === 'announcement') 📢 Announcement
                                    @elseif($post->type === 'question') ❓ Question
                                    @elseif($post->type === 'resource') 📚 Resource
                                    @elseif($post->type === 'event') 📅 Event
                                    @elseif($post->type === 'job') 💼 Job
                                    @else 📝 General
                                    @endif
                                </span>
                                @if($post->is_pinned)
                                <span class="badge bg-warning bg-opacity-10 text-warning ms-2">
                                    <i class="bi bi-pin-angle-fill"></i> Pinned
                                </span>
                                @endif
                                @if($post->is_important)
                                <span class="badge bg-danger bg-opacity-10 text-danger ms-2">
                                    <i class="bi bi-exclamation-circle-fill"></i> Important
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Post Actions Dropdown -->
                    @if(auth()->id() === $post->user_id || $group->canManage(auth()->user()))
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            @if(auth()->id() === $post->user_id)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('groups.posts.edit', [$group->slug, $post->id]) }}">
                                    <i class="bi bi-pencil me-2"></i> Edit Post
                                </a>
                            </li>
                            @endif
                            @if($group->canManage(auth()->user()))
                            @if(!$post->is_pinned)
                            <li>
                                <form action="{{ route('groups.posts.pin', [$group->slug, $post->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-pin-angle me-2"></i> Pin Post
                                    </button>
                                </form>
                            </li>
                            @else
                            <li>
                                <form action="{{ route('groups.posts.unpin', [$group->slug, $post->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-pin-angle-fill me-2"></i> Unpin Post
                                    </button>
                                </form>
                            </li>
                            @endif
                            @if(!$post->is_important)
                            <li>
                                <form action="{{ route('groups.posts.important', [$group->slug, $post->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-exclamation-circle me-2"></i> Mark Important
                                    </button>
                                </form>
                            </li>
                            @else
                            <li>
                                <form action="{{ route('groups.posts.unimportant', [$group->slug, $post->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i> Remove Important
                                    </button>
                                </form>
                            </li>
                            @endif
                            @endif
                            @if(auth()->id() === $post->user_id || $group->canManage(auth()->user()))
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('groups.posts.destroy', [$group->slug, $post->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i> Delete Post
                                    </button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Post Content -->
                <div class="card-body p-4 pt-2">
                    <h4 class="fw-bold mb-3">{{ $post->title }}</h4>

                    <div class="post-content lh-lg text-dark" style=" word-wrap: break-word;">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Enhanced Attachments Section -->
                    @if($post->attachments && count($post->attachments) > 0)
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-paperclip text-primary me-2"></i>
                            <h6 class="fw-semibold mb-0">Attachments ({{ count($post->attachments) }})</h6>
                        </div>

                        @php
                        $imageAttachments = array_filter($post->attachments, fn($att) => strpos($att['type'], 'image/')
                        !== false);
                        $videoAttachments = array_filter($post->attachments, fn($att) => strpos($att['type'], 'video/')
                        !== false);
                        $codeAttachments = array_filter($post->attachments, fn($att) =>
                        strpos($att['type'], 'text/') !== false ||
                        in_array(pathinfo($att['name'], PATHINFO_EXTENSION), ['js', 'php', 'py', 'java', 'html', 'css',
                        'json', 'xml', 'sql', 'sh', 'bash', 'yml', 'yaml', 'md', 'rst'])
                        );
                        $otherAttachments = array_filter($post->attachments, fn($att) =>
                        !in_array($att, $imageAttachments) &&
                        !in_array($att, $videoAttachments) &&
                        !in_array($att, $codeAttachments)
                        );
                        @endphp

                        <!-- Image Gallery -->
                        @if(count($imageAttachments) > 0)
                        <div class="mb-4">
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-images me-1"></i> Images
                            </small>
                            <div class="row g-2">
                                @foreach($imageAttachments as $index => $attachment)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="position-relative rounded-3 overflow-hidden"
                                        style="aspect-ratio: 1; cursor: pointer;"
                                        onclick="openImageModal('{{ Storage::url($attachment['path']) }}', '{{ $attachment['name'] }}')">
                                        <img src="{{ Storage::url($attachment['path']) }}"
                                            alt="{{ $attachment['name'] }}" class="w-100 h-100"
                                            style="object-fit: cover;">
                                        @if(count($imageAttachments) > 1)
                                        <div class="position-absolute bottom-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">
                                                {{ $index + 1 }}/{{ count($imageAttachments) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Video Player -->
                        @if(count($videoAttachments) > 0)
                        <div class="mb-4">
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-camera-video me-1"></i> Videos
                            </small>
                            <div class="row g-3">
                                @foreach($videoAttachments as $attachment)
                                <div class="col-md-6">
                                    <div class="bg-dark rounded-3 overflow-hidden">
                                        <video class="w-100" controls style="max-height: 200px;">
                                            <source src="{{ Storage::url($attachment['path']) }}"
                                                type="{{ $attachment['type'] }}">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="p-2 bg-dark border-top border-secondary">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-camera-video-fill text-primary me-2"></i>
                                                <small class="text-white-50 text-truncate flex-grow-1">{{
                                                    $attachment['name'] }}</small>
                                                <a href="{{ Storage::url($attachment['path']) }}"
                                                    class="btn btn-sm btn-outline-light ms-2"
                                                    download="{{ $attachment['name'] }}">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Code Preview -->
                        @if(count($codeAttachments) > 0)
                        <div class="mb-4">
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-file-earmark-code me-1"></i> Code Files
                            </small>
                            @foreach($codeAttachments as $attachment)
                            @php
                            $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                            $language = match($extension) {
                            'js', 'mjs', 'cjs' => 'javascript',
                            'ts', 'tsx' => 'typescript',
                            'php' => 'php',
                            'py' => 'python',
                            'rb' => 'ruby',
                            'java' => 'java',
                            'kt', 'kts' => 'kotlin',
                            'swift' => 'swift',
                            'go' => 'go',
                            'rs' => 'rust',
                            'c' => 'c',
                            'cpp', 'cc', 'cxx' => 'cpp',
                            'cs' => 'csharp',
                            'html', 'htm' => 'html',
                            'css', 'scss', 'sass', 'less' => 'css',
                            'json' => 'json',
                            'xml' => 'xml',
                            'yml', 'yaml' => 'yaml',
                            'md', 'markdown' => 'markdown',
                            'sql' => 'sql',
                            'sh', 'bash', 'zsh' => 'bash',
                            'dockerfile' => 'dockerfile',
                            'gitignore' => 'gitignore',
                            'env' => 'dotenv',
                            default => 'plaintext'
                            };

                            $content = Storage::disk('public')->exists($attachment['path'])
                            ? Storage::disk('public')->get($attachment['path'])
                            : '';
                            @endphp
                            <div class="bg-dark rounded-3 overflow-hidden mb-3">
                                <div
                                    class="d-flex justify-content-between align-items-center px-3 py-2 bg-black bg-opacity-50">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-code-fill text-info me-2"></i>
                                        <span class="text-white-50 small">{{ $attachment['name'] }}</span>
                                        <span class="badge bg-primary ms-2">{{ strtoupper($extension) }}</span>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-outline-light me-1"
                                            onclick="copyCode({{ json_encode($content) }})" title="Copy code">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                        <a href="{{ Storage::url($attachment['path']) }}"
                                            class="btn btn-sm btn-outline-light" download="{{ $attachment['name'] }}">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                                @if($content)
                                <pre class="mb-0 p-3"
                                    style="max-height: 300px; overflow: auto;"><code class="language-{{ $language }}">{{ $content }}</code></pre>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Other Files -->
                        @if(count($otherAttachments) > 0)
                        <div class="mb-2">
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-files me-1"></i> Other Files
                            </small>
                            <div class="row g-2">
                                @foreach($otherAttachments as $attachment)
                                @php
                                $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                                $icon = match($extension) {
                                'pdf' => 'bi-file-pdf-fill text-danger',
                                'doc', 'docx' => 'bi-file-word-fill text-primary',
                                'xls', 'xlsx' => 'bi-file-excel-fill text-success',
                                'ppt', 'pptx' => 'bi-file-ppt-fill text-warning',
                                'zip', 'rar', '7z', 'tar', 'gz' => 'bi-file-zip-fill text-secondary',
                                'mp3', 'wav', 'ogg', 'flac' => 'bi-file-music-fill text-info',
                                default => 'bi-file-earmark-fill text-secondary'
                                };
                                @endphp
                                <div class="col-md-6">
                                    <a href="{{ Storage::url($attachment['path']) }}" class="text-decoration-none"
                                        download="{{ $attachment['name'] }}">
                                        <div
                                            class="d-flex align-items-center p-2 border rounded-3 hover-shadow transition">
                                            <div class="bg-light rounded-2 p-2 me-2">
                                                <i class="bi {{ $icon }} fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1 min-width-0">
                                                <small class="fw-semibold text-dark d-block text-truncate">
                                                    {{ $attachment['name'] }}
                                                </small>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted">{{ round($attachment['size'] / 1024) }}
                                                        KB</small>
                                                    <span class="badge bg-light text-dark ms-2">{{
                                                        strtoupper($extension) }}</span>
                                                </div>
                                            </div>
                                            <i class="bi bi-download text-muted ms-2"></i>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Post Stats -->
                    <div class="d-flex align-items-center gap-3 mt-4 pt-3 border-top">
                        <form action="{{ route('groups.posts.like', [$group->slug, $post->id]) }}" method="POST"
                            class="like-form">
                            @csrf
                            <button type="submit"
                                class="btn btn-link text-dark p-0 text-decoration-none d-flex align-items-center gap-2">
                                <div
                                    class="rounded-circle p-2 {{ $post->is_liked ? 'bg-danger bg-opacity-10' : 'bg-light' }} transition">
                                    <i class="bi bi-heart{{ $post->is_liked ? '-fill text-danger' : '' }} fs-5"></i>
                                </div>
                                <span class="{{ $post->is_liked ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    {{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}
                                </span>
                            </button>
                        </form>

                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle p-2 bg-light">
                                <i class="bi bi-chat fs-5 text-primary"></i>
                            </div>
                            <span class="text-muted">
                                {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                            </span>
                        </div>

                        @if($post->views_count ?? false)
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle p-2 bg-light">
                                <i class="bi bi-eye fs-5 text-info"></i>
                            </div>
                            <span class="text-muted">
                                {{ $post->views_count }} {{ Str::plural('view', $post->views_count) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card border-0 shadow-sm" id="comments">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0 d-flex align-items-center">
                        <i class="bi bi-chat-text me-2 text-primary"></i>
                        Comments
                        <span class="badge bg-light text-dark ms-2">{{ $post->comments_count }}</span>
                    </h5>
                    <small class="text-muted">Most relevant first</small>
                </div>

                <div class="card-body">
                    <!-- Add Comment - Enhanced -->
                    @if($group->is_member)
                    <form action="{{ route('groups.posts.comments.store', [$group->slug, $post->id]) }}" method="POST"
                        class="mb-4" id="commentForm">
                        @csrf
                        <div class="d-flex gap-3">
                            <img src="{{ auth()->user()->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="bg-light rounded-4 p-3">
                                    <textarea name="content" class="form-control border-0 bg-transparent p-0" rows="2"
                                        placeholder="Add a comment..."
                                        style="resize: none; outline: none; box-shadow: none;" id="commentInput"
                                        required></textarea>
                                </div>
                                <div class="d-flex justify-content-end align-items-center mt-2">
                                    <button type="submit" class="btn btn-primary btn-sm px-4" id="submitComment">
                                        <i class="bi bi-send me-1"></i> Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info bg-opacity-10 border-0 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <span>
                            <a href="{{ route('groups.join', $group->slug) }}" class="alert-link fw-semibold">Join the
                                group</a>
                            to join the conversation.
                        </span>
                    </div>
                    @endif

                    <!-- Comments List - Enhanced -->
                    @forelse($post->comments as $comment)
                    <div class="d-flex gap-3 mb-4 comment-item" id="comment-{{ $comment->id }}">
                        <img src="{{ $comment->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                            class="rounded-circle border flex-shrink-0"
                            style="width: 40px; height: 40px; object-fit: cover;">

                        <div class="flex-grow-1">
                            <div class="bg-light rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <a href="{{ route('profile.show', $comment->user->profile->username ?? $comment->user->name) }}"
                                            class="text-decoration-none fw-semibold text-dark">
                                            {{ $comment->user->profile->username ?? $comment->user->name }}
                                        </a>
                                        @if($comment->user_id === $post->user_id)
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary ms-2 small">Author</span>
                                        @endif
                                        <span class="text-muted small ms-2">
                                            <i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    @if(auth()->id() === $comment->user_id || $group->canManage(auth()->user()))
                                    <div class="dropdown">
                                        <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            @if(auth()->id() === $comment->user_id)
                                            <li>
                                                <button class="dropdown-item" onclick="editComment({{ $comment->id }})">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </button>
                                            </li>
                                            @endif
                                            <li>
                                                <form
                                                    action="{{ route('groups.comments.destroy', [$group->slug, $comment->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Delete this comment?')">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <!-- Comment Content -->
                                <div id="comment-content-{{ $comment->id }}">
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>

                                <!-- Edit Form -->
                                <form id="comment-edit-form-{{ $comment->id }}"
                                    action="{{ route('groups.comments.update', [$group->slug, $comment->id]) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                    <div class="mt-2">
                                        <textarea name="content" class="form-control form-control-sm"
                                            rows="2">{{ $comment->content }}</textarea>
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="cancelEditComment({{ $comment->id }})">Cancel</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Comment Actions -->
                                <div class="d-flex align-items-center gap-3 mt-2">
                                    <form action="{{ route('groups.comments.like', [$group->slug, $comment->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-link text-dark p-0 text-decoration-none small d-flex align-items-center gap-1">
                                            <i
                                                class="bi bi-heart{{ $comment->is_liked ? '-fill text-danger' : '' }}"></i>
                                            <span class="{{ $comment->is_liked ? 'text-danger' : 'text-muted' }}">
                                                {{ $comment->likes_count }}
                                            </span>
                                        </button>
                                    </form>

                                    <button
                                        class="btn btn-link text-dark p-0 text-decoration-none small d-flex align-items-center gap-1 reply-toggle"
                                        data-comment-id="{{ $comment->id }}">
                                        <i class="bi bi-reply"></i>
                                        Reply
                                    </button>

                                    @if($comment->replies->count() > 0)
                                    <button
                                        class="btn btn-link text-dark p-0 text-decoration-none small d-flex align-items-center gap-1 view-replies-toggle"
                                        data-comment-id="{{ $comment->id }}">
                                        <i class="bi bi-chevron-down"></i>
                                        {{ $comment->replies->count() }} {{ Str::plural('reply',
                                        $comment->replies->count()) }}
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Reply Form -->
                            <div id="reply-form-{{ $comment->id }}" class="mt-3 ms-4" style="display: none;">
                                <form action="{{ route('groups.posts.comments.store', [$group->slug, $post->id]) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div class="d-flex gap-2">
                                        <img src="{{ auth()->user()->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                            class="rounded-circle"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <div class="bg-light rounded-4 p-2">
                                                <input type="text" name="content"
                                                    class="form-control border-0 bg-transparent p-2"
                                                    placeholder="Write a reply...">
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                                    <i class="bi bi-send me-1"></i> Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                            <div id="replies-{{ $comment->id }}" class="mt-3 ms-4" style="display: none;">
                                @foreach($comment->replies as $reply)
                                <div class="d-flex gap-2 mb-3" id="comment-{{ $reply->id }}">
                                    <img src="{{ $reply->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}"
                                        class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <div class="bg-light rounded-4 p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <a href="{{ route('profile.show', $reply->user->profile->username ?? $reply->user->name) }}"
                                                        class="text-decoration-none fw-semibold small text-dark">
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
                                                    <button type="submit" class="btn btn-link text-danger p-0 small"
                                                        onclick="return confirm('Delete this reply?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                            <p class="mb-0 small mt-1">{{ $reply->content }}</p>
                                            <div class="mt-1">
                                                <form
                                                    action="{{ route('groups.comments.like', [$group->slug, $reply->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-link text-dark p-0 text-decoration-none small">
                                                        <i
                                                            class="bi bi-heart{{ $reply->is_liked ? '-fill text-danger' : '' }}"></i>
                                                        <span class="ms-1 small">{{ $reply->likes_count }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-chat fs-1 text-muted"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">No comments yet</h6>
                        <p class="text-muted mb-0">Be the first to share your thoughts!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" class="img-fluid rounded-3" alt="">
                <div id="modalImageCaption" class="text-white text-start mt-2 small"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-text-primary:hover {
        color: #0d6efd !important;
    }

    .hover-shadow {
        transition: all 0.2s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-1px);
    }

    .transition {
        transition: all 0.2s ease;
    }

    .post-content {
        line-height: 1.7;
    }

    .post-content p {
        margin-bottom: 1.2rem;
    }

    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }

    .bg-opacity-50 {
        --bs-bg-opacity: 0.5;
    }

    .bg-opacity-75 {
        --bs-bg-opacity: 0.75;
    }

    .min-width-0 {
        min-width: 0;
    }
</style>

<script>
    // Toggle reply forms
document.querySelectorAll('.reply-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
    });
});

// Toggle replies visibility
document.querySelectorAll('.view-replies-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        const replies = document.getElementById(`replies-${commentId}`);
        const icon = this.querySelector('i');

        if (replies.style.display === 'none') {
            replies.style.display = 'block';
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        } else {
            replies.style.display = 'none';
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        }
    });
});

// Edit comment
function editComment(commentId) {
    document.getElementById(`comment-content-${commentId}`).style.display = 'none';
    document.getElementById(`comment-edit-form-${commentId}`).style.display = 'block';
}

// Cancel edit
function cancelEditComment(commentId) {
    document.getElementById(`comment-content-${commentId}`).style.display = 'block';
    document.getElementById(`comment-edit-form-${commentId}`).style.display = 'none';
}

// Open image modal
function openImageModal(imageUrl, caption = '') {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalImageCaption').textContent = caption;
    modal.show();
}

// Copy code to clipboard
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Code copied to clipboard!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}

// Initialize highlight.js
document.addEventListener('DOMContentLoaded', function() {
    if (typeof hljs !== 'undefined') {
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });
    }

    // Auto-resize textarea
    const commentInput = document.getElementById('commentInput');
    if (commentInput) {
        commentInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});
</script>
@endsection