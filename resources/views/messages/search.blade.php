@extends('layouts.app')

@section('title', 'Search Messages - DevDoko')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left Sidebar - Recent Conversations -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Recent Chats
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($conversations ?? [] as $conversation)
                    @if($conversation['user'])
                    <a href="{{ route('messages.show', $conversation['user']) }}"
                        class="list-group-item list-group-item-action border-0 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $conversation['user']->avatar_url }}" class="rounded-circle"
                                style="width: 48px; height: 48px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-semibold mb-0">{{ $conversation['user']->profile->username }}</h6>
                                    <small class="text-muted">{{
                                        $conversation['last_message']?->created_at->diffForHumans(null, null, true)
                                        }}</small>
                                </div>
                                <p class="text-muted small mb-0 text-truncate">
                                    {{ Str::limit($conversation['last_message']?->content, 30) }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endif
                    @empty
                    <div class="text-center py-4">
                        <p class="text-muted small mb-0">No recent conversations</p>
                    </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('messages.index') }}" class="text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Back to Messages
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content - Search Results -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-search me-2 text-primary"></i>
                            Search Messages
                        </h5>
                        <span class="badge bg-light text-dark px-3 py-2">
                            {{ $messages->total() }} results found
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search Form -->
                    <form action="{{ route('messages.search') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control form-control-lg bg-light border-0"
                                placeholder="Search messages, code snippets, files..." value="{{ request('query') }}"
                                autofocus>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Search in messages, code snippets, and file names
                        </small>
                    </form>

                    <!-- Search Results -->
                    @if(request()->has('query'))
                    @if($messages->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($messages as $message)
                        @php
                        $otherUser = $message->sender_id === Auth::id() ? $message->receiver : $message->sender;
                        @endphp
                        <a href="{{ route('messages.show', $otherUser) }}#message-{{ $message->id }}"
                            class="list-group-item list-group-item-action border-0 p-3 mb-2 rounded-3"
                            style="background-color: #f8f9fa;">
                            <div class="d-flex gap-3">
                                <img src="{{ $otherUser->avatar_url }}" class="rounded-circle flex-shrink-0"
                                    style="width: 48px; height: 48px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-semibold">{{ $otherUser->profile->username }}</span>
                                            <span
                                                class="badge bg-{{ $message->sender_id === Auth::id() ? 'secondary' : 'primary' }} ms-2">
                                                {{ $message->sender_id === Auth::id() ? 'You' : 'Them' }}
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            {{ $message->created_at->format('M j, Y • g:i A') }}
                                        </small>
                                    </div>

                                    @if($message->type === 'code')
                                    <div class="bg-dark rounded-3 p-2 mb-2">
                                        <span class="badge bg-primary mb-2">{{ $message->code_language }}</span>
                                        <pre class="text-white-50 small mb-0"
                                            style="max-height: 100px; overflow-y: auto;"><code>{{ Str::limit($message->code_snippet, 200) }}</code></pre>
                                    </div>
                                    @elseif($message->type === 'file')
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-file-earmark fs-4 text-primary"></i>
                                        <div>
                                            <span class="fw-semibold d-block">{{ $message->file_name }}</span>
                                            <small class="text-muted">{{ number_format($message->file_size / 1024, 1) }}
                                                KB</small>
                                        </div>
                                    </div>
                                    @endif

                                    <p class="mb-0 {{ $message->type === 'text' ? 'fs-6' : 'text-muted small' }}">
                                        @if($message->type === 'text')
                                        {!! preg_replace('/(' . preg_quote(request('query'), '/') . ')/i', '<span
                                            class="bg-warning bg-opacity-25 p-1 rounded">$1</span>',
                                        e($message->content)) !!}
                                        @else
                                        {{ Str::limit($message->content, 100) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $messages->appends(request()->query())->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                        <h5>No messages found</h5>
                        <p class="text-muted mb-0">Try different keywords or check your spelling</p>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                        <h5>Search your messages</h5>
                        <p class="text-muted">Enter keywords to search through your conversations</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection