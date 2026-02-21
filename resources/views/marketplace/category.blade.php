{{-- resources/views/marketplace/category.blade.php --}}
@extends('layouts.app')

@section('title', $category->name . ' - Marketplace')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-shop me-2 text-primary"></i>
                {{ $category->name }}
            </h1>
            <p class="text-muted mb-0">{{ $category->description ?? 'Browse listings in this category' }}</p>
        </div>
        @auth
        <a href="{{ route('marketplace.create') }}?category={{ $category->id }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            Create Listing
        </a>
        @endauth
    </div>

    <div class="row">
        <!-- Sidebar Filters (same as index) -->
        <div class="col-lg-3 mb-4">
            @include('marketplace.partials.filters', ['categories' => $categories])
        </div>

        <!-- Listings Grid -->
        <div class="col-lg-9">
            @include('marketplace.partials.search-bar')

            @include('marketplace.partials.listing-grid', ['listings' => $listings])
        </div>
    </div>
</div>
@endsection
