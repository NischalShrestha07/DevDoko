{{-- resources/views/tags/trending.blade.php --}}
@extends('layouts.app')

@section('title', 'Trending Skills - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <!-- Header -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h1 class="h3 mb-2">
                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                    Trending Skills
                </h1>
                <p class="app-text-muted mb-0">Tech skills ranked by number of developers</p>
            </div>
        </div>

        <!-- Skills -->
        @forelse($techTags as $techTag)
        <a href="{{ route('developers.index', ['skill' => $techTag->name]) }}"
            class="text-decoration-none">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="fw-bold fs-5 app-text-muted me-3" style="width: 2.5rem;">
                            #{{ $loop->iteration + ($techTags->currentPage() - 1) * $techTags->perPage() }}
                        </span>
                        <span class="fw-semibold text-dark">
                            <i class="bi bi-hash"></i>{{ $techTag->name }}
                        </span>
                    </div>
                    <span class="badge app-bg-secondary app-text-primary rounded-pill px-3 py-2">
                        {{ number_format($techTag->profiles_count) }} developers
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-graph-up-arrow display-1 app-text-muted mb-3"></i>
                <h5 class="app-text-muted">No skills tracked yet</h5>
                <p class="app-text-muted mb-0">Once developers add tech tags to their profiles, trending skills will appear here</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($techTags->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $techTags->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
