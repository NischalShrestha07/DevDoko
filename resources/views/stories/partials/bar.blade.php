<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex gap-3 stories-scroll" id="storiesTray">
            <div class="text-center flex-shrink-0" style="width: 70px;">
                <div class="spinner-border spinner-border-sm text-muted" role="status">
                    <span class="visually-hidden">Loading stories…</span>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="file" id="storyMediaInput" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,video/mp4,video/quicktime" class="d-none">

{{-- Composer: preview + caption before publishing --}}
<div class="modal fade" id="storyComposerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold"><i class="bi bi-plus-circle me-2"></i>New Story</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="story-composer-preview mb-3" id="storyComposerPreview"></div>
                <label for="storyCaptionInput" class="form-label fw-semibold small">Caption <span class="text-muted fw-normal">(optional)</span></label>
                <textarea id="storyCaptionInput" class="form-control" rows="2" maxlength="280" placeholder="Say something about this…"></textarea>
                <div class="d-flex justify-content-end mt-1">
                    <small class="text-muted" id="storyCaptionCount">0/280</small>
                </div>
                <div class="progress mt-3 d-none" id="storyUploadProgress" style="height: 4px;">
                    <div class="progress-bar" id="storyUploadProgressBar" style="width: 0%;"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="storyShareBtn">
                    <i class="bi bi-send me-2"></i>Share
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Fullscreen viewer --}}
<div id="storyViewer" class="story-viewer d-none">
    <div class="story-viewer-inner" id="storyViewerInner">
        <div class="story-progress-bars" id="storyProgressBars"></div>

        <div class="story-viewer-header d-flex align-items-center justify-content-between px-2 pt-3">
            <div class="d-flex align-items-center gap-2">
                <img id="storyHeaderAvatar" src="" alt="" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                <div>
                    <div id="storyHeaderName" class="text-white fw-semibold small"></div>
                    <div id="storyHeaderTime" class="text-white-50" style="font-size: 11px;"></div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button type="button" id="storyMuteBtn" class="btn btn-sm text-white d-none" title="Unmute">
                    <i class="bi bi-volume-mute-fill"></i>
                </button>
                <button type="button" id="storyDeleteBtn" class="btn btn-sm text-white d-none" title="Delete story">
                    <i class="bi bi-trash"></i>
                </button>
                <button type="button" id="storyCloseBtn" class="btn btn-sm text-white" title="Close">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>
        </div>

        <div class="story-media-area" id="storyMediaArea"></div>

        <div id="storyCaption" class="story-caption text-white text-center px-3"></div>

        <button type="button" id="storyViewersBtn" class="btn btn-sm btn-outline-light rounded-pill story-viewers-btn d-none">
            <i class="bi bi-eye me-1"></i><span id="storyViewersCount">0</span> views
        </button>
    </div>
</div>

<div class="modal fade" id="storyViewersModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold">Viewers</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div id="storyViewersList" class="list-group list-group-flush"></div>
            </div>
        </div>
    </div>
</div>

{{--
    Runs on DOMContentLoaded, not inline: app.js ships as <script type="module">
    (deferred), so window.DevDoko does not exist yet while the body is parsing.
--}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const IMAGE_DURATION = 5000;
    const VIDEO_MAX_DURATION = 30000;
    const MAX_UPLOAD_BYTES = 25 * 1024 * 1024;
    const HOLD_THRESHOLD = 200;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    let groups = [];
    let groupIndex = 0;
    let storyIndex = 0;
    let progressTimer = null;
    let progressStart = 0;
    let progressDuration = 0;
    let paused = false;
    let muted = true;
    let pendingFile = null;
    let holdTimer = null;
    let didHold = false;

    const el = (id) => document.getElementById(id);
    const viewer = el('storyViewer');
    const viewerInner = el('storyViewerInner');
    const mediaArea = el('storyMediaArea');

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    /* ---------- tray ---------- */

    async function loadStories() {
        try {
            const data = await window.DevDoko.fetch('{{ route('stories.index') }}');
            groups = [];
            if (data.mine) groups.push(data.mine);
            groups = groups.concat(data.others || []);
            renderTray(data);
        } catch (e) {
            el('storiesTray').innerHTML =
                '<small class="text-muted">Could not load stories. <a href="#" id="storyRetry">Retry</a></small>';
            el('storyRetry')?.addEventListener('click', (ev) => { ev.preventDefault(); loadStories(); });
        }
    }

    function renderTray(data) {
        const mine = data.mine;
        const hasMine = !!mine;
        const myAvatar = @json(auth()->user()->avatar_url);

        let html = `
            <div class="text-center flex-shrink-0 story-tile" style="width: 70px;">
                <div class="position-relative d-inline-block">
                    <div class="story-ring ${hasMine ? (mine.has_unseen ? 'unseen' : 'seen') : 'none'} mx-auto d-flex align-items-center justify-content-center"
                         style="width: 62px; height: 62px;" data-action="${hasMine ? 'open-mine' : 'add'}">
                        <img src="${escapeHtml(myAvatar)}" alt="Your story" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                    </div>
                    <span class="story-add-badge" data-action="add" title="Add to your story"><i class="bi bi-plus-lg"></i></span>
                </div>
                <small class="d-block text-truncate mt-1 app-text-primary" style="font-size: 11px;">Your Story</small>
            </div>
        `;

        (data.others || []).forEach((group, idx) => {
            html += `
                <div class="text-center flex-shrink-0 story-tile" style="width: 70px;" data-action="open-other" data-index="${idx}">
                    <div class="story-ring ${group.has_unseen ? 'unseen' : 'seen'} mx-auto d-flex align-items-center justify-content-center" style="width: 62px; height: 62px;">
                        <img src="${escapeHtml(group.avatar_url)}" alt="${escapeHtml(group.username)}" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                    </div>
                    <small class="d-block text-truncate mt-1 app-text-primary" style="font-size: 11px;">${escapeHtml(group.username)}</small>
                </div>
            `;
        });

        el('storiesTray').innerHTML = html;
    }

    el('storiesTray').addEventListener('click', function (e) {
        const target = e.target.closest('[data-action]');
        if (!target) return;
        const action = target.dataset.action;

        if (action === 'add') {
            el('storyMediaInput').click();
        } else if (action === 'open-mine') {
            openViewer(0, 0);
        } else if (action === 'open-other') {
            const mineOffset = groups[0] && groups[0].is_mine ? 1 : 0;
            openViewer(mineOffset + parseInt(target.dataset.index, 10), 0);
        }
    });

    /* ---------- composer ---------- */

    el('storyMediaInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        e.target.value = '';
        if (!file) return;

        if (file.size > MAX_UPLOAD_BYTES) {
            window.DevDoko.toast('File too large. Maximum size is 25MB.', 'error');
            return;
        }

        pendingFile = file;
        const url = URL.createObjectURL(file);
        const isVideo = file.type.startsWith('video/');
        el('storyComposerPreview').innerHTML = isVideo
            ? `<video src="${url}" controls playsinline></video>`
            : `<img src="${url}" alt="Story preview">`;

        el('storyCaptionInput').value = '';
        el('storyCaptionCount').textContent = '0/280';
        el('storyUploadProgress').classList.add('d-none');
        el('storyUploadProgressBar').style.width = '0%';

        new bootstrap.Modal(el('storyComposerModal')).show();
    });

    el('storyCaptionInput').addEventListener('input', function () {
        el('storyCaptionCount').textContent = `${this.value.length}/280`;
    });

    el('storyComposerModal').addEventListener('hidden.bs.modal', function () {
        const media = el('storyComposerPreview').querySelector('img, video');
        if (media) URL.revokeObjectURL(media.src);
        el('storyComposerPreview').innerHTML = '';
        pendingFile = null;
    });

    el('storyShareBtn').addEventListener('click', function () {
        if (!pendingFile) return;

        const btn = this;
        const formData = new FormData();
        formData.append('media', pendingFile);
        const caption = el('storyCaptionInput').value.trim();
        if (caption) formData.append('caption', caption);

        const bar = el('storyUploadProgressBar');
        el('storyUploadProgress').classList.remove('d-none');
        window.DevDoko.setLoading(btn, true);

        // XHR rather than fetch: gives real upload progress for large videos.
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('stories.store') }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.addEventListener('progress', function (evt) {
            if (evt.lengthComputable) {
                bar.style.width = Math.round((evt.loaded / evt.total) * 100) + '%';
            }
        });

        xhr.addEventListener('load', function () {
            window.DevDoko.setLoading(btn, false);
            if (xhr.status >= 200 && xhr.status < 300) {
                bootstrap.Modal.getInstance(el('storyComposerModal'))?.hide();
                window.DevDoko.toast('Story posted!', 'success');
                loadStories();
            } else {
                let message = 'Failed to post story.';
                try {
                    const res = JSON.parse(xhr.responseText);
                    message = res.errors ? Object.values(res.errors)[0][0] : (res.message || message);
                } catch (err) {}
                window.DevDoko.toast(message, 'error');
                el('storyUploadProgress').classList.add('d-none');
            }
        });

        xhr.addEventListener('error', function () {
            window.DevDoko.setLoading(btn, false);
            el('storyUploadProgress').classList.add('d-none');
            window.DevDoko.toast('Network error. Please try again.', 'error');
        });

        xhr.send(formData);
    });

    /* ---------- viewer ---------- */

    const currentGroup = () => groups[groupIndex];
    const currentStory = () => currentGroup()?.stories[storyIndex];

    function openViewer(gIndex, sIndex) {
        if (!groups[gIndex]) return;
        groupIndex = gIndex;
        storyIndex = sIndex;
        muted = true;
        viewer.classList.remove('d-none');
        document.body.style.overflow = 'hidden';
        renderStory();
    }

    function closeViewer() {
        clearTimeout(progressTimer);
        clearTimeout(holdTimer);
        viewer.classList.add('d-none');
        mediaArea.innerHTML = '';
        document.body.style.overflow = '';
        loadStories();
    }

    function renderProgressBars() {
        el('storyProgressBars').innerHTML = currentGroup().stories.map((_, idx) => `
            <div class="story-progress-bar"><div class="story-progress-bar-fill" id="progressFill${idx}"></div></div>
        `).join('');
    }

    function renderStory() {
        const group = currentGroup();
        const story = currentStory();
        if (!group || !story) return closeViewer();

        renderProgressBars();
        for (let i = 0; i < storyIndex; i++) {
            const fill = el(`progressFill${i}`);
            if (fill) fill.style.width = '100%';
        }

        el('storyHeaderAvatar').src = group.avatar_url;
        el('storyHeaderName').textContent = group.is_mine ? 'Your Story' : group.username;
        el('storyHeaderTime').textContent = story.time_ago;
        el('storyCaption').textContent = story.caption || '';
        el('storyDeleteBtn').classList.toggle('d-none', !group.is_mine);
        el('storyViewersBtn').classList.toggle('d-none', !group.is_mine);
        el('storyViewersCount').textContent = story.views_count ?? 0;
        el('storyMuteBtn').classList.toggle('d-none', story.media_type !== 'video');

        mediaArea.innerHTML = '';

        if (story.media_type === 'video') {
            const video = document.createElement('video');
            video.src = story.media_url;
            video.autoplay = true;
            video.playsInline = true;
            // Muted so autoplay is never blocked; user can unmute via the header button.
            video.muted = muted;
            updateMuteIcon();
            mediaArea.appendChild(video);
            video.addEventListener('loadedmetadata', () => {
                startProgress(Math.min((video.duration || 8) * 1000, VIDEO_MAX_DURATION));
            });
            video.addEventListener('error', () => startProgress(IMAGE_DURATION));
        } else {
            const img = document.createElement('img');
            img.src = story.media_url;
            img.alt = story.caption || 'Story';
            mediaArea.appendChild(img);
            startProgress(IMAGE_DURATION);
        }

        if (!story.viewed && !group.is_mine) {
            story.viewed = true;
            window.DevDoko.fetch(`/stories/${story.id}/view`, { method: 'POST' }).catch(() => {});
        }
    }

    function updateMuteIcon() {
        el('storyMuteBtn').innerHTML = muted
            ? '<i class="bi bi-volume-mute-fill"></i>'
            : '<i class="bi bi-volume-up-fill"></i>';
        el('storyMuteBtn').title = muted ? 'Unmute' : 'Mute';
    }

    el('storyMuteBtn').addEventListener('click', function () {
        muted = !muted;
        const video = mediaArea.querySelector('video');
        if (video) video.muted = muted;
        updateMuteIcon();
    });

    function startProgress(duration) {
        clearTimeout(progressTimer);
        paused = false;
        progressDuration = duration;
        progressStart = Date.now();

        const fill = el(`progressFill${storyIndex}`);
        if (fill) {
            fill.style.transition = 'none';
            fill.style.width = '0%';
            requestAnimationFrame(() => {
                fill.style.transition = `width ${duration}ms linear`;
                fill.style.width = '100%';
            });
        }

        progressTimer = setTimeout(nextStory, duration);
    }

    function pauseProgress() {
        if (paused) return;
        paused = true;
        clearTimeout(progressTimer);
        const fill = el(`progressFill${storyIndex}`);
        if (fill) {
            const pct = Math.min(100, ((Date.now() - progressStart) / progressDuration) * 100);
            fill.style.transition = 'none';
            fill.style.width = pct + '%';
        }
        mediaArea.querySelector('video')?.pause();
    }

    function resumeProgress() {
        if (!paused) return;
        paused = false;
        const remaining = Math.max(0, progressDuration - (Date.now() - progressStart));
        progressStart = Date.now() - (progressDuration - remaining);
        const fill = el(`progressFill${storyIndex}`);
        if (fill) {
            fill.style.transition = `width ${remaining}ms linear`;
            requestAnimationFrame(() => { fill.style.width = '100%'; });
        }
        progressTimer = setTimeout(nextStory, remaining);
        mediaArea.querySelector('video')?.play().catch(() => {});
    }

    function nextStory() {
        const group = currentGroup();
        if (storyIndex + 1 < group.stories.length) {
            storyIndex++;
            renderStory();
        } else if (groupIndex + 1 < groups.length) {
            groupIndex++;
            storyIndex = 0;
            renderStory();
        } else {
            closeViewer();
        }
    }

    function prevStory() {
        if (storyIndex > 0) {
            storyIndex--;
            renderStory();
        } else if (groupIndex > 0) {
            groupIndex--;
            storyIndex = groups[groupIndex].stories.length - 1;
            renderStory();
        }
    }

    /* Tap left/right to navigate, press-and-hold to pause. */
    viewerInner.addEventListener('pointerdown', function (e) {
        if (e.target.closest('button')) return;
        didHold = false;
        holdTimer = setTimeout(() => { didHold = true; pauseProgress(); }, HOLD_THRESHOLD);
    });

    viewerInner.addEventListener('pointerup', function (e) {
        if (e.target.closest('button')) return;
        clearTimeout(holdTimer);
        if (didHold) return resumeProgress();

        const rect = viewerInner.getBoundingClientRect();
        (e.clientX - rect.left < rect.width * 0.35) ? prevStory() : nextStory();
    });

    viewerInner.addEventListener('pointercancel', function () {
        clearTimeout(holdTimer);
        if (didHold) resumeProgress();
    });

    el('storyCloseBtn').addEventListener('click', closeViewer);

    el('storyDeleteBtn').addEventListener('click', async function () {
        const story = currentStory();
        if (!story) return;

        pauseProgress();
        const confirmed = await window.DevDoko.confirm('Delete this story?');
        if (!confirmed) return resumeProgress();

        try {
            await window.DevDoko.fetch(`/stories/${story.id}`, { method: 'DELETE' });
            window.DevDoko.toast('Story deleted.', 'success');

            const group = currentGroup();
            group.stories.splice(storyIndex, 1);
            if (!group.stories.length) groups.splice(groupIndex, 1);

            if (!groups.length || !groups[groupIndex]) return closeViewer();
            if (storyIndex >= groups[groupIndex].stories.length) storyIndex = 0;
            renderStory();
        } catch (err) {
            window.DevDoko.toast('Failed to delete story.', 'error');
            resumeProgress();
        }
    });

    el('storyViewersBtn').addEventListener('click', async function () {
        const story = currentStory();
        if (!story) return;
        pauseProgress();

        const list = el('storyViewersList');
        list.innerHTML = '<div class="text-center text-muted py-4 small">Loading…</div>';
        new bootstrap.Modal(el('storyViewersModal')).show();

        try {
            const data = await window.DevDoko.fetch(`/stories/${story.id}/viewers`);
            list.innerHTML = data.viewers.length
                ? data.viewers.map(v => `
                    <div class="list-group-item d-flex align-items-center gap-2">
                        <img src="${escapeHtml(v.avatar_url)}" alt="" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                        <div class="flex-grow-1"><div class="small fw-semibold">${escapeHtml(v.username)}</div></div>
                        <small class="text-muted">${escapeHtml(v.viewed_at)}</small>
                    </div>
                `).join('')
                : '<div class="text-center text-muted py-4 small">No views yet</div>';
        } catch (e) {
            list.innerHTML = '<div class="text-center text-muted py-4 small">Could not load viewers</div>';
        }
    });

    el('storyViewersModal').addEventListener('hidden.bs.modal', resumeProgress);

    document.addEventListener('keydown', function (e) {
        if (viewer.classList.contains('d-none')) return;
        if (e.key === 'Escape') closeViewer();
        if (e.key === 'ArrowRight') nextStory();
        if (e.key === 'ArrowLeft') prevStory();
    });

    loadStories();
});
</script>
