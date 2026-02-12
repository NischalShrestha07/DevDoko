{{-- resources/views/groups/resources.blade.php --}}
@extends('layouts.app')

@section('title', 'Resources - ' . $group->name)

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
                <i class="bi bi-folder me-2 text-primary"></i>
                Resources
            </h4>
            <p class="text-muted mb-0">
                <a href="{{ route('groups.show', $group->slug) }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to {{ $group->name }}
                </a>
            </p>
        </div>
        @if($group->is_member)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
            <i class="bi bi-plus-lg"></i> Add Resource
        </button>
        @endif
    </div>

    <!-- Resource Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('groups.resources', $group->slug) }}"
                    class="btn btn-sm {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All
                </a>
                @foreach($types as $value => $label)
                <a href="{{ route('groups.resources', [$group->slug, 'type' => $value]) }}"
                    class="btn btn-sm {{ request('type') == $value ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Resources Grid -->
    @if($resources->count() > 0)
    <div class="row g-4">
        @foreach($resources as $resource)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body p-4">
                    <!-- Resource Type Icon -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <span class="fs-2 me-3">
                                {{ match($resource->type) {
                                'link' => '🔗',
                                'file' => '📁',
                                'code' => '💻',
                                'tutorial' => '📖',
                                'tool' => '🛠️',
                                'book' => '📚',
                                default => '📄',
                                } }}
                            </span>
                            <div>
                                <h5 class="fw-semibold mb-1">
                                    @if($resource->type === 'link' && $resource->url)
                                    <a href="{{ $resource->url }}" target="_blank"
                                        class="text-decoration-none text-dark">
                                        {{ $resource->title }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                    @elseif($resource->type === 'file' && $resource->file_path)
                                    <a href="{{ route('groups.resources.download', [$group->slug, $resource->id]) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $resource->title }}
                                        <i class="bi bi-download ms-1 small"></i>
                                    </a>
                                    @else
                                    {{ $resource->title }}
                                    @endif
                                </h5>
                                <span class="badge bg-light text-dark">{{ $types[$resource->type] ?? $resource->type
                                    }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($resource->description)
                    <p class="text-muted small mb-3">
                        {{ Str::limit($resource->description, 100) }}
                    </p>
                    @endif

                    <!-- Tags -->
                    @if($resource->tags)
                    <div class="mb-3">
                        @foreach($resource->tags as $tag)
                        <span class="badge bg-secondary bg-opacity-10 text-dark me-1">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Metadata -->
                    <div class="d-flex gap-3 small text-muted mb-3">
                        @if($resource->type === 'file' && $resource->metadata)
                        <span><i class="bi bi-file-earmark"></i> {{ round($resource->metadata['size'] / 1024) }}
                            KB</span>
                        @endif
                        @if($resource->type === 'code' && $resource->metadata)
                        <span><i class="bi bi-code-slash"></i> {{ $resource->metadata['language'] ?? 'Code' }}</span>
                        @endif
                    </div>

                    <!-- Stats & Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
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
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted">
                                by
                                <a href="{{ route('profile.show', $resource->user->profile->username ?? $resource->user->name) }}"
                                    class="text-decoration-none">
                                    {{ $resource->user->profile->username ?? $resource->user->name }}
                                </a>
                            </small>
                            @if($group->canManage(auth()->user()) || auth()->id() === $resource->user_id)
                            <button type="button" class="btn btn-link text-danger p-0" onclick="if(confirm('Delete this resource?')) {
                                                document.getElementById('delete-resource-{{ $resource->id }}').submit();
                                            }">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="delete-resource-{{ $resource->id }}"
                                action="{{ route('groups.resources.destroy', [$group->slug, $resource->id]) }}"
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
        @endforeach
    </div>

    <div class="mt-4">
        {{ $resources->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
            <i class="bi bi-folder2-open text-primary" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-semibold mb-2">No resources yet</h5>
        <p class="text-muted mb-4">Share helpful links, files, code snippets, and more!</p>
        @if($group->is_member)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
            <i class="bi bi-plus-lg"></i> Add Resource
        </button>
        @endif
    </div>
    @endif
</div>

@if($group->is_member)
<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
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
                        <label class="form-label fw-semibold">Resource Type</label>
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
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Link Field -->
                    <div class="mb-3 resource-field" id="link-field">
                        <label class="form-label fw-semibold">URL</label>
                        <input type="url" name="url" class="form-control" placeholder="https://...">
                    </div>

                    <!-- File Field -->
                    <div class="mb-3 resource-field" id="file-field" style="display: none;">
                        <label class="form-label fw-semibold">File</label>
                        <input type="file" name="file" class="form-control">
                        <div class="form-text">Max file size: 25MB</div>
                    </div>

                    <!-- Code Field -->
                    <div class="mb-3 resource-field" id="code-field" style="display: none;">
                        <label class="form-label fw-semibold">Code Language</label>
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
                            <option value="bash">Bash</option>
                            <option value="json">JSON</option>
                        </select>
                        <label class="form-label fw-semibold">Code</label>
                        <textarea name="code" class="form-control font-monospace" rows="8"
                            placeholder="Paste your code here..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags</label>
                        <input type="text" name="tags" class="form-control"
                            placeholder="laravel, php, api (comma separated)">
                        <div class="form-text">Add tags to help others find this resource</div>
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
    const typeSelect = document.getElementById('resourceType');
    if (typeSelect) {
        typeSelect.dispatchEvent(new Event('change'));
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

    .font-monospace {
        font-family: 'Courier New', Courier, monospace;
        font-size: 14px;
    }
</style>
@endsection