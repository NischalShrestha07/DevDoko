{{-- resources/views/groups/activity.blade.php --}}
@extends('layouts.app')

@section('title', 'Activity - ' . $group->name)

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-activity me-2 text-primary"></i>
                Activity Log
            </h4>
            <p class="text-muted mb-0">
                <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to {{ $group->name }}
                </a>
            </p>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @if($activities->count() > 0)
            <div class="timeline">
                @foreach($activities as $activity)
                <div class="timeline-item">
                    <div class="timeline-marker">
                        @switch($activity->action)
                        @case('member_joined')
                        <div class="bg-success rounded-circle p-2">
                            <i class="bi bi-person-plus-fill text-white"></i>
                        </div>
                        @break
                        @case('member_approved')
                        <div class="bg-info rounded-circle p-2">
                            <i class="bi bi-check-circle-fill text-white"></i>
                        </div>
                        @break
                        @case('member_left')
                        @case('member_removed')
                        <div class="bg-secondary rounded-circle p-2">
                            <i class="bi bi-person-dash-fill text-white"></i>
                        </div>
                        @break
                        @case('member_role_updated')
                        <div class="bg-warning rounded-circle p-2">
                            <i class="bi bi-shield-check text-white"></i>
                        </div>
                        @break
                        @case('post_created')
                        <div class="bg-primary rounded-circle p-2">
                            <i class="bi bi-file-text-fill text-white"></i>
                        </div>
                        @break
                        @case('resource_added')
                        <div class="bg-indigo rounded-circle p-2">
                            <i class="bi bi-folder-plus text-white"></i>
                        </div>
                        @break
                        @case('event_created')
                        <div class="bg-danger rounded-circle p-2">
                            <i class="bi bi-calendar-plus-fill text-white"></i>
                        </div>
                        @break
                        @default
                        <div class="bg-secondary rounded-circle p-2">
                            <i class="bi bi-circle-fill text-white"></i>
                        </div>
                        @endswitch
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                @if($activity->user)
                                <a href="{{ route('profile.show', $activity->user->profile->username ?? $activity->user->name) }}"
                                    class="text-decoration-none fw-semibold">
                                    {{ $activity->user->profile->username ?? $activity->user->name }}
                                </a>
                                @else
                                <span class="fw-semibold">System</span>
                                @endif
                                <span class="text-muted ms-2 small">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <p class="mb-0">{{ $activity->description ?? $activity->action }}</p>
                        @if($activity->data)
                        <div class="mt-2 small text-muted bg-light p-2 rounded">
                            <pre class="mb-0"
                                style="font-size: 12px;">{{ json_encode($activity->data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-activity text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">No activity yet</h5>
                <p class="text-muted mb-0">Group activities will appear here</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 24px;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: -42px;
        top: 0;
    }

    .timeline-content {
        padding-left: 15px;
    }

    .bg-indigo {
        background-color: #6610f2;
    }

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
@endsection