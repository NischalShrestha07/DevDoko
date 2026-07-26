{{-- resources/views/marketplace/show.blade.php --}}
@extends('layouts.app')

@section('title', $listing->title . ' - Marketplace')

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('marketplace.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back to Marketplace
        </a>
    </div>

    <div class="row">
        <!-- Images Column -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <!-- Main Image -->
                    <div class="main-image mb-3 text-center">
                        @if($listing->primaryImage)
                        <img src="{{ Storage::url($listing->primaryImage->image_path) }}" class="img-fluid rounded"
                            alt="{{ $listing->title }}" style="max-height: 500px; width: 100%; object-fit: contain;">
                        @elseif($listing->images->count() > 0)
                        <img src="{{ Storage::url($listing->images->first()->image_path) }}" class="img-fluid rounded"
                            alt="{{ $listing->title }}" style="max-height: 500px; width: 100%; object-fit: contain;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 400px;">
                            <i class="bi bi-image text-muted" style="font-size: 64px;"></i>
                        </div>
                        @endif
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($listing->images->count() > 1)
                    <div class="row g-2">
                        @foreach($listing->images as $image)
                        <div class="col-3">
                            <img src="{{ Storage::url($image->image_path) }}" class="img-fluid rounded thumbnail-img"
                                alt="Thumbnail" style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"
                                onclick="this.closest('.row').previousElementSibling.querySelector('img').src = this.src">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Column -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <!-- Title & Price -->
                    <h2 class="h4 fw-bold mb-2">{{ $listing->title }}</h2>
                    <div class="d-flex align-items-center mb-3">
                        <span class="h3 fw-bold text-primary me-3">{{ $listing->formatted_price }}</span>
                        @if($listing->price_type === 'negotiable')
                        <span class="badge bg-info">Negotiable</span>
                        @endif
                    </div>

                    <!-- Seller Info -->
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        <img src="{{ $listing->user->profile->avatar_url }}" class="rounded-circle me-3"
                            style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <a href="{{ route('profile.show', $listing->user->profile->username) }}"
                                class="text-decoration-none fw-semibold d-block">
                                {{ $listing->user->profile->username }}
                            </a>
                            <small class="text-muted">Member since {{ $listing->user->created_at->format('M Y')
                                }}</small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @auth
                    @if($listing->user_id !== auth()->id())
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-primary btn-lg" id="expressInterestBtn"
                            onclick="showInterestModal({{ $listing->id }}, '{{ $listing->slug }}', '{!! addslashes($listing->title) !!}')">
                            <i class="bi bi-chat-dots me-2"></i> Express Interest
                        </button>
                        <button class="btn btn-outline-primary save-listing-btn w-100"
                            data-listing-id="{{ $listing->id }}" data-listing-slug="{{ $listing->slug }}"
                            data-saved="{{ $listing->is_saved ? 'true' : 'false' }}">
                            <i class="bi bi-bookmark{{ $listing->is_saved ? '-fill' : '' }} me-2"></i>
                            <span class="save-text">{{ $listing->is_saved ? 'Saved' : 'Save Listing' }}</span>
                        </button>
                    </div>
                    @else
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('marketplace.edit', $listing->slug) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i> Edit Listing
                        </a>
                        <button class="btn btn-outline-danger" onclick="deleteListing('{{ $listing->slug }}')">
                            <i class="bi bi-trash me-2"></i> Delete Listing
                        </button>
                    </div>
                    @endif
                    @endauth

                    <!-- Details Table -->
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Condition:</td>
                            <td class="fw-semibold">{{ $listing->condition_label ?? 'Not specified' }}</td>
                        </tr>
                        @if($listing->brand)
                        <tr>
                            <td class="text-muted">Brand:</td>
                            <td class="fw-semibold">{{ $listing->brand }}</td>
                        </tr>
                        @endif
                        @if($listing->model)
                        <tr>
                            <td class="text-muted">Model:</td>
                            <td class="fw-semibold">{{ $listing->model }}</td>
                        </tr>
                        @endif
                        @if($listing->location)
                        <tr>
                            <td class="text-muted">Location:</td>
                            <td class="fw-semibold">{{ $listing->location }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Shipping:</td>
                            <td class="fw-semibold">
                                @if($listing->is_shippable)
                                <span class="badge bg-success">Available</span>
                                @else
                                <span class="badge bg-secondary">Not Available</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Local Pickup:</td>
                            <td class="fw-semibold">
                                @if($listing->is_local_pickup)
                                <span class="badge bg-success">Available</span>
                                @else
                                <span class="badge bg-secondary">Not Available</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Listed:</td>
                            <td class="fw-semibold">{{ $listing->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Views:</td>
                            <td class="fw-semibold">{{ $listing->views_count }}</td>
                        </tr>
                    </table>

                    <!-- Description -->
                    <h6 class="fw-semibold mt-4 mb-2">Description</h6>
                    <p class="text-muted">{{ nl2br(e($listing->description)) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Listings -->
    @if($similarListings->count() > 0)
    <div class="mt-5">
        <h5 class="fw-semibold mb-4">Similar Listings</h5>
        <div class="row g-4">
            @foreach($similarListings as $similar)
            <div class="col-md-3">
                @include('marketplace.partials.listing-card', ['listing' => $similar])
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Interest Modal -->
<div class="modal fade" id="interestModal" tabindex="-1" data-listing-slug="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Express Interest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="interestForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Message (optional)</label>
                        <textarea class="form-control" name="message" rows="3"
                            placeholder="Introduce yourself and ask any questions..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Offer Price (optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rs</span>
                            <input type="number" class="form-control" name="offered_price" step="0.01" min="0">
                        </div>
                        <small class="text-muted">Leave blank to accept listed price</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitInterestBtn">Send Interest</button>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.save-listing-btn').forEach(function(btn) {
        if (btn.dataset.bound) return;
        btn.dataset.bound = '1';

        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            const slug = this.dataset.listingSlug;
            const icon = this.querySelector('i');
            const textSpan = this.querySelector('.save-text');

            try {
                const response = await fetch('/marketplace/' + slug + '/save', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    if (data.saved) {
                        this.classList.remove('btn-outline-primary');
                        this.classList.add('btn-primary');
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill');
                        if (textSpan) textSpan.textContent = 'Saved';
                        this.dataset.saved = 'true';
                    } else {
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-outline-primary');
                        icon.classList.remove('bi-bookmark-fill');
                        icon.classList.add('bi-bookmark');
                        if (textSpan) textSpan.textContent = 'Save';
                        this.dataset.saved = 'false';
                    }
                }
            } catch (error) {
                console.error('Error toggling save:', error);
            }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.showInterestModal = function(listingId, listingSlug, listingTitle) {
            const modal = document.getElementById('interestModal');
            modal.dataset.listingSlug = listingSlug;

            const modalTitle = document.querySelector('#interestModal .modal-title');
            if (modalTitle) {
                modalTitle.textContent = `Express Interest in "${listingTitle}"`;
            }

            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        };

        const submitInterestBtn = document.getElementById('submitInterestBtn');
        if (submitInterestBtn) {
            submitInterestBtn.addEventListener('click', async function() {
                const modal = document.getElementById('interestModal');
                const listingSlug = modal.dataset.listingSlug;

                if (!listingSlug) {
                    alert('Error: Listing not found');
                    return;
                }

                const message = document.querySelector('#interestModal textarea[name="message"]').value;
                const offeredPrice = document.querySelector('#interestModal input[name="offered_price"]').value;

                try {
                    const response = await fetch(`/marketplace/${listingSlug}/interest`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: message,
                            offered_price: offeredPrice ? parseFloat(offeredPrice) : null
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        bsModal.hide();

                        document.querySelector('#interestModal textarea[name="message"]').value = '';
                        document.querySelector('#interestModal input[name="offered_price"]').value = '';

                        alert('Interest expressed successfully! The seller will contact you soon.');
                    } else {
                        alert(data.error || data.message || 'Failed to express interest');
                    }
                } catch (error) {
                    console.error('Error expressing interest:', error);
                    alert('Failed to express interest. Please try again.');
                }
            });
        }

        window.deleteListing = function(listingSlug) {
            if (confirm('Are you sure you want to delete this listing? This action cannot be undone.')) {
                fetch(`/marketplace/${listingSlug}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = '{{ route("marketplace.index") }}';
                    } else {
                        alert('Failed to delete listing');
                    }
                });
            }
        };
    });
</script>
@endsection