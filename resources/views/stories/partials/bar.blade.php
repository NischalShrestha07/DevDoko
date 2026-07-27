<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex gap-3 stories-scroll" id="storiesTray">
            <div class="text-center flex-shrink-0" style="width: 70px;">
                <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
            </div>
        </div>
    </div>
</div>

<input type="file" id="storyMediaInput" accept="image/*,video/*" class="d-none">

<div id="storyViewer" class="story-viewer d-none">
    <div class="story-viewer-inner">
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
                <button type="button" id="storyDeleteBtn" class="btn btn-sm text-white d-none" title="Delete story">
                    <i class="bi bi-trash"></i>
                </button>
                <button type="button" id="storyCloseBtn" class="btn btn-sm text-white" title="Close">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>
        </div>

        <div class="story-media-area" id="storyMediaArea"></div>

        <div class="story-tap-zone story-tap-prev" style="left: 0;"></div>
        <div class="story-tap-zone story-tap-next" style="right: 0;"></div>

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

<script>
(function() {
    const STORY_IMAGE_DURATION = 5000;
    const STORY_VIDEO_MAX_DURATION = 20000;

    let groups = [];
    let groupIndex = 0;
    let storyIndex = 0;
    let progressTimer = null;
    let progressStart = 0;
    let progressDuration = 0;
    let paused = false;

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    async function loadStories() {
        try {
            const data = await window.DevDoko.fetch('{{ route('stories.index') }}');
            renderTray(data);
        } catch (e) {
            console.error('Failed to load stories', e);
        }
    }

    function renderTray(data) {
        groups = [];
        if (data.mine) groups.push(data.mine);
        groups = groups.concat(data.others || []);

        const tray = document.getElementById('storiesTray');
        const mine = data.mine;
        const myAvatar = '{{ auth()->user()->avatar_url }}';
        const hasMine = !!mine;

        let html = `
            <div class="text-center flex-shrink-0 story-tile" style="width: 70px;" data-action="${hasMine ? 'open-mine' : 'add'}">
                <div class="position-relative d-inline-block">
                    <div class="story-ring ${hasMine ? (mine.has_unseen ? 'unseen' : 'seen') : 'none'} mx-auto d-flex align-items-center justify-content-center" style="width: 62px; height: 62px;">
                        <img src="${escapeHtml(myAvatar)}" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                    </div>
                    <span class="story-add-badge" data-action="add"><i class="bi bi-plus-lg"></i></span>
                </div>
                <small class="d-block text-truncate mt-1 app-text-primary" style="font-size: 11px;">Your Story</small>
            </div>
        `;

        (data.others || []).forEach((group, idx) => {
            html += `
                <div class="text-center flex-shrink-0 story-tile" style="width: 70px;" data-action="open-other" data-index="${idx}">
                    <div class="story-ring ${group.has_unseen ? 'unseen' : 'seen'} mx-auto d-flex align-items-center justify-content-center" style="width: 62px; height: 62px;">
                        <img src="${escapeHtml(group.avatar_url)}" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                    </div>
                    <small class="d-block text-truncate mt-1 app-text-primary" style="font-size: 11px;">${escapeHtml(group.username)}</small>
                </div>
            `;
        });

        tray.innerHTML = html;
    }

    document.getElementById('storiesTray').addEventListener('click', function(e) {
        const tile = e.target.closest('[data-action]');
        if (!tile) return;
        const action = tile.dataset.action;

        if (action === 'add') {
            document.getElementById('storyMediaInput').click();
        } else if (action === 'open-mine') {
            openViewer(0, 0);
        } else if (action === 'open-other') {
            const mineOffset = groups[0] && groups[0].is_mine ? 1 : 0;
            openViewer(mineOffset + parseInt(tile.dataset.index, 10), 0);
        }
    });

    document.getElementById('storyMediaInput').addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 25 * 1024 * 1024) {
            window.DevDoko.toast('File too large. Max 25MB.', 'error');
            e.target.value = '';
            return;
        }

        const caption = window.prompt('Add a caption (optional):', '') || '';

        const formData = new FormData();
        formData.append('media', file);
        if (caption) formData.append('caption', caption);

        try {
            await window.DevDoko.fetch('{{ route('stories.store') }}', {
                method: 'POST',
                body: formData,
            });
            window.DevDoko.toast('Story posted!', 'success');
            loadStories();
        } catch (err) {
            window.DevDoko.toast('Failed to post story.', 'error');
        }

        e.target.value = '';
    });

    function currentGroup() {
        return groups[groupIndex];
    }

    function currentStory() {
        return currentGroup()?.stories[storyIndex];
    }

    function openViewer(gIndex, sIndex) {
        if (!groups.length || !groups[gIndex]) return;
        groupIndex = gIndex;
        storyIndex = sIndex;
        document.getElementById('storyViewer').classList.remove('d-none');
        renderStory();
    }

    function closeViewer() {
        clearTimeout(progressTimer);
        document.getElementById('storyViewer').classList.add('d-none');
        document.getElementById('storyMediaArea').innerHTML = '';
        loadStories();
    }

    function renderProgressBars() {
        const group = currentGroup();
        const bars = group.stories.map((_, idx) => `
            <div class="story-progress-bar">
                <div class="story-progress-bar-fill" id="progressFill${idx}"></div>
            </div>
        `).join('');
        document.getElementById('storyProgressBars').innerHTML = bars;
    }

    function renderStory() {
        const group = currentGroup();
        const story = currentStory();
        if (!group || !story) {
            closeViewer();
            return;
        }

        renderProgressBars();
        for (let i = 0; i < storyIndex; i++) {
            const fill = document.getElementById(`progressFill${i}`);
            if (fill) fill.style.width = '100%';
        }

        document.getElementById('storyHeaderAvatar').src = group.avatar_url;
        document.getElementById('storyHeaderName').textContent = group.is_mine ? 'Your Story' : group.username;
        document.getElementById('storyHeaderTime').textContent = story.time_ago;
        document.getElementById('storyCaption').textContent = story.caption || '';
        document.getElementById('storyDeleteBtn').classList.toggle('d-none', !group.is_mine);
        document.getElementById('storyViewersBtn').classList.toggle('d-none', !group.is_mine);

        const mediaArea = document.getElementById('storyMediaArea');
        mediaArea.innerHTML = '';

        if (story.media_type === 'video') {
            const video = document.createElement('video');
            video.src = story.media_url;
            video.autoplay = true;
            video.playsInline = true;
            video.muted = false;
            mediaArea.appendChild(video);
            video.addEventListener('loadedmetadata', () => {
                const duration = Math.min((video.duration || 8) * 1000, STORY_VIDEO_MAX_DURATION);
                startProgress(duration);
            });
            video.addEventListener('error', () => startProgress(STORY_IMAGE_DURATION));
        } else {
            const img = document.createElement('img');
            img.src = story.media_url;
            mediaArea.appendChild(img);
            startProgress(STORY_IMAGE_DURATION);
        }

        if (!story.viewed && !group.is_mine) {
            window.DevDoko.fetch(`/stories/${story.id}/view`, { method: 'POST' }).catch(() => {});
            story.viewed = true;
        }

        if (group.is_mine) {
            fetchViewersCount(story.id);
        }
    }

    async function fetchViewersCount(storyId) {
        try {
            const data = await window.DevDoko.fetch(`/stories/${storyId}/viewers`);
            document.getElementById('storyViewersCount').textContent = data.viewers.length;
        } catch (e) {}
    }

    function startProgress(duration) {
        clearTimeout(progressTimer);
        paused = false;
        progressDuration = duration;
        progressStart = Date.now();

        const fill = document.getElementById(`progressFill${storyIndex}`);
        if (fill) {
            fill.style.transition = `width ${duration}ms linear`;
            requestAnimationFrame(() => { fill.style.width = '100%'; });
        }

        progressTimer = setTimeout(nextStory, duration);
    }

    function pauseProgress() {
        if (paused) return;
        paused = true;
        clearTimeout(progressTimer);
        const fill = document.getElementById(`progressFill${storyIndex}`);
        if (fill) {
            const elapsed = Date.now() - progressStart;
            const pct = Math.min(100, (elapsed / progressDuration) * 100);
            fill.style.transition = 'none';
            fill.style.width = pct + '%';
        }
        const media = document.querySelector('#storyMediaArea video');
        if (media) media.pause();
    }

    function resumeProgress() {
        if (!paused) return;
        paused = false;
        const elapsed = Date.now() - progressStart;
        const remaining = Math.max(0, progressDuration - elapsed);
        const fill = document.getElementById(`progressFill${storyIndex}`);
        if (fill) {
            fill.style.transition = `width ${remaining}ms linear`;
            requestAnimationFrame(() => { fill.style.width = '100%'; });
        }
        progressTimer = setTimeout(nextStory, remaining);
        const media = document.querySelector('#storyMediaArea video');
        if (media) media.play();
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

    document.getElementById('storyCloseBtn').addEventListener('click', closeViewer);
    document.querySelector('.story-tap-prev').addEventListener('click', prevStory);
    document.querySelector('.story-tap-next').addEventListener('click', nextStory);

    document.getElementById('storyDeleteBtn').addEventListener('click', async function() {
        const story = currentStory();
        if (!story) return;
        const confirmed = await window.DevDoko.confirm('Delete this story?');
        if (!confirmed) return;

        try {
            await window.DevDoko.fetch(`/stories/${story.id}`, { method: 'DELETE' });
            window.DevDoko.toast('Story deleted.', 'success');
            const group = currentGroup();
            group.stories.splice(storyIndex, 1);
            if (!group.stories.length) {
                groups.splice(groupIndex, 1);
            }
            if (!groups.length || !groups[groupIndex]) {
                closeViewer();
            } else {
                if (storyIndex >= groups[groupIndex].stories.length) storyIndex = 0;
                renderStory();
            }
        } catch (err) {
            window.DevDoko.toast('Failed to delete story.', 'error');
        }
    });

    document.getElementById('storyViewersBtn').addEventListener('click', async function() {
        const story = currentStory();
        if (!story) return;
        pauseProgress();

        try {
            const data = await window.DevDoko.fetch(`/stories/${story.id}/viewers`);
            const list = document.getElementById('storyViewersList');
            if (!data.viewers.length) {
                list.innerHTML = '<div class="text-center text-muted py-4 small">No views yet</div>';
            } else {
                list.innerHTML = data.viewers.map(v => `
                    <div class="list-group-item d-flex align-items-center gap-2">
                        <img src="${escapeHtml(v.avatar_url)}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="small fw-semibold">${escapeHtml(v.username)}</div>
                        </div>
                        <small class="text-muted">${escapeHtml(v.viewed_at)}</small>
                    </div>
                `).join('');
            }
            const modal = new bootstrap.Modal(document.getElementById('storyViewersModal'));
            modal.show();
        } catch (e) {}
    });

    document.getElementById('storyViewersModal').addEventListener('hidden.bs.modal', resumeProgress);

    document.addEventListener('keydown', function(e) {
        if (document.getElementById('storyViewer').classList.contains('d-none')) return;
        if (e.key === 'Escape') closeViewer();
        if (e.key === 'ArrowRight') nextStory();
        if (e.key === 'ArrowLeft') prevStory();
    });

    loadStories();
})();
</script>
