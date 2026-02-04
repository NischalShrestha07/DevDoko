{{-- resources/posts/partials/code-snippet --}}
<div class="code-snippet-card mb-3">
    <div class="card-header bg-dark text-light d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-info">{{ $snippet->language }}</span>
            @if($snippet->post->user_id === auth()->id())
            <button class="btn btn-sm btn-outline-light ms-2" onclick="copyCode('{{ $snippet->id }}')">
                <i class="bi bi-clipboard"></i> Copy
            </button>
            @endif
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="bi bi-download"></i> Download</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-share"></i> Share Code</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-flag"></i> Report</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        <pre class="m-0"><code class="language-{{ $snippet->language }}">{{ $snippet->code }}</code></pre>
    </div>
    <div class="card-footer bg-light">
        <div class="row">
            <div class="col">
                <small class="text-muted">
                    <i class="bi bi-eye"></i> {{ $snippet->views ?? 0 }} views
                </small>
            </div>
            <div class="col text-end">
                <small class="text-muted">
                    <i class="bi bi-download"></i> {{ $snippet->downloads ?? 0 }} downloads
                </small>
            </div>
        </div>
    </div>
</div>