{{-- resources/views/groups/partials/post-card.blade.php --}}
<div class="d-flex">
    <div class="flex-shrink-0 me-3">
        <a href="{{ route('profile.show', $post->user->profile->username ?? $post->user->name) }}">
            <img src="{{ $post->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
        </a>
    </div>
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <a href="{{ route('profile.show', $post->user->profile->username ?? $post->user->name) }}"
                    class="text-decoration-none text-dark fw-semibold">
                    {{ $post->user->profile->username ?? $post->user->name }}
                </a>
                <span class="text-muted mx-2">·</span>
                <span class="text-muted small">{{ $post->formatted_date }}</span>
                @if($post->is_pinned)
                <span class="badge bg-warning bg-opacity-10 text-warning ms-2">
                    <i class="bi bi-pin-angle-fill"></i> Pinned
                </span>
                @endif
                @if($post->type === 'announcement')
                <span class="badge bg-primary ms-2">📢 Announcement</span>
                @endif
            </div>
        </div>

        <h6 class="fw-semibold mb-2">
            <a href="{{ route('groups.post', [$post->group->slug, $post->id]) }}"
                class="text-dark text-decoration-none">
                {{ $post->title }}
            </a>
        </h6>

        <p class="text-muted mb-2">{{ Str::limit($post->content, 200) }}</p>

        {{-- Attachments Section --}}
        @if($post->attachments && count($post->attachments) > 0)
        <div class="mb-3">
            @php
            $imageAttachments = array_filter($post->attachments, function($att) {
            return strpos($att['type'], 'image/') !== false;
            });
            $videoAttachments = array_filter($post->attachments, function($att) {
            return strpos($att['type'], 'video/') !== false;
            });
            $codeAttachments = array_filter($post->attachments, function($att) {
            return strpos($att['type'], 'text/') !== false ||
            in_array(pathinfo($att['name'], PATHINFO_EXTENSION), ['js', 'php', 'py', 'java', 'html', 'css', 'json',
            'xml', 'sql', 'sh', 'bash']);
            });
            $otherAttachments = array_filter($post->attachments, function($att) use ($imageAttachments,
            $videoAttachments, $codeAttachments) {
            return !in_array($att, $imageAttachments) &&
            !in_array($att, $videoAttachments) &&
            !in_array($att, $codeAttachments);
            });
            @endphp

            {{-- Image Gallery --}}
            @if(count($imageAttachments) > 0)
            <div class="mb-2">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($imageAttachments as $index => $attachment)
                    <div class="position-relative" style="width: 100px; height: 100px;">
                        <img src="{{ Storage::url($attachment['path']) }}" alt="{{ $attachment['name'] }}"
                            class="rounded-3 w-100 h-100" style="object-fit: cover; cursor: pointer;"
                            onclick="openImageModal('{{ Storage::url($attachment['path']) }}', '{{ $attachment['name'] }}')">
                        @if(count($imageAttachments) > 4 && $index === 3)
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 rounded-3 d-flex align-items-center justify-content-center"
                            style="cursor: pointer;" onclick="openImageGallery({{ json_encode(array_map(function($att) {
                                 return Storage::url($att['path']);
                             }, $imageAttachments)) }})">
                            <span class="text-white fw-bold">+{{ count($imageAttachments) - 4 }}</span>
                        </div>
                        @php break; @endphp
                        @endif
                    </div>
                    @endforeach
                </div>
                <small class="text-muted d-block mt-1">
                    <i class="bi bi-images"></i> {{ count($imageAttachments) }} {{ Str::plural('image',
                    count($imageAttachments)) }}
                </small>
            </div>
            @endif

            {{-- Video Player --}}
            @if(count($videoAttachments) > 0)
            <div class="mb-2">
                @foreach($videoAttachments as $attachment)
                <div class="position-relative rounded-3 overflow-hidden bg-light" style="max-width: 400px;">
                    <video class="w-100" controls style="max-height: 225px;">
                        <source src="{{ Storage::url($attachment['path']) }}" type="{{ $attachment['type'] }}">
                        Your browser does not support the video tag.
                    </video>
                    <div class="p-2 bg-light border-top">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-camera-video-fill text-primary me-2"></i>
                            <small class="text-muted text-truncate flex-grow-1">{{ $attachment['name'] }}</small>
                            <a href="{{ Storage::url($attachment['path']) }}"
                                class="btn btn-sm btn-outline-primary ms-2" download="{{ $attachment['name'] }}">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Code Preview --}}
            @if(count($codeAttachments) > 0)
            <div class="mb-2">
                @foreach($codeAttachments as $attachment)
                @php
                $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                $language = match($extension) {
                'js' => 'javascript',
                'php' => 'php',
                'py' => 'python',
                'java' => 'java',
                'html' => 'html',
                'css' => 'css',
                'json' => 'json',
                'xml' => 'xml',
                'sql' => 'sql',
                'sh', 'bash' => 'bash',
                default => 'plaintext'
                };

                // Try to read file content if it's a text file
                $content = '';
                if (Storage::disk('public')->exists($attachment['path'])) {
                $content = Storage::disk('public')->get($attachment['path']);
                $content = Str::limit($content, 500);
                }
                @endphp
                <div class="bg-dark rounded-3 overflow-hidden" style="max-width: 100%;">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-black bg-opacity-25">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-code-fill text-info me-2"></i>
                            <span class="text-white-50 small">{{ $attachment['name'] }}</span>
                            <span class="badge bg-primary ms-2">{{ strtoupper($extension) }}</span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-light me-1"
                                onclick="copyCode({{ json_encode($content) }})" title="Copy code">
                                <i class="bi bi-clipboard"></i>
                            </button>
                            <a href="{{ Storage::url($attachment['path']) }}" class="btn btn-sm btn-outline-light"
                                download="{{ $attachment['name'] }}">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                    @if($content)
                    <pre class="mb-0 p-3"
                        style="max-height: 200px; overflow: auto;"><code class="language-{{ $language }}">{{ $content }}</code></pre>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- Other Files --}}
            @if(count($otherAttachments) > 0)
            <div class="mb-2">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($otherAttachments as $attachment)
                    @php
                    $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                    $icon = match($extension) {
                    'pdf' => 'bi-file-pdf-fill text-danger',
                    'doc', 'docx' => 'bi-file-word-fill text-primary',
                    'xls', 'xlsx' => 'bi-file-excel-fill text-success',
                    'ppt', 'pptx' => 'bi-file-ppt-fill text-warning',
                    'zip', 'rar', '7z', 'tar', 'gz' => 'bi-file-zip-fill text-secondary',
                    default => 'bi-file-earmark-fill text-secondary'
                    };
                    @endphp
                    <a href="{{ Storage::url($attachment['path']) }}" class="text-decoration-none"
                        download="{{ $attachment['name'] }}" target="_blank">
                        <div class="d-flex align-items-center bg-light rounded-3 p-2 border">
                            <i class="bi {{ $icon }} fs-5 me-2"></i>
                            <div>
                                <small class="fw-semibold text-dark d-block">{{ Str::limit($attachment['name'], 30)
                                    }}</small>
                                <small class="text-muted">{{ round($attachment['size'] / 1024) }} KB</small>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="d-flex gap-3">
            <form action="{{ route('groups.posts.like', [$post->group->slug, $post->id]) }}" method="POST"
                class="like-form">
                @csrf
                <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                    <i class="bi bi-heart{{ $post->is_liked ? '-fill text-danger' : '' }}"></i>
                    <span class="ms-1">{{ $post->likes_count }}</span>
                </button>
            </form>

            <a href="{{ route('groups.post', [$post->group->slug, $post->id]) }}#comments"
                class="btn btn-link text-dark p-0 text-decoration-none small">
                <i class="bi bi-chat"></i>
                <span class="ms-1">{{ $post->comments_count }}</span>
            </a>

            @if($post->group->canManage(auth()->user()) && !$post->is_pinned)
            <form action="{{ route('groups.posts.pin', [$post->group->slug, $post->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                    <i class="bi bi-pin-angle"></i> Pin
                </button>
            </form>
            @endif

            @if($post->group->canManage(auth()->user()) && $post->is_pinned)
            <form action="{{ route('groups.posts.unpin', [$post->group->slug, $post->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-dark p-0 text-decoration-none small">
                    <i class="bi bi-pin-angle"></i> Unpin
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" class="img-fluid rounded-3" alt="">
                <div id="modalImageCaption" class="text-white text-start mt-2 small"></div>
            </div>
        </div>
    </div>
</div>

{{-- Image Gallery Modal --}}
<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="galleryModalTitle">Gallery</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="imageGalleryCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="galleryCarouselInner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageGalleryCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageGalleryCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Open single image modal
function openImageModal(imageUrl, caption = '') {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalImageCaption').textContent = caption;
    modal.show();
}

// Open image gallery
function openImageGallery(images) {
    const modal = new bootstrap.Modal(document.getElementById('imageGalleryModal'));
    const carouselInner = document.getElementById('galleryCarouselInner');

    carouselInner.innerHTML = images.map((image, index) => `
        <div class="carousel-item ${index === 0 ? 'active' : ''}">
            <img src="${image}" class="d-block w-100" style="max-height: 80vh; object-fit: contain;" alt="Gallery image ${index + 1}">
        </div>
    `).join('');

    modal.show();
}

// Copy code to clipboard
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        // Show temporary tooltip
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Code copied to clipboard!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}

// Initialize highlight.js for code blocks
document.addEventListener('DOMContentLoaded', function() {
    if (typeof hljs !== 'undefined') {
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });
    }
});
</script>