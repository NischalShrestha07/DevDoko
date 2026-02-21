{{-- resources/views/marketplace/interests/sent.blade.php --}}
@extends('layouts.app')

@section('title', 'My Interests - Marketplace')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-send me-2 text-primary"></i>
                Sent Interests
            </h1>
            <p class="text-muted mb-0">Items you've shown interest in</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('marketplace.interests.received') }}" class="btn btn-outline-primary">
                <i class="bi bi-inbox"></i> Received
            </a>
            <a href="{{ route('marketplace.interests.sent') }}" class="btn btn-primary">
                <i class="bi bi-send"></i> Sent
            </a>
        </div>
    </div>

    <!-- Interests List -->
    @if($interests->count() > 0)
    <div class="row g-4">
        @foreach($interests as $interest)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="position-relative">
                    <a href="{{ route('marketplace.show', $interest->listing->slug) }}">
                        @if($interest->listing->images->count() > 0)
                        <img src="{{ Storage::url($interest->listing->images->first()->image_path) }}"
                            class="card-img-top" alt="{{ $interest->listing->title }}"
                            style="height: 160px; object-fit: cover;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                        @endif
                    </a>
                    <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $interest->status_color }}">
                        {{ ucfirst($interest->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $interest->listing->user->profile->avatar_url }}" class="rounded-circle me-2"
                            style="width: 24px; height: 24px; object-fit: cover;">
                        <small class="text-muted">
                            <a href="{{ route('profile.show', $interest->listing->user->profile->username) }}"
                                class="text-decoration-none">
                                {{ $interest->listing->user->profile->username }}
                            </a>
                        </small>
                    </div>

                    <h6 class="fw-semibold mb-2">
                        <a href="{{ route('marketplace.show', $interest->listing->slug) }}"
                            class="text-dark text-decoration-none">
                            {{ Str::limit($interest->listing->title, 50) }}
                        </a>
                    </h6>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h6 fw-bold text-primary mb-0">{{ $interest->listing->formatted_price }}</span>
                        @if($interest->offered_price)
                        <span class="badge bg-info">Your offer: {{ $interest->formatted_offered_price }}</span>
                        @endif
                    </div>

                    @if($interest->message)
                    <div class="bg-light p-2 rounded small mb-2">
                        <i class="bi bi-chat-text me-1"></i>
                        "{{ Str::limit($interest->message, 60) }}"
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $interest->time_ago }}
                        </small>

                        <a href="{{ route('marketplace.interests.show', $interest) }}"
                            class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
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
        <i class="bi bi-send display-1 text-muted"></i>
        <h5 class="mt-3 mb-2">No interests sent yet</h5>
        <p class="text-muted">Browse the marketplace and express interest in items you like</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
            <i class="bi bi-shop me-2"></i> Browse Marketplace
        </a>
    </div>
    @endif
</div>
@endsection