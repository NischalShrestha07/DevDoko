@extends('layouts.app')

@section('title', 'My Applications - DevDoko')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-1">
        <i class="bi bi-send text-primary me-2"></i>
        My Applications
    </h4>
    <p class="text-muted mb-4">{{ $applications->total() }} application(s) submitted</p>

    @forelse($applications as $application)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <a href="{{ route('jobs.show', $application->job) }}" class="text-decoration-none text-dark fw-semibold d-block h6 mb-1">
                            {{ $application->job->title }}
                        </a>
                        <small class="text-muted">{{ $application->job->company_name }} &middot; Applied {{ $application->created_at->diffForHumans() }}</small>
                    </div>

                    <span class="badge rounded-pill {{ match($application->status) {
                        'accepted' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'reviewed' => 'bg-info text-dark',
                        default => 'bg-secondary',
                    } }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                <i class="bi bi-send text-primary" style="font-size: 48px;"></i>
            </div>
            <h5 class="fw-semibold mb-2">No applications yet</h5>
            <p class="text-muted mb-4">Browse open positions and apply to get started.</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-briefcase me-2"></i>Browse Jobs
            </a>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $applications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
