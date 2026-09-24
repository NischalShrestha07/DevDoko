@extends('layouts.app')

@section('title', 'Reports - Admin - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-flag fs-2 me-3 text-danger"></i>
        <div>
            <h4 class="fw-bold mb-0">Reports</h4>
            <p class="app-text-muted mb-0">Review posts and groups reported by users</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @forelse($reports as $report)
            <div class="d-flex align-items-start gap-3 p-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                <span class="badge {{ $report->status === 'pending' ? 'bg-warning text-dark' : ($report->status === 'resolved' ? 'bg-success' : 'bg-secondary') }} mt-1">
                    {{ ucfirst($report->status) }}
                </span>
                <div class="flex-grow-1 min-width-0">
                    <div class="small app-text-muted mb-1">
                        {{ class_basename($report->reportable_type) }} reported by
                        <strong>{{ $report->reporter->name ?? 'Unknown user' }}</strong>
                        &middot; {{ $report->created_at->diffForHumans() }}
                    </div>
                    <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $report->reason)) }}</div>
                    @if($report->details)
                    <div class="small app-text-muted">{{ $report->details }}</div>
                    @endif
                    @if($report->reportable instanceof \App\Models\Post)
                    <a href="{{ route('posts.show', $report->reportable) }}" class="small">View post &rarr;</a>
                    @else
                    <span class="small app-text-muted">Reported content was deleted.</span>
                    @endif
                </div>
                @if($report->status === 'pending')
                <div class="d-flex gap-2 flex-shrink-0">
                    <form action="{{ route('admin.reports.resolve', $report) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">Resolve</button>
                    </form>
                    <form action="{{ route('admin.reports.dismiss', $report) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Dismiss</button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center app-text-muted py-5">No reports yet.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection
