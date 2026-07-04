{{-- resources/views/groups/recommended.blade.php --}}
@extends('layouts.app')

@section('title', 'Recommended Groups - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-stars me-2 text-warning"></i>
                Recommended For You
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

    @if($groups->count() > 0)
    <div class="row g-4">
        @foreach($groups as $group)
        <div class="col-md-4 col-lg-3">
            @include('groups.partials.group-card', ['group' => $group])
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
            <i class="bi bi-stars text-warning" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-semibold mb-2">No recommendations yet</h5>
        <p class="text-muted mb-4">Explore all groups to find ones you like.</p>
        <a href="{{ route('groups.index') }}" class="btn btn-primary">
            <i class="bi bi-people me-2"></i> Browse All Groups
        </a>
    </div>
    @endif
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
