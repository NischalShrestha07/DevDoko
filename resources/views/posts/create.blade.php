@extends('layouts.app')


@section('title', 'Create Post - DevDoko')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <!-- Header -->
    <div
        style="display: flex; align-items: center; padding: 16px; background: white; border-bottom: 1px solid var(--border-color);">
        <a href="{{ route('home') }}" style="margin-right: 16px; color: var(--text-color); text-decoration: none;">
            <i class="bi bi-x-lg" style="font-size: 24px;"></i>
        </a>
        <h5 style="margin: 0; font-weight: 600;">Create New Post</h5>
        <button type="submit" form="create-post-form"
            style="margin-left: auto; background: var(--primary-color); color: white; border: none; padding: 6px 16px; border-radius: 8px; font-weight: 600;">
            Share
        </button>
    </div>

    <!-- Create Post Form -->
    <form id="create-post-form" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"
        style="background: white; padding: 20px; border-radius: 0 0 8px 8px;">
        @csrf

        <!-- Post Type Selection -->
        <div style="margin-bottom: 24px;">
            <div style="display: flex; gap: 12px; justify-content: center;">
                <input type="radio" name="type" value="text" id="type-text" class="d-none" {{ $type=='text' ? 'checked'
                    : '' }} onchange="togglePostType()">
                <label for="type-text" class="create-post-option {{ $type == 'text' ? 'active' : '' }}"
                    style="cursor: pointer; padding: 12px 24px; border: 2px solid {{ $type == 'text' ? 'var(--primary-color)' : 'var(--border-color)' }}; border-radius: 8px;">
                    <i class="bi bi-file-text"
                        style="color: {{ $type == 'text' ? 'var(--primary-color)' : 'inherit' }};"></i>
                    <span style="margin-left: 8px;">Text</span>
                </label>

                <input type="radio" name="type" value="image" id="type-image" class="d-none" {{ $type=='image'
                    ? 'checked' : '' }} onchange="togglePostType()">
                <label for="type-image" class="create-post-option {{ $type == 'image' ? 'active' : '' }}"
                    style="cursor: pointer; padding: 12px 24px; border: 2px solid {{ $type == 'image' ? 'var(--primary-color)' : 'var(--border-color)' }}; border-radius: 8px;">
                    <i class="bi bi-image" style="color: {{ $type == 'image' ? '#45bd62' : 'inherit' }};"></i>
                    <span style="margin-left: 8px;">Image</span>
                </label>

                <input type="radio" name="type" value="code" id="type-code" class="d-none" {{ $type=='code' ? 'checked'
                    : '' }} onchange="togglePostType()">
                <label for="type-code" class="create-post-option {{ $type == 'code' ? 'active' : '' }}"
                    style="cursor: pointer; padding: 12px 24px; border: 2px solid {{ $type == 'code' ? 'var(--primary-color)' : 'var(--border-color)' }}; border-radius: 8px;">
                    <i class="bi bi-code-slash" style="color: {{ $type == 'code' ? '#f7b928' : 'inherit' }};"></i>
                    <span style="margin-left: 8px;">Code</span>
                </label>
            </div>
        </div>

        <!-- Image Upload Section -->
        <div id="image-section" style="display: {{ $type == 'image' ? 'block' : 'none' }}; margin-bottom: 24px;">
            <div style="border: 2px dashed var(--border-color); border-radius: 8px; padding: 40px; text-align: center; cursor: pointer;"
                onclick="document.getElementById('media-input').click()">
                <i class="bi bi-cloud-arrow-up"
                    style="font-size: 48px; color: var(--border-color); margin-bottom: 16px;"></i>
                <h5 style="color: var(--text-color); margin-bottom: 8px;">Upload Photos</h5>
                <p style="color: #8e8e8e; margin-bottom: 16px;">Drag and drop images here</p>
                <div
                    style="background: var(--primary-color); color: white; padding: 8px 24px; border-radius: 8px; display: inline-block;">
                    Select from computer
                </div>
            </div>
            <input type="file" id="media-input" name="media[]" multiple accept="image/*" class="d-none"
                onchange="previewImages(this)">

            <!-- Image Preview -->
            <div id="image-preview" style="display: flex; gap: 12px; margin-top: 16px; flex-wrap: wrap;"></div>

            @error('media')
            <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
            @enderror
            @error('media.*')
            <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Code Section -->
        <div id="code-section" style="display: {{ $type == 'code' ? 'block' : 'none' }}; margin-bottom: 24px;">
            <!-- Language Selection -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Programming Language</label>
                <select name="language" class="form-select"
                    style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                    <option value="">Select Language</option>
                    <option value="php" {{ old('language')=='php' ? 'selected' : '' }}>PHP</option>
                    <option value="javascript" {{ old('language')=='javascript' ? 'selected' : '' }}>JavaScript</option>
                    <option value="python" {{ old('language')=='python' ? 'selected' : '' }}>Python</option>
                    <option value="java" {{ old('language')=='java' ? 'selected' : '' }}>Java</option>
                    <option value="cpp" {{ old('language')=='cpp' ? 'selected' : '' }}>C++</option>
                    <option value="html" {{ old('language')=='html' ? 'selected' : '' }}>HTML</option>
                    <option value="css" {{ old('language')=='css' ? 'selected' : '' }}>CSS</option>
                    <option value="sql" {{ old('language')=='sql' ? 'selected' : '' }}>SQL</option>
                </select>
                @error('language')
                <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Code Editor -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Code</label>
                <textarea name="code" rows="12"
                    style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: 'Courier New', monospace;"
                    placeholder="Paste your code here...">{{ old('code') }}</textarea>
                @error('code')
                <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Caption -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Caption</label>
            <textarea name="caption" rows="4"
                style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; resize: vertical;"
                placeholder="What's on your mind?">{{ old('caption') }}</textarea>
            @error('caption')
            <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tags -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Tags</label>
            <input type="text" name="tags"
                style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px;"
                placeholder="laravel, php, webdev (separate with commas)" value="{{ old('tags') }}">
            <small style="color: #8e8e8e; font-size: 12px;">Add relevant tags to help others discover your post</small>
            @error('tags')
            <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Visibility & Location -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Who can see this?</label>
                <select name="visibility"
                    style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px;">
                    <option value="public" {{ old('visibility', 'public' )=='public' ? 'selected' : '' }}>Public
                    </option>
                    <option value="followers" {{ old('visibility')=='followers' ? 'selected' : '' }}>Followers Only
                    </option>
                </select>
                @error('visibility')
                <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Add Location</label>
                <input type="text" name="location"
                    style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px;"
                    placeholder="e.g., San Francisco, CA" value="{{ old('location') }}">
                @error('location')
                <div style="color: #ed4956; font-size: 12px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function togglePostType() {
    const imageSection = document.getElementById('image-section');
    const codeSection = document.getElementById('code-section');

    const textType = document.getElementById('type-text');
    const imageType = document.getElementById('type-image');
    const codeType = document.getElementById('type-code');

    if (textType.checked) {
        imageSection.style.display = 'none';
        codeSection.style.display = 'none';
    } else if (imageType.checked) {
        imageSection.style.display = 'block';
        codeSection.style.display = 'none';
    } else if (codeType.checked) {
        imageSection.style.display = 'none';
        codeSection.style.display = 'block';
    }
}

function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.position = 'relative';
                div.style.width = '100px';
                div.style.height = '100px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '×';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '4px';
                removeBtn.style.right = '4px';
                removeBtn.style.background = 'rgba(0,0,0,0.5)';
                removeBtn.style.color = 'white';
                removeBtn.style.border = 'none';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.width = '24px';
                removeBtn.style.height = '24px';
                removeBtn.style.cursor = 'pointer';
                removeBtn.onclick = function() {
                    div.remove();
                };

                div.appendChild(img);
                div.appendChild(removeBtn);
                preview.appendChild(div);
            }

            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
@endsection