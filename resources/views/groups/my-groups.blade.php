{{-- resources/views/groups/my-groups.blade.php --}}
@extends('layouts.app')

@section('title', 'My Groups - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-bookmark-check-fill me-2 text-primary"></i>
                My Groups
            </h1>
            <p class="text-muted mb-0">Groups you've joined or created</p>
        </div>
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create Group
        </a>
    </div>

    @if($pendingRequests->count() > 0)
    <div class="alert alert-warning mb-4">
        <i class="bi bi-hourglass-split"></i>
        You have {{ $pendingRequests->count() }} pending
        {{ Str::plural('request', $pendingRequests->count()) }} to join groups.
    </div>
    @endif

    @if($groups->count() > 0)
    <div class="row g-4">
        @foreach($groups as $group)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    @if($group->cover_url)
                    <img src="{{ $group->cover_url }}" class="card-img-top" alt="{{ $group->name }}"
                        style="height: 120px; object-fit: cover;">
                    @else
                    <div class="bg-gradient-primary" style="height: 120px; border-radius: 0.375rem 0.375rem 0 0;"></div>
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

                    <span class="badge bg-light text-dark mb-2">
                        {{ $group->category_label }}
                    </span>

                    <p class="small text-muted mb-2">
                        {{ Str::limit($group->description, 80) }}
                    </p>

                    <div class="d-flex justify-content-center gap-3 small text-muted">
                        <span><i class="bi bi-people"></i> {{ $group->members_count }} members</span>
                        <span><i class="bi bi-chat"></i> {{ $group->posts_count }} posts</span>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pb-3 pt-0">
                    <div class="d-flex gap-2">
                        <a href="{{ route('groups.show', $group->slug) }}"
                            class="btn btn-outline-primary btn-sm flex-grow-1">
                            <i class="bi bi-box-arrow-in-right"></i> View
                        </a>
                        @if($group->canManage(auth()->user()))
                        <a href="{{ route('groups.edit', $group->slug) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-gear"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
            <i class="bi bi-people text-primary" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-semibold mb-2">No groups yet</h5>
        <p class="text-muted mb-4">You haven't joined any groups. Discover and join groups that interest you!</p>
        <a href="{{ route('groups.index') }}" class="btn btn-primary">
            <i class="bi bi-compass"></i> Discover Groups
        </a>
    </div>
    @endif
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection