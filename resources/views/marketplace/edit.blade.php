{{-- resources/views/marketplace/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Listing - Marketplace')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-pencil me-2 text-primary"></i>
                        Edit Listing
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('marketplace.update', $listing) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Basic Information</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $listing->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category</label>
                                <input type="text" name="category" class="form-control"
                                    value="{{ old('category', $listing->category) }}"
                                    placeholder="e.g., Laptops, Books, Software" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="6"
                                    required>{{ old('description', $listing->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Pricing</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Price Type</label>
                                <select name="price_type" id="priceType" class="form-select" required>
                                    <option value="fixed" {{ $listing->price_type == 'fixed' ? 'selected' : '' }}>Fixed
                                        Price</option>
                                    <option value="negotiable" {{ $listing->price_type == 'negotiable' ? 'selected' : ''
                                        }}>Negotiable</option>
                                    <option value="free" {{ $listing->price_type == 'free' ? 'selected' : '' }}>Free
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3" id="priceField"
                                style="{{ $listing->price_type == 'free' ? 'display: none;' : '' }}">
                                <label class="form-label fw-semibold">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs</span>
                                    <input type="number" name="price" id="price" class="form-control"
                                        value="{{ old('price', $listing->price) }}" step="0.01" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Condition & Details -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Condition & Details</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Condition</label>
                                    <select name="condition" class="form-select">
                                        <option value="">Select condition</option>
                                        @foreach($conditions as $value => $label)
                                        <option value="{{ $value }}" {{ $listing->condition == $value ? 'selected' : ''
                                            }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Brand</label>
                                    <input type="text" name="brand" class="form-control"
                                        value="{{ old('brand', $listing->brand) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Model</label>
                                    <input type="text" name="model" class="form-control"
                                        value="{{ old('model', $listing->model) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Location & Shipping -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Location & Shipping</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Location</label>
                                <input type="text" name="location" class="form-control"
                                    value="{{ old('location', $listing->location) }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_shippable" class="form-check-input"
                                            id="isShippable" value="1" {{ $listing->is_shippable ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isShippable">
                                            Available for shipping
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_local_pickup" class="form-check-input"
                                            id="isLocalPickup" value="1" {{ $listing->is_local_pickup ? 'checked' : ''
                                        }}>
                                        <label class="form-check-label" for="isLocalPickup">
                                            Available for local pickup
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('marketplace.show', $listing->slug) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Listing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('priceType').addEventListener('change', function() {
    const priceField = document.getElementById('priceField');
    priceField.style.display = this.value === 'free' ? 'none' : 'block';
});
</script>
@endsection