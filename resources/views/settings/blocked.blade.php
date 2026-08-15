@extends('layouts.app')

@section('title', 'Blocked Accounts - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <h4 class="fw-bold mb-1"><i class="bi bi-slash-circle me-2 text-danger"></i>Blocked Accounts</h4>
            <p class="app-text-muted mb-4">Blocked accounts can't see your posts, message you, or follow you — and you won't see theirs.</p>

            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    @forelse($blockedUsers as $blocked)
                        <div class="list-group-item d-flex align-items-center gap-3 py-3">
                            <img src="{{ $blocked->avatar_url }}" alt="" class="rounded-circle"
                                style="width: 44px; height: 44px; object-fit: cover;">
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold text-truncate">{{ $blocked->profile->username ?? $blocked->name }}</div>
                                <small class="app-text-muted text-truncate d-block">{{ $blocked->name }}</small>
                            </div>
                            <form action="{{ route('users.unblock', $blocked) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Unblock</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-slash-circle app-text-muted" style="font-size: 2.5rem;"></i>
                            <p class="app-text-muted mt-3 mb-0">You haven't blocked anyone.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $blockedUsers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
