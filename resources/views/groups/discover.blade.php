{{-- resources/views/groups/discover.blade.php --}}
@extends('layouts.app')

@section('title', 'Discover Groups - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-compass me-2 text-primary"></i>
                Discover Groups
            </h4>
            <p class="text-muted mb-0">
                <a href="{{ route('groups.index') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to All Groups
                </a>
            </p>
        </div>
        @auth
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create Group
        </a>
        @endauth
    </div>

    <!-- Browse by Category -->
    <div class="mb-5">
        <h6 class="fw-semibold mb-3">Browse by Category</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $slug => $label)
            <a href="{{ route('groups.category', $slug) }}" class="btn btn-outline-secondary">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Popular Groups -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-graph-up-arrow me-2 text-success"></i> Popular Groups
            </h6>
            <a href="{{ route('groups.recommended') }}" class="btn btn-sm btn-outline-primary">View More</a>
        </div>
        @if($recommended->count() > 0)
        <div class="row g-4">
            @foreach($recommended as $group)
            <div class="col-md-4 col-lg-2">
                @include('groups.partials.group-card', ['group' => $group])
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">No groups yet.</p>
        @endif
    </div>

    <!-- Recently Created -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-clock-history me-2 text-info"></i> Recently Created
            </h6>
            <a href="{{ route('groups.trending') }}" class="btn btn-sm btn-outline-primary">See Trending</a>
        </div>
        @if($recent->count() > 0)
        <div class="row g-4">
            @foreach($recent as $group)
            <div class="col-md-4 col-lg-2">
                @include('groups.partials.group-card', ['group' => $group])
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">No groups yet.</p>
        @endif
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection
