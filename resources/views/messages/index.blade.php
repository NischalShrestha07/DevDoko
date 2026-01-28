{{-- resources/views/messages/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Messages - DevDoko')

@section('content')
<div class="row">
    <!-- Conversations List -->
    <div class="col-md-4">
        <div class="card border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Messages</h5>
            </div>
            <div class="card-body p-0">
                @foreach($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation['user']) }}"
                    class="d-flex align-items-center p-3 border-bottom text-decoration-none text-dark">
                    <div class="position-relative">
                        @if($conversation['user']->profile->avatar)
                        <img src="{{ asset('storage/' . $conversation['user']->profile->avatar) }}"
                            class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px;">
                            <i class="bi bi-person-fill text-white fs-4"></i>
                        </div>
                        @endif
                        @if($conversation['unread_count'] > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $conversation['unread_count'] }}
                        </span>
                        @endif
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">{{ $conversation['user']->profile->username }}</h6>
                            <small class="text-muted">
                                {{ $conversation['last_message']->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <p class="mb-0 text-muted text-truncate" style="max-width: 200px;">
                            {{ $conversation['last_message']->content }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- No conversation selected -->
    <div class="col-md-8 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <i class="bi bi-chat-left-text fs-1 text-muted mb-3"></i>
            <h5>Your Messages</h5>
            <p class="text-muted">Select a conversation to start messaging</p>
        </div>
    </div>
</div>
@endsection