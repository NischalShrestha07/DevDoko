{{-- resources/views/groups/tabs/members.blade.php --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-people me-2"></i>
                Members ({{ $group->members_count ?? $group->members()->wherePivot('status', 'active')->count() }})
            </h5>
            @if($group->canManage(auth()->user()))
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inviteModal">
                <i class="bi bi-envelope-plus"></i> Invite Members
            </button>
            @endif
        </div>

        @if(isset($pendingRequests) && $pendingRequests->count() > 0)
        <div class="mb-4">
            <h6 class="fw-semibold mb-3 text-warning">
                <i class="bi bi-hourglass-split"></i>
                Pending Requests ({{ $pendingRequests->count() }})
            </h6>
            <div class="list-group">
                @foreach($pendingRequests as $user)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ $user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                            class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <a href="{{ route('profile.show', $user->profile->username ?? $user->name) }}"
                                class="text-decoration-none fw-semibold">
                                {{ $user->profile->username ?? $user->name }}
                            </a>
                            <small class="text-muted d-block">
                                Requested {{ $user->pivot->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    <div class="btn-group">
                        <form action="{{ route('groups.members.approve', [$group->slug, $user->id]) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('groups.members.reject', [$group->slug, $user->id]) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Reject this request?')">
                                <i class="bi bi-x-lg"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="row g-3">
            @foreach($members as $member)
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 border rounded">
                    <img src="{{ $member->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) }}"
                        class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
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
                                <small class="text-muted d-block">
                                    Joined {{ \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() }}
                                </small>
                            </div>
                            @if($group->canManage(auth()->user()) && $member->id !== $group->owner_id && $member->id !==
                            auth()->id())
                            <div class="dropdown">
                                <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if($member->pivot->role !== 'admin')
                                    <li>
                                        <form action="{{ route('groups.members.role', [$group->slug, $member->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="role" value="admin">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-shield-check"></i> Make Admin
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    @if($member->pivot->role !== 'moderator' && $member->pivot->role !== 'admin')
                                    <li>
                                        <form action="{{ route('groups.members.role', [$group->slug, $member->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="role" value="moderator">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-shield"></i> Make Moderator
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    @if($member->pivot->role !== 'member' && $member->pivot->role !== 'owner')
                                    <li>
                                        <form action="{{ route('groups.members.role', [$group->slug, $member->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="role" value="member">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-person"></i> Make Member
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('groups.members.remove', [$group->slug, $member->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Remove this member from the group?')">
                                                <i class="bi bi-person-x"></i> Remove Member
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

        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </div>
</div>

@if($group->canManage(auth()->user()))
<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('groups.invite', $group->slug) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Invite Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="colleague@example.com"
                            required>
                        <div class="form-text">Send an invitation to join this group</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Personal Message (optional)</label>
                        <textarea name="message" class="form-control" rows="3"
                            placeholder="Hi! I'd like to invite you to join our group..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Send Invitation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif