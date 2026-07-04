{{-- resources/views/groups/partials/group-card.blade.php --}}
<div class="card h-100 border-0 shadow-sm hover-scale">
    <div class="position-relative">
        @if($group->cover_url)
        <img src="{{ $group->cover_url }}" class="card-img-top" alt="{{ $group->name }}"
            style="height: 100px; object-fit: cover;">
        @else
        <div class="bg-gradient-primary" style="height: 100px; border-radius: 0.375rem 0.375rem 0 0;"></div>
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
            {{ $group->privacy_label }}
        </span>

        <p class="small text-muted mb-2">
            {{ Str::limit($group->description, 80) }}
        </p>

        <div class="d-flex justify-content-between align-items-center small text-muted">
            <span><i class="bi bi-people"></i> {{ $group->members_count }} members</span>
            <span><i class="bi bi-chat"></i> {{ $group->posts_count ?? 0 }} posts</span>
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
