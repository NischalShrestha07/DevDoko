{{-- resources/views/marketplace/saved.blade.php --}}
@extends('layouts.app')

@section('title', 'Saved Items - Marketplace')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-bookmark me-2 text-primary"></i>
                Saved Items
            </h1>
            <p class="text-muted mb-0">Items you've saved for later</p>
        </div>
    </div>

    @if($listings->count() > 0)
    <div class="row g-4">
        @foreach($listings as $listing)
        <div class="col-md-6 col-lg-4">
            @include('marketplace.partials.listing-card', ['listing' => $listing])
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $listings->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-bookmark display-1 text-muted"></i>
        <h5 class="mt-3 mb-2">No saved items</h5>
        <p class="text-muted">Click the bookmark icon on items you like to save them here</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
            <i class="bi bi-shop me-2"></i> Browse Marketplace
        </a>
    </div>
    @endif
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
@endsection
