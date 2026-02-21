{{-- resources/views/marketplace/interests/received.blade.php --}}
@extends('layouts.app')

@section('title', 'Received Interests - Marketplace')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-chat-heart me-2 text-primary"></i>
                Received Interests
            </h1>
            <p class="text-muted mb-0">Manage buyers interested in your listings</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('marketplace.interests.received') }}" class="btn btn-primary">
                <i class="bi bi-inbox"></i> Received
            </a>
            <a href="{{ route('marketplace.interests.sent') }}" class="btn btn-outline-primary">
                <i class="bi bi-send"></i> Sent
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-chat-dots text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Total Interests</h6>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Pending</h6>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Accepted</h6>
                            <h4 class="mb-0">{{ $stats['accepted'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interests List -->
    @if($interests->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($interests as $interest)
                <div class="list-group-item p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="d-flex">
                                <!-- Listing Image -->
                                <div class="me-3">
                                    @if($interest->listing->images->count() > 0)
                                    <img src="{{ Storage::url($interest->listing->images->first()->image_path) }}"
                                        class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Listing Details -->
                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        <a href="{{ route('marketplace.show', $interest->listing->slug) }}"
                                            class="text-dark text-decoration-none">
                                            {{ $interest->listing->title }}
                                        </a>
                                    </h6>
                                    <p class="small text-muted mb-1">
                                        <i class="bi bi-tag"></i> {{ $interest->listing->formatted_price }}
                                    </p>
                                    <p class="small text-muted mb-0">
                                        <i class="bi bi-clock"></i> {{ $interest->time_ago }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <!-- Buyer Info -->
                            <div class="d-flex align-items-center">
                                <img src="{{ $interest->user->profile->avatar_url }}" class="rounded-circle me-2"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                                <div>
                                    <a href="{{ route('profile.show', $interest->user->profile->username) }}"
                                        class="text-decoration-none fw-semibold small">
                                        {{ $interest->user->profile->username }}
                                    </a>
                                    @if($interest->offered_price)
                                    <span class="badge bg-info ms-2">Offer: {{ $interest->formatted_offered_price
                                        }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($interest->message)
                            <p class="small text-muted mt-2 mb-0">
                                <i class="bi bi-chat-quote"></i> "{{ Str::limit($interest->message, 50) }}"
                            </p>
                            @endif
                        </div>

                        <div class="col-lg-2">
                            <!-- Status -->
                            <span class="badge bg-{{ $interest->status_color }} px-3 py-2">
                                {{ ucfirst($interest->status) }}
                            </span>
                        </div>

                        <div class="col-lg-1 text-end">
                            <!-- Actions -->
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('marketplace.interests.show', $interest) }}">
                                            <i class="bi bi-chat-dots me-2"></i> View Conversation
                                        </a>
                                    </li>
                                    @if($interest->status === 'pending')
                                    <li>
                                        <form action="{{ route('marketplace.interests.update', $interest) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="bi bi-check-circle me-2"></i> Accept
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('marketplace.interests.update', $interest) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="decline">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-x-circle me-2"></i> Decline
                                            </button>
                                        </form>
                                    </li>
                                    @elseif($interest->status === 'accepted')
                                    <li>
                                        <form action="{{ route('marketplace.interests.update', $interest) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="complete">
                                            <button type="submit" class="dropdown-item text-info">
                                                <i class="bi bi-check2-all me-2"></i> Mark Completed
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $interests->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-chat-heart display-1 text-muted"></i>
        <h5 class="mt-3 mb-2">No interests yet</h5>
        <p class="text-muted">When buyers express interest in your listings, they'll appear here</p>
        <a href="{{ route('marketplace.my-listings') }}" class="btn btn-primary">
            <i class="bi bi-bag me-2"></i> View My Listings
        </a>
    </div>
    @endif
</div>
@endsection