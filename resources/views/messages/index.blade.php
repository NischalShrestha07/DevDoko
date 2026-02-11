{{-- resources/views/messages/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Messages - DevDoko')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left Sidebar - Conversations -->
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-chat-dots me-2 text-primary"></i>
                        Messages
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ $conversations->count() }} chats
                        </span>
                        <a href="{{ route('developers.index') }}"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-plus-lg me-1"></i>
                            New Chat
                        </a>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="px-3 pb-2 border-bottom">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('messages.index', ['filter' => 'all']) }}"
                            class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            All
                        </a>
                        <a href="{{ route('messages.index', ['filter' => 'unread']) }}"
                            class="btn btn-sm {{ $filter === 'unread' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Unread
                            @php $totalUnread = $conversations->sum('unread_count'); @endphp
                            @if($totalUnread > 0)
                            <span class="badge bg-danger ms-1">{{ $totalUnread }}</span>
                            @endif
                        </a>
                        <a href="{{ route('messages.index', ['filter' => 'code']) }}"
                            class="btn btn-sm {{ $filter === 'code' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            <i class="bi bi-code-slash me-1"></i> Code
                        </a>
                    </div>
                </div>

                <!-- Conversations List -->
                <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    @forelse($conversations as $conversation)
                    @if($conversation['user'])
                    <a href="{{ route('messages.show', $conversation['user']) }}"
                        class="list-group-item list-group-item-action border-0 p-3 {{ request()->route('user')?->id === $conversation['user']->id ? 'active bg-light' : '' }}"
                        style="transition: all 0.2s;">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Avatar with online status -->
                            <div class="position-relative">
                                <img src="{{ $conversation['user']->avatar_url }}"
                                    alt="{{ $conversation['user']->name }}" class="rounded-circle border"
                                    style="width: 56px; height: 56px; object-fit: cover;" loading="lazy">
                                @if($conversation['is_online'])
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

                                <!-- Meta Info -->
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    @if($conversation['code_snippet_count'] > 0)
                                    <span class="badge bg-dark-subtle text-dark px-2 py-1 rounded-pill"
                                        style="font-size: 10px;">
                                        <i class="bi bi-code-square me-1"></i> {{ $conversation['code_snippet_count'] }}
                                    </span>
                                    @endif

                                    @if($conversation['unread_count'] > 0)
                                    <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 11px;">
                                        {{ $conversation['unread_count'] }} new
                                    </span>
                                    @endif
                                </div>
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

        <!-- Middle Column - Main Content -->
        <div class="col-lg-5 col-xl-6">
            @if(request()->route('user'))
            @include('messages.partials.conversation')
            @else
            <div class="card border-0 shadow-sm h-100 d-flex align-items-center justify-content-center"
                style="min-height: 500px;">
                <div class="text-center p-5">
                    <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                        <i class="bi bi-chat-left-text text-primary" style="font-size: 48px;"></i>
                    </div>
                    <h4 class="fw-semibold mb-3">Your Messages</h4>
                    <p class="text-muted mb-4" style="max-width: 400px;">
                        Select a conversation from the left to start messaging. Share code, files, and collaborate with
                        other developers.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <span class="badge bg-light text-dark p-3 rounded-3">
                            <i class="bi bi-code-slash me-2"></i>Code Sharing
                        </span>
                        <span class="badge bg-light text-dark p-3 rounded-3">
                            <i class="bi bi-file-earmark me-2"></i>File Upload
                        </span>
                        <span class="badge bg-light text-dark p-3 rounded-3">
                            <i class="bi bi-emoji-smile me-2"></i>Reactions
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Sidebar - Developer Tools -->
        <div class="col-lg-3">
            <!-- Starred Messages -->
            @if(isset($starredMessages) && $starredMessages->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-star-fill text-warning me-2"></i>
                        Starred
                    </h6>
                    <a href="{{ route('messages.starred') }}" class="small text-decoration-none">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($starredMessages->take(3) as $message)
                    <a href="{{ route('messages.show', $message->sender_id === Auth::id() ? $message->receiver : $message->sender) }}"
                        class="list-group-item list-group-item-action border-0 p-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ ($message->sender_id === Auth::id() ? $message->receiver : $message->sender)->avatar_url }}"
                                class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between">
                                    <small class="fw-semibold text-truncate">
                                        {{ ($message->sender_id === Auth::id() ? $message->receiver :
                                        $message->sender)->profile->username }}
                                    </small>
                                    <small class="text-muted">{{ $message->created_at->diffForHumans(null, null, true)
                                        }}</small>
                                </div>
                                <p class="text-truncate small mb-0 text-muted">{{ Str::limit($message->content, 30) }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Code Snippets -->
            @if(isset($codeSnippets) && $codeSnippets->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-code-square text-primary me-2"></i>
                        Recent Code Shares
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($codeSnippets->take(3) as $snippet)
                    <a href="{{ route('messages.show', $snippet->sender_id === Auth::id() ? $snippet->receiver : $snippet->sender) }}#message-{{ $snippet->id }}"
                        class="list-group-item list-group-item-action border-0 p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ ($snippet->sender_id === Auth::id() ? $snippet->receiver : $snippet->sender)->avatar_url }}"
                                class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                            <small class="fw-semibold">
                                {{ ($snippet->sender_id === Auth::id() ? $snippet->receiver :
                                $snippet->sender)->profile->username }}
                            </small>
                            <small class="text-muted">{{ $snippet->created_at->diffForHumans(null, null, true)
                                }}</small>
                        </div>
                        <div class="bg-dark rounded-3 p-2" style="max-height: 60px; overflow: hidden;">
                            <code class="text-white-50 small">
                                {{ Str::limit($snippet->code_snippet, 60) }}
                            </code>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-secondary">{{ $snippet->code_language }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('developers.index') }}"
                            class="btn btn-outline-primary d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-people me-2"></i>Find Developers</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('messages.search') }}"
                            class="btn btn-outline-secondary d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-search me-2"></i>Search Messages</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <button class="btn btn-outline-dark d-flex align-items-center justify-content-between"
                            onclick="showShortcuts()">
                            <span><i class="bi bi-keyboard me-2"></i>Keyboard Shortcuts</span>
                            <i class="bi bi-question-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Suggested Developers -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-person-plus-fill text-success me-2"></i>
                        Suggested Developers
                    </h6>
                    <a href="{{ route('developers.index') }}" class="small text-decoration-none">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    @php
                    $suggestedDevelopers = \App\Models\User::where('id', '!=', Auth::id())
                    ->whereDoesntHave('followers', function($q) {
                    $q->where('follower_id', Auth::id());
                    })
                    ->with('profile')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                    @endphp

                    @forelse($suggestedDevelopers as $dev)
                    <div class="list-group-item border-0 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $dev->avatar_url }}" class="rounded-circle"
                                style="width: 48px; height: 48px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-semibold mb-0">{{ $dev->profile->username ?? $dev->name }}</h6>
                                        <small class="text-muted">{{ $dev->profile->title ?? 'Developer' }}</small>
                                    </div>
                                    @if($dev->isOnline())
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Online</span>
                                    @endif
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="{{ route('messages.show', $dev) }}"
                                        class="btn btn-sm btn-outline-primary flex-grow-1">
                                        <i class="bi bi-chat me-1"></i> Message
                                    </a>
                                    <form action="{{ route('users.follow', $dev) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-muted small mb-0">No suggestions available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keyboard Shortcuts Modal -->
<div class="modal fade" id="shortcutsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-dark text-white">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-keyboard me-2"></i>
                    Keyboard Shortcuts
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><kbd class="bg-light text-dark px-2 py-1 rounded">⌘/Ctrl</kbd> + <kbd
                                class="bg-light text-dark px-2 py-1 rounded">Enter</kbd></span>
                        <span class="text-muted">Send message</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><kbd class="bg-light text-dark px-2 py-1 rounded">⌘/Ctrl</kbd> + <kbd
                                class="bg-light text-dark px-2 py-1 rounded">K</kbd></span>
                        <span class="text-muted">Search messages</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><kbd class="bg-light text-dark px-2 py-1 rounded">⌘/Ctrl</kbd> + <kbd
                                class="bg-light text-dark px-2 py-1 rounded">I</kbd></span>
                        <span class="text-muted">Insert code</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><kbd class="bg-light text-dark px-2 py-1 rounded">⌘/Ctrl</kbd> + <kbd
                                class="bg-light text-dark px-2 py-1 rounded">U</kbd></span>
                        <span class="text-muted">Upload file</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><kbd class="bg-light text-dark px-2 py-1 rounded">S</kbd></span>
                        <span class="text-muted">Star message</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><kbd class="bg-light text-dark px-2 py-1 rounded">R</kbd></span>
                        <span class="text-muted">Reply to message</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showShortcuts() {
    new bootstrap.Modal(document.getElementById('shortcutsModal')).show();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K - Search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        window.location.href = "{{ route('messages.search') }}";
    }
});

// Mark messages as read when conversation is viewed
@if(request()->route('user'))
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        fetch("{{ route('messages.read', request()->route('user')) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
    }
});
@endif
</script>
@endsection