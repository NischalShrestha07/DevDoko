{{-- resources/views/groups/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Developer Groups - DevDoko')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-people-fill me-2 text-primary"></i>
                Developer Groups
            </h1>
            <p class="text-muted mb-0">Connect with developers who share your interests</p>
        </div>

        @auth
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            Create Group
        </a>
        @endauth
    </div>

    @auth
    @if($myGroups && $myGroups->count() > 0)
    <!-- My Groups -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-bookmark-check-fill me-2 text-success"></i>
                Your Groups
            </h5>
            <a href="{{ route('groups.my-groups') }}" class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>

        <div class="row g-3">
            @foreach($myGroups->take(4) as $group)
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                @if($group->icon_url)
                                <img src="{{ $group->icon_url }}" alt="{{ $group->name }}" class="rounded-circle"
                                    style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-people-fill text-primary fs-2"></i>
                                </div>
                                @endif
                            </div>
                            <h6 class="fw-semibold text-dark mb-1">{{ $group->name }}</h6>
                            <p class="small text-muted mb-2">{{ $group->members_count }} members</p>
                            <span class="badge bg-light text-dark">{{ $group->category_label }}</span>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endauth

    <!-- Discover Groups -->
    <div class="mb-4">
        <h5 class="fw-semibold mb-3">
            <i class="bi bi-compass me-2 text-primary"></i>
            Discover Groups
        </h5>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('groups.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search groups..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ request('category')==$value ? 'selected' : '' }}>{{ $label
                                }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="popular" {{ request('sort')=='popular' ? 'selected' : '' }}>Most Members
                            </option>
                            <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Newest</option>
                            <option value="active" {{ request('sort')=='active' ? 'selected' : '' }}>Most Active
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Groups Grid -->
        <div class="row g-4">
            @forelse($groups as $group)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="position-relative">
                        @if($group->cover_url)
                        <img src="{{ $group->cover_url }}" class="card-img-top" alt="{{ $group->name }}"
                            style="height: 100px; object-fit: cover;">
                        @else
                        <div class="bg-gradient-primary" style="height: 100px; border-radius: 0.375rem 0.375rem 0 0;">
                        </div>
                        @endif

                        <div class="position-absolute top-100 start-50 translate-middle">
                            @if($group->icon_url)
                            <img src="{{ $group->icon_url }}" alt="{{ $group->name }}"
                                class="rounded-circle border border-3 border-white"
                                style="width: 64px; height: 64px; object-fit: cover;">
                            @else
                            <div class="bg-white rounded-circle border border-3 border-primary d-inline-flex align-items-center justify-content-center"
                                style="width: 64px; height: 64px;">
                                <i class="bi bi-people-fill text-primary fs-2"></i>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-body text-center pt-5 pb-3">
                        <h6 class="fw-semibold mb-1">
                            <a href="{{ route('groups.show', $group->slug) }}" class="text-dark text-decoration-none">
                                {{ $group->name }}
                            </a>
                        </h6>

                        <div class="d-flex justify-content-center gap-2 mb-2">
                            <span class="badge bg-light text-dark">
                                {{ $group->category_label }}
                            </span>
                            <span class="badge bg-light text-dark">
                                {{ $group->privacy_label }}
                            </span>
                        </div>

                        <p class="small text-muted mb-2">
                            {{ Str::limit($group->description, 80) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center small text-muted">
                            <span>
                                <i class="bi bi-people"></i> {{ $group->members_count }}
                            </span>
                            <span>
                                <i class="bi bi-chat"></i> {{ $group->posts_count }}
                            </span>
                            <span>
                                <i class="bi bi-calendar"></i> {{ $group->upcoming_events_count ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pb-3 pt-0">
                        @auth
                        @if($group->is_member)
                        <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-check-lg me-1"></i> Member
                        </a>
                        @elseif($group->is_pending)
                        <button class="btn btn-secondary btn-sm w-100" disabled>
                            <i class="bi bi-hourglass me-1"></i> Pending
                        </button>
                        @else
                        <form action="{{ route('groups.join', $group->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-plus-circle me-1"></i> Join Group
                            </button>
                        </form>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login to Join
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                        <i class="bi bi-people text-primary" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No groups found</h5>
                    <p class="text-muted mb-4">Be the first to create a group for your tech community</p>
                    @auth
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i> Create Group
                    </a>
                    @endauth
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $groups->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }

    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush
@endsection