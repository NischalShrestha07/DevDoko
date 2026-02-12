{{-- resources/views/groups/show.blade.php --}}
@extends('layouts.app')

@section('title', $group->name . ' - DevDoko Groups')

@section('content')
<div class="container py-4">
    <!-- Group Header -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <!-- Cover Image -->
        <div class="position-relative" style="height: 200px;">
            @if($group->cover_url)
            <img src="{{ $group->cover_url }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $group->name }}">
            @else
            <div class="w-100 h-100 bg-gradient-primary"></div>
            @endif

            <!-- Group Icon -->
            <div class="position-absolute bottom-0 start-0 translate-middle-y ms-4" style="bottom: -40px !important;">
                @if($group->icon_url)
                <img src="{{ $group->icon_url }}" alt="{{ $group->name }}"
                    class="rounded-circle border border-4 border-white"
                    style="width: 120px; height: 120px; object-fit: cover;">
                @else
                <div class="bg-white rounded-circle border border-4 border-primary d-inline-flex align-items-center justify-content-center"
                    style="width: 120px; height: 120px;">
                    <i class="bi bi-people-fill text-primary fs-1"></i>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="position-absolute bottom-0 end-0 mb-3 me-3">
                @auth
                @if($group->owner_id === auth()->id() || $userRole === 'admin')
                <div class="btn-group">
                    <a href="{{ route('groups.edit', $group->slug) }}" class="btn btn-light btn-sm">
                        <i class="bi bi-gear"></i> Manage
                    </a>
                    <button type="button" class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('groups.members', $group->slug) }}">
                                <i class="bi bi-people"></i> Members
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('groups.activity', $group->slug) }}">
                                <i class="bi bi-activity"></i> Activity Log
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('groups.destroy', $group->slug) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"
                                    onclick="return confirm('Are you sure you want to delete this group?')">
                                    <i class="bi bi-trash"></i> Delete Group
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @elseif($group->is_member)
                <div class="btn-group">
                    <form action="{{ route('groups.leave', $group->slug) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Leave this group?')">
                            <i class="bi bi-box-arrow-right"></i> Leave Group
                        </button>
                    </form>
                </div>
                @elseif($group->is_pending)
                <button class="btn btn-secondary btn-sm" disabled>
                    <i class="bi bi-hourglass"></i> Pending Approval
                </button>
                @else
                <form action="{{ route('groups.join', $group->slug) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Join Group
                    </button>
                </form>
                @endif
                @endauth
            </div>
        </div>

        <!-- Group Info -->
        <div class="card-body pt-5 pb-3">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1">{{ $group->name }}</h3>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark">
                            {{ $group->category_label }}
                        </span>
                        <span class="badge bg-light text-dark">
                            {{ $group->privacy_label }}
                        </span>
                        @foreach($group->tags ?? [] as $tag)
                        <span class="badge bg-secondary bg-opacity-10 text-dark">
                            #{{ $tag }}
                        </span>
                        @endforeach
                    </div>
                    <p class="text-muted">{{ $group->description }}</p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end gap-4">
                        <div class="text-center">
                            <h5 class="fw-bold mb-0">{{ $membersCount }}</h5>
                            <small class="text-muted">Members</small>
                        </div>
                        <div class="text-center">
                            <h5 class="fw-bold mb-0">{{ $onlineMembers }}</h5>
                            <small class="text-muted">Online</small>
                        </div>
                        <div class="text-center">
                            <h5 class="fw-bold mb-0">{{ $group->posts_count }}</h5>
                            <small class="text-muted">Posts</small>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> Created {{ $group->created_at->diffForHumans() }}
                        </small><br>
                        <small class="text-muted">
                            <i bi bi-person"></i> By
                            <a href="{{ route('profile.show', $group->owner->profile->username) }}"
                                class="text-decoration-none">
                                {{ $group->owner->profile->username ?? $group->owner->name }}
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card-footer bg-white border-0 pt-0">
            <ul class="nav nav-tabs border-0" id="groupTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts"
                        type="button" role="tab">
                        <i class="bi bi-chat"></i> Posts
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources"
                        type="button" role="tab">
                        <i class="bi bi-folder"></i> Resources
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button"
                        role="tab">
                        <i class="bi bi-calendar-event"></i> Events
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members"
                        type="button" role="tab">
                        <i class="bi bi-people"></i> Members
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="groupTabsContent">
        <!-- Posts Tab -->
        <div class="tab-pane fade show active" id="posts" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Create Post Box -->
                    @if($group->canPost(auth()->user()))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <form action="{{ route('groups.posts.store', $group->slug) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="title" class="form-control border-0 bg-light"
                                        placeholder="Post title..." required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="content" class="form-control border-0 bg-light" rows="3"
                                        placeholder="Share something with the group..."></textarea>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary me-2"
                                            onclick="document.getElementById('post-attachments').click();">
                                            <i class="bi bi-paperclip"></i> Attach
                                        </button>
                                        <input type="file" id="post-attachments" name="attachments[]" multiple
                                            class="d-none">
                                        <select name="type" class="form-select-sm d-inline-block w-auto">
                                            <option value="general">General</option>
                                            @if($group->canManage(auth()->user()))
                                            <option value="announcement">Announcement</option>
                                            @endif
                                            <option value="question">Question</option>
                                            <option value="resource">Resource</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-send"></i> Post
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Pinned Posts -->
                    @if($group->pinnedPosts->count() > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-pin-angle-fill text-warning me-2"></i>
                            <h6 class="fw-semibold mb-0">Pinned Posts</h6>
                        </div>
                        @foreach($group->pinnedPosts as $post)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                @include('groups.partials.post-card', ['post' => $post])
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Recent Posts -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Recent Posts</h6>
                            <a href="#" class="btn btn-sm btn-link">View All</a>
                        </div>

                        @forelse($group->posts as $post)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                @include('groups.partials.post-card', ['post' => $post])
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 bg-light rounded">
                            <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                            <p class="text-muted mt-3 mb-0">No posts yet. Be the first to post!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- About Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                About
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            <p class="small">{{ $group->description }}</p>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Created</span>
                                    <span class="small">{{ $group->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Privacy</span>
                                    <span class="small">{{ $group->privacy_label }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Member Approval</span>
                                    <span class="small">{{ ucwords(str_replace('_', ' ', $group->member_approval))
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admins Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-shield-check me-2"></i>
                                Admins & Moderators
                            </h6>
                        </div>
                        <div class="card-body pt-0">
                            @foreach($group->admins as $admin)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $admin->profile->avatar_url }}" class="rounded-circle me-2"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <a href="{{ route('profile.show', $admin->profile->username) }}"
                                        class="text-decoration-none text-dark fw-semibold small">
                                        {{ $admin->profile->username }}
                                    </a>
                                    <span class="badge bg-light text-dark ms-2 small">
                                        {{ $admin->pivot->role }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    @if($group->upcomingEvents->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-calendar-event me-2"></i>
                                Upcoming Events
                            </h6>
                            <a href="{{ route('groups.events', $group->slug) }}" class="btn btn-sm btn-link">View
                                All</a>
                        </div>
                        <div class="card-body pt-0">
                            @foreach($group->upcomingEvents as $event)
                            <div class="d-flex align-items-start mb-3 pb-2 border-bottom">
                                <div class="bg-light rounded p-2 text-center me-3" style="min-width: 50px;">
                                    <div class="small fw-bold">{{ $event->starts_at->format('M') }}</div>
                                    <div class="fs-5 fw-bold">{{ $event->starts_at->format('d') }}</div>
                                </div>
                                <div>
                                    <h6 class="small fw-semibold mb-1">{{ $event->title }}</h6>
                                    <p class="small text-muted mb-0">
                                        <i class="bi bi-people"></i> {{ $event->attendees_count ?? 0 }} attending
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Recent Resources -->
                    @if($group->resources->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-folder me-2"></i>
                                Recent Resources
                            </h6>
                            <a href="{{ route('groups.resources', $group->slug) }}" class="btn btn-sm btn-link">View
                                All</a>
                        </div>
                        <div class="card-body pt-0">
                            @foreach($group->resources as $resource)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                <a href="#" class="text-decoration-none small flex-grow-1">{{ $resource->title }}</a>
                                <span class="badge bg-light text-dark">{{ $resource->type }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resources Tab -->
        <div class="tab-pane fade" id="resources" role="tabpanel">
            @include('groups.tabs.resources', ['group' => $group])
        </div>

        <!-- Events Tab -->
        <div class="tab-pane fade" id="events" role="tabpanel">
            @include('groups.tabs.events', ['group' => $group])
        </div>

        <!-- Members Tab -->
        <div class="tab-pane fade" id="members" role="tabpanel">
            @include('groups.tabs.members', ['group' => $group])
        </div>
    </div>
</div>
@endsection

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
        border: none;
        color: #0d6efd;
        background: transparent;
    }

    .nav-tabs .nav-link.active {
        border: none;
        color: #0d6efd;
        background: transparent;
        border-bottom: 2px solid #0d6efd;
    }
</style>