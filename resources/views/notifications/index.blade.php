@extends('layouts.app')
{{-- resources/notifications/index --}}
@section('title', 'Notifications - DevDoko')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-bell-fill me-2 text-primary"></i>
                Notifications
            </h1>
            <p class="text-muted mb-0">Stay updated with your developer network</p>
        </div>

        <div class="d-flex gap-2">
            @if($notifications->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                    <i class="bi bi-check2-all me-2"></i>
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Notification Filters -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('notifications.index') }}"
                class="btn btn-sm {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-bell me-2"></i>
                All
            </a>
            <a href="{{ route('notifications.index', ['type' => 'message']) }}"
                class="btn btn-sm {{ request('type') === 'message' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-chat-dots me-2"></i>
                Messages
            </a>
            <a href="{{ route('notifications.index', ['type' => 'like']) }}"
                class="btn btn-sm {{ request('type') === 'like' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-heart-fill me-2"></i>
                Likes
            </a>
            <a href="{{ route('notifications.index', ['type' => 'comment']) }}"
                class="btn btn-sm {{ request('type') === 'comment' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-chat-text me-2"></i>
                Comments
            </a>
            <a href="{{ route('notifications.index', ['type' => 'follow']) }}"
                class="btn btn-sm {{ request('type') === 'follow' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-person-plus me-2"></i>
                Follows
            </a>
            <a href="{{ route('notifications.index', ['type' => 'mention']) }}"
                class="btn btn-sm {{ request('type') === 'mention' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                <i class="bi bi-at me-2"></i>
                Mentions
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                <div class="list-group-item border-0 p-4 {{ !$notification->read_at ? 'bg-light bg-opacity-50' : '' }}"
                    style="transition: all 0.2s;">
                    <div class="d-flex gap-3">
                        <!-- Avatar -->
                        <div class="position-relative">
                            @if($notification->fromUser)
                            <a
                                href="{{ route('profile.show', $notification->fromUser->profile->username ?? $notification->fromUser->name) }}">
                                <img src="{{ $notification->fromUser->avatar_url }}"
                                    alt="{{ $notification->fromUser->name }}" class="rounded-circle border"
                                    style="width: 56px; height: 56px; object-fit: cover;">
                            </a>
                            @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                style="width: 56px; height: 56px;">
                                <i class="bi bi-person text-muted fs-4"></i>
                            </div>
                            @endif

                            <!-- Notification Type Icon -->
                            <div class="position-absolute bottom-0 end-0 bg-{{ $notification->type === 'message' ? 'primary' : ($notification->type === 'like' ? 'danger' : ($notification->type === 'comment' ? 'success' : ($notification->type === 'follow' ? 'info' : 'warning'))) }}
                                                rounded-circle border border-2 border-white d-flex align-items-center justify-content-center"
                                style="width: 24px; height: 24px;">
                                <i class="bi bi-{{ $notification->type === 'message' ? 'chat' : ($notification->type === 'like' ? 'heart-fill' : ($notification->type === 'comment' ? 'chat-text' : ($notification->type === 'follow' ? 'person-plus-fill' : 'at'))) }} text-white"
                                    style="font-size: 12px;"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    @if($notification->fromUser)
                                    <a href="{{ route('profile.show', $notification->fromUser->profile->username ?? $notification->fromUser->name) }}"
                                        class="text-decoration-none text-dark fw-semibold">
                                        {{ $notification->fromUser->profile->username ?? $notification->fromUser->name
                                        }}
                                    </a>
                                    @endif
                                    <span class="text-muted ms-2 small">
                                        {{ $notification->message }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 ms-3">
                                    <small class="text-muted"
                                        title="{{ $notification->created_at->format('M j, Y g:i A') }}">
                                        {{ $notification->time_ago }}
                                    </small>

                                    @if(!$notification->read_at)
                                    <span class="badge bg-primary rounded-pill px-2 py-1"
                                        style="font-size: 10px;">New</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Additional Data -->
                            @if($notification->type === 'message' && isset($notification->data['content']))
                            <div class="mt-2 p-3 bg-light rounded-3">
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-chat-quote me-1"></i>
                                    "{{ $notification->data['content'] }}"
                                </p>
                                @if(isset($notification->data['type']) && $notification->data['type'] === 'code')
                                <span class="badge bg-dark mt-2 px-2 py-1">
                                    <i class="bi bi-code-slash me-1"></i> Code
                                </span>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] === 'file')
                                <span class="badge bg-info mt-2 px-2 py-1">
                                    <i class="bi bi-file-earmark me-1"></i> File
                                </span>
                                @endif
                            </div>
                            @endif

                            @if($notification->type === 'like' && isset($notification->data['post_title']))
                            <div class="mt-2">
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                    <i class="bi bi-heart-fill text-danger me-1"></i>
                                    Liked your post: "{{ Str::limit($notification->data['post_title'], 50) }}"
                                </span>
                            </div>
                            @endif

                            @if($notification->type === 'follow')
                            <div class="mt-2">
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                    <i class="bi bi-person-plus-fill text-success me-1"></i>
                                    Started following you
                                </span>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 mt-3">
                                @if($notification->type === 'message' && isset($notification->data['sender_id']))
                                <a href="{{ route('messages.show', $notification->fromUser ?? $notification->data['sender_id']) }}"
                                    class="btn btn-sm btn-primary rounded-pill px-4">
                                    <i class="bi bi-chat me-1"></i>
                                    View Message
                                </a>
                                @endif

                                @if($notification->type === 'like' && isset($notification->data['post_id']))
                                <a href="{{ route('posts.show', $notification->data['post_id']) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-file-text me-1"></i>
                                    View Post
                                </a>
                                @endif

                                @if($notification->type === 'comment' && isset($notification->data['post_id']))
                                <a href="{{ route('posts.show', $notification->data['post_id']) }}#comments"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-chat-text me-1"></i>
                                    View Comment
                                </a>
                                @endif

                                @if($notification->type === 'follow' && $notification->fromUser)
                                <a href="{{ route('profile.show', $notification->fromUser->profile->username ?? $notification->fromUser->name) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-person me-1"></i>
                                    View Profile
                                </a>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 ms-2"
                                        onclick="return confirm('Delete this notification?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-4">
                {{ $notifications->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-bell text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No notifications yet</h5>
                <p class="text-muted mb-4" style="max-width: 400px; margin: 0 auto;">
                    When you receive notifications about messages, likes, comments, and follows, they'll appear here.
                </p>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('developers.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-people me-2"></i>
                        Find Developers
                    </a>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-file-text me-2"></i>
                        Explore Posts
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<style>
    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection