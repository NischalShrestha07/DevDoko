{{-- resources/views/groups/members.blade.php --}}
@extends('layouts.app')

@section('title', 'Members - ' . $group->name)

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-people me-2 text-primary"></i>
                Members
            </h4>
            <p class="text-muted mb-0">
                <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to {{ $group->name }}
                </a>
            </p>
        </div>
        @if($group->canManage(auth()->user()))
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
            <i class="bi bi-envelope-plus me-2"></i> Invite Members
        </button>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $members->total() ?? $group->members_count }}</h5>
                        <small class="text-muted">Total Members</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-shield-check text-success fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $admins->count() }}</h5>
                        <small class="text-muted">Admins & Moderators</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-clock-history text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $group->created_at->diffForHumans() }}</h5>
                        <small class="text-muted">Group Age</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-calendar-check text-warning fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $group->posts_count }}</h5>
                        <small class="text-muted">Total Posts</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Section -->
    @if(isset($pendingRequests) && $pendingRequests->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-semibold mb-0 text-warning">
                <i class="bi bi-hourglass-split me-2"></i>
                Pending Join Requests ({{ $pendingRequests->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($pendingRequests as $user)
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                            class="rounded-circle me-3 border" style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <a href="{{ route('profile.show', $user->profile->username ?? $user->name) }}"
                                class="text-decoration-none fw-semibold text-dark">
                                {{ $user->profile->username ?? $user->name }}
                            </a>
                            <div class="d-flex align-items-center mt-1">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="bi bi-envelope"></i> {{ $user->email }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> Requested {{ $user->pivot->created_at->diffForHumans()
                                    }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <form action="{{ route('groups.members.approve', [$group->slug, $user->id]) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm rounded-start-3">
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('groups.members.reject', [$group->slug, $user->id]) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-end-3"
                                onclick="return confirm('Reject this membership request?')">
                                <i class="bi bi-x-lg"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Admins & Moderators Section -->
    @if($admins->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-shield-check me-2 text-primary"></i>
                Admins & Moderators
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($admins as $admin)
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 bg-light rounded-3">
                        <img src="{{ $admin->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($admin->name) }}"
                            class="rounded-circle me-3 border" style="width: 56px; height: 56px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <a href="{{ route('profile.show', $admin->profile->username ?? $admin->name) }}"
                                        class="text-decoration-none fw-semibold text-dark">
                                        {{ $admin->profile->username ?? $admin->name }}
                                    </a>
                                    <span
                                        class="badge {{ $admin->pivot->role === 'owner' ? 'bg-warning' : ($admin->pivot->role === 'admin' ? 'bg-primary' : 'bg-info') }} ms-2">
                                        @if($admin->pivot->role === 'owner')
                                        👑 Owner
                                        @elseif($admin->pivot->role === 'admin')
                                        🛡️ Admin
                                        @else
                                        ⚔️ Moderator
                                        @endif
                                    </span>
                                    <small class="d-block text-muted mt-1">
                                        <i class="bi bi-calendar3"></i> Joined {{
                                        \Carbon\Carbon::parse($admin->pivot->joined_at)->format('M d, Y') }}
                                    </small>
                                </div>
                                @if($group->canManage(auth()->user()) && $admin->id !== $group->owner_id && $admin->id
                                !== auth()->id())
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        @if($admin->pivot->role !== 'admin')
                                        <li>
                                            <form
                                                action="{{ route('groups.members.role', [$group->slug, $admin->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="admin">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-shield-check me-2"></i> Make Admin
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($admin->pivot->role !== 'moderator' && $admin->pivot->role !== 'admin')
                                        <li>
                                            <form
                                                action="{{ route('groups.members.role', [$group->slug, $admin->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="moderator">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-shield me-2"></i> Make Moderator
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($admin->pivot->role !== 'member' && $admin->pivot->role !== 'owner')
                                        <li>
                                            <form
                                                action="{{ route('groups.members.role', [$group->slug, $admin->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="member">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-person me-2"></i> Make Member
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form
                                                action="{{ route('groups.members.remove', [$group->slug, $admin->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Remove this member from the group?')">
                                                    <i class="bi bi-person-x me-2"></i> Remove Member
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- All Members Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-people me-2 text-primary"></i>
                All Members ({{ $members->total() ?? $members->count() }})
            </h5>
            <div class="d-flex gap-2">
                <input type="text" id="memberSearch" class="form-control form-control-sm"
                    placeholder="Search members..." style="width: 200px;">
                <select id="memberRole" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">All Roles</option>
                    <option value="admin">Admins</option>
                    <option value="moderator">Moderators</option>
                    <option value="member">Members</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3" id="membersList">
                @forelse($members as $member)
                <div class="col-md-6 col-lg-4 member-item"
                    data-name="{{ strtolower($member->profile->username ?? $member->name) }}"
                    data-role="{{ $member->pivot->role }}">
                    <div class="d-flex align-items-center p-3 border rounded-3 hover-shadow transition">
                        <img src="{{ $member->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) }}"
                            class="rounded-circle me-3 border" style="width: 48px; height: 48px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <a href="{{ route('profile.show', $member->profile->username ?? $member->name) }}"
                                        class="text-decoration-none fw-semibold text-dark">
                                        {{ $member->profile->username ?? $member->name }}
                                    </a>
                                    <span class="badge bg-light text-dark ms-2">
                                        @if($member->pivot->role === 'owner')
                                        👑 Owner
                                        @elseif($member->pivot->role === 'admin')
                                        🛡️ Admin
                                        @elseif($member->pivot->role === 'moderator')
                                        ⚔️ Moderator
                                        @else
                                        👤 Member
                                        @endif
                                    </span>
                                    <small class="d-block text-muted">
                                        <i class="bi bi-calendar3"></i> Joined {{
                                        \Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y') }}
                                    </small>
                                </div>
                                @if($group->canManage(auth()->user()) && $member->id !== $group->owner_id && $member->id
                                !== auth()->id())
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        @if($member->pivot->role !== 'admin')
                                        <li>
                                            <form
                                                action="{{ route('groups.members.role', [$group->slug, $member->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="admin">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-shield-check me-2"></i> Make Admin
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($member->pivot->role !== 'moderator' && $member->pivot->role !== 'admin')
                                        <li>
                                            <form
                                                action="{{ route('groups.members.role', [$group->slug, $member->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="moderator">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-shield me-2"></i> Make Moderator
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($member->pivot->role !== 'member' && $member->pivot->role !== 'owner')
                                        <li>
                                            <form
                                                action="{{ route('groups.members.role', [$group->slug, $member->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="member">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-person me-2"></i> Make Member
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form
                                                action="{{ route('groups.members.remove', [$group->slug, $member->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Remove this member from the group?')">
                                                    <i class="bi bi-person-x me-2"></i> Remove Member
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-people text-muted fs-1"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">No members yet</h5>
                        <p class="text-muted mb-0">Be the first to join this group!</p>
                    </div>
                </div>
                @endforelse
            </div>

            @if($members->hasPages())
            <div class="mt-4">
                {{ $members->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@if($group->canManage(auth()->user()))
<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold" id="inviteModalLabel">
                    <i class="bi bi-envelope-plus me-2 text-primary"></i>
                    Invite Members
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <form action="{{ route('groups.invite', $group->slug) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0"
                                placeholder="colleague@example.com" required>
                        </div>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle"></i> Send an invitation to join this group
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Personal Message</label>
                        <textarea name="message" class="form-control" rows="3"
                            placeholder="Hi! I'd like to invite you to join our group..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i> Send Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .hover-shadow {
        transition: all 0.2s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }

    .transition {
        transition: all 0.2s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const memberSearch = document.getElementById('memberSearch');
    const memberRole = document.getElementById('memberRole');
    const memberItems = document.querySelectorAll('.member-item');

    function filterMembers() {
        const searchTerm = memberSearch.value.toLowerCase();
        const roleFilter = memberRole.value;

        memberItems.forEach(item => {
            const name = item.dataset.name;
            const role = item.dataset.role;

            let show = true;

            if (searchTerm && !name.includes(searchTerm)) {
                show = false;
            }

            if (roleFilter && role !== roleFilter && !(roleFilter === 'admin' && (role === 'owner' || role === 'admin'))) {
                show = false;
            }

            item.style.display = show ? 'block' : 'none';
        });
    }

    memberSearch.addEventListener('input', filterMembers);
    memberRole.addEventListener('change', filterMembers);
});
</script>
@endsection