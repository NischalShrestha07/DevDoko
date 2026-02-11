@php $conversationUser = $user ?? request()->route('user'); @endphp

<div class="card border-0 shadow-sm h-100 d-flex flex-column">
    <!-- Chat Header -->
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('profile.show', $conversationUser->profile->username) }}"
                class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                <div class="position-relative">
                    <img src="{{ $conversationUser->avatar_url }}" alt="{{ $conversationUser->name }}"
                        class="rounded-circle border" style="width: 48px; height: 48px; object-fit: cover;">
                    @if($conversationUser->isOnline())
                    <span
                        class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                        style="width: 12px; height: 12px;"></span>
                    @endif
                </div>
                <div>
                    <h6 class="fw-semibold mb-0">{{ $conversationUser->profile->username ?? $conversationUser->name }}
                    </h6>
                    <small class="text-muted">
                        @if($conversationUser->isOnline())
                        <span class="text-success">● Online</span>
                        @else
                        Last seen {{ $conversationUser->last_login_at?->diffForHumans() ?? 'recently' }}
                        @endif
                    </small>
                </div>
            </a>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('profile.show', $conversationUser->profile->username) }}"
                class="btn btn-outline-secondary btn-sm" title="View Profile">
                <i class="bi bi-person"></i>
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('profile.show', $conversationUser->profile->username) }}">
                            <i class="bi bi-person me-2"></i>View Full Profile
                        </a>
                    </li>
                    <li>
                        <span class="dropdown-item-text small text-muted">
                            <i class="bi bi-code-slash me-2"></i>
                            {{ $conversationUser->profile->skills ? count(explode(',',
                            $conversationUser->profile->skills)) : 0 }} skills
                        </span>
                    </li>
                    <li>
                        <span class="dropdown-item-text small text-muted">
                            <i class="bi bi-calendar me-2"></i>
                            Joined {{ $conversationUser->created_at->format('M Y') }}
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    @auth
                    @if(auth()->id() !== $conversationUser->id)
                    <li>
                        <form action="{{ route('users.follow', $conversationUser) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i
                                    class="bi bi-{{ auth()->user()->isFollowing($conversationUser) ? 'person-dash' : 'person-plus' }} me-2"></i>
                                {{ auth()->user()->isFollowing($conversationUser) ? 'Unfollow' : 'Follow' }}
                            </button>
                        </form>
                    </li>
                    @endif
                    @endauth
                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-flag me-2"></i>Report User</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="flex-grow-1 p-4" id="messagesContainer"
        style="overflow-y: auto; max-height: 500px; background-color: #f8f9fa;">
        @if(isset($groupedMessages) && $groupedMessages->count() > 0)
        @foreach($groupedMessages as $date => $messagesGroup)
        <div class="text-center mb-4">
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill small">
                {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
            </span>
        </div>

        @foreach($messagesGroup as $message)
        @include('messages.partials.message', ['message' => $message])
        @endforeach
        @endforeach
        @else
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="text-center" style="max-width: 400px;">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-chat-dots text-primary" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-semibold mb-2">
                    Start a conversation with {{ $conversationUser->profile->username ?? $conversationUser->name }}
                </h5>
                <p class="text-muted mb-4">
                    Introduce yourself, ask about their projects, or share some code!
                </p>

                <!-- Suggested Icebreakers -->
                <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill"
                        onclick="setIcebreaker('Hey! I saw your profile and would love to connect.')">
                        👋 Introduction
                    </button>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill"
                        onclick="setIcebreaker('I really liked your recent post!')">
                        💬 Comment on post
                    </button>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill"
                        onclick="setIcebreaker('Do you have any projects you\'re currently working on?')">
                        🚀 Ask about projects
                    </button>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill"
                        onclick="setIcebreaker('Would you be interested in collaborating on something?')">
                        🤝 Collaboration
                    </button>
                </div>

                <small class="text-muted d-block">
                    <i class="bi bi-shield-check me-1"></i>
                    Your messages are private and secure
                </small>
            </div>
        </div>
        @endif
    </div>

    <!-- Message Input Area with Developer Features -->
    <div class="card-footer bg-white border-0 p-3">
        <!-- Quick Actions Toolbar -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="insertCode()">
                <i class="bi bi-code-slash me-1"></i> Code
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="uploadFile()">
                <i class="bi bi-file-earmark me-1"></i> File
            </button>

            <!-- Templates Dropdown -->
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="dropdown">
                    <i class="bi bi-file-text me-1"></i> Templates
                </button>
                <div class="dropdown-menu p-2" style="min-width: 280px;">
                    <h6 class="dropdown-header text-muted fw-semibold">Developer Templates</h6>
                    <button class="dropdown-item py-2"
                        onclick="setIcebreaker('👋 Hi! I came across your profile and I\'m impressed with your work. Would you be open to connecting?')">
                        <i class="bi bi-hand-thumbs-up me-2 text-primary"></i>
                        Introduction
                    </button>
                    <button class="dropdown-item py-2"
                        onclick="setIcebreaker('💻 I noticed you work with development. I\'m also experienced in that and would love to exchange knowledge.')">
                        <i class="bi bi-code-square me-2 text-success"></i>
                        Tech Stack
                    </button>
                    <button class="dropdown-item py-2"
                        onclick="setIcebreaker('🤝 I have an idea for a project and I think your skills would be a great fit. Interested in collaborating?')">
                        <i class="bi bi-people me-2 text-warning"></i>
                        Collaboration
                    </button>
                    <button class="dropdown-item py-2"
                        onclick="setIcebreaker('❓ Could I ask you a few questions about development?')">
                        <i class="bi bi-question-circle me-2 text-info"></i>
                        Ask for Help
                    </button>
                    <hr class="dropdown-divider">
                    <button class="dropdown-item py-2"
                        onclick="setIcebreaker('Thank you for your help earlier! Really appreciate it.')">
                        <i class="bi bi-heart me-2 text-danger"></i>
                        Thank You
                    </button>
                </div>
            </div>

            <!-- Emoji Dropdown -->
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="dropdown">
                    <i class="bi bi-emoji-smile"></i>
                </button>
                <div class="dropdown-menu p-3" style="min-width: 300px;">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="emoji-reaction" onclick="addEmoji('👍')"
                            style="cursor: pointer; font-size: 24px;">👍</span>
                        <span class="emoji-reaction" onclick="addEmoji('❤️')"
                            style="cursor: pointer; font-size: 24px;">❤️</span>
                        <span class="emoji-reaction" onclick="addEmoji('🎉')"
                            style="cursor: pointer; font-size: 24px;">🎉</span>
                        <span class="emoji-reaction" onclick="addEmoji('🚀')"
                            style="cursor: pointer; font-size: 24px;">🚀</span>
                        <span class="emoji-reaction" onclick="addEmoji('👨‍💻')"
                            style="cursor: pointer; font-size: 24px;">👨‍💻</span>
                        <span class="emoji-reaction" onclick="addEmoji('🔥')"
                            style="cursor: pointer; font-size: 24px;">🔥</span>
                        <span class="emoji-reaction" onclick="addEmoji('⭐')"
                            style="cursor: pointer; font-size: 24px;">⭐</span>
                        <span class="emoji-reaction" onclick="addEmoji('🤔')"
                            style="cursor: pointer; font-size: 24px;">🤔</span>
                        <span class="emoji-reaction" onclick="addEmoji('💡')"
                            style="cursor: pointer; font-size: 24px;">💡</span>
                        <span class="emoji-reaction" onclick="addEmoji('✅')"
                            style="cursor: pointer; font-size: 24px;">✅</span>
                    </div>
                </div>
            </div>

            <div class="flex-grow-1"></div>
            <small class="text-muted">
                <i class="bi bi-keyboard me-1"></i>
                <kbd class="bg-light text-dark px-1 rounded">⌘↵</kbd> send
            </small>
        </div>

        <!-- Code Input (Hidden by default) -->
        <div id="codeInputContainer" style="display: none;" class="mb-3">
            <div class="card border-0 bg-dark text-white">
                <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center py-2">
                    <span><i class="bi bi-code-slash me-2"></i>Code Snippet</span>
                    <button type="button" class="btn-close btn-close-white btn-sm" onclick="cancelCode()"></button>
                </div>
                <div class="card-body p-2">
                    <select id="codeLanguage"
                        class="form-select form-select-sm bg-dark text-white border-secondary mb-2">
                        <option value="">Select language</option>
                        @foreach($codeLanguages ?? ['php', 'javascript', 'python', 'java', 'csharp', 'ruby', 'go',
                        'rust', 'typescript', 'html', 'css', 'sql', 'json', 'bash'] as $lang)
                        <option value="{{ $lang }}">{{ ucfirst($lang) }}</option>
                        @endforeach
                    </select>
                    <textarea id="codeSnippet" rows="5"
                        class="form-control form-control-sm bg-dark text-white border-secondary font-monospace"
                        placeholder="Paste your code here..."></textarea>
                </div>
                <div class="card-footer bg-dark border-0 py-2">
                    <button class="btn btn-sm btn-primary" onclick="sendCode()">Share Code</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="cancelCode()">Cancel</button>
                </div>
            </div>
        </div>

        <!-- File Upload Input (Hidden by default) -->
        <div id="fileInputContainer" style="display: none;" class="mb-3">
            <div class="card border-0 bg-light">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark fs-4"></i>
                            <span>Upload file</span>
                        </div>
                        <button type="button" class="btn-close btn-sm" onclick="cancelFile()"></button>
                    </div>
                    <div class="mt-2">
                        <input type="file" id="fileUpload" class="form-control form-control-sm">
                        <small class="text-muted">Max size: 10MB</small>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary" onclick="sendFile()">Upload</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="cancelFile()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Message Input -->
        <form action="{{ route('messages.store', $conversationUser) }}" method="POST" id="messageForm"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" id="messageType" value="text">
            <input type="hidden" name="reply_to_id" id="replyToId" value="">
            <input type="hidden" name="code_snippet" id="hiddenCodeSnippet" value="">
            <input type="hidden" name="code_language" id="hiddenCodeLanguage" value="">

            <div class="d-flex align-items-end gap-2">
                <div class="flex-grow-1 position-relative">
                    <textarea name="content" id="messageContent" rows="1"
                        class="form-control rounded-3 border-0 bg-light" placeholder="Write a message..."
                        style="resize: none; max-height: 150px; padding: 12px 16px;" autofocus></textarea>

                    <!-- Reply preview -->
                    <div id="replyPreview" style="display: none;" class="mt-2 p-2 bg-white rounded-3 border">
                        <div class="d-flex align-items-start gap-2">
                            <div class="flex-grow-1">
                                <small class="fw-semibold">Replying to message</small>
                                <p id="replyContent" class="small text-muted mb-0 text-truncate"></p>
                            </div>
                            <button type="button" class="btn-close btn-sm" onclick="cancelReply()"></button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary rounded-circle p-3"
                    style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;"
                    id="sendButton">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let replyToId = null;
let replyContent = '';

// Auto-resize textarea
document.getElementById('messageContent')?.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Insert code
function insertCode() {
    document.getElementById('codeInputContainer').style.display = 'block';
    document.getElementById('fileInputContainer').style.display = 'none';
    document.getElementById('messageType').value = 'code';
}

// Cancel code
function cancelCode() {
    document.getElementById('codeInputContainer').style.display = 'none';
    document.getElementById('codeSnippet').value = '';
    document.getElementById('codeLanguage').value = '';
    document.getElementById('messageType').value = 'text';
}

// Send code
function sendCode() {
    const code = document.getElementById('codeSnippet').value;
    const language = document.getElementById('codeLanguage').value;

    if (!code) {
        alert('Please enter some code');
        return;
    }

    document.getElementById('hiddenCodeSnippet').value = code;
    document.getElementById('hiddenCodeLanguage').value = language;

    // Submit the form
    document.getElementById('messageForm').submit();
}

// Upload file
function uploadFile() {
    document.getElementById('fileInputContainer').style.display = 'block';
    document.getElementById('codeInputContainer').style.display = 'none';
    document.getElementById('messageType').value = 'file';
}

// Cancel file
function cancelFile() {
    document.getElementById('fileInputContainer').style.display = 'none';
    document.getElementById('fileUpload').value = '';
    document.getElementById('messageType').value = 'text';
}

// Send file
function sendFile() {
    const file = document.getElementById('fileUpload').files[0];
    if (!file) {
        alert('Please select a file');
        return;
    }

    // Submit the form with file
    const form = document.getElementById('messageForm');
    form.enctype = 'multipart/form-data';
    form.submit();
}

// Add emoji to message
function addEmoji(emoji) {
    const input = document.getElementById('messageContent');
    input.value += emoji;
    input.dispatchEvent(new Event('input'));
}

// Reply to message
function replyTo(messageId, content) {
    replyToId = messageId;
    replyContent = content;

    document.getElementById('replyToId').value = messageId;
    document.getElementById('replyContent').textContent = content;
    document.getElementById('replyPreview').style.display = 'block';

    document.getElementById('messageContent').focus();
}

// Cancel reply
function cancelReply() {
    replyToId = null;
    document.getElementById('replyToId').value = '';
    document.getElementById('replyPreview').style.display = 'none';
}

// Set icebreaker message
function setIcebreaker(text) {
    const input = document.getElementById('messageContent');
    input.value = text;
    input.focus();
    input.dispatchEvent(new Event('input'));
}

// Star message
function toggleStar(messageId, button) {
    fetch(`/messages/${messageId}/star`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.toggle('text-warning');
            button.classList.toggle('bi-star');
            button.classList.toggle('bi-star-fill');
        }
    });
}

// Delete message
function deleteMessage(messageId) {
    if (confirm('Delete this message?')) {
        fetch(`/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`message-${messageId}`).remove();
            }
        });
    }
}

// Add reaction
function addReaction(messageId, reaction, button) {
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
            // Update UI with new reaction
            location.reload(); // Simple approach, can be improved
        }
    });
}

// Scroll to bottom on load
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Enter - Send message
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('messageForm')?.submit();
    }

    // Ctrl/Cmd + I - Insert code
    if ((e.ctrlKey || e.metaKey) && e.key === 'i') {
        e.preventDefault();
        insertCode();
    }

    // Ctrl/Cmd + U - Upload file
    if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
        e.preventDefault();
        uploadFile();
    }

    // Escape - Cancel reply/code/file
    if (e.key === 'Escape') {
        cancelReply();
        cancelCode();
        cancelFile();
    }
});
</script>