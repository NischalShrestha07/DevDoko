@extends('layouts.app')

@section('title', 'Create Post - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="text-decoration-none text-dark me-3">
                            <i class="bi bi-x-lg fs-4"></i>
                        </a>
                        <h5 class="mb-0 fw-bold">Create New Post</h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Post Type Selection -->
                    <div class="border-bottom px-4 py-3">
                        <div class="row g-1 row-cols-3 row-cols-md-5 row-cols-lg-9">
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn active w-100 py-2" data-type="text"><i class="bi bi-text-paragraph d-block fs-5 mb-1"></i><small>Text</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="code"><i class="bi bi-code-slash d-block fs-5 mb-1"></i><small>Code</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="image"><i class="bi bi-image d-block fs-5 mb-1"></i><small>Image</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="video"><i class="bi bi-play-circle d-block fs-5 mb-1"></i><small>Video</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="link"><i class="bi bi-link-45deg d-block fs-5 mb-1"></i><small>Link</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="question"><i class="bi bi-question-circle d-block fs-5 mb-1"></i><small>Question</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="project"><i class="bi bi-briefcase d-block fs-5 mb-1"></i><small>Project</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="article"><i class="bi bi-file-text d-block fs-5 mb-1"></i><small>Article</small></button></div>
                            <div class="col"><button type="button" class="btn btn-outline-primary post-type-btn w-100 py-2" data-type="status"><i class="bi bi-chat-dots d-block fs-5 mb-1"></i><small>Status</small></button></div>
                        </div>
                    </div>

                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" id="postType" value="text">

                        <!-- Post Content Area -->
                        <div class="p-4">
                            <!-- Title Section -->
                            <div id="titleSection" class="mb-3">
                                <input type="text" class="form-control border-0 fs-4 fw-bold" id="title" name="title"
                                    placeholder="Title (optional)" maxlength="200" value="{{ old('title') }}">
                                @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content Section -->
                            <div class="mb-3" id="contentSection">
                                <textarea class="form-control border-0" id="content" name="content" rows="6"
                                    placeholder="What's on your mind, {{ auth()->user()->name }}?"
                                    maxlength="20000">{{ old('content') }}</textarea>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div class="text-muted small">
                                        <span id="charCount">0</span>/20000
                                    </div>
                                    <div class="small">
                                        <i class="bi bi-clock"></i>
                                        <span id="readingTime">0 min read</span>
                                    </div>
                                </div>
                                @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Code Section -->
                            <div id="codeSection" class="mb-3 d-none">
                                <div class="mb-3">
                                    <select class="form-select" id="code_language" name="code_language">
                                        <option value="">Select Language</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="typescript">TypeScript</option>
                                        <option value="python">Python</option>
                                        <option value="java">Java</option>
                                        <option value="php">PHP</option>
                                        <option value="cpp">C++</option>
                                        <option value="csharp">C#</option>
                                        <option value="ruby">Ruby</option>
                                        <option value="go">Go</option>
                                        <option value="rust">Rust</option>
                                        <option value="swift">Swift</option>
                                        <option value="kotlin">Kotlin</option>
                                        <option value="html">HTML</option>
                                        <option value="css">CSS</option>
                                        <option value="sql">SQL</option>
                                        <option value="bash">Bash/Shell</option>
                                        <option value="dockerfile">Dockerfile</option>
                                        <option value="yaml">YAML</option>
                                        <option value="json">JSON</option>
                                        <option value="markdown">Markdown</option>
                                    </select>
                                </div>
                                <textarea class="form-control font-monospace" id="code_snippet" name="code_snippet"
                                    rows="12" placeholder="Paste your code here..."
                                    maxlength="20000">{{ old('code_snippet') }}</textarea>
                                <div class="text-end text-muted small mt-1">
                                    <span id="codeCharCount">0</span>/20000
                                </div>
                                @error('code_snippet')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload Section -->
                            <div id="imageSection" class="mb-3 d-none">
                                <div class="border rounded p-4">
                                    <div id="imagePreview" class="d-none mb-3 text-center">
                                        <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                        <div class="mt-2">
                                            <button type="button" id="removeImage" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                    <div id="imageUploadArea" class="py-5 text-center">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted mb-3">Drag & drop an image or click to browse</p>
                                        <input type="file" id="image" name="image" accept="image/*" class="d-none">
                                        <button type="button" id="browseImage" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Select Image
                                        </button>
                                        <div class="form-text mt-2">
                                            Maximum file size: 20MB. Supported: JPEG, PNG, GIF, WebP, SVG
                                        </div>
                                    </div>
                                    <!-- Image Caption -->
                                    <div id="imageCaptionWrap" class="mt-3">
                                        <textarea class="form-control border-0" id="imageCaption"
                                            rows="3" placeholder="Add a caption... (optional)"
                                            maxlength="20000">{{ old('content') }}</textarea>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <div class="text-muted small">
                                                <span id="imageCaptionCount">0</span>/20000
                                            </div>
                                            <div class="small">
                                                <i class="bi bi-clock"></i>
                                                <span id="imageCaptionReadTime">0 min read</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Upload Section -->
                            <div id="videoSection" class="mb-3 d-none">
                                <div class="border rounded p-4 text-center">
                                    <div id="videoPreview" class="d-none mb-3">
                                        <video id="videoPlayer" controls class="w-100 rounded"
                                            style="max-height: 300px;">
                                            <source src="" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="mt-2">
                                            <button type="button" id="removeVideo" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Remove Video
                                            </button>
                                        </div>
                                    </div>
                                    <div id="videoUploadArea" class="py-5">
                                        <i class="bi bi-camera-video fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted mb-3">Select a video file to upload</p>
                                        <input type="file" id="video" name="video" accept="video/*" class="d-none">
                                        <button type="button" id="browseVideo" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Select Video
                                        </button>
                                        <div class="form-text mt-2">
                                            Maximum file size: 50MB. Supported: MP4, AVI, MOV, WMV
                                        </div>
                                    </div>
                                </div>
                                @error('video')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Link Section -->
                            <div id="linkSection" class="mb-3 d-none">
                                <div class="mb-3">
                                    <label class="form-label">URL</label>
                                    <input type="url" class="form-control" id="link_url" name="link_url"
                                        placeholder="https://example.com" value="{{ old('link_url') }}">
                                    @error('link_url')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Title (optional)</label>
                                        <input type="text" class="form-control" id="link_title" name="link_title"
                                            value="{{ old('link_title') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Image URL (optional)</label>
                                        <input type="url" class="form-control" id="link_image" name="link_image"
                                            value="{{ old('link_image') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description (optional)</label>
                                    <textarea class="form-control" id="link_description" name="link_description"
                                        rows="3">{{ old('link_description') }}</textarea>
                                </div>
                            </div>

                            <!-- Tags Section -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-tags"></i> Tags
                                </label>
                                <div class="border rounded p-3">
                                    <!-- Selected Tags -->
                                    <div id="selectedTags" class="d-flex flex-wrap gap-2 mb-3">
                                        @if(old('tags'))
                                        @php
                                        $oldTags = explode(',', old('tags'));
                                        @endphp
                                        @foreach($oldTags as $tag)
                                        @if(!empty(trim($tag)))
                                        <div class="selected-tag rounded-pill px-3 py-1 d-flex align-items-center">
                                            #{{ trim($tag) }}
                                            <button type="button" class="btn-close btn-close-sm ms-2"
                                                data-tag-name="{{ trim($tag) }}"></button>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
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
                                <!-- Hidden input for selected tags -->
                                <input type="hidden" id="tagsInput" name="tags" value="{{ old('tags') }}">
                            </div>

                            <!-- Visibility Settings -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-eye"></i> Visibility
                                </label>
                                <div class="list-group">
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="visibility"
                                            value="public" {{ old('visibility', 'public' )=='public' ? 'checked' : ''
                                            }}>
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-globe text-primary"></i> Public
                                            </h6>
                                            <p class="text-muted small mb-0">Anyone can see this post</p>
                                        </div>
                                    </label>
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="visibility"
                                            value="followers" {{ old('visibility')=='followers' ? 'checked' : '' }}>
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-people text-success"></i> Followers Only
                                            </h6>
                                            <p class="text-muted small mb-0">Only your followers can see this post</p>
                                        </div>
                                    </label>
                                    <label class="list-group-item d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="visibility"
                                            value="private" {{ old('visibility')=='private' ? 'checked' : '' }}>
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

                        </div>

                        <!-- Form Actions -->
                        <div class="card-footer bg-white border-top py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="bi bi-info-circle"></i> Your post will be visible based on your visibility
                                    settings.
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                    <button type="button" onclick="showPreview()" class="btn btn-outline-secondary">
                                        <i class="bi bi-eye"></i> Preview
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <span id="submitText">
                                            <i class="bi bi-send"></i> Publish Post
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

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Post Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('createPostForm').submit()">
                    <i class="bi bi-send"></i> Publish Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .post-type-btn {
        transition: all 0.2s;
        border-radius: 10px;
    }

    .post-type-btn.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    .post-type-btn:hover:not(.active) {
        background-color: rgba(13, 110, 253, 0.08);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    #imageUploadArea,
    #videoUploadArea {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    #imageUploadArea:hover,
    #videoUploadArea:hover {
        border-color: var(--primary-color);
        background-color: rgba(13, 110, 253, 0.05);
    }

    .tag-suggestion {
        transition: all 0.2s;
    }

    .tag-suggestion:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-1px);
    }

    .selected-tag {
        background-color: #e7f1ff;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        animation: fadeIn 0.3s;
    }

    .selected-tag .btn-close {
        font-size: 0.6rem;
        opacity: 0.7;
    }

    .selected-tag .btn-close:hover {
        opacity: 1;
    }

    .font-monospace {
        font-family: 'Fira Code', 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 14px;
        line-height: 1.5;
    }

    /* Syntax highlighting for code preview */
    pre {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
    }

    code {
        color: #d63384;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading animation */
    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }

    .loading {
        animation: pulse 1.5s infinite;
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize Highlight.js
    if (typeof hljs !== 'undefined') {
        hljs.highlightAll();
    }

    // Elements
    const postTypeBtns = document.querySelectorAll('.post-type-btn');
    const postTypeInput = document.getElementById('postType');
    const sections = {
        content: document.getElementById('contentSection'),
        code: document.getElementById('codeSection'),
        image: document.getElementById('imageSection'),
        video: document.getElementById('videoSection'),
        link: document.getElementById('linkSection'),
        title: document.getElementById('titleSection')
    };
    const submitText = document.getElementById('submitText');
    const form = document.getElementById('createPostForm');

    // Character counters
    const contentTextarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    const codeTextarea = document.getElementById('code_snippet');
    const codeCharCount = document.getElementById('codeCharCount');
    const readingTime = document.getElementById('readingTime');

    // File upload elements
    const imageInput = document.getElementById('image');
    const videoInput = document.getElementById('video');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const imageUploadArea = document.getElementById('imageUploadArea');
    const videoUploadArea = document.getElementById('videoUploadArea');
    const browseImageBtn = document.getElementById('browseImage');
    const browseVideoBtn = document.getElementById('browseVideo');
    const removeImageBtn = document.getElementById('removeImage');
    const removeVideoBtn = document.getElementById('removeVideo');

    // Tags management
    const tagInput = document.getElementById('tagInput');
    const addTagBtn = document.getElementById('addTagBtn');
    const selectedTagsDiv = document.getElementById('selectedTags');
    const tagsInput = document.getElementById('tagsInput');
    const popularTags = document.querySelectorAll('.tag-suggestion');

    // Form submission
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');

    // State
    let selectedTags = new Set();
    let currentActiveType = 'text';

    // Initialize tags from hidden input
    function initializeTags() {
        if (tagsInput.value) {
            const tags = tagsInput.value.split(',').filter(tag => tag.trim());
            tags.forEach(tag => {
                if (tag.trim()) {
                    selectedTags.add(tag.trim());
                }
            });
        }
    }

    // Initialize from old input
    function initializeFromOldInput() {
        // Set active type from old input or URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const typeFromUrl = urlParams.get('type');
        const typeFromOld = '{{ old("type", "text") }}';

        let initialType = 'text';
        if (typeFromOld && typeFromOld !== 'text') {
            initialType = typeFromOld;
        } else if (typeFromUrl) {
            initialType = typeFromUrl;
        }

        // Activate the correct type button
        const initialBtn = document.querySelector(`.post-type-btn[data-type="${initialType}"]`);
        if (initialBtn) {
            activatePostType(initialType, initialBtn);
        } else {
            // Fallback to text
            const textBtn = document.querySelector('.post-type-btn[data-type="text"]');
            if (textBtn) {
                activatePostType('text', textBtn);
            }
        }
    }

    function activatePostType(type, button) {
        // Update UI
        postTypeBtns.forEach(b => b.classList.remove('active'));
        button.classList.add('active');
        postTypeInput.value = type;
        currentActiveType = type;

        // Hide all sections
        Object.values(sections).forEach(section => {
            if (section) section.classList.add('d-none');
        });

        // Show appropriate sections
        updateTitleVisibility(type);

        switch(type) {
            case 'code':
                if (sections.code) sections.code.classList.remove('d-none');
                break;
            case 'image':
                if (sections.image) sections.image.classList.remove('d-none');
                break;
            case 'video':
                if (sections.video) sections.video.classList.remove('d-none');
                break;
            case 'link':
                if (sections.link) sections.link.classList.remove('d-none');
                break;
            default:
                if (sections.content) sections.content.classList.remove('d-none');
                break;
        }

        // Update placeholder and submit text
        updatePlaceholder(type);
        updateSubmitButtonText(type);

        // Validate form for current type
        validateForm();
    }

    // Post type switching
    postTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;

            // Don't do anything if already active
            if (currentActiveType === type) return;

            activatePostType(type, this);
        });
    });

    function updateTitleVisibility(type) {
        if (!sections.title) return;

        if (['text', 'article', 'question', 'project'].includes(type)) {
            sections.title.classList.remove('d-none');
        } else {
            sections.title.classList.add('d-none');
        }
    }

    function updatePlaceholder(type) {
        const placeholders = {
            text: "What's on your mind?",
            article: "Write your article here...",
            question: "Ask your question...",
            project: "Describe your project...",
            status: "Share an update...",
            code: "Paste your code here...",
            image: "Describe your image (optional)...",
            video: "Describe your video (optional)...",
            link: ""
        };

        if (contentTextarea && placeholders[type]) {
            contentTextarea.placeholder = placeholders[type];
        }
    }

    function updateSubmitButtonText(type) {
        const buttonTexts = {
            text: "Publish Post",
            code: "Share Code",
            image: "Share Image",
            video: "Share Video",
            link: "Share Link",
            question: "Ask Question",
            project: "Share Project",
            article: "Publish Article",
            status: "Share Update"
        };

        if (submitText) {
            const text = buttonTexts[type] || "Publish Post";
            submitText.innerHTML = `<i class="bi bi-send"></i> ${text}`;
        }
    }

    // Character counter for content
    if (contentTextarea && charCount && readingTime) {
        contentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            if (charCount) charCount.textContent = length;

            // Calculate reading time (200 words per minute)
            const words = this.value.trim().split(/\s+/).length;
            const minutes = Math.max(1, Math.ceil(words / 200));
            if (readingTime) readingTime.textContent = `${minutes} min read`;

            // Validate form
            validateForm();
        });

        // Trigger initial calculation
        contentTextarea.dispatchEvent(new Event('input'));
    }

    // Character counter for code
    if (codeTextarea && codeCharCount) {
        codeTextarea.addEventListener('input', function() {
            if (codeCharCount) codeCharCount.textContent = this.value.length;
            validateForm();
        });
        codeTextarea.dispatchEvent(new Event('input'));
    }

    // Image caption character counter + read time
    const imageCaption = document.getElementById('imageCaption');
    const imageCaptionCount = document.getElementById('imageCaptionCount');
    const imageCaptionReadTime = document.getElementById('imageCaptionReadTime');
    if (imageCaption) {
        imageCaption.addEventListener('input', function() {
            if (imageCaptionCount) imageCaptionCount.textContent = this.value.length;
            const words = this.value.trim().split(/\s+/).length;
            const minutes = Math.max(1, Math.ceil(words / 200));
            if (imageCaptionReadTime) imageCaptionReadTime.textContent = `${minutes} min read`;
            validateForm();
        });
    }

    // Image upload handling
    if (browseImageBtn && imageInput) {
        browseImageBtn.addEventListener('click', () => imageInput.click());

        if (imageUploadArea) {
            imageUploadArea.addEventListener('click', () => imageInput.click());

            imageUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                imageUploadArea.style.borderColor = '#0d6efd';
                imageUploadArea.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
            });

            imageUploadArea.addEventListener('dragleave', () => {
                imageUploadArea.style.borderColor = '#dee2e6';
                imageUploadArea.style.backgroundColor = '';
            });

            imageUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                imageUploadArea.style.borderColor = '#dee2e6';
                imageUploadArea.style.backgroundColor = '';

                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    handleImageUpload(e.dataTransfer.files[0]);
                }
            });
        }

        imageInput.addEventListener('change', function(e) {
            if (this.files.length) {
                handleImageUpload(this.files[0]);
            }
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            if (imagePreview) imagePreview.classList.add('d-none');
            if (imageUploadArea) imageUploadArea.classList.remove('d-none');
            if (imageInput) imageInput.value = '';
            validateForm();
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
            if (imagePreview) {
                const img = imagePreview.querySelector('img');
                if (img) img.src = e.target.result;
                imagePreview.classList.remove('d-none');
            }
            if (imageUploadArea) imageUploadArea.classList.add('d-none');
            validateForm();
        };
        reader.readAsDataURL(file);
    }

    // Video upload handling
    if (browseVideoBtn && videoInput) {
        browseVideoBtn.addEventListener('click', () => videoInput.click());

        if (videoUploadArea) {
            videoUploadArea.addEventListener('click', () => videoInput.click());
        }

        videoInput.addEventListener('change', function(e) {
            if (this.files.length) {
                handleVideoUpload(this.files[0]);
            }
        });
    }

    if (removeVideoBtn) {
        removeVideoBtn.addEventListener('click', function() {
            if (videoPreview) videoPreview.classList.add('d-none');
            if (videoUploadArea) videoUploadArea.classList.remove('d-none');
            if (videoInput) videoInput.value = '';
            validateForm();
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
        if (videoPreview) {
            const videoPlayer = videoPreview.querySelector('#videoPlayer');
            if (videoPlayer) {
                videoPlayer.src = url;
                videoPreview.classList.remove('d-none');
            }
        }
        if (videoUploadArea) videoUploadArea.classList.add('d-none');
        validateForm();
    }

    // Tags management
    function addTag(tagName) {
        tagName = tagName.trim().toLowerCase();

        if (!tagName || selectedTags.size >= 10) return;

        // Check if tag already exists
        if (Array.from(selectedTags).some(tag => tag.toLowerCase() === tagName)) {
            return;
        }

        selectedTags.add(tagName);

        const tagElement = document.createElement('div');
        tagElement.className = 'selected-tag rounded-pill px-3 py-1 d-flex align-items-center';
        tagElement.innerHTML = `
            <i class="bi bi-hash me-1"></i>${tagName}
            <button type="button" class="btn-close btn-close-sm ms-2" data-tag-name="${tagName}"></button>
        `;

        if (selectedTagsDiv) selectedTagsDiv.appendChild(tagElement);
        updateTagsInput();
        validateForm();
    }

    function removeTag(tagName) {
        selectedTags.delete(tagName);
        updateTagsInput();
        validateForm();

        // Remove from UI
        if (selectedTagsDiv) {
            const tagElement = selectedTagsDiv.querySelector(`[data-tag-name="${tagName}"]`);
            if (tagElement) {
                tagElement.parentElement.remove();
            }
        }
    }

    function updateTagsInput() {
        if (tagsInput) {
            tagsInput.value = Array.from(selectedTags).join(',');
        }
    }

    // Initialize tags from old input
    initializeTags();

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

    // Form validation
    function validateForm() {
        const type = currentActiveType;
        let isValid = true;
        let errorMessage = '';

        switch(type) {
            case 'text':
            case 'article':
            case 'question':
            case 'project':
            case 'status':
                if (contentTextarea && !contentTextarea.value.trim()) {
                    isValid = false;
                    errorMessage = 'Please add some content.';
                }
                break;

            case 'code':
                if (codeTextarea && !codeTextarea.value.trim()) {
                    isValid = false;
                    errorMessage = 'Please add some code.';
                }
                break;

            case 'image':
                if (imageInput && !imageInput.files.length &&
                    imageCaption && !imageCaption.value.trim()) {
                    isValid = false;
                    errorMessage = 'Please add an image or description.';
                }
                break;

            case 'video':
                if (videoInput && !videoInput.files.length &&
                    contentTextarea && !contentTextarea.value.trim()) {
                    isValid = false;
                    errorMessage = 'Please add a video or description.';
                }
                break;

            case 'link':
                const linkUrl = document.getElementById('link_url');
                if (linkUrl && !linkUrl.value.trim()) {
                    isValid = false;
                    errorMessage = 'Please enter a URL.';
                }
                break;
        }

        // Update submit button state
        if (submitBtn) {
            submitBtn.disabled = !isValid;
            if (!isValid) {
                submitBtn.setAttribute('title', errorMessage);
            } else {
                submitBtn.removeAttribute('title');
            }
        }

        return isValid;
    }

    // Live preview function
    window.showPreview = function() {
        const previewContent = document.getElementById('previewContent');
        const type = currentActiveType;
        let html = '';

        if (type === 'code' && codeTextarea) {
            html = '<pre><code class="language-' + (document.getElementById('code_language')?.value || 'plaintext') + '">'
                + escapeHtml(codeTextarea.value) + '</code></pre>';
        } else if (type === 'image' && imagePreview?.querySelector('img')?.src) {
            const caption = document.getElementById('imageCaption')?.value || contentTextarea?.value || '';
            html = '<img src="' + imagePreview.querySelector('img').src + '" class="img-fluid rounded mb-3" alt="Preview">'
                + (caption ? '<hr>' + marked.parse(caption) : '');
        } else if (type === 'link') {
            html = '<div class="card p-3 mb-3">'
                + (document.getElementById('link_image')?.value ? '<img src="' + document.getElementById('link_image').value + '" class="img-fluid rounded mb-2" style="max-height:200px">' : '')
                + '<h5>' + (document.getElementById('link_title')?.value || 'Untitled') + '</h5>'
                + '<p class="text-muted small">' + (document.getElementById('link_description')?.value || '') + '</p>'
                + '<a href="' + (document.getElementById('link_url')?.value || '#') + '" target="_blank" class="btn btn-sm btn-outline-primary">Visit Link</a>'
                + '</div>';
        } else {
            html = contentTextarea?.value ? marked.parse(contentTextarea.value) : '<p class="text-muted">No content yet.</p>';
        }

        previewContent.innerHTML = html;
        if (typeof hljs !== 'undefined') previewContent.querySelectorAll('pre code').forEach(hljs.highlightElement);
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    };

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto-save draft to localStorage
    const DRAFT_KEY = 'devdoko_draft_' + window.location.pathname;
    let saveDraftTimer;

    function saveDraft() {
        const draft = {
            type: currentActiveType,
            title: document.getElementById('title')?.value || '',
            content: contentTextarea?.value || '',
            image_caption: document.getElementById('imageCaption')?.value || '',
            code_snippet: codeTextarea?.value || '',
            code_language: document.getElementById('code_language')?.value || '',
            link_url: document.getElementById('link_url')?.value || '',
            link_title: document.getElementById('link_title')?.value || '',
            link_description: document.getElementById('link_description')?.value || '',
            link_image: document.getElementById('link_image')?.value || '',
            tags: Array.from(selectedTags),
            visibility: document.querySelector('input[name="visibility"]:checked')?.value || 'public',
            savedAt: Date.now()
        };
        try {
            localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
        } catch (e) {}
    }

    function restoreDraft() {
        try {
            const raw = localStorage.getItem(DRAFT_KEY);
            if (!raw) return;
            const draft = JSON.parse(raw);
            if (!draft.content && !draft.image_caption && !draft.code_snippet && !draft.title) {
                localStorage.removeItem(DRAFT_KEY);
                return;
            }

            const savedAt = Number(draft.savedAt || 0);
            const age = Date.now() - savedAt;
            if (age > 86400000) {
                localStorage.removeItem(DRAFT_KEY);
                return;
            }

            if (draft.type) {
                const draftBtn = document.querySelector(`.post-type-btn[data-type="${draft.type}"]`);
                if (draftBtn) activatePostType(draft.type, draftBtn);
            }
            setTimeout(() => {
                if (draft.title) document.getElementById('title').value = draft.title;
                if (draft.content && contentTextarea) contentTextarea.value = draft.content;
                if (draft.image_caption) {
                    const ic = document.getElementById('imageCaption');
                    if (ic) ic.value = draft.image_caption;
                }
                if (draft.code_snippet && codeTextarea) codeTextarea.value = draft.code_snippet;
                if (draft.code_language) document.getElementById('code_language').value = draft.code_language;
                if (draft.link_url) document.getElementById('link_url').value = draft.link_url;
                if (draft.link_title) document.getElementById('link_title').value = draft.link_title;
                if (draft.link_description) document.getElementById('link_description').value = draft.link_description;
                if (draft.link_image) document.getElementById('link_image').value = draft.link_image;
                if (draft.visibility) {
                    const radio = document.querySelector('input[name="visibility"][value="' + draft.visibility + '"]');
                    if (radio) radio.checked = true;
                }
                if (draft.tags?.length) {
                    draft.tags.forEach(t => addTag(t));
                }
                contentTextarea?.dispatchEvent(new Event('input'));
                const ic = document.getElementById('imageCaption');
                if (ic) ic.dispatchEvent(new Event('input'));
            }, 100);

            if (window.DevDoko?.toast) {
                const timeLabel = savedAt ? new Date(savedAt).toLocaleTimeString() : 'earlier';
                window.DevDoko.toast('Restored your draft saved at ' + timeLabel + '.', 'info', 3500);
            }
        } catch (e) {}
    }

    // Auto-save on input changes
    document.querySelectorAll('#createPostForm input, #createPostForm textarea, #createPostForm select').forEach(el => {
        el.addEventListener('input', function() {
            clearTimeout(saveDraftTimer);
            saveDraftTimer = setTimeout(saveDraft, 2000);
        });
    });

    // Clear draft on successful submit
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sync image caption to content field before submit
            if (currentActiveType === 'image' && imageCaption && contentTextarea) {
                contentTextarea.value = imageCaption.value;
            }

            if (!validateForm()) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
            try { localStorage.removeItem(DRAFT_KEY); } catch (e) {}
            if (submitBtn) submitBtn.disabled = true;
            if (submitText) submitText.classList.add('d-none');
            if (submitSpinner) submitSpinner.classList.remove('d-none');
        });
    }

    // Image paste support for content textarea
    if (contentTextarea) {
        contentTextarea.addEventListener('paste', async function(e) {
            const items = e.clipboardData?.items;
            if (!items) return;
            for (const item of items) {
                if (item.type.startsWith('image/')) {
                    e.preventDefault();
                    const file = item.getAsFile();
                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content);
                    try {
                        const res = await fetch('/posts/upload-image', {
                            method: 'POST',
                            body: formData,
                            headers: { 'Accept': 'application/json' }
                        });
                        if (res.ok) {
                            const data = await res.json();
                            const cursorPos = contentTextarea.selectionStart;
                            const textBefore = contentTextarea.value.substring(0, cursorPos);
                            const textAfter = contentTextarea.value.substring(cursorPos);
                            contentTextarea.value = textBefore + '\n![image](' + data.url + ')\n' + textAfter;
                            contentTextarea.dispatchEvent(new Event('input'));
                        }
                    } catch (e) {}
                    return;
                }
            }
        });
    }

    // Initialize the form
    initializeFromOldInput();
    if (!document.querySelector('input[name="tags"]')?.value) {
        restoreDraft();
    }
});
</script>
