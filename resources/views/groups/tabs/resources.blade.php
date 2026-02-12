{{-- resources/views/groups/tabs/resources.blade.php --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-folder me-2"></i>
                Resources ({{ $group->resources->count() }})
            </h5>
            @if($group->is_member)
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#addResourceModal">
                <i class="bi bi-plus-lg"></i> Add Resource
            </button>
            @endif
        </div>

        @if($resources->count() > 0)
        <div class="row g-3">
            @foreach($resources as $resource)
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-shrink-0 me-3">
                                @php
                                $icon = match($resource->type) {
                                'link' => '🔗',
                                'file' => '📁',
                                'code' => '💻',
                                'tutorial' => '📖',
                                'tool' => '🛠️',
                                'book' => '📚',
                                default => '📄',
                                };
                                @endphp
                                <span class="fs-4">{{ $icon }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">
                                    @if($resource->type === 'link' && $resource->url)
                                    <a href="{{ $resource->url }}" target="_blank" class="text-decoration-none">
                                        {{ $resource->title }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                    @elseif($resource->type === 'file' && $resource->file_path)
                                    <a href="{{ route('groups.resources.download', [$group->slug, $resource->id]) }}"
                                        class="text-decoration-none">
                                        {{ $resource->title }}
                                        <i class="bi bi-download ms-1 small"></i>
                                    </a>
                                    @else
                                    {{ $resource->title }}
                                    @endif
                                </h6>
                                <p class="small text-muted mb-2">{{ Str::limit($resource->description, 100) }}</p>
                                <div class="d-flex align-items-center gap-2 small">
                                    <span class="text-muted">
                                        <i class="bi bi-person"></i>
                                        <a href="{{ route('profile.show', $resource->user->profile->username ?? $resource->user->name) }}"
                                            class="text-decoration-none">
                                            {{ $resource->user->profile->username ?? $resource->user->name }}
                                        </a>
                                    </span>
                                    <span class="text-muted">•</span>
                                    <span class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $resource->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @if($resource->tags)
                                <div class="mt-2">
                                    @foreach($resource->tags as $tag)
                                    <span class="badge bg-light text-dark me-1">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <div class="d-flex gap-3">
                                <form action="{{ route('groups.resources.like', [$group->slug, $resource->id]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                                        <i class="bi bi-heart{{ $resource->is_liked ? '-fill text-danger' : '' }}"></i>
                                        <span class="ms-1">{{ $resource->likes_count }}</span>
                                    </button>
                                </form>
                                @if($resource->type === 'file' && $resource->file_path)
                                <span class="small text-muted">
                                    <i class="bi bi-download"></i> {{ $resource->downloads_count }}
                                </span>
                                @endif
                            </div>
                            @if($group->canManage(auth()->user()) || auth()->id() === $resource->user_id)
                            <form action="{{ route('groups.resources.destroy', [$group->slug, $resource->id]) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none small"
                                    onclick="return confirm('Delete this resource?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $resources->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-folder2-open fs-1 text-muted"></i>
            <p class="text-muted mt-3 mb-0">No resources yet.</p>
            @if($group->is_member)
            <p class="text-muted small">Be the first to share a resource!</p>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#addResourceModal">
                <i class="bi bi-plus-lg"></i> Add Resource
            </button>
            @endif
        </div>
        @endif
    </div>
</div>

@if($group->is_member)
<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('groups.resources.store', $group->slug) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Resource Type</label>
                        <select name="type" id="resourceType" class="form-select" required>
                            <option value="link">🔗 Link</option>
                            <option value="file">📁 File</option>
                            <option value="code">💻 Code Snippet</option>
                            <option value="tutorial">📖 Tutorial</option>
                            <option value="tool">🛠️ Tool</option>
                            <option value="book">📚 Book</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Link Field (shown for link type) -->
                    <div class="mb-3 resource-field" id="link-field">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control" placeholder="https://...">
                    </div>

                    <!-- File Field (shown for file type) -->
                    <div class="mb-3 resource-field" id="file-field" style="display: none;">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control">
                        <div class="form-text">Max file size: 25MB</div>
                    </div>

                    <!-- Code Field (shown for code type) -->
                    <div class="mb-3 resource-field" id="code-field" style="display: none;">
                        <label class="form-label">Code Language</label>
                        <select name="language" class="form-select mb-2">
                            <option value="">Select Language</option>
                            <option value="javascript">JavaScript</option>
                            <option value="python">Python</option>
                            <option value="php">PHP</option>
                            <option value="java">Java</option>
                            <option value="csharp">C#</option>
                            <option value="ruby">Ruby</option>
                            <option value="go">Go</option>
                            <option value="rust">Rust</option>
                            <option value="html">HTML</option>
                            <option value="css">CSS</option>
                            <option value="sql">SQL</option>
                        </select>
                        <label class="form-label">Code</label>
                        <textarea name="code" class="form-control font-monospace" rows="8"
                            placeholder="Paste your code here..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control"
                            placeholder="laravel, php, api (comma separated)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('resourceType').addEventListener('change', function() {
    // Hide all fields
    document.querySelectorAll('.resource-field').forEach(field => {
        field.style.display = 'none';
    });

    // Show selected field
    const type = this.value;
    if (type === 'link') {
        document.getElementById('link-field').style.display = 'block';
    } else if (type === 'file') {
        document.getElementById('file-field').style.display = 'block';
    } else if (type === 'code') {
        document.getElementById('code-field').style.display = 'block';
    }
});

// Trigger change to show initial field
document.addEventListener('DOMContentLoaded', function() {
    const event = new Event('change');
    document.getElementById('resourceType').dispatchEvent(event);
});
</script>
@endif