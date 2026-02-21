{{-- resources/views/marketplace/partials/listing-card.blade.php --}}
<div class="card h-100 border-0 shadow-sm hover-lift">
    <a href="{{ route('marketplace.show', $listing->slug) }}" class="text-decoration-none">
        <div class="position-relative">
            @if($listing->images->count() > 0)
            <img src="{{ Storage::url($listing->images->first()->image_path) }}" class="card-img-top"
                alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
            @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                <i class="bi bi-image text-muted fs-1"></i>
            </div>
            @endif

            @if($listing->price_type === 'free')
            <span class="position-absolute top-0 start-0 m-2 badge bg-success">FREE</span>
            @elseif($listing->price_type === 'negotiable')
            <span class="position-absolute top-0 start-0 m-2 badge bg-info">Negotiable</span>
            @endif
        </div>

        <div class="card-body">
            <h6 class="fw-semibold text-dark mb-2">{{ Str::limit($listing->title, 50) }}</h6>

            <p class="small text-muted mb-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ $listing->user->profile->username }}
            </p>

            <div class="d-flex justify-content-between align-items-center">
                <span class="h5 fw-bold text-primary mb-0">{{ $listing->formatted_price }}</span>
                <small class="text-muted">
                    <i class="bi bi-clock"></i> {{ $listing->time_ago }}
                </small>
            </div>

            @if($listing->category)
            <div class="mt-2">
                <span class="badge bg-light text-dark">{{ $listing->category }}</span>
            </div>
            @endif
        </div>
    </a>

    @auth
    @if($listing->user_id !== auth()->id())
    <div class="card-footer bg-white border-0 pb-3 pt-0">
        <button
            class="btn btn-sm w-100 save-listing-btn {{ $listing->is_saved ? 'btn-primary' : 'btn-outline-primary' }}"
            data-listing-id="{{ $listing->id }}" data-listing-slug="{{ $listing->slug }}"
            data-saved="{{ $listing->is_saved ? 'true' : 'false' }}">
            <i class="bi bi-bookmark{{ $listing->is_saved ? '-fill' : '' }} me-1"></i>
            <span class="save-text">{{ $listing->is_saved ? 'Saved' : 'Save' }}</span>
        </button>
    </div>
    @endif
    @endauth
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