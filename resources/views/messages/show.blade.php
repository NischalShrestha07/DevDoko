@extends('layouts.app')

@section('title', 'Messages with @' . ($user->profile->username ?? $user->name) . ' - DevDoko')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left Sidebar - Conversations List -->
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-chat-dots me-2 text-primary"></i>
                        Messages
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ $conversations->count() ?? 0 }} chats
                        </span>
                        <a href="{{ route('developers.index') }}"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-plus-lg me-1"></i>
                            New
                        </a>
                    </div>
                </div>

                <!-- Quick Find Developer -->
                <div class="p-3 border-bottom">
                    <form action="{{ route('developers.index') }}" method="GET" class="d-flex gap-2">
                        <div class="position-relative flex-grow-1">
                            <i
                                class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="search" name="search" class="form-control rounded-pill bg-light border-0"
                                placeholder="Find a developer..." style="padding-left: 35px;">
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Go
                        </button>
                    </form>
                </div>

                <!-- Conversations List -->
                <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    @forelse($conversations ?? [] as $conversation)
                    @if($conversation['user'])
                    <a href="{{ route('messages.show', $conversation['user']) }}"
                        class="list-group-item list-group-item-action border-0 p-3 {{ $user->id === $conversation['user']->id ? 'active bg-light' : '' }}"
                        style="transition: all 0.2s;">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Avatar with online status -->
                            <div class="position-relative">
                                <img src="{{ $conversation['user']->avatar_url }}"
                                    alt="{{ $conversation['user']->name }}" class="rounded-circle border"
                                    style="width: 56px; height: 56px; object-fit: cover;" loading="lazy">
                                @if($conversation['is_online'] ?? false)
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                    style="width: 14px; height: 14px;"></span>
                                @endif
                            </div>

                            <!-- Conversation Info -->
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-semibold mb-0 text-truncate">
                                        {{ $conversation['user']->profile->username ?? $conversation['user']->name }}
                                    </h6>
                                    <small class="text-muted flex-shrink-0 ms-2">
                                        {{ $conversation['last_message']?->created_at->diffForHumans(null, null, true)
                                        }}
                                    </small>
                                </div>

                                <!-- Last Message Preview -->
                                @if($conversation['last_message'])
                                <div class="d-flex align-items-center gap-2">
                                    @if($conversation['last_message']->type === 'code')
                                    <span class="badge bg-dark text-white px-2 py-1" style="font-size: 11px;">
                                        <i class="bi bi-code-slash me-1"></i> Code
                                    </span>
                                    @elseif($conversation['last_message']->type === 'file')
                                    <span class="badge bg-info text-white px-2 py-1" style="font-size: 11px;">
                                        <i class="bi bi-file-earmark me-1"></i> File
                                    </span>
                                    @endif

                                    <p class="mb-0 text-muted text-truncate small" style="max-width: 180px;">
                                        @if($conversation['last_message']->sender_id === Auth::id())
                                        <span class="text-secondary">You:</span>
                                        @endif
                                        {{ Str::limit($conversation['last_message']->content, 30) }}
                                    </p>
                                </div>
                                @endif

                                <!-- Unread Badge -->
                                @if($conversation['unread_count'] > 0)
                                <div class="mt-1">
                                    <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 11px;">
                                        {{ $conversation['unread_count'] }} new
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif
                    @empty
                    <div class="text-center py-5 px-3">
                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-chat-dots text-muted" style="font-size: 32px;"></i>
                        </div>
                        <h6 class="fw-semibold">No conversations yet</h6>
                        <p class="text-muted small mb-3">Start messaging other developers</p>
                        <a href="{{ route('developers.index') }}" class="btn btn-primary btn-sm px-4 rounded-pill">
                            <i class="bi bi-people me-2"></i>Find Developers
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Current Conversation -->
        <div class="col-lg-8 col-xl-9">
            @include('messages.partials.conversation', ['conversationUser' => $user])
        </div>
    </div>
</div>
@endsection