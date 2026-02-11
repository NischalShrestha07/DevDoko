{{-- resources/views/messages/partials/message.blade.php --}}
@php
$isOwn = $message->sender_id === Auth::id();
$isCode = $message->type === 'code';
$isFile = $message->type === 'file';
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
                        style="max-width: 100%; overflow-x: auto;"><code>{{ $message->code_snippet }}</code></pre>
                </div>
                @elseif($isFile)
                <!-- File attachment -->
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-3 p-3">
                        <i class="bi bi-file-earmark-arrow-down fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 {{ $isOwn ? 'text-white' : 'text-dark' }}">{{ $message->file_name }}
                        </h6>
                        <small class="{{ $isOwn ? 'text-white-50' : 'text-muted' }}">
                            {{ number_format($message->file_size / 1024, 1) }} KB
                        </small>
                        <a href="{{ $message->file_url }}"
                            class="btn btn-sm {{ $isOwn ? 'btn-light' : 'btn-primary' }} d-block mt-2" download>
                            <i class="bi bi-download me-1"></i> Download
                        </a>
                    </div>
                </div>
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
                <i class="bi bi-check2-all text-primary" title="Read"></i>
                @elseif($message->delivered_at)
                <i class="bi bi-check2 text-muted" title="Delivered"></i>
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
                                onclick="replyTo('{{ $message->id }}', '{{ addslashes(Str::limit($message->content, 50)) }}')">
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

            <!-- Reactions -->
            @if($message->reactions && $message->reactions->count() > 0)
            <div class="d-flex flex-wrap gap-1 mt-2">
                @foreach($message->reactions->groupBy('reaction') as $reaction => $users)
                <span
                    class="badge bg-white text-dark border rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1"
                    style="font-size: 13px;">
                    {{ $reaction }}
                    <span class="text-muted ms-1">{{ $users->count() }}</span>
                </span>
                @endforeach
            </div>
            @endif
        </div>

        @if($isOwn)
        <a href="{{ route('profile.show', Auth::user()->profile->username) }}">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle border"
                style="width: 36px; height: 36px; object-fit: cover;">
        </a>
        @endif
    </div>
</div>