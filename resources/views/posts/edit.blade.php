{{-- resources/views/posts/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Post - ' . Str::limit($post->title ?? 'Untitled', 30) . ' | DevDoko')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark me-3">
                            <i class="bi bi-x-lg fs-4"></i>
                        </a>
                        <h5 class="mb-0 fw-bold">Edit Post</h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Post Type Indicator -->
                    <div class="border-bottom px-4 py-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="bi bi-{{ $post->type_icon }} me-1"></i>
                                Editing {{ ucfirst($post->type) }} Post
                            </span>
                            <span class="ms-3 text-muted small">
                                <i class="bi bi-clock"></i> Created {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <form id="editPostForm" action="{{ route('posts.update', $post) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" id="postType" value="{{ $post->type }}">

                        <!-- Post Content Area -->
                        <div class="p-4">
                            <!-- Title Section (shown for relevant types) -->
                            @if(in_array($post->type, ['text', 'article', 'question', 'project']))
                            <div id="titleSection" class="mb-3">
                                <input type="text" class="form-control border-0 fs-4 fw-bold" id="title" name="title"
                                    placeholder="Title (optional)" maxlength="200"
                                    value="{{ old('title', $post->title) }}">
                                @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Content Section (for text-based posts) -->
                            @if(in_array($post->type, ['text', 'article', 'question', 'project', 'status']))
                            <div class="mb-3" id="contentSection">
                                <textarea class="form-control border-0" id="content" name="content" rows="6"
                                    placeholder="What's on your mind?"
                                    maxlength="20000">{{ old('content', $post->content) }}</textarea>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div class="text-muted small">
                                        <span id="charCount">{{ strlen($post->content ?? '') }}</span>/20000
                                    </div>
                                    <div class="small">
                                        <i class="bi bi-clock"></i>
                                        <span id="readingTime">{{ $post->reading_time ?? 1 }} min read</span>
                                    </div>
                                </div>
                                @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Code Section -->
                            @if($post->type === 'code')
                            <div id="codeSection" class="mb-3">
                                <div class="mb-3">
                                    <select class="form-select" id="code_language" name="code_language">
                                        <option value="">Select Language</option>
                                        <option value="javascript" {{ old('code_language', $post->code_language) ==
                                            'javascript' ? 'selected' : '' }}>JavaScript</option>
                                        <option value="typescript" {{ old('code_language', $post->code_language) ==
                                            'typescript' ? 'selected' : '' }}>TypeScript</option>
                                        <option value="python" {{ old('code_language', $post->code_language) == 'python'
                                            ? 'selected' : '' }}>Python</option>
                                        <option value="java" {{ old('code_language', $post->code_language) == 'java' ?
                                            'selected' : '' }}>Java</option>
                                        <option value="php" {{ old('code_language', $post->code_language) == 'php' ?
                                            'selected' : '' }}>PHP</option>
                                        <option value="cpp" {{ old('code_language', $post->code_language) == 'cpp' ?
                                            'selected' : '' }}>C++</option>
                                        <option value="csharp" {{ old('code_language', $post->code_language) == 'csharp'
                                            ? 'selected' : '' }}>C#</option>
                                        <option value="ruby" {{ old('code_language', $post->code_language) == 'ruby' ?
                                            'selected' : '' }}>Ruby</option>
                                        <option value="go" {{ old('code_language', $post->code_language) == 'go' ?
                                            'selected' : '' }}>Go</option>
                                        <option value="rust" {{ old('code_language', $post->code_language) == 'rust' ?
                                            'selected' : '' }}>Rust</option>
                                        <option value="swift" {{ old('code_language', $post->code_language) == 'swift' ?
                                            'selected' : '' }}>Swift</option>
                                        <option value="kotlin" {{ old('code_language', $post->code_language) == 'kotlin'
                                            ? 'selected' : '' }}>Kotlin</option>
                                        <option value="html" {{ old('code_language', $post->code_language) == 'html' ?
                                            'selected' : '' }}>HTML</option>
                                        <option value="css" {{ old('code_language', $post->code_language) == 'css' ?
                                            'selected' : '' }}>CSS</option>
                                        <option value="sql" {{ old('code_language', $post->code_language) == 'sql' ?
                                            'selected' : '' }}>SQL</option>
                                        <option value="bash" {{ old('code_language', $post->code_language) == 'bash' ?
                                            'selected' : '' }}>Bash/Shell</option>
                                    </select>
                                </div>
                                <textarea class="form-control font-monospace" id="code_snippet" name="code_snippet"
                                    rows="12" placeholder="Paste your code here..."
                                    maxlength="20000">{{ old('code_snippet', $post->code_snippet) }}</textarea>
                                <div class="text-end text-muted small mt-1">
                                    <span id="codeCharCount">{{ strlen($post->code_snippet ?? '') }}</span>/20000
                                </div>
                                @error('code_snippet')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Image Section -->
                            @if($post->type === 'image')
                            <div id="imageSection" class="mb-3">
                                <div class="border rounded p-4 text-center">
                                    <!-- Current Image Preview -->
                                    @if($post->image_path)
                                    <div id="currentImagePreview" class="mb-3">
                                        <img src="{{ Storage::url($post->image_path) }}" alt="Current image"
                                            class="img-fluid rounded" style="max-height: 300px;">
                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="remove_image" class="form-check-input"
                                                    id="removeImage">
                                                <label class="form-check-label text-danger" for="removeImage">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- New Image Upload -->
                                    <div id="imageUploadArea" class="{{ $post->image_path ? 'py-3' : 'py-5' }}">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted mb-3">Upload a new image (optional)</p>
                                        <input type="file" id="image" name="image" accept="image/*" class="d-none">
                                        <button type="button" id="browseImage" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Select New Image
                                        </button>
                                        <div id="newImagePreview" class="d-none mt-3">
                                            <img src="" alt="Preview" class="img-fluid rounded"
                                                style="max-height: 200px;">
                                            <div class="mt-2">
                                                <button type="button" id="removeNewImage" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Maximum file size: 20MB. Supported: JPEG, PNG, GIF, WebP, SVG
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Video Section -->
                            @if($post->type === 'video')
                            <div id="videoSection" class="mb-3">
                                <div class="border rounded p-4 text-center">
                                    <!-- Current Video Preview -->
                                    @if($post->video_path)
                                    <div id="currentVideoPreview" class="mb-3">
                                        <video controls class="w-100 rounded" style="max-height: 300px;">
                                            <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="remove_video" class="form-check-input"
                                                    id="removeVideo">
                                                <label class="form-check-label text-danger" for="removeVideo">
                                                    Remove current video
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- New Video Upload -->
                                    <div id="videoUploadArea" class="{{ $post->video_path ? 'py-3' : 'py-5' }}">
                                        <i class="bi bi-camera-video fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted mb-3">Upload a new video (optional)</p>
                                        <input type="file" id="video" name="video" accept="video/*" class="d-none">
                                        <button type="button" id="browseVideo" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Select New Video
                                        </button>
                                        <div id="newVideoPreview" class="d-none mt-3">
                                            <video id="videoPlayer" controls class="w-100 rounded"
                                                style="max-height: 200px;">
                                                <source src="" type="video/mp4">
                                            </video>
                                            <div class="mt-2">
                                                <button type="button" id="removeNewVideo" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            Maximum file size: 50MB. Supported: MP4, AVI, MOV, WMV
                                        </div>
                                    </div>
                                </div>
                                @error('video')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Link Section -->
                            @if($post->type === 'link')
                            <div id="linkSection" class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">URL</label>
                                    <input type="url" class="form-control" id="link_url" name="link_url"
                                        placeholder="https://example.com"
                                        value="{{ old('link_url', $post->link_url) }}">
                                    @error('link_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Title (optional)</label>
                                        <input type="text" class="form-control" id="link_title" name="link_title"
                                            value="{{ old('link_title', $post->link_title) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Image URL (optional)</label>
                                        <input type="url" class="form-control" id="link_image" name="link_image"
                                            value="{{ old('link_image', $post->link_image) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description (optional)</label>
                                    <textarea class="form-control" id="link_description" name="link_description"
                                        rows="3">{{ old('link_description', $post->link_description) }}</textarea>
                                </div>
                            </div>
                            @endif

                            <!-- Tags Section -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-tags"></i> Tags
                                </label>
                                <div class="border rounded p-3">
                                    <!-- Selected Tags -->
                                    <div id="selectedTags" class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($post->tags as $tag)
                                        <div class="selected-tag rounded-pill px-3 py-1 d-flex align-items-center">
                                            <i class="bi bi-hash me-1"></i>{{ $tag->name }}
                                            <button type="button" class="btn-close btn-close-sm ms-2"
                                                data-tag-id="{{ $tag->id }}" data-tag-name="{{ $tag->name }}"></button>
                                        </div>
                                        @endforeach
                                    </div>

                                    <!-- Tag Input -->
                                    <div class="input-group">
                                        <input type="text" id="tagInput" class="form-control"
                                            placeholder="Add tags (press Enter, comma, or space)">
                                        <button type="button" id="addTagBtn" class="btn btn-outline-primary">
                                            <i class="bi bi-plus"></i> Add
                                        </button>
                                    </div>
                                    <div class="form-text mt-2">
                                        Add relevant tags to help others discover your post. Max 10 tags.
                                    </div>

                                    <!-- Popular Tags -->
                                    <div class="mt-3">
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-fire"></i> Popular tags:
                                        </p>
                                        <div id="popularTags" class="d-flex flex-wrap gap-2">
                                            @foreach($tags->take(15) as $tag)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary tag-suggestion"
                                                data-tag-name="{{ $tag->name }}">
                                                <i class="bi bi-hash"></i>{{ $tag->name }}
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Hidden inputs for selected tags -->
                                <input type="hidden" id="tagsInput" name="tags"
                                    value="{{ $post->tags->pluck('id')->implode(',') }}">
                            </div>

                            <!-- Visibility Settings -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-eye"></i> Visibility
                                </label>
                                <div class="list-group">
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="visibility"
                                            value="public" {{ old('visibility', $post->visibility) == 'public' ?
                                        'checked' : '' }}>
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-globe text-primary"></i> Public
                                            </h6>
                                            <p class="text-muted small mb-0">Anyone can see this post</p>
                                        </div>
                                    </label>
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="visibility"
                                            value="followers" {{ old('visibility', $post->visibility) == 'followers' ?
                                        'checked' : '' }}>
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-people text-success"></i> Followers Only
                                            </h6>
                                            <p class="text-muted small mb-0">Only your followers can see this post</p>
                                        </div>
                                    </label>
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="visibility"
                                            value="private" {{ old('visibility', $post->visibility) == 'private' ?
                                        'checked' : '' }}>
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-lock text-danger"></i> Private
                                            </h6>
                                            <p class="text-muted small mb-0">Only you can see this post</p>
                                        </div>
                                    </label>
                                </div>
                                @error('visibility')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Advanced Options -->
                            <div class="mb-4">
                                <button class="btn btn-link text-decoration-none p-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#advancedOptions">
                                    <i class="bi bi-gear"></i> Advanced Options
                                </button>
                                <div class="collapse mt-2" id="advancedOptions">
                                    <div class="card card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enableComments"
                                                name="enable_comments" value="1" checked>
                                            <label class="form-check-label" for="enableComments">
                                                Allow comments
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="enableLikes"
                                                name="enable_likes" value="1" checked>
                                            <label class="form-check-label" for="enableLikes">
                                                Allow likes
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="enableSharing"
                                                name="enable_sharing" value="1" checked>
                                            <label class="form-check-label" for="enableSharing">
                                                Allow sharing
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="card-footer bg-white border-top py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <span id="submitText">
                                            <i class="bi bi-check-lg"></i> Update Post
                                        </span>
                                        <div id="submitSpinner" class="spinner-border spinner-border-sm d-none ms-2"
                                            role="status"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this post? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const contentTextarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    const readingTime = document.getElementById('readingTime');
    const codeTextarea = document.getElementById('code_snippet');
    const codeCharCount = document.getElementById('codeCharCount');

    // File upload elements
    const imageInput = document.getElementById('image');
    const videoInput = document.getElementById('video');
    const browseImageBtn = document.getElementById('browseImage');
    const browseVideoBtn = document.getElementById('browseVideo');
    const newImagePreview = document.getElementById('newImagePreview');
    const newVideoPreview = document.getElementById('newVideoPreview');
    const removeNewImage = document.getElementById('removeNewImage');
    const removeNewVideo = document.getElementById('removeNewVideo');

    // Tags management
    const tagInput = document.getElementById('tagInput');
    const addTagBtn = document.getElementById('addTagBtn');
    const selectedTagsDiv = document.getElementById('selectedTags');
    const tagsInput = document.getElementById('tagsInput');
    const popularTags = document.querySelectorAll('.tag-suggestion');

    // State
    let selectedTags = new Set(
        tagsInput.value ? tagsInput.value.split(',').filter(id => id) : []
    );

    // Character counter for content
    if (contentTextarea && charCount && readingTime) {
        contentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;

            // Calculate reading time (200 words per minute)
            const words = this.value.trim().split(/\s+/).length;
            const minutes = Math.max(1, Math.ceil(words / 200));
            readingTime.textContent = `${minutes} min read`;
        });
    }

    // Character counter for code
    if (codeTextarea && codeCharCount) {
        codeTextarea.addEventListener('input', function() {
            codeCharCount.textContent = this.value.length;
        });
    }

    // Image upload handling
    if (browseImageBtn && imageInput) {
        browseImageBtn.addEventListener('click', () => imageInput.click());

        imageInput.addEventListener('change', function(e) {
            if (this.files.length) {
                handleImageUpload(this.files[0]);
            }
        });
    }

    function handleImageUpload(file) {
        // Validate file
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPEG, PNG, GIF, WebP, SVG).');
            return;
        }

        if (file.size > 20 * 1024 * 1024) {
            alert('File size must be less than 20MB.');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = newImagePreview.querySelector('img');
            img.src = e.target.result;
            newImagePreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    if (removeNewImage) {
        removeNewImage.addEventListener('click', function() {
            newImagePreview.classList.add('d-none');
            imageInput.value = '';
        });
    }

    // Video upload handling
    if (browseVideoBtn && videoInput) {
        browseVideoBtn.addEventListener('click', () => videoInput.click());

        videoInput.addEventListener('change', function(e) {
            if (this.files.length) {
                handleVideoUpload(this.files[0]);
            }
        });
    }

    function handleVideoUpload(file) {
        // Validate file
        const videoTypes = ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv'];
        if (!videoTypes.some(type => file.type.includes(type))) {
            alert('Please select a video file (MP4, AVI, MOV, WMV).');
            return;
        }

        if (file.size > 50 * 1024 * 1024) {
            alert('File size must be less than 50MB.');
            return;
        }

        // Show preview
        const url = URL.createObjectURL(file);
        const videoPlayer = newVideoPreview.querySelector('#videoPlayer');
        if (videoPlayer) {
            videoPlayer.src = url;
            newVideoPreview.classList.remove('d-none');
        }
    }

    if (removeNewVideo) {
        removeNewVideo.addEventListener('click', function() {
            newVideoPreview.classList.add('d-none');
            videoInput.value = '';
        });
    }

    // Tags management
    function addTag(tagName, tagId = null) {
        tagName = tagName.trim();

        if (!tagName || selectedTags.size >= 10) return;

        // Check if tag already exists
        const existingTags = Array.from(selectedTagsDiv.querySelectorAll('.selected-tag'))
            .map(el => el.querySelector('span')?.textContent?.replace('#', '') || '');

        if (existingTags.some(tag => tag.toLowerCase() === tagName.toLowerCase())) {
            alert('Tag already added!');
            return;
        }

        // Add to UI
        const tagElement = document.createElement('div');
        tagElement.className = 'selected-tag rounded-pill px-3 py-1 d-flex align-items-center';
        tagElement.innerHTML = `
            <i class="bi bi-hash me-1"></i>${tagName}
            <button type="button" class="btn-close btn-close-sm ms-2" data-tag-name="${tagName}"></button>
        `;

        selectedTagsDiv.appendChild(tagElement);

        // Add to hidden input
        if (tagId) {
            selectedTags.add(tagId);
        }
        updateTagsInput();
    }

    function removeTag(tagName) {
        // Remove from UI
        const tags = selectedTagsDiv.querySelectorAll('.selected-tag');
        tags.forEach(tag => {
            if (tag.querySelector('span')?.textContent === '#' + tagName ||
                tag.textContent.includes(tagName)) {
                tag.remove();
            }
        });

        // Remove from hidden input (you'll need to map tag names to IDs)
        // This is simplified - you might need a more sophisticated approach
        updateTagsInput();
    }

    function updateTagsInput() {
        // You need to maintain a mapping of tag names to IDs
        // For now, we'll just clear it and let the form submit with existing tags
        // The backend will handle tag updates
    }

    // Add tag from input
    if (addTagBtn && tagInput) {
        addTagBtn.addEventListener('click', function() {
            const tagName = tagInput.value.trim();
            if (tagName) {
                addTag(tagName);
                tagInput.value = '';
            }
        });

        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',' || e.key === ' ') {
                e.preventDefault();
                const tagName = this.value.trim();
                if (tagName) {
                    addTag(tagName);
                    this.value = '';
                }
            }
        });
    }

    // Add tag from popular tags
    if (popularTags) {
        popularTags.forEach(tag => {
            tag.addEventListener('click', function() {
                const tagName = this.dataset.tagName;
                addTag(tagName);
            });
        });
    }

    // Remove tag
    if (selectedTagsDiv) {
        selectedTagsDiv.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-close')) {
                const tagName = e.target.dataset.tagName;
                removeTag(tagName);
            }
        });
    }

    // Form submission
    const form = document.getElementById('editPostForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');

    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitText.classList.add('d-none');
            submitSpinner.classList.remove('d-none');
        });
    }
});
</script>