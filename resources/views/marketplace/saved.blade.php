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
@endsection
