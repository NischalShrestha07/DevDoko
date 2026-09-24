{{-- resources/views/partials/right-sidebar.blade.php --}}
{{-- Shared Substack-style right rail: search + subscriptions + recommendations.
     Page-specific data: $rightSidebarHeading, $rightSidebarUsers OR $rightSidebarPosts, $rightSidebarSeeAllUrl. --}}
<div class="app-right-sidebar">
    {{-- Search --}}
    <div class="position-relative mb-4" id="sidebarSearchWrap">
        <div class="input-group">
            <span class="input-group-text app-input-icon border-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control app-search-input border-0" id="sidebarSearchInput"
                placeholder="Search DevDoko" autocomplete="off" style="border-radius: 10px;">
        </div>
        <div class="app-dropdown shadow border-0 rounded-3 d-none" id="sidebarSearchResults"
            style="position: absolute; top: 100%; left: 0; right: 0; margin-top: 6px; max-height: 70vh; overflow-y: auto; z-index: 1050;"></div>
    </div>

    @auth
    {{-- Subscriptions: people the viewer follows --}}
    @if(isset($rightSidebarFollowing) && $rightSidebarFollowing->count() > 0)
    <div class="mb-4">
        <h6 class="fw-bold mb-3 app-text-primary">Subscriptions</h6>
        <div class="d-flex gap-3 overflow-auto pb-1 stories-scroll">
            @foreach($rightSidebarFollowing as $followedUser)
            <a href="{{ route('profile.show', $followedUser->profile->username ?? $followedUser->id) }}"
                class="text-decoration-none text-center flex-shrink-0" style="width: 64px;">
                <img src="{{ $followedUser->profile->avatar_url }}" alt="{{ $followedUser->name }}" class="rounded-circle mb-1"
                    style="width: 56px; height: 56px; object-fit: cover;">
                <div class="small app-text-primary text-truncate">{{ $followedUser->profile->username ?? $followedUser->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
    @endauth

    {{-- Recommended for you / New & Trending --}}
    @if((isset($rightSidebarUsers) && $rightSidebarUsers->count() > 0) || (isset($rightSidebarPosts) && $rightSidebarPosts->count() > 0))
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 app-text-primary">{{ $rightSidebarHeading ?? 'Recommended for you' }}</h6>
            <a href="{{ $rightSidebarSeeAllUrl ?? route('explore') }}" class="text-decoration-none small fw-semibold" style="color: var(--accent);">See all</a>
        </div>

        @if(isset($rightSidebarUsers))
        <div id="sidebarSuggestions">
            @foreach($rightSidebarUsers as $recUser)
            <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }} sidebar-suggestion">
                <a href="{{ route('profile.show', $recUser->profile->username ?? $recUser->id) }}" class="text-decoration-none flex-shrink-0 me-2">
                    <img src="{{ $recUser->profile->avatar_url }}" alt="{{ $recUser->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                </a>
                <div class="flex-grow-1 min-width-0">
                    <a href="{{ route('profile.show', $recUser->profile->username ?? $recUser->id) }}" class="text-decoration-none d-flex align-items-center gap-1">
                        <span class="fw-semibold app-text-primary text-truncate">{{ $recUser->profile->username ?? $recUser->name }}</span>
                        @if($recUser->profile->is_verified ?? false)
                        <i class="bi bi-patch-check-fill" style="font-size: 12px; color: var(--accent);"></i>
                        @endif
                    </a>
                    <div class="small app-text-muted text-truncate">{{ $recUser->profile->title ?? Str::limit($recUser->profile->bio ?? '', 40) }}</div>
                </div>
                @auth
                <form action="{{ route('users.follow', $recUser) }}" method="POST" class="follow-form ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-pill fw-semibold app-sidebar-follow-btn">Follow</button>
                </form>
                @endauth
                <button type="button" class="btn btn-sm border-0 bg-transparent app-text-muted p-1 ms-1 sidebar-suggestion-dismiss" aria-label="Dismiss suggestion">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            @endforeach
        </div>
        @else
        <div>
            @foreach($rightSidebarPosts as $recPost)
            <a href="{{ route('posts.show', $recPost) }}" class="d-flex align-items-center text-decoration-none {{ !$loop->last ? 'mb-3' : '' }}">
                <div class="flex-grow-1 min-width-0 me-2">
                    <div class="fw-semibold app-text-primary text-truncate">{{ $recPost->title ?? Str::limit(strip_tags($recPost->content ?? ''), 60) }}</div>
                    <div class="small app-text-muted text-truncate">{{ $recPost->user->profile->username ?? $recPost->user->name }} &middot; {{ $recPost->created_at->diffForHumans() }}</div>
                </div>
                @if($recPost->image_url)
                <img src="{{ $recPost->image_url }}" alt="" class="rounded-3 flex-shrink-0" style="width: 48px; height: 48px; object-fit: cover;">
                @endif
            </a>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>

<script>
if (!window._rightSidebarInit) {
    window._rightSidebarInit = true;

    document.addEventListener('click', function(e) {
        const dismiss = e.target.closest('.sidebar-suggestion-dismiss');
        if (!dismiss) return;
        const row = dismiss.closest('.sidebar-suggestion');
        if (row) row.remove();
    });

    (function() {
        const input = document.getElementById('sidebarSearchInput');
        const results = document.getElementById('sidebarSearchResults');
        if (!input || !results) return;

        let debounce = null;

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function renderSection(title, items, rowHtml) {
            if (!items.length) return '';
            return `<div class="px-3 pt-2 pb-1 small fw-semibold app-text-muted text-uppercase" style="font-size: 11px;">${title}</div>`
                + items.map(rowHtml).join('');
        }

        function render(data) {
            const html = renderSection('Developers', data.users, u => `
                <a href="${u.url}" class="dropdown-item px-3 py-2 d-flex align-items-center gap-2">
                    <img src="${escapeHtml(u.avatar_url)}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                    <span class="app-text-primary">${escapeHtml(u.username)}</span>
                </a>`)
                + renderSection('Tags', data.tags, t => `
                <a href="${t.url}" class="dropdown-item px-3 py-2 d-flex align-items-center justify-content-between">
                    <span class="app-text-primary">#${escapeHtml(t.name)}</span>
                    <small class="app-text-muted">${t.posts_count}</small>
                </a>`)
                + renderSection('Posts', data.posts, p => `
                <a href="${p.url}" class="dropdown-item px-3 py-2 text-truncate app-text-primary">${escapeHtml(p.title)}</a>`);

            if (!html) {
                results.innerHTML = '<div class="px-3 py-3 small app-text-muted text-center">No results</div>';
            } else {
                results.innerHTML = html;
            }
            results.classList.remove('d-none');
        }

        input.addEventListener('input', function() {
            clearTimeout(debounce);
            const q = this.value.trim();
            if (!q) { results.classList.add('d-none'); return; }

            debounce = setTimeout(async () => {
                try {
                    const res = await fetch(`{{ route('search.quick') }}?q=${encodeURIComponent(q)}`, {
                        headers: { Accept: 'application/json' }
                    });
                    if (res.ok) render(await res.json());
                } catch (e) {}
            }, 250);
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                window.location.href = `{{ route('search') }}?q=${encodeURIComponent(this.value.trim())}`;
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#sidebarSearchWrap')) results.classList.add('d-none');
        });
    })();
}
</script>
