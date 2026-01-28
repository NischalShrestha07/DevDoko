{{-- resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Post - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h5 class="mb-0">Create New Post</h5>
                </div>
            </div>
            <div class="card-body">
                <!-- Post Type Selection -->
                <div class="mb-4">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('posts.create') }}?type=text"
                            class="btn btn-outline-primary {{ $type == 'text' ? 'active' : '' }}">
                            <i class="bi bi-file-text"></i> Text
                        </a>
                        <a href="{{ route('posts.create') }}?type=image"
                            class="btn btn-outline-success {{ $type == 'image' ? 'active' : '' }}">
                            <i class="bi bi-image"></i> Image
                        </a>
                        <a href="{{ route('posts.create') }}?type=video"
                            class="btn btn-outline-info {{ $type == 'video' ? 'active' : '' }}">
                            <i class="bi bi-play-btn"></i> Video
                        </a>
                        <a href="{{ route('posts.create') }}?type=code"
                            class="btn btn-outline-warning {{ $type == 'code' ? 'active' : '' }}">
                            <i class="bi bi-code-slash"></i> Code
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="type" value="{{ $type }}">

                    <!-- Media Upload for Images/Videos -->
                    @if(in_array($type, ['image', 'video']))
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Upload {{ $type === 'image' ? 'Images' : 'Videos' }}
                        </label>
                        <div class="border rounded p-4 text-center" style="border-style: dashed !important;">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
                            <p class="mb-3">Drag and drop {{ $type === 'image' ? 'images' : 'videos' }} here</p>
                            <input type="file" class="form-control d-none" id="media-input" name="media[]" multiple
                                accept="{{ $type === 'image' ? 'image/*' : 'video/*' }}">
                            <label for="media-input" class="btn btn-primary">Select from computer</label>
                            <div id="media-preview" class="mt-3 row g-2"></div>
                        </div>
                        @error('media')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('media.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <!-- Code Input -->
                    @if($type === 'code')
                    <div class="mb-4">
                        <label for="language" class="form-label fw-bold">Programming Language</label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language"
                            name="language" required>
                            <option value="">Select Language</option>
                            <option value="php" {{ old('language')=='php' ? 'selected' : '' }}>PHP</option>
                            <option value="javascript" {{ old('language')=='javascript' ? 'selected' : '' }}>JavaScript
                            </option>
                            <option value="python" {{ old('language')=='python' ? 'selected' : '' }}>Python</option>
                            <option value="java" {{ old('language')=='java' ? 'selected' : '' }}>Java</option>
                            <option value="cpp" {{ old('language')=='cpp' ? 'selected' : '' }}>C++</option>
                            <option value="csharp" {{ old('language')=='csharp' ? 'selected' : '' }}>C#</option>
                            <option value="html" {{ old('language')=='html' ? 'selected' : '' }}>HTML</option>
                            <option value="css" {{ old('language')=='css' ? 'selected' : '' }}>CSS</option>
                            <option value="sql" {{ old('language')=='sql' ? 'selected' : '' }}>SQL</option>
                            <option value="ruby" {{ old('language')=='ruby' ? 'selected' : '' }}>Ruby</option>
                            <option value="go" {{ old('language')=='go' ? 'selected' : '' }}>Go</option>
                            <option value="swift" {{ old('language')=='swift' ? 'selected' : '' }}>Swift</option>
                            <option value="kotlin" {{ old('language')=='kotlin' ? 'selected' : '' }}>Kotlin</option>
                            <option value="typescript" {{ old('language')=='typescript' ? 'selected' : '' }}>TypeScript
                            </option>
                            <option value="rust" {{ old('language')=='rust' ? 'selected' : '' }}>Rust</option>
                            <option value="other" {{ old('language')=='other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('language')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="form-label fw-bold">Code</label>
                        <textarea class="form-control font-monospace @error('code') is-invalid @enderror" id="code"
                            name="code" rows="15" placeholder="Paste your code here..."
                            required>{{ old('code') }}</textarea>
                        @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <!-- Caption -->
                    <div class="mb-4">
                        <label for="caption" class="form-label fw-bold">Caption</label>
                        <textarea class="form-control @error('caption') is-invalid @enderror" id="caption"
                            name="caption" rows="4" placeholder="What's on your mind?">{{ old('caption') }}</textarea>
                        @error('caption')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags"
                            name="tags" placeholder="laravel, php, webdev (separate with commas)"
                            value="{{ old('tags') }}">
                        @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Add relevant tags to help others discover your post</div>
                    </div>

                    <!-- Visibility and Location -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="visibility" class="form-label fw-bold">Who can see this?</label>
                            <select class="form-select @error('visibility') is-invalid @enderror" id="visibility"
                                name="visibility">
                                <option value="public" {{ old('visibility', 'public' )=='public' ? 'selected' : '' }}>
                                    Public
                                </option>
                                <option value="followers" {{ old('visibility')=='followers' ? 'selected' : '' }}>
                                    Followers Only
                                </option>
                            </select>
                            @error('visibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="location" class="form-label fw-bold">Add Location (Optional)</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location" placeholder="e.g., Kathmandu, Nepal"
                                value="{{ old('location') }}">
                            @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Share Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Media preview for images/videos
    const mediaInput = document.getElementById('media-input');
    const mediaPreview = document.getElementById('media-preview');

    if (mediaInput) {
        mediaInput.addEventListener('change', function(e) {
            mediaPreview.innerHTML = '';
            const files = Array.from(e.target.files);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-4 col-md-3';

                    const div = document.createElement('div');
                    div.className = 'position-relative';

                    if (file.type.startsWith('image/')) {
                        div.innerHTML = `
                            <img src="${e.target.result}"
                                 class="img-fluid rounded"
                                 style="width: 100%; height: 120px; object-fit: cover;">
                            <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                    onclick="this.parentElement.parentElement.remove()">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                    } else if (file.type.startsWith('video/')) {
                        div.innerHTML = `
                            <div class="bg-dark rounded d-flex align-items-center justify-content-center"
                                 style="width: 100%; height: 120px;">
                                <i class="bi bi-play-btn-fill text-white fs-3"></i>
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                    onclick="this.parentElement.parentElement.remove()">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                    }

                    col.appendChild(div);
                    mediaPreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Character counter for caption
    const captionInput = document.getElementById('caption');
    if (captionInput) {
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        captionInput.parentElement.appendChild(counter);

        captionInput.addEventListener('input', function() {
            const length = this.value.length;
            counter.textContent = `${length}/2000`;

            if (length > 1900) {
                counter.className = 'text-danger float-end';
            } else if (length > 1800) {
                counter.className = 'text-warning float-end';
            } else {
                counter.className = 'text-muted float-end';
            }
        });

        // Trigger once on load
        captionInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush
@endsection