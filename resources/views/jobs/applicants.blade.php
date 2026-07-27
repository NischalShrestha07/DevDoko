@extends('layouts.app')

@section('title', 'Applicants - ' . $job->title . ' | DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none text-dark d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Job</span>
        </a>
    </div>

    <h4 class="fw-bold mb-1">
        <i class="bi bi-people text-primary me-2"></i>
        Applicants for {{ $job->title }}
    </h4>
    <p class="text-muted mb-4">{{ $applications->total() }} total application(s)</p>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    @forelse($applications as $application)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $application->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($application->user->name) }}"
                            alt="{{ $application->user->name }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <a href="{{ route('profile.show', $application->user->profile->username ?? $application->user->name) }}"
                                class="text-decoration-none text-dark fw-semibold d-block">
                                {{ $application->user->name }}
                            </a>
                            <small class="text-muted">Applied {{ $application->created_at->diffForHumans() }}</small>
                        </div>
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

                <p class="mt-3 mb-3">{{ $application->cover_letter }}</p>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ $application->resume_url }}" target="_blank" rel="noopener noreferrer"
                        class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-file-earmark-text me-1"></i>View Resume
                    </a>

                    <form action="{{ route('job-applications.update-status', $application) }}" method="POST" class="d-flex align-items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                <i class="bi bi-people text-primary" style="font-size: 48px;"></i>
            </div>
            <h5 class="fw-semibold mb-2">No applicants yet</h5>
            <p class="text-muted mb-0">Applications will show up here once candidates apply.</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $applications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
