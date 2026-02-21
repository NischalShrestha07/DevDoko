{{-- resources/views/marketplace/interests/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Interest Details - Marketplace')

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ $interest->user_id === auth()->id() ? route('marketplace.interests.sent') : route('marketplace.interests.received') }}"
            class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Interests
        </a>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Conversation Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-chat-dots me-2"></i>
                        Conversation
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Buyer/Seller Info -->
                    <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            @if($interest->user_id === auth()->id())
                            <!-- I'm the buyer, showing seller -->
                            <img src="{{ $interest->listing->user->profile->avatar_url }}" class="rounded-circle me-3"
                                style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <div class="fw-semibold">Seller: {{ $interest->listing->user->profile->username }}</div>
                                <small class="text-muted">Member since {{
                                    $interest->listing->user->created_at->format('M Y') }}</small>
                            </div>
                            @else
                            <!-- I'm the seller, showing buyer -->
                            <img src="{{ $interest->user->profile->avatar_url }}" class="rounded-circle me-3"
                                style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <div class="fw-semibold">Buyer: {{ $interest->user->profile->username }}</div>
                                <small class="text-muted">Member since {{ $interest->user->created_at->format('M Y')
                                    }}</small>
                            </div>
                            @endif
                        </div>

                        <span class="badge bg-{{ $interest->status_color }} px-3 py-2">
                            {{ ucfirst($interest->status) }}
                        </span>
                    </div>

                    <!-- Interest Details -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Interest Details</h6>
                        <div class="border rounded p-3">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">Listed Price</small>
                                    <span class="fw-semibold">{{ $interest->listing->formatted_price }}</span>
                                </div>
                                @if($interest->offered_price)
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">Offered Price</small>
                                    <span class="fw-semibold text-info">{{ $interest->formatted_offered_price }}</span>
                                </div>
                                @endif
                                <div class="col-12 mb-2">
                                    <small class="text-muted d-block">Interested on</small>
                                    <span>{{ $interest->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                @if($interest->message)
                                <div class="col-12">
                                    <small class="text-muted d-block">Message</small>
                                    <p class="mb-0 p-3 bg-light rounded">{{ $interest->message }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($interest->listing->user_id === auth()->id() && $interest->status === 'pending')
                    <div class="d-flex gap-2">
                        <form action="{{ route('marketplace.interests.update', $interest) }}" method="POST"
                            class="flex-grow-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-2"></i> Accept Interest
                            </button>
                        </form>
                        <form action="{{ route('marketplace.interests.update', $interest) }}" method="POST"
                            class="flex-grow-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="decline">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-2"></i> Decline
                            </button>
                        </form>
                    </div>
                    @elseif($interest->listing->user_id === auth()->id() && $interest->status === 'accepted')
                    <form action="{{ route('marketplace.interests.update', $interest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="complete">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check2-all me-2"></i> Mark as Completed
                        </button>
                    </form>
                    @endif

                    <!-- Next Steps Based on Status -->
                    @if($interest->status === 'accepted')
                    <div class="alert alert-success mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Interest Accepted!</strong>
                        @if($interest->listing->user_id === auth()->id())
                        You've accepted this interest. The listing has been marked as reserved.
                        Please coordinate with the buyer to complete the transaction.
                        @else
                        The seller has accepted your interest! The item is now reserved for you.
                        Please contact the seller to arrange payment and delivery.
                        @endif
                    </div>
                    @elseif($interest->status === 'completed')
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Transaction Completed!</strong>
                        Thank you for using DevDoko Marketplace!
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Listing Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-bag me-2"></i>
                        Listing Details
                    </h5>
                </div>
                <div class="card-body p-0">
                    <a href="{{ route('marketplace.show', $interest->listing->slug) }}" class="text-decoration-none">
                        @if($interest->listing->images->count() > 0)
                        <img src="{{ Storage::url($interest->listing->images->first()->image_path) }}" class="w-100"
                            alt="{{ $interest->listing->title }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                        @endif
                    </a>
                    <div class="p-3">
                        <h6 class="fw-semibold mb-2">
                            <a href="{{ route('marketplace.show', $interest->listing->slug) }}"
                                class="text-dark text-decoration-none">
                                {{ $interest->listing->title }}
                            </a>
                        </h6>
                        <p class="small text-muted mb-2">{{ Str::limit($interest->listing->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 fw-bold text-primary">{{ $interest->listing->formatted_price }}</span>
                            <a href="{{ route('marketplace.show', $interest->listing->slug) }}"
                                class="btn btn-sm btn-outline-primary">
                                View Listing
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-person-circle me-2"></i>
                        Contact
                    </h5>
                </div>
                <div class="card-body">
                    @if($interest->user_id === auth()->id())
                    <!-- I'm the buyer - show seller contact -->
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $interest->listing->user->profile->avatar_url }}" class="rounded-circle me-3"
                            style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <div class="fw-semibold">{{ $interest->listing->user->profile->username }}</div>
                            <small class="text-muted">Seller</small>
                        </div>
                    </div>
                    <a href="{{ route('messages.show', $interest->listing->user) }}" class="btn btn-primary w-100">
                        <i class="bi bi-chat me-2"></i> Send Message
                    </a>
                    @else
                    <!-- I'm the seller - show buyer contact -->
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $interest->user->profile->avatar_url }}" class="rounded-circle me-3"
                            style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <div class="fw-semibold">{{ $interest->user->profile->username }}</div>
                            <small class="text-muted">Buyer</small>
                        </div>
                    </div>
                    <a href="{{ route('messages.show', $interest->user) }}" class="btn btn-primary w-100">
                        <i class="bi bi-chat me-2"></i> Send Message
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection