{{-- resources/views/marketplace/my-interests.blade.php --}}
@extends('layouts.app')

@section('title', 'My Interests - Marketplace')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-heart me-2 text-primary"></i>
                My Interests
            </h1>
            <p class="text-muted mb-0">Items you've shown interest in</p>
        </div>
        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-shop me-2"></i> Browse More
        </a>
    </div>

    @if($interests->count() > 0)
    <div class="row g-4">
        @foreach($interests as $interest)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <a href="{{ route('marketplace.show', $interest->listing->slug) }}" class="text-decoration-none">
                    <div class="position-relative">
                        @if($interest->listing->images->count() > 0)
                        <img src="{{ Storage::url($interest->listing->images->first()->image_path) }}"
                            class="card-img-top" alt="{{ $interest->listing->title }}"
                            style="height: 160px; object-fit: cover;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                        @endif
                        <span
                            class="position-absolute top-0 end-0 m-2 badge bg-{{ $interest->status === 'pending' ? 'warning' : ($interest->status === 'accepted' ? 'success' : 'secondary') }}">
                            {{ ucfirst($interest->status) }}
                        </span>
                    </div>
                </a>

                <div class="card-body">
                    <h6 class="fw-semibold mb-1">
                        <a href="{{ route('marketplace.show', $interest->listing->slug) }}"
                            class="text-dark text-decoration-none">
                            {{ Str::limit($interest->listing->title, 40) }}
                        </a>
                    </h6>

                    <p class="small text-muted mb-2">
                        <i class="bi bi-person-circle me-1"></i>
                        <a href="{{ route('profile.show', $interest->listing->user->profile->username) }}"
                            class="text-decoration-none">
                            {{ $interest->listing->user->profile->username }}
                        </a>
                    </p>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h6 fw-bold text-primary mb-0">{{ $interest->listing->formatted_price }}</span>
                        @if($interest->offered_price)
                        <span class="badge bg-info">Offer: Rs {{ number_format($interest->offered_price, 2) }}</span>
                        @endif
                    </div>

                    @if($interest->message)
                    <div class="bg-light p-2 rounded small mb-2">
                        <i class="bi bi-chat-text me-1"></i>
                        {{ Str::limit($interest->message, 60) }}
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $interest->created_at->diffForHumans() }}
                        </small>

                        @if($interest->status === 'pending')
                        <button class="btn btn-sm btn-outline-danger" onclick="cancelInterest({{ $interest->id }})">
                            Cancel
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $interests->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-heart display-1 text-muted"></i>
        <h5 class="mt-3 mb-2">No interests yet</h5>
        <p class="text-muted">When you express interest in items, they'll appear here</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
            <i class="bi bi-shop me-2"></i> Browse Marketplace
        </a>
    </div>
    @endif
</div>

<script>
    function cancelInterest(id) {
    if (confirm('Cancel your interest in this item?')) {
        // Implement cancel interest functionality
        alert('Cancel functionality - to be implemented');
    }
}
</script>
@endsection