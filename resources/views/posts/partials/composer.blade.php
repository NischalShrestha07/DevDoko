{{--
    Instagram-style post composer.
    Step 1: pick a post type / drop media. Step 2: two-pane preview + caption.
    Included once in the app layout; opened by any [data-composer-open] element.
--}}
<div class="modal fade" id="postComposerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl composer-dialog">
        <div class="modal-content composer-content">

            <div class="modal-header py-2 px-3">
                <button type="button" class="btn btn-sm border-0 composer-back d-none" id="composerBackBtn" title="Back">
                    <i class="bi bi-arrow-left fs-5"></i>
                </button>
                <h6 class="modal-title fw-semibold mx-auto mb-0" id="composerTitle">Create new post</h6>
                <button type="button" class="btn btn-sm border-0 composer-share d-none fw-semibold app-accent-text" id="composerShareBtn">Share</button>
                <button type="button" class="btn-close ms-2" id="composerCloseBtn" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">

                {{-- ---------- STEP 1: type + media ---------- --}}
                <div id="composerStep1">
                    <div class="composer-types px-3 pt-3">
                        <div class="d-flex gap-2 overflow-auto pb-2 composer-type-row">
                            @foreach ([
                                ['type' => 'image',    'icon' => 'bi-image',       'label' => 'Photo'],
                                ['type' => 'video',    'icon' => 'bi-camera-reels','label' => 'Video'],
                                ['type' => 'text',     'icon' => 'bi-chat-square-text', 'label' => 'Text'],
                                ['type' => 'code',     'icon' => 'bi-code-slash',  'label' => 'Code'],
                                ['type' => 'link',     'icon' => 'bi-link-45deg',  'label' => 'Link'],
                                ['type' => 'question', 'icon' => 'bi-patch-question', 'label' => 'Question'],
                                ['type' => 'article',  'icon' => 'bi-file-text',   'label' => 'Article'],
                            ] as $t)
                                <button type="button" class="btn btn-sm rounded-pill composer-type-chip flex-shrink-0"
                                    data-type="{{ $t['type'] }}">
                                    <i class="bi {{ $t['icon'] }} me-1"></i>{{ $t['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- media dropzone (image/video types) --}}
                    <div class="composer-dropzone m-3" id="composerDropzone">
                        <i class="bi bi-images composer-dropzone-icon" id="composerDropIcon"></i>
                        <p class="fs-5 mb-1 mt-3" id="composerDropTitle">Drag photos and videos here</p>
                        <p class="app-text-muted small mb-3">or paste from your clipboard</p>
                        <button type="button" class="btn btn-primary rounded-3 px-4" id="composerSelectBtn">
                            Select from computer
                        </button>
                        <div class="text-muted small mt-3" id="composerDropHint">JPG, PNG, GIF, WEBP up to 20MB</div>
                    </div>

                    {{-- non-media types go straight to a textarea --}}
                    <div class="p-4 d-none" id="composerTextStart">
                        <textarea class="form-control border-0 composer-quick-input" id="composerQuickText" rows="6"
                            placeholder="What's on your mind?"></textarea>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-primary rounded-pill px-4" id="composerTextNext">Next</button>
                        </div>
                    </div>
                </div>

                {{-- ---------- STEP 2: preview + details ---------- --}}
                <div id="composerStep2" class="d-none">
                    <div class="row g-0 composer-panes">

                        <div class="col-md-7 composer-preview-pane" id="composerPreviewPane">
                            <div class="composer-preview" id="composerPreview"></div>
                        </div>

                        <div class="col-md-5 composer-detail-pane" id="composerDetailPane">
                            <div class="d-flex align-items-center gap-2 p-3 border-bottom">
                                <img src="{{ auth()->user()->avatar_url }}" alt="" class="rounded-circle"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                                <span class="fw-semibold small">{{ auth()->user()->profile->username ?? auth()->user()->name }}</span>
                            </div>

                            <div class="composer-fields p-3">
                                {{-- title (article/question/project) --}}
                                <div class="mb-3 d-none" id="composerTitleField">
                                    <input type="text" class="form-control" id="composerPostTitle" maxlength="200"
                                        placeholder="Title">
                                </div>

                                {{-- caption --}}
                                <div class="mb-2" id="composerCaptionField">
                                    <textarea class="form-control border-0 px-0 composer-caption" id="composerCaption"
                                        rows="5" maxlength="20000" placeholder="Write a caption..."></textarea>
                                    <div class="d-flex justify-content-end">
                                        <small class="app-text-muted" id="composerCaptionCount">0/20000</small>
                                    </div>
                                </div>

                                {{-- code --}}
                                <div class="mb-3 d-none" id="composerCodeField">
                                    <select class="form-select form-select-sm mb-2" id="composerCodeLang">
                                        @foreach (['javascript','typescript','php','python','java','csharp','cpp','c','go','rust','ruby','swift','kotlin','sql','bash','html','css','json','yaml','markdown','plaintext'] as $lang)
                                            <option value="{{ $lang }}">{{ $lang }}</option>
                                        @endforeach
                                    </select>
                                    <textarea class="form-control font-monospace composer-code" id="composerCode" rows="10"
                                        maxlength="20000" placeholder="Paste your code here..." spellcheck="false"></textarea>
                                </div>

                                {{-- link --}}
                                <div class="mb-3 d-none" id="composerLinkField">
                                    <input type="url" class="form-control mb-2" id="composerLinkUrl" maxlength="500"
                                        placeholder="https://example.com">
                                    <input type="text" class="form-control mb-2" id="composerLinkTitle" maxlength="200"
                                        placeholder="Link title (optional)">
                                    <textarea class="form-control" id="composerLinkDesc" rows="2" maxlength="500"
                                        placeholder="Link description (optional)"></textarea>
                                </div>

                                {{-- tags --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold app-text-muted text-uppercase">Tags</label>
                                    <div class="composer-tag-box form-control d-flex flex-wrap gap-1 align-items-center"
                                        id="composerTagBox">
                                        <input type="text" class="border-0 flex-grow-1 composer-tag-input"
                                            id="composerTagInput" placeholder="Add tag and press Enter">
                                    </div>
                                    <div class="form-text app-text-muted">Up to 10 tags</div>
                                </div>

                                {{-- visibility --}}
                                <div class="mb-3">
                                    <label for="composerVisibility" class="form-label small fw-semibold app-text-muted text-uppercase">Audience</label>
                                    <select class="form-select form-select-sm" id="composerVisibility">
                                        <option value="public">🌐 Public — anyone can see</option>
                                        <option value="followers">👥 Followers only</option>
                                        <option value="private">🔒 Private — only me</option>
                                    </select>
                                </div>

                                <div class="progress d-none" id="composerProgress" style="height: 4px;">
                                    <div class="progress-bar" id="composerProgressBar" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="file" id="composerFileInput" class="d-none"
    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo">

<script>
document.addEventListener('DOMContentLoaded', function () {
    const MAX_IMAGE_BYTES = 20 * 1024 * 1024;
    const MAX_VIDEO_BYTES = 50 * 1024 * 1024;
    const MAX_TAGS = 10;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const el = (id) => document.getElementById(id);

    const modalEl = el('postComposerModal');
    if (!modalEl) return;
    const modal = new bootstrap.Modal(modalEl);

    // Types that use the media dropzone as their entry point.
    const MEDIA_TYPES = ['image', 'video'];
    // Types that show a title field on step 2.
    const TITLE_TYPES = ['article', 'question', 'project'];

    let state = { type: null, file: null, previewUrl: null, tags: [], submitting: false };

    /* ---------------- open / reset ---------------- */

    function reset() {
        if (state.previewUrl) URL.revokeObjectURL(state.previewUrl);
        state = { type: null, file: null, previewUrl: null, tags: [], submitting: false };

        el('composerStep1').classList.remove('d-none');
        el('composerStep2').classList.add('d-none');
        el('composerBackBtn').classList.add('d-none');
        el('composerShareBtn').classList.add('d-none');
        el('composerTitle').textContent = 'Create new post';

        el('composerPreview').innerHTML = '';
        el('composerQuickText').value = '';
        el('composerCaption').value = '';
        el('composerPostTitle').value = '';
        el('composerCode').value = '';
        el('composerLinkUrl').value = '';
        el('composerLinkTitle').value = '';
        el('composerLinkDesc').value = '';
        el('composerVisibility').value = 'public';
        el('composerCaptionCount').textContent = '0/20000';
        el('composerProgress').classList.add('d-none');
        el('composerProgressBar').style.width = '0%';
        el('composerFileInput').value = '';
        renderTags();
        selectType('image');
    }

    function selectType(type) {
        state.type = type;
        document.querySelectorAll('.composer-type-chip').forEach(chip => {
            chip.classList.toggle('active', chip.dataset.type === type);
        });

        const isMedia = MEDIA_TYPES.includes(type);
        el('composerDropzone').classList.toggle('d-none', !isMedia);
        el('composerTextStart').classList.toggle('d-none', isMedia);

        if (type === 'video') {
            el('composerDropIcon').className = 'bi bi-camera-reels composer-dropzone-icon';
            el('composerDropTitle').textContent = 'Drag a video here';
            el('composerDropHint').textContent = 'MP4, MOV, AVI up to 50MB';
            el('composerFileInput').setAttribute('accept', 'video/mp4,video/quicktime,video/x-msvideo');
        } else if (type === 'image') {
            el('composerDropIcon').className = 'bi bi-images composer-dropzone-icon';
            el('composerDropTitle').textContent = 'Drag photos here';
            el('composerDropHint').textContent = 'JPG, PNG, GIF, WEBP up to 20MB';
            el('composerFileInput').setAttribute('accept', 'image/jpeg,image/png,image/jpg,image/gif,image/webp');
        } else {
            const placeholders = {
                text: "What's on your mind?",
                code: 'Describe what this code does...',
                link: 'Say something about this link...',
                question: 'What would you like to ask?',
                article: 'Start writing your article...',
            };
            el('composerQuickText').placeholder = placeholders[type] || "What's on your mind?";
        }
    }

    document.querySelectorAll('.composer-type-chip').forEach(chip => {
        chip.addEventListener('click', () => selectType(chip.dataset.type));
    });

    /* ---------------- step 1 -> 2 ---------------- */

    function acceptFile(file) {
        if (!file) return;
        const isVideo = file.type.startsWith('video/');
        const isImage = file.type.startsWith('image/');

        if (!isVideo && !isImage) {
            return window.DevDoko.toast('Only images and videos are supported.', 'error');
        }
        const limit = isVideo ? MAX_VIDEO_BYTES : MAX_IMAGE_BYTES;
        if (file.size > limit) {
            return window.DevDoko.toast(`File too large. Max ${isVideo ? '50' : '20'}MB.`, 'error');
        }

        state.type = isVideo ? 'video' : 'image';
        state.file = file;
        if (state.previewUrl) URL.revokeObjectURL(state.previewUrl);
        state.previewUrl = URL.createObjectURL(file);
        goToStep2();
    }

    function goToStep2() {
        const type = state.type;

        el('composerStep1').classList.add('d-none');
        el('composerStep2').classList.remove('d-none');
        el('composerBackBtn').classList.remove('d-none');
        el('composerShareBtn').classList.remove('d-none');
        el('composerTitle').textContent = 'Create new post';

        // media preview pane only makes sense when there is media;
        // without it the detail pane takes the full modal width
        const hasMedia = !!state.file;
        el('composerPreviewPane').classList.toggle('d-none', !hasMedia);
        el('composerDetailPane').classList.toggle('col-md-5', hasMedia);
        el('composerDetailPane').classList.toggle('col-md-12', !hasMedia);
        el('composerPreview').innerHTML = hasMedia
            ? (state.type === 'video'
                ? `<video src="${state.previewUrl}" controls playsinline></video>`
                : `<img src="${state.previewUrl}" alt="Preview">`)
            : '';

        el('composerTitleField').classList.toggle('d-none', !TITLE_TYPES.includes(type));
        el('composerCodeField').classList.toggle('d-none', type !== 'code');
        el('composerLinkField').classList.toggle('d-none', type !== 'link');

        // carry over whatever was typed on step 1
        const quick = el('composerQuickText').value.trim();
        if (quick && !el('composerCaption').value) el('composerCaption').value = quick;
        updateCaptionCount();

        setTimeout(() => {
            (type === 'code' ? el('composerCode') : el('composerCaption')).focus();
        }, 200);
    }

    el('composerSelectBtn').addEventListener('click', () => el('composerFileInput').click());
    el('composerFileInput').addEventListener('change', function (e) {
        acceptFile(e.target.files[0]);
        e.target.value = '';
    });

    el('composerTextNext').addEventListener('click', function () {
        if (!el('composerQuickText').value.trim() && state.type !== 'link' && state.type !== 'code') {
            return window.DevDoko.toast('Write something first.', 'warning');
        }
        goToStep2();
    });

    el('composerBackBtn').addEventListener('click', function () {
        el('composerStep2').classList.add('d-none');
        el('composerStep1').classList.remove('d-none');
        el('composerBackBtn').classList.add('d-none');
        el('composerShareBtn').classList.add('d-none');
        state.file = null;
        if (state.previewUrl) { URL.revokeObjectURL(state.previewUrl); state.previewUrl = null; }
    });

    /* ---------------- dropzone ---------------- */

    const dropzone = el('composerDropzone');
    ['dragenter', 'dragover'].forEach(ev => dropzone.addEventListener(ev, (e) => {
        e.preventDefault();
        dropzone.classList.add('dragging');
    }));
    ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, (e) => {
        e.preventDefault();
        if (ev === 'dragleave' && dropzone.contains(e.relatedTarget)) return;
        dropzone.classList.remove('dragging');
    }));
    dropzone.addEventListener('drop', (e) => acceptFile(e.dataTransfer.files[0]));

    // paste an image straight from the clipboard
    document.addEventListener('paste', function (e) {
        if (!modalEl.classList.contains('show')) return;
        if (!el('composerStep2').classList.contains('d-none')) return; // step 1 only
        const item = [...(e.clipboardData?.items || [])].find(i => i.type.startsWith('image/'));
        if (item) acceptFile(item.getAsFile());
    });

    /* ---------------- caption / tags ---------------- */

    function updateCaptionCount() {
        el('composerCaptionCount').textContent = `${el('composerCaption').value.length}/20000`;
    }
    el('composerCaption').addEventListener('input', updateCaptionCount);

    function renderTags() {
        const box = el('composerTagBox');
        box.querySelectorAll('.composer-tag').forEach(n => n.remove());
        state.tags.forEach((tag, idx) => {
            const chip = document.createElement('span');
            chip.className = 'badge rounded-pill composer-tag d-inline-flex align-items-center gap-1';
            chip.innerHTML = `<span></span><i class="bi bi-x-lg" style="font-size:.65rem;cursor:pointer"></i>`;
            chip.querySelector('span').textContent = tag;
            chip.querySelector('i').addEventListener('click', () => {
                state.tags.splice(idx, 1);
                renderTags();
            });
            box.insertBefore(chip, el('composerTagInput'));
        });
    }

    function addTag(raw) {
        const tag = raw.trim().replace(/^#/, '').slice(0, 50);
        if (!tag) return;
        if (state.tags.length >= MAX_TAGS) return window.DevDoko.toast(`Max ${MAX_TAGS} tags.`, 'warning');
        if (state.tags.includes(tag)) return;
        state.tags.push(tag);
        renderTags();
    }

    el('composerTagInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(this.value);
            this.value = '';
        } else if (e.key === 'Backspace' && !this.value && state.tags.length) {
            state.tags.pop();
            renderTags();
        }
    });
    el('composerTagInput').addEventListener('blur', function () {
        addTag(this.value);
        this.value = '';
    });
    el('composerTagBox').addEventListener('click', () => el('composerTagInput').focus());

    /* ---------------- submit ---------------- */

    el('composerShareBtn').addEventListener('click', function () {
        if (state.submitting) return;

        const type = state.type;
        const caption = el('composerCaption').value.trim();
        const code = el('composerCode').value.trim();
        const linkUrl = el('composerLinkUrl').value.trim();

        // mirror the server's per-type content rules so we fail fast
        if (type === 'code' && !code) return window.DevDoko.toast('Add your code snippet.', 'warning');
        if (type === 'link' && !linkUrl) return window.DevDoko.toast('Add a link URL.', 'warning');
        if (['text', 'article', 'question'].includes(type) && !caption) {
            return window.DevDoko.toast('Write something first.', 'warning');
        }
        if (MEDIA_TYPES.includes(type) && !state.file && !caption) {
            return window.DevDoko.toast('Add media or a caption.', 'warning');
        }

        const fd = new FormData();
        fd.append('type', type);
        fd.append('visibility', el('composerVisibility').value);
        if (caption) fd.append('content', caption);
        if (state.tags.length) fd.append('tags', state.tags.join(','));

        const title = el('composerPostTitle').value.trim();
        if (TITLE_TYPES.includes(type) && title) fd.append('title', title);

        if (type === 'code') {
            fd.append('code_snippet', code);
            fd.append('code_language', el('composerCodeLang').value);
        }
        if (type === 'link') {
            fd.append('link_url', linkUrl);
            const lt = el('composerLinkTitle').value.trim();
            const ld = el('composerLinkDesc').value.trim();
            if (lt) fd.append('link_title', lt);
            if (ld) fd.append('link_description', ld);
        }
        if (state.file) fd.append(type === 'video' ? 'video' : 'image', state.file);

        state.submitting = true;
        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Sharing...';
        el('composerProgress').classList.remove('d-none');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('posts.store') }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.addEventListener('progress', function (evt) {
            if (evt.lengthComputable) {
                el('composerProgressBar').style.width = Math.round((evt.loaded / evt.total) * 100) + '%';
            }
        });

        xhr.addEventListener('load', function () {
            state.submitting = false;
            btn.disabled = false;
            btn.textContent = 'Share';

            if (xhr.status >= 200 && xhr.status < 300) {
                let res = {};
                try { res = JSON.parse(xhr.responseText); } catch (e) {}
                modal.hide();
                window.DevDoko.toast('Post shared!', 'success');
                setTimeout(() => window.location.href = res.redirect_url || '/home', 600);
            } else {
                let message = 'Failed to share post.';
                try {
                    const res = JSON.parse(xhr.responseText);
                    message = res.errors ? Object.values(res.errors)[0][0] : (res.message || message);
                } catch (e) {}
                window.DevDoko.toast(message, 'error');
                el('composerProgress').classList.add('d-none');
            }
        });

        xhr.addEventListener('error', function () {
            state.submitting = false;
            btn.disabled = false;
            btn.textContent = 'Share';
            el('composerProgress').classList.add('d-none');
            window.DevDoko.toast('Network error. Please try again.', 'error');
        });

        xhr.send(fd);
    });

    /* ---------------- open triggers / close ---------------- */

    function isDirty() {
        return !!(state.file || el('composerCaption').value.trim() || el('composerQuickText').value.trim() ||
            el('composerCode').value.trim() || el('composerLinkUrl').value.trim() || state.tags.length);
    }

    el('composerCloseBtn').addEventListener('click', async function () {
        if (state.submitting) return;
        if (isDirty() && !(await window.DevDoko.confirm('Discard this post?'))) return;
        modal.hide();
    });

    modalEl.addEventListener('hidden.bs.modal', reset);

    // Any element with data-composer-open="<type>" opens the composer.
    // Keeps its href as a no-JS fallback to the full create page.
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-composer-open]');
        if (!trigger) return;
        e.preventDefault();
        modal.show();
        const type = trigger.dataset.composerOpen;
        if (type) selectType(type);
    });

    reset();
});
</script>
