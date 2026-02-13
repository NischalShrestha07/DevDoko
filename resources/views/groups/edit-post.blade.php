{{-- resources/views/groups/edit-post.blade.php
@extends('layouts.app')

@section('title', 'Edit Post - ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Back to Post -->
            <div class="mb-3">
                <a href="{{ route('groups.post', [$group->slug, $post->id]) }}"
                    class="text-decoration-none d-inline-flex align-items-center text-secondary">
                    <i class="bi bi-arrow-left-circle-fill me-2"></i>
                    Back to Post
                </a>
            </div>

            <!-- Edit Post Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-semibold mb-0 d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                        Edit Post
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('groups.posts.update', [$group->slug, $post->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-heading me-1"></i> Title
                            </label>
                            <input type="text" name="title"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                value="{{ old('title', $post->title) }}" placeholder="Post title..." required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Post Type -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag me-1"></i> Post Type
                            </label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                @foreach($postTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $post->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-text-paragraph me-1"></i> Content
                            </label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                rows="10" placeholder="Write your post content here..."
                                required>{{ old('content', $post->content) }}</textarea>
                            <div class="form-text text-muted">
                                <i class="bi bi-markdown"></i> Markdown is supported
                            </div>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Attachments (Read-only) -->
                        @if($post->attachments && count($post->attachments) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-paperclip me-1"></i> Current Attachments
                            </label>
                            <div class="bg-light p-3 rounded-3">
                                @foreach($post->attachments as $attachment)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-file-earmark me-2"></i>
                                    <span class="flex-grow-1">{{ $attachment['name'] }}</span>
                                    <span class="text-muted small me-2">{{ round($attachment['size'] / 1024) }}
                                        KB</span>
                                    <a href="{{ Storage::url($attachment['path']) }}"
                                        class="btn btn-sm btn-outline-secondary" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> To add or remove attachments, please create a new
                                post.
                            </div>
                        </div>
                        @endif

                        <hr class="my-4">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('groups.post', [$group->slug, $post->id]) }}"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            @if($group->canManage(auth()->user()) || auth()->id() === $post->user_id)
            <div class="card border-0 shadow-sm mt-4 border border-danger">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-semibold mb-0 text-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Danger Zone
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-semibold mb-1">Delete this post</h6>
                            <p class="text-muted small mb-0">
                                Once you delete a post, there is no going back. Please be certain.
                            </p>
                        </div>
                        <form action="{{ route('groups.posts.destroy', [$group->slug, $post->id]) }}" method="POST"
                            onsubmit="return confirm('Are you absolutely sure you want to delete this post? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Delete Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection --}}

{{-- resources/views/groups/edit-post.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Post - ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Back to Post -->
            <div class="mb-3">
                <a href="{{ route('groups.post', [$group->slug, $post->id]) }}"
                    class="text-decoration-none d-inline-flex align-items-center text-secondary">
                    <i class="bi bi-arrow-left-circle-fill me-2"></i>
                    Back to Post
                </a>
            </div>

            <!-- Edit Post Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-semibold mb-0 d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                        Edit Post
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('groups.posts.update', [$group->slug, $post->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-heading me-1"></i> Title
                            </label>
                            <input type="text" name="title"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                value="{{ old('title', $post->title) }}" placeholder="Post title..." required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Post Type -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag me-1"></i> Post Type
                            </label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                @php
                                // Define fallback post types if not passed from controller
                                $availablePostTypes = $postTypes ?? [
                                'general' => '📝 General',
                                'announcement' => '📢 Announcement',
                                'question' => '❓ Question',
                                'resource' => '📚 Resource',
                                'event' => '📅 Event',
                                'job' => '💼 Job',
                                ];
                                @endphp

                                @foreach($availablePostTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $post->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-text-paragraph me-1"></i> Content
                            </label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                rows="10" placeholder="Write your post content here..."
                                required>{{ old('content', $post->content) }}</textarea>
                            <div class="form-text text-muted">
                                <i class="bi bi-markdown"></i> Markdown is supported
                            </div>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Attachments (Read-only) -->
                        @if($post->attachments && count($post->attachments) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-paperclip me-1"></i> Current Attachments
                            </label>
                            <div class="bg-light p-3 rounded-3">
                                @foreach($post->attachments as $attachment)
                                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                    @php
                                    $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                                    $icon = match($extension) {
                                    'jpg', 'jpeg', 'png', 'gif', 'webp' => 'bi-file-image',
                                    'pdf' => 'bi-file-pdf',
                                    'doc', 'docx' => 'bi-file-word',
                                    'xls', 'xlsx' => 'bi-file-excel',
                                    'zip', 'rar', '7z' => 'bi-file-zip',
                                    default => 'bi-file-earmark'
                                    };
                                    @endphp
                                    <i class="bi {{ $icon }} me-2 fs-5"></i>
                                    <span class="flex-grow-1">{{ $attachment['name'] }}</span>
                                    <span class="text-muted small me-3">{{ round($attachment['size'] / 1024) }}
                                        KB</span>
                                    <a href="{{ Storage::url($attachment['path']) }}"
                                        class="btn btn-sm btn-outline-secondary" target="_blank" title="View file">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle"></i> To add, remove, or update attachments, please create a
                                new post or contact a group admin.
                            </div>
                        </div>
                        @endif

                        <!-- Post Metadata -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded-3">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-calendar3"></i> Created
                                    </small>
                                    <span class="fw-semibold">{{ $post->created_at->format('M d, Y \a\t h:i A')
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded-3">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-pencil"></i> Last Updated
                                    </small>
                                    <span class="fw-semibold">{{ $post->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('groups.post', [$group->slug, $post->id]) }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            @if($group->canManage(auth()->user()) || auth()->id() === $post->user_id)
            <div class="card border-0 shadow-sm mt-4 border border-danger">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-semibold mb-0 text-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Danger Zone
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-semibold mb-1">Delete this post</h6>
                            <p class="text-muted small mb-0">
                                Once you delete a post, there is no going back. All comments and attachments will be
                                permanently removed.
                            </p>
                        </div>
                        <form action="{{ route('groups.posts.destroy', [$group->slug, $post->id]) }}" method="POST"
                            onsubmit="return confirm('Are you absolutely sure you want to delete this post? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Delete Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .border-bottom:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
</style>

<script>
    // Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="content"]');
    if (textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});
</script>
@endsection