{{-- resources/views/marketplace/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Marketplace - DevDoko')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-shop me-2 text-primary"></i>
                Developer Marketplace
            </h1>
            <p class="text-muted mb-0">Buy and sell developer gear, books, software, and services</p>
        </div>
        @auth
        <a href="{{ route('marketplace.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            Create Listing
        </a>
        @endauth
    </div>

    <!-- Featured Listings -->
    @if($featuredListings->count() > 0)
    <div class="mb-5">
        <h5 class="fw-semibold mb-3">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Featured Listings
        </h5>
        <div class="row g-4">
            @foreach($featuredListings as $listing)
            <div class="col-md-3">
                @include('marketplace.partials.listing-card', ['listing' => $listing, 'featured' => true])
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Categories</h6>
                    <div class="list-group list-group-flush mb-4">
                        <a href="{{ route('marketplace.index') }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !request('category') ? 'active' : '' }}">
                            <span>All Categories</span>
                            <span class="badge bg-primary rounded-pill">{{ $listings->total() }}</span>
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('marketplace.index', array_merge(request()->query(), ['category' => $category])) }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $category ? 'active' : '' }}">
                            <span>{{ $category }}</span>
                        </a>
                        @endforeach
                    </div>

                    <hr>

                    <!-- Price Range Filter -->
                    <h6 class="fw-semibold mb-3">Price Range</h6>
                    <form method="GET" action="{{ route('marketplace.index') }}" id="filterForm">
                        @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control" placeholder="Min $"
                                    value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control" placeholder="Max $"
                                    value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Condition Filter -->
                        <h6 class="fw-semibold mb-3">Condition</h6>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="condition[]" value="new"
                                    id="conditionNew" {{ in_array('new', (array)request('condition', [])) ? 'checked'
                                    : '' }}>
                                <label class="form-check-label" for="conditionNew">New</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="condition[]" value="like_new"
                                    id="conditionLikeNew" {{ in_array('like_new', (array)request('condition', []))
                                    ? 'checked' : '' }}>
                                <label class="form-check-label" for="conditionLikeNew">Like New</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="condition[]" value="good"
                                    id="conditionGood" {{ in_array('good', (array)request('condition', [])) ? 'checked'
                                    : '' }}>
                                <label class="form-check-label" for="conditionGood">Good</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="condition[]" value="fair"
                                    id="conditionFair" {{ in_array('fair', (array)request('condition', [])) ? 'checked'
                                    : '' }}>
                                <label class="form-check-label" for="conditionFair">Fair</label>
                            </div>
                        </div>

                        <!-- Sort -->
                        <h6 class="fw-semibold mb-3">Sort By</h6>
                        <select name="sort" class="form-select mb-3">
                            <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort')=='price_low' ? 'selected' : '' }}>Price: Low to
                                High</option>
                            <option value="price_high" {{ request('sort')=='price_high' ? 'selected' : '' }}>Price: High
                                to Low</option>
                            <option value="popular" {{ request('sort')=='popular' ? 'selected' : '' }}>Most Viewed
                            </option>
                        </select>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>

                        @if(request()->anyFilled(['category', 'min_price', 'max_price', 'condition', 'sort']))
                        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Clear Filters
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Listings Grid -->
        <div class="col-lg-9">
            <!-- Search Bar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('marketplace.index') }}" class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control border-0 bg-light" name="search"
                                    placeholder="Search listings..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select border-0 bg-light">
                                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort')=='price_low' ? 'selected' : '' }}>Price:
                                    Low to High</option>
                                <option value="price_high" {{ request('sort')=='price_high' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-3">
                <p class="text-muted">
                    Showing {{ $listings->firstItem() ?? 0 }} - {{ $listings->lastItem() ?? 0 }}
                    of {{ $listings->total() }} listings
                </p>
            </div>

            <!-- Listings Grid -->
            @if($listings->count() > 0)
            <div class="row g-4">
                @foreach($listings as $listing)
                <div class="col-md-6 col-lg-4">
                    @include('marketplace.partials.listing-card', ['listing' => $listing])
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $listings->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-shop display-1 text-muted"></i>
                <h5 class="mt-3 mb-2">No listings found</h5>
                <p class="text-muted">Try adjusting your filters or create a new listing</p>
                @auth
                <a href="{{ route('marketplace.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Create Listing
                </a>
                @endauth
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
