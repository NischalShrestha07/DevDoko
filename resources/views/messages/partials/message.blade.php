{{-- resources/views/messages/partials/message.blade.php --}}
@php
$isOwn = $message->sender_id === Auth::id();
$isCode = $message->type === 'code';
$isFile = $message->type === 'file';
$fileExtension = $isFile ? strtolower(pathinfo($message->file_name, PATHINFO_EXTENSION)) : null;

// Determine file type for display
$isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
$isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
$isAudio = in_array($fileExtension, ['mp3', 'wav', 'ogg', 'm4a']);
$isPDF = $fileExtension === 'pdf';
$isArchive = in_array($fileExtension, ['zip', 'rar', '7z', 'tar', 'gz']);
$isDocument = in_array($fileExtension, ['doc', 'docx', 'txt', 'md', 'rtf']);
@endphp

<div id="message-{{ $message->id }}" class="d-flex mb-4 {{ $isOwn ? 'justify-content-end' : 'justify-content-start' }}">
    <div class="d-flex {{ $isOwn ? 'flex-row-reverse' : 'flex-row' }} align-items-start gap-2" style="max-width: 75%;">
        @if(!$isOwn)
        <a href="{{ route('profile.show', $message->sender->profile->username) }}">
            <img src="{{ $message->sender->avatar_url }}" alt="{{ $message->sender->name }}"
                class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;">
        </a>
        @endif

        <div class="flex-grow-1">
            <!-- Reply indicator -->
            @if($message->replyTo)
            <div class="mb-1 p-2 bg-light rounded-3 small" style="border-left: 3px solid #0d6efd;">
                <span class="text-muted">Replying to</span>
                <span class="fw-semibold">{{ $message->replyTo->sender->profile->username }}</span>
                <p class="text-muted mb-0 text-truncate" style="max-width: 200px;">
                    {{ Str::limit($message->replyTo->content, 50) }}
                </p>
            </div>
            @endif

            <!-- Message bubble -->
            <div class="rounded-4 p-3 {{ $isOwn ? 'bg-primary text-white' : 'bg-light' }}"
                style="word-wrap: break-word;">

                @if($isCode)
                <!-- Code snippet -->
                <div class="mb-2">
                    <span class="badge {{ $isOwn ? 'bg-white text-primary' : 'bg-dark text-white' }} mb-2">
                        <i class="bi bi-code-slash me-1"></i> {{ $message->code_language ?? 'code' }}
                    </span>
                    <pre class="mb-0 p-2 bg-dark text-white rounded-3"
                        style="max-width: 100%; overflow-x: auto; max-height: 300px;"><code>{{ $message->code_snippet }}</code></pre>
                </div>

                @elseif($isFile)
                <!-- File attachment with preview -->
                <div class="mb-2">
                    @if($isImage)
                    <!-- Image preview -->
                    <div class="mb-2">
                        <a href="{{ $message->file_url }}" target="_blank">
                            <img src="{{ $message->file_url }}" alt="{{ $message->file_name }}"
                                class="img-fluid rounded-3"
                                style="max-height: 200px; max-width: 200px; cursor: pointer;"
                                onclick="openImageModal('{{ $message->file_url }}', '{{ $message->file_name }}')">
                        </a>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-image fs-5"></i>
                            <span class="small">{{ $message->file_name }}</span>
                        </div>
                        <a href="{{ $message->file_url }}" class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }}"
                            download>
                            <i class="bi bi-download"></i>
                        </a>
                    </div>

                    @elseif($isVideo)
                    <!-- Video preview -->
                    <div class="mb-2">
                        <video controls class="w-100 rounded-3" style="max-height: 300px;">
                            <source src="{{ $message->file_url }}" type="video/{{ $fileExtension }}">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-camera-reels fs-5"></i>
                            <span class="small">{{ $message->file_name }}</span>
                        </div>
                        <a href="{{ $message->file_url }}" class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }}"
                            download>
                            <i class="bi bi-download"></i>
                        </a>
                    </div>

                    @elseif($isAudio)
                    <!-- Audio player -->
                    <div class="mb-2">
                        <audio controls class="w-100">
                            <source src="{{ $message->file_url }}" type="audio/{{ $fileExtension }}">
                            Your browser does not support the audio tag.
                        </audio>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-music-note fs-5"></i>
                            <span class="small">{{ $message->file_name }}</span>
                        </div>
                        <a href="{{ $message->file_url }}" class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }}"
                            download>
                            <i class="bi bi-download"></i>
                        </a>
                    </div>

                    @elseif($isPDF)
                    <!-- PDF preview -->
                    <div class="mb-2">
                        <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 border">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-pdf fs-1 text-danger"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold text-dark mb-1">{{ $message->file_name }}</h6>
                                <div class="mt-2">
                                    <a href="{{ $message->file_url }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-eye"></i> Preview
                                    </a>
                                    <a href="{{ $message->file_url }}" class="btn btn-sm btn-primary" download>
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif($isArchive)
                    <!-- Archive file -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-zip fs-1 text-warning"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1 {{ $isOwn ? 'text-white' : 'text-dark' }}">{{
                                $message->file_name }}</h6>
                            <a href="{{ $message->file_url }}"
                                class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }} d-block mt-2" download>
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>

                    @elseif($isDocument)
                    <!-- Document file -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-text fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1 {{ $isOwn ? 'text-white' : 'text-dark' }}">{{
                                $message->file_name }}</h6>
                            <a href="{{ $message->file_url }}" target="_blank"
                                class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }} d-block mt-2">
                                <i class="bi bi-eye me-1"></i> View
                            </a>
                        </div>
                    </div>

                    @else
                    <!-- Generic file -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-secondary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-earmark fs-1 text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1 {{ $isOwn ? 'text-white' : 'text-dark' }}">{{
                                $message->file_name }}</h6>
                            <a href="{{ $message->file_url }}"
                                class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }} d-block mt-2" download>
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Optional caption for files -->
                @if($message->content && $message->content !== $message->file_name)
                <div class="mt-2 pt-2 border-top {{ $isOwn ? 'border-white border-opacity-25' : '' }}">
                    <p class="mb-0 small break-word">{{ $message->content }}</p>
                </div>
                @endif

                @else
                <!-- Text message -->
                <p class="mb-0">{{ $message->content }}</p>
                @endif
            </div>

            <!-- Message metadata -->
            <div
                class="d-flex align-items-center gap-2 mt-1 {{ $isOwn ? 'justify-content-end' : 'justify-content-start' }}">
                <small class="text-muted">{{ $message->created_at->format('g:i A') }}</small>

                @if($isOwn)
                @if($message->read_at)
                <i class="bi bi-check2-all text-primary" title="Read {{ $message->read_at->diffForHumans() }}"></i>
                @elseif($message->delivered_at)
                <i class="bi bi-check2 text-muted" title="Delivered {{ $message->delivered_at->diffForHumans() }}"></i>
                @else
                <i class="bi bi-check text-muted" title="Sent"></i>
                @endif
                @endif

                <!-- Message actions -->
                <div class="dropdown d-inline">
                    <button class="btn btn-link btn-sm text-muted p-0" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end py-2">
                        <li>
                            <button class="dropdown-item py-2"
                                onclick="replyTo('{{ $message->id }}', '{{ addslashes(Str::limit($message->content ?? $message->file_name, 50)) }}')">
                                <i class="bi bi-reply me-2"></i>Reply
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item py-2" onclick="toggleStar('{{ $message->id }}', this)">
                                @php
                                $isStarred = ($isOwn && $message->is_starred_by_sender) || (!$isOwn &&
                                $message->is_starred_by_receiver);
                                @endphp
                                <i
                                    class="bi bi-star{{ $isStarred ? '-fill' : '' }} me-2 {{ $isStarred ? 'text-warning' : '' }}"></i>
                                {{ $isStarred ? 'Unstar' : 'Star' }}
                            </button>
                        </li>
                        @if($isFile && ($isImage || $isVideo || $isPDF))
                        <li>
                            <a href="{{ $message->file_url }}" target="_blank" class="dropdown-item py-2">
                                <i class="bi bi-eye me-2"></i>Open in new tab
                            </a>
                        </li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <button class="dropdown-item py-2 text-danger"
                                onclick="deleteMessage('{{ $message->id }}')">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if($isOwn)
        <a href="{{ route('profile.show', Auth::user()->profile->username) }}">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle border"
                style="width: 36px; height: 36px; object-fit: cover;">
        </a>
        @endif
    </div>
</div>

<!-- Image Modal for full-size preview -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid rounded-3" alt="" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script>
    // Open image in modal
function openImageModal(imageUrl, imageName) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageUrl;
    modalImage.alt = imageName || 'Image';

    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

// Add quick reaction
function addQuickReaction(messageId, reaction) {
    fetch(`/messages/${messageId}/reactions`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reaction })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload to show updated reactions
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Toggle reaction (remove if exists, add if not)
function toggleReaction(messageId, reaction) {
    // First try to remove (simplified - you might want to check if user already reacted)
    fetch(`/messages/${messageId}/reactions`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reaction })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // If removal failed, add it
            return addQuickReaction(messageId, reaction);
        }
        location.reload();
    })
    .catch(() => {
        // If error, try to add
        addQuickReaction(messageId, reaction);
    });
}
</script>