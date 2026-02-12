{{-- resources/views/groups/events.blade.php --}}
@extends('layouts.app')

@section('title', 'Events - ' . $group->name)

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-calendar-event me-2 text-primary"></i>
                Events
            </h4>
            <p class="text-muted mb-0">
                <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to {{ $group->name }}
                </a>
            </p>
        </div>
        @if($group->is_member)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
            <i class="bi bi-plus-lg"></i> Create Event
        </button>
        @endif
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
    <div class="row g-4">
        @foreach($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body p-4">
                    <!-- Event Type Badge -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                            {{ match($event->type) {
                            'meetup' => '🤝 Meetup',
                            'hackathon' => '⚡ Hackathon',
                            'workshop' => '🔧 Workshop',
                            'webinar' => '🎥 Webinar',
                            'social' => '🎉 Social',
                            default => '📅 Event',
                            } }}
                        </span>
                        <span class="badge bg-light text-dark">
                            {{ match($event->format) {
                            'online' => '🌐 Online',
                            'in_person' => '📍 In Person',
                            'hybrid' => '🔄 Hybrid',
                            default => '📌 Event',
                            } }}
                        </span>
                    </div>

                    <!-- Date -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-3 p-3 text-center me-3" style="min-width: 70px;">
                            <div class="small text-muted">{{ $event->starts_at->format('M') }}</div>
                            <div class="fs-3 fw-bold">{{ $event->starts_at->format('d') }}</div>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1">{{ $event->title }}</h5>
                            <div class="small text-muted">
                                <i class="bi bi-clock"></i> {{ $event->starts_at->format('g:i A') }}
                                @if($event->ends_at)
                                - {{ $event->ends_at->format('g:i A') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-muted small mb-3">
                        {{ Str::limit($event->description, 100) }}
                    </p>

                    <!-- Location/Meeting Info -->
                    @if($event->format !== 'online' && $event->location)
                    <div class="d-flex align-items-center small text-muted mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        {{ $event->location }}
                    </div>
                    @endif

                    @if($event->format !== 'in_person' && $event->meeting_link)
                    <div class="d-flex align-items-center small mb-3">
                        <i class="bi bi-camera-video me-2 text-primary"></i>
                        <a href="{{ $event->meeting_link }}" target="_blank" class="text-decoration-none">
                            Join Meeting <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </div>
                    @endif

                    <!-- Attendees & Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <div>
                            <span class="small text-muted">
                                <i class="bi bi-people"></i>
                                {{ $event->attendees_count ?? 0 }}/{{ $event->max_attendees ?? '∞' }} attending
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            @if($group->is_member)
                            <form action="{{ route('groups.events.attend', [$group->slug, $event->id]) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm {{ $event->is_attending ? 'btn-success' : 'btn-outline-primary' }}">
                                    {{ $event->is_attending ? '✓ Attending' : 'Attend' }}
                                </button>
                            </form>
                            @endif
                            @if($group->canManage(auth()->user()) || auth()->id() === $event->user_id)
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="if(confirm('Delete this event?')) {
                                                document.getElementById('delete-event-{{ $event->id }}').submit();
                                            }">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="delete-event-{{ $event->id }}"
                                action="{{ route('groups.events.destroy', [$group->slug, $event->id]) }}" method="POST"
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Organizer -->
                    <div class="d-flex align-items-center mt-3">
                        <img src="{{ $event->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($event->user->name) }}"
                            class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                        <small class="text-muted">
                            Organized by
                            <a href="{{ route('profile.show', $event->user->profile->username ?? $event->user->name) }}"
                                class="text-decoration-none fw-semibold">
                                {{ $event->user->profile->username ?? $event->user->name }}
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
            <i class="bi bi-calendar-x text-primary" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-semibold mb-2">No events yet</h5>
        <p class="text-muted mb-4">Be the first to organize an event for this group!</p>
        @if($group->is_member)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
            <i class="bi bi-plus-lg"></i> Create Event
        </button>
        @endif
    </div>
    @endif
</div>

@if($group->is_member)
<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('groups.events.store', $group->slug) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Event Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Event Type</label>
                            <select name="type" class="form-select" required>
                                <option value="meetup">🤝 Meetup</option>
                                <option value="hackathon">⚡ Hackathon</option>
                                <option value="workshop">🔧 Workshop</option>
                                <option value="webinar">🎥 Webinar</option>
                                <option value="social">🎉 Social</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Format</label>
                            <select name="format" id="eventFormat" class="form-select" required>
                                <option value="online">🌐 Online</option>
                                <option value="in_person">📍 In Person</option>
                                <option value="hybrid">🔄 Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Max Attendees</label>
                            <input type="number" name="max_attendees" class="form-control" placeholder="Unlimited"
                                min="1" max="1000">
                        </div>
                    </div>

                    <div class="mb-3" id="locationField">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" name="location" class="form-control"
                            placeholder="Venue address or location name">
                    </div>

                    <div class="mb-3" id="meetingLinkField">
                        <label class="form-label fw-semibold">Meeting Link</label>
                        <input type="url" name="meeting_link" class="form-control"
                            placeholder="https://meet.google.com/...">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Start Date & Time</label>
                            <input type="datetime-local" name="starts_at" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">End Date & Time (optional)</label>
                            <input type="datetime-local" name="ends_at" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="5" required
                            placeholder="Describe your event, agenda, prerequisites, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calendar-check"></i> Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('eventFormat').addEventListener('change', function() {
    const format = this.value;
    const locationField = document.getElementById('locationField');
    const meetingLinkField = document.getElementById('meetingLinkField');

    locationField.style.display = (format === 'in_person' || format === 'hybrid') ? 'block' : 'none';
    meetingLinkField.style.display = (format === 'online' || format === 'hybrid') ? 'block' : 'none';
});

// Trigger change to show initial fields
document.addEventListener('DOMContentLoaded', function() {
    const event = new Event('change');
    const formatSelect = document.getElementById('eventFormat');
    if (formatSelect) {
        formatSelect.dispatchEvent(event);
    }
});
</script>
@endif

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection