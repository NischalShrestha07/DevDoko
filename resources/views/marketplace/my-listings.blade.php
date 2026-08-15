{{-- resources/views/marketplace/my-listings.blade.php --}}
@extends('layouts.app')

@section('title', 'My Listings - Marketplace')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-bag me-2 text-primary"></i>
                My Listings
            </h1>
            <p class="app-text-muted mb-0">Manage your marketplace listings</p>
        </div>
        <a href="{{ route('marketplace.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            Create New Listing
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-bag-check text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Active</h6>
                            <h4 class="mb-0">{{ $stats['active'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Pending</h6>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Sold</h6>
                            <h4 class="mb-0">{{ $stats['sold'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-eye text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Total Views</h6>
                            <h4 class="mb-0">{{ $stats['total_views'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listings Tabs -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a href="{{ route('marketplace.my-listings') }}" class="nav-link {{ ! $status ? 'active' : '' }}">All Listings</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('marketplace.my-listings', ['status' => 'active']) }}" class="nav-link {{ $status === 'active' ? 'active' : '' }}">Active</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('marketplace.my-listings', ['status' => 'sold']) }}" class="nav-link {{ $status === 'sold' ? 'active' : '' }}">Sold</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('marketplace.my-listings', ['status' => 'expired']) }}" class="nav-link {{ $status === 'expired' ? 'active' : '' }}">Expired</a>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            @include('marketplace.partials.my-listings-table', ['listings' => $listings])

            @if($listings->hasPages())
            <div class="mt-4">
                {{ $listings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
