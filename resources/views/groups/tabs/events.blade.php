{{-- resources/views/groups/tabs/events.blade.php --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                Events
            </h5>
            @if($group->is_member)
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#createEventModal">
                <i class="bi bi-plus-lg"></i> Create Event
            </button>
            @endif
        </div>

        @if($events->count() > 0)
        <div class="row g-4">
            @foreach($events as $event)
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="bg-light rounded p-3 text-center me-3" style="min-width: 70px;">
                                <div class="small text-muted">{{ $event->starts_at->format('M') }}</div>
                                <div class="fs-3 fw-bold">{{ $event->starts_at->format('d') }}</div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">{{ $event->title }}</h6>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-light text-dark">
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
                                <p class="small text-muted mb-2">{{ Str::limit($event->description, 100) }}</p>
                                <div class="d-flex align-items-center gap-2 small mb-2">
                                    <i class="bi bi-clock"></i>
                                    <span>
                                        {{ $event->starts_at->format('g:i A') }}
                                        @if($event->ends_at)
                                        - {{ $event->ends_at->format('g:i A') }}
                                        @endif
                                    </span>
                                </div>
                                @if($event->format !== 'online' && $event->location)
                                <div class="d-flex align-items-center gap-2 small mb-2">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                                @endif
                                @if($event->format !== 'in_person' && $event->meeting_link)
                                <div class="d-flex align-items-center gap-2 small mb-2">
                                    <i class="bi bi-camera-video"></i>
                                    <a href="{{ $event->meeting_link }}" target="_blank"
                                        class="text-decoration-none">Join Meeting</a>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                    <div>
                                        <span class="small text-muted">
                                            <i class="bi bi-people"></i> {{ $event->attendees_count ?? 0 }}/{{
                                            $event->max_attendees ?? '∞' }} attending
                                        </span>
                                    </div>
                                    <div>
                                        @if($group->is_member)
                                        <form action="{{ route('groups.events.attend', [$group->slug, $event->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $event->is_attending ? 'btn-success' : 'btn-outline-primary' }}">
                                                {{ $event->is_attending ? '✓ Attending' : 'Attend' }}
                                            </button>
                                        </form>
                                        @endif
                                        @if($group->canManage(auth()->user()) || auth()->id() === $event->user_id)
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="if(confirm('Delete this event?')) {
                                                            document.getElementById('delete-event-{{ $event->id }}').submit();
                                                        }">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-event-{{ $event->id }}"
                                            action="{{ route('groups.events.destroy', [$group->slug, $event->id]) }}"
                                            method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
            <i class="bi bi-calendar-x fs-1 text-muted"></i>
            <p class="text-muted mt-3 mb-0">No upcoming events.</p>
            @if($group->is_member)
            <p class="text-muted small">Organize your first event!</p>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#createEventModal">
                <i class="bi bi-plus-lg"></i> Create Event
            </button>
            @endif
        </div>
        @endif
    </div>
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
                            <label class="form-label">Event Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Type</label>
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
                            <label class="form-label">Format</label>
                            <select name="format" id="eventFormat" class="form-select" required>
                                <option value="online">🌐 Online</option>
                                <option value="in_person">📍 In Person</option>
                                <option value="hybrid">🔄 Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Max Attendees (optional)</label>
                            <input type="number" name="max_attendees" class="form-control" min="1" max="1000">
                        </div>
                    </div>

                    <!-- Location Field (shown for in_person/hybrid) -->
                    <div class="mb-3" id="locationField">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control"
                            placeholder="Venue address or location name">
                    </div>

                    <!-- Meeting Link Field (shown for online/hybrid) -->
                    <div class="mb-3" id="meetingLinkField">
                        <label class="form-label">Meeting Link</label>
                        <input type="url" name="meeting_link" class="form-control"
                            placeholder="https://meet.google.com/...">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date & Time</label>
                            <input type="datetime-local" name="starts_at" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date & Time (optional)</label>
                            <input type="datetime-local" name="ends_at" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
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
    document.getElementById('eventFormat').dispatchEvent(event);
});
</script>
@endif