{{-- resources/views/marketplace/saved-searches.blade.php --}}
@extends('layouts.app')

@section('title', 'Saved Searches - Marketplace')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-search-heart me-2 text-primary"></i>
                Saved Searches
            </h1>
            <p class="app-text-muted mb-0">Filters you've saved for quick reuse</p>
        </div>
    </div>

    @if($searches->count() > 0)
    <div class="list-group shadow-sm">
        @foreach($searches as $search)
        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div>
                <div class="fw-semibold">{{ $search->name }}</div>
                <div class="app-text-muted small">
                    @foreach($search->filters as $key => $value)
                        @if($key !== 'search' && $value)
                            <span class="badge bg-light text-dark border me-1">{{ $key }}: {{ is_array($value) ? implode(', ', $value) : $value }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('marketplace.index', $search->filters) }}"
                    class="btn btn-sm btn-primary">Run search</a>
                <form action="{{ route('marketplace.saved-searches.destroy', $search) }}" method="POST"
                    onsubmit="return confirm('Remove this saved search?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $searches->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-search-heart display-1 app-text-muted"></i>
        <h5 class="mt-3 mb-2">No saved searches</h5>
        <p class="app-text-muted">Apply filters on the marketplace and save them to check back later</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
            <i class="bi bi-shop me-2"></i> Browse Marketplace
        </a>
    </div>
    @endif
</div>
@endsection
