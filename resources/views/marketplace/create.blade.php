{{-- resources/views/marketplace/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Listing - Marketplace')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Create New Listing
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Display Validation Errors -->
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data"
                        id="listingForm">
                        @csrf

                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Basic Information</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    placeholder="e.g., MacBook Pro 16" maxlength="200" required>
                                <div class="form-text">Be specific and include key details</div>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category <span
                                        class="text-danger">*</span></label>
                                <div class="category-input-wrapper">
                                    <input type="text" name="category" id="categoryInput"
                                        class="form-control @error('category') is-invalid @enderror"
                                        value="{{ old('category') }}"
                                        placeholder="e.g., Laptops, Books, Software, Services"
                                        list="categorySuggestions" autocomplete="off" required>
                                    <datalist id="categorySuggestions">
                                        @php
                                        $existingCategories = \App\Models\MarketplaceListing::getUniqueCategories();
                                        @endphp
                                        @foreach($existingCategories as $cat)
                                        <option value="{{ $cat }}">
                                            @endforeach
                                        <option value="Laptops">
                                        <option value="Desktops">
                                        <option value="Monitors">
                                        <option value="Keyboards">
                                        <option value="Programming Books">
                                        <option value="Courses">
                                        <option value="Software Licenses">
                                        <option value="Development Tools">
                                        <option value="Services">
                                    </datalist>
                                </div>
                                <div class="form-text">Type your category or select from suggestions</div>
                                @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description <span
                                        class="text-danger">*</span></label>
                                <textarea name="description"
                                    class="form-control @error('description') is-invalid @enderror" rows="6"
                                    placeholder="Describe your item in detail..."
                                    required>{{ old('description') }}</textarea>
                                <div class="form-text">Include condition, features, reason for selling, etc.</div>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Pricing</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Price Type <span
                                        class="text-danger">*</span></label>
                                <select name="price_type" id="priceType"
                                    class="form-select @error('price_type') is-invalid @enderror" required>
                                    <option value="fixed" {{ old('price_type')=='fixed' ? 'selected' : '' }}>Fixed Price
                                    </option>
                                    <option value="negotiable" {{ old('price_type')=='negotiable' ? 'selected' : '' }}>
                                        Negotiable</option>
                                    <option value="free" {{ old('price_type')=='free' ? 'selected' : '' }}>Free</option>
                                </select>
                                @error('price_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="priceField">
                                <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="price" id="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00">
                                </div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Condition & Details -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Condition & Details</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Condition</label>
                                    <select name="condition"
                                        class="form-select @error('condition') is-invalid @enderror">
                                        <option value="">Select condition</option>
                                        <option value="new" {{ old('condition')=='new' ? 'selected' : '' }}>New</option>
                                        <option value="like_new" {{ old('condition')=='like_new' ? 'selected' : '' }}>
                                            Like New</option>
                                        <option value="good" {{ old('condition')=='good' ? 'selected' : '' }}>Good
                                        </option>
                                        <option value="fair" {{ old('condition')=='fair' ? 'selected' : '' }}>Fair
                                        </option>
                                        <option value="poor" {{ old('condition')=='poor' ? 'selected' : '' }}>Poor
                                        </option>
                                    </select>
                                    @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Brand (optional)</label>
                                    <input type="text" name="brand"
                                        class="form-control @error('brand') is-invalid @enderror"
                                        value="{{ old('brand') }}" placeholder="e.g., Apple, Dell, Logitech">
                                    @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Model (optional)</label>
                                    <input type="text" name="model"
                                        class="form-control @error('model') is-invalid @enderror"
                                        value="{{ old('model') }}" placeholder="e.g., MacBook Pro 16">
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location & Shipping -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Location & Shipping</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Location (optional)</label>
                                <input type="text" name="location"
                                    class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="e.g., San Francisco, CA">
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="is_shippable" class="form-check-input"
                                            id="isShippable" value="1" {{ old('is_shippable') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isShippable">
                                            Available for shipping
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="is_local_pickup" class="form-check-input"
                                            id="isLocalPickup" value="1" {{ old('is_local_pickup') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isLocalPickup">
                                            Available for local pickup
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Images <span class="text-danger">*</span></h6>

                            <div class="border rounded p-4 text-center" id="uploadArea">
                                <div id="uploadPrompt">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted mb-3">Drag & drop images or click to browse</p>
                                    <p class="text-muted small mb-3">Upload up to 10 images (max 5MB each)</p>
                                    <button type="button" class="btn btn-primary" id="browseBtn">
                                        <i class="bi bi-upload"></i> Select Images
                                    </button>
                                </div>

                                <div id="imagePreviewContainer" class="row g-3 mt-3 d-none">
                                    <!-- Images will be previewed here -->
                                </div>

                                <input type="file" name="images[]" id="imageInput" class="d-none" multiple
                                    accept="image/jpeg,image/png,image/jpg,image/gif">
                            </div>
                            @error('images')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-send"></i> Create Listing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #uploadArea {
        border: 2px dashed #dee2e6;
        cursor: pointer;
        transition: all 0.3s;
        min-height: 200px;
    }

    #uploadArea:hover {
        border-color: var(--primary-color);
        background-color: rgba(13, 110, 253, 0.05);
    }

    .preview-image {
        position: relative;
        aspect-ratio: 1;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .remove-image:hover {
        background: #dc3545;
        color: white;
    }

    .category-input-wrapper {
        position: relative;
    }

    .category-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-top: none;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .category-suggestions.show {
        display: block;
    }

    .category-suggestion-item {
        padding: 8px 12px;
        cursor: pointer;
    }

    .category-suggestion-item:hover {
        background-color: #f8f9fa;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Price type toggle
    const priceType = document.getElementById('priceType');
    const priceField = document.getElementById('priceField');
    const priceInput = document.getElementById('price');

    function togglePriceField() {
        if (priceType.value === 'free') {
            priceField.style.display = 'none';
            priceInput.value = 0;
            priceInput.required = false;
        } else {
            priceField.style.display = 'block';
            priceInput.required = true;
        }
    }

    priceType.addEventListener('change', togglePriceField);
    togglePriceField();

    // Image upload handling
    const uploadArea = document.getElementById('uploadArea');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const imageInput = document.getElementById('imageInput');
    const browseBtn = document.getElementById('browseBtn');
    const previewContainer = document.getElementById('imagePreviewContainer');
    let selectedFiles = [];

    // Browse button click
    browseBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        imageInput.click();
    });

    // Click on upload area
    uploadArea.addEventListener('click', (e) => {
        if (e.target !== browseBtn && !e.target.closest('.remove-image')) {
            imageInput.click();
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#0d6efd';
        uploadArea.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#dee2e6';
        uploadArea.style.backgroundColor = '';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#dee2e6';
        uploadArea.style.backgroundColor = '';

        if (e.dataTransfer.files.length) {
            handleFiles(e.dataTransfer.files);
        }
    });

    // File input change
    imageInput.addEventListener('change', function() {
        if (this.files.length) {
            handleFiles(this.files);
        }
    });

    function handleFiles(files) {
        const remainingSlots = 10 - selectedFiles.length;
        const filesToAdd = Math.min(files.length, remainingSlots);

        for (let i = 0; i < filesToAdd; i++) {
            const file = files[i];

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select only image files');
                continue;
            }

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File ' + file.name + ' is too large. Maximum size is 5MB');
                continue;
            }

            selectedFiles.push(file);
        }

        updateFileInput();
        updatePreviews();
    }

    function updateFileInput() {
        // Create a new FileList-like array
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }

    function updatePreviews() {
        previewContainer.innerHTML = '';

        if (selectedFiles.length > 0) {
            uploadPrompt.classList.add('d-none');
            previewContainer.classList.remove('d-none');

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewCol = document.createElement('div');
                    previewCol.className = 'col-4 col-md-3';
                    previewCol.innerHTML = `
                        <div class="preview-image">
                            <img src="${e.target.result}" alt="Preview ${index + 1}">
                            <button type="button" class="remove-image" data-index="${index}">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(previewCol);

                    // Add remove event listener
                    previewCol.querySelector('.remove-image').addEventListener('click', function() {
                        const idx = parseInt(this.dataset.index);
                        selectedFiles.splice(idx, 1);
                        updateFileInput();
                        updatePreviews();

                        if (selectedFiles.length === 0) {
                            uploadPrompt.classList.remove('d-none');
                            previewContainer.classList.add('d-none');
                        }
                    });
                };
                reader.readAsDataURL(file);
            });
        } else {
            uploadPrompt.classList.remove('d-none');
            previewContainer.classList.add('d-none');
        }
    }

    // Form validation
    document.getElementById('listingForm').addEventListener('submit', function(e) {
        if (selectedFiles.length === 0) {
            e.preventDefault();
            alert('Please upload at least one image');
            return false;
        }

        if (priceType.value !== 'free' && !priceInput.value) {
            e.preventDefault();
            alert('Please enter a price');
            return false;
        }
    });

    // Category suggestions - simple datalist is already handling this
});
</script>

@endsection
