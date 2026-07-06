<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevDoko')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
    <link rel="icon" href="{{ asset('assets/devdokoIcon.png') }}">
</head>

<body>
    <div id="toast-container" class="toast-container"></div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-question-circle text-warning display-4 mb-3 d-block"></i>
                    <p class="confirm-message mb-0 fw-semibold">Are you sure?</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-confirm-yes">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    @auth
    @php
        $recentNotifications = auth()->user()->notifications()
            ->with('fromUser.profile')
            ->latest()
            ->take(5)
            ->get();
        $unreadCount = $recentNotifications->whereNull('read_at')->count();
        if (!$unreadCount) $unreadCount = auth()->user()->unreadNotifications()->count();
    @endphp
    @endauth

    <!-- Top Header Bar -->
    <header class="top-header d-flex align-items-center px-3 py-0 border-bottom">
        <div class="d-flex align-items-center flex-grow-1">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none text-dark fw-bold fs-6">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko" class="rounded-circle me-2 d-md-none"
                    style="width: 32px; height: 32px; object-fit: cover;">
                <span class="d-md-none">DevDoko</span>
            </a>
        </div>
        <div class="d-flex align-items-center gap-2">
            @auth
            <div class="dropdown" id="headerNotifContainer">
                <a href="#" class="btn btn-sm position-relative text-secondary border-0 notification-bell-btn" data-bs-toggle="dropdown" aria-expanded="false" id="headerNotifBell" style="background: transparent;">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge-header" id="notifBadgeHeader" style="font-size: 9px;">{{ $unreadCount }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="width: 380px; max-height: 480px; overflow-y: auto; border-radius: 12px; margin-top: 8px !important;" id="notifDropdown">
                    <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                        <strong class="fs-6"><i class="bi bi-bell-fill me-2"></i>Notifications</strong>
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-primary text-decoration-none p-0 small">Mark all read</button>
                        </form>
                    </div>
                    <div class="dropdown-divider my-0"></div>
                    @forelse($recentNotifications as $notif)
                    <a href="{{ $notif->action_url ?? '#' }}" class="dropdown-item px-3 py-3 notif-item {{ !$notif->read_at ? 'bg-light' : '' }}" data-notif-id="{{ $notif->id }}" style="border-bottom: 1px solid #f0f0f0;">
                        <div class="d-flex gap-2 align-items-start">
                            <div class="position-relative flex-shrink-0">
                                @if($notif->fromUser)
                                <img src="{{ $notif->fromUser->avatar_url }}" alt="" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person text-muted" style="font-size: 14px;"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <small class="text-muted" style="font-size: 11px; white-space: nowrap;">{{ $notif->time_ago }}</small>
                                    @if(!$notif->read_at)
                                    <span class="badge bg-primary rounded-pill" style="font-size: 8px; width: 8px; height: 8px; padding: 0;"></span>
                                    @endif
                                </div>
                                <div style="font-size: 13px; line-height: 1.3; word-break: break-word;">{{ Str::limit($notif->message ?? 'New notification', 80) }}</div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-4 px-3">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 24px;"></i>
                        <p class="text-muted small mt-2 mb-0">No notifications yet</p>
                    </div>
                    @endforelse
                    <div class="dropdown-divider my-0"></div>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center py-2 fw-semibold text-primary" style="font-size: 13px;">
                        View All Notifications
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endauth
            <button id="darkModeToggleHeader" onclick="DevDoko.toggleTheme()"
                class="btn btn-sm rounded-pill d-flex align-items-center gap-1 text-secondary border-0"
                aria-label="Toggle dark mode"
                style="background: transparent;">
                <i class="bi bi-moon-fill fs-6"></i>
                <span class="small d-none d-md-inline">Theme</span>
            </button>
        </div>
    </header>

    <!-- Desktop Sidebar -->
    <div class="d-none d-md-flex flex-column bg-white border-end vh-100 position-fixed"
        style="width: 260px; top: 0; left: 0; z-index: 1020;">
        <!-- Logo -->
        <div class="p-3 border-bottom">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko" class="rounded-circle border"
                    style="width: 45px; height: 45px; object-fit: cover;">
                <span class="ms-2 fw-bold fs-4">DevDoko</span>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex-grow-1 overflow-auto py-2">
            <div class="px-2 mb-3">
                <div class="small text-secondary text-uppercase fw-semibold px-3 mb-2">Main</div>
                <a href="{{ route('home') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('home') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('home'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('search') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('search') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('search'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-search fs-5 me-3"></i>
                    <span>Search</span>
                </a>
                <a href="{{ route('explore') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('explore') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('explore'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-compass fs-5 me-3"></i>
                    <span>Explore</span>
                </a>
                <a href="{{ route('jobs.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('jobs.*') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('jobs.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-briefcase fs-5 me-3"></i>
                    <span>Jobs</span>
                </a>
                <a href="{{ route('messages.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('messages.*') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('messages.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Messages</span>
                </a>
                @auth
                <a href="{{ route('notifications.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('notifications.*') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('notifications.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-bell{{ request()->routeIs('notifications.*') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Notifications</span>
                    <span class="badge bg-danger rounded-pill ms-auto" id="notifBadge">{{ $unreadCount }}</span>
                </a>
                @endauth
                <a href="{{ route('posts.create') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('posts.create') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('posts.create'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Create</span>
                </a>
                <a href="{{ route('developers.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('developers.*') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('developers.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-people fs-5 me-3"></i>
                    <span>Developers</span>
                </a>
            </div>

            <!-- Groups -->
            <div class="px-2 mb-3">
                <div class="small text-secondary text-uppercase fw-semibold px-3 mb-2">Community</div>
                <div class="dropdown">
                    <a href="#"
                        class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-people-fill fs-5 me-3"></i>
                        <span class="flex-grow-1">Groups</span>
                    </a>
                    <ul class="dropdown-menu w-100 mt-1 shadow-sm border-0">
                        <li><a class="dropdown-item py-2 {{ request()->routeIs('groups.index') ? 'active bg-light' : '' }}"
                                href="{{ route('groups.index') }}"><i class="bi bi-compass me-2"></i> Discover</a></li>
                        <li><a class="dropdown-item py-2 {{ request()->routeIs('groups.my-groups') ? 'active bg-light' : '' }}"
                                href="{{ route('groups.my-groups') }}"><i class="bi bi-bookmark-check me-2"></i> My Groups</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2" href="{{ route('groups.create') }}"><i class="bi bi-plus-circle me-2 text-primary"></i> Create Group</a></li>
                    </ul>
                </div>
            </div>

            @auth
            <!-- Marketplace -->
            <div class="px-2 mb-3">
                <div class="small text-secondary text-uppercase fw-semibold px-3 mb-2">Marketplace</div>
                <a href="{{ route('marketplace.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('marketplace.index') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('marketplace.index'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-shop fs-5 me-3"></i>
                    <span>Browse</span>
                </a>
                <a href="{{ route('marketplace.my-listings') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('marketplace.my-listings') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(request()->routeIs('marketplace.my-listings'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-bag fs-5 me-3"></i>
                    <span class="flex-grow-1">My Listings</span>
                    @php $activeListingsCount = auth()->user()->marketplaceListings()->where('status', 'active')->count(); @endphp
                    @if($activeListingsCount > 0)
                    <span class="badge bg-success rounded-pill ms-auto">{{ $activeListingsCount }}</span>
                    @endif
                </a>
                <div class="dropdown mt-1">
                    <a href="#"
                        class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-chat-heart fs-5 me-3"></i>
                        <span class="flex-grow-1">Interests</span>
                    </a>
                    <ul class="dropdown-menu w-100 mt-1 shadow-sm border-0">
                        <li>
                            <a class="dropdown-item py-2 d-flex justify-content-between align-items-center"
                                href="{{ route('marketplace.interests.received') }}">
                                <span><i class="bi bi-inbox me-2"></i> Received</span>
                                @php
                                $pendingReceived = auth()->user()->marketplaceListings()
                                    ->withCount(['interests' => fn($q) => $q->where('status', 'pending')])
                                    ->get()->sum('interests_count');
                                @endphp
                                @if($pendingReceived > 0)
                                <span class="badge bg-warning rounded-pill">{{ $pendingReceived }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex justify-content-between align-items-center"
                                href="{{ route('marketplace.interests.sent') }}">
                                <span><i class="bi bi-send me-2"></i> Sent</span>
                                @php $pendingSent = auth()->user()->marketplaceInterests()->where('status', 'pending')->count(); @endphp
                                @if($pendingSent > 0)
                                <span class="badge bg-info rounded-pill">{{ $pendingSent }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('marketplace.saved') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 position-relative {{ request()->routeIs('marketplace.saved') ? 'bg-light fw-semibold' : '' }} hover-bg-light mt-1">
                    @if(request()->routeIs('marketplace.saved'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-bookmark fs-5 me-3"></i>
                    <span class="flex-grow-1">Saved</span>
                    @php $savedCount = auth()->user()->savedMarketplaceListings()->count(); @endphp
                    @if($savedCount > 0)
                    <span class="badge bg-primary rounded-pill ms-auto">{{ $savedCount }}</span>
                    @endif
                </a>
                <a href="{{ route('marketplace.create') }}"
                    class="d-flex align-items-center px-3 py-2 text-primary text-decoration-none rounded-3 hover-bg-light mt-2">
                    <i class="bi bi-plus-circle fs-5 me-3"></i>
                    <span>Sell Something</span>
                </a>
            </div>
            @endauth
        </div>

        <!-- Bottom: Dark mode toggle + Admin + Logout -->
        <div class="p-3 border-top">
            @auth
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 hover-bg-light mb-1">
                <i class="bi bi-shield-check fs-5 me-3"></i>
                <span>Admin</span>
            </a>
            @endif
            @endauth
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="d-flex align-items-center w-100 px-3 py-2 text-dark bg-transparent border-0 rounded-3 hover-bg-light">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    <span>Logout</span>
                </button>
            </form>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="d-md-none fixed-bottom bg-white border-top py-2 px-3" style="z-index: 1030;">
        <div class="d-flex justify-content-around align-items-center">
            <a href="{{ route('home') }}" class="text-dark text-decoration-none text-center">
                <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Home</small>
            </a>
            <a href="{{ route('search') }}" class="text-dark text-decoration-none text-center">
                <i class="bi bi-search fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Search</small>
            </a>
            <a href="{{ route('jobs.index') }}" class="text-dark text-decoration-none text-center">
                <i class="bi bi-briefcase fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Jobs</small>
            </a>
            <a href="{{ route('marketplace.index') }}" class="text-dark text-decoration-none text-center">
                <i class="bi bi-shop fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Shop</small>
            </a>
            <a href="#" id="darkModeToggleMobile" onclick="DevDoko.toggleTheme(); return false;"
                class="text-dark text-decoration-none text-center">
                <i class="bi bi-moon-fill fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Theme</small>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="text-dark text-decoration-none text-center position-relative">
                <i class="bi bi-bell fs-5"></i>
                @auth
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge-mobile"
                    style="font-size: 8px;">{{ $unreadCount > 99 ? '99+' : ($unreadCount ?: '') }}</span>
                @endauth
                <small class="d-block" style="font-size: 10px;">Activity</small>
            </a>
            @auth
            <a href="{{ route('profile.show', auth()->user()->profile?->username) }}"
                class="text-dark text-decoration-none text-center">
                @if(auth()->user()->profile?->avatar)
                <img src="{{ auth()->user()->profile?->avatar_url }}" class="rounded-circle"
                    style="width: 20px; height: 20px; object-fit: cover;">
                @else
                <i class="bi bi-person-circle fs-5"></i>
                @endif
                <small class="d-block" style="font-size: 10px;">Profile</small>
            </a>
            @endauth
        </div>
    </nav>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    @auth
    <script>
    // Notification system
    (function() {
        let prevCount = {{ $unreadCount ?? 0 }};

        async function pollUnread() {
            try {
                const res = await fetch('/notifications/count', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                });
                if (res.ok) {
                    const data = await res.json();
                    const count = data.count || 0;
                    const badge = document.getElementById('notifBadge');
                    const headerBadge = document.getElementById('notifBadgeHeader');
                    const mobileBadge = document.querySelector('.notification-badge-mobile');
                    const bellBtn = document.querySelector('.notification-bell-btn');

                    [badge, headerBadge].forEach(el => {
                        if (el) {
                            el.textContent = count || '';
                            el.style.display = count > 0 ? '' : 'none';
                        }
                    });

                    if (mobileBadge) {
                        mobileBadge.textContent = count > 99 ? '99+' : (count || '');
                        mobileBadge.style.display = count > 0 ? '' : 'none';
                    }

                    // Trigger bell animation when count increases
                    if (count > prevCount && bellBtn) {
                        bellBtn.classList.add('has-unread');
                        setTimeout(() => bellBtn.classList.remove('has-unread'), 1500);
                    }
                    if (bellBtn && count > 0) {
                        bellBtn.classList.add('has-unread');
                    } else if (bellBtn) {
                        bellBtn.classList.remove('has-unread');
                    }
                    prevCount = count;

                    if (count > 0 && document.title.indexOf(')') === -1) {
                        document.title = '(' + count + ') ' + document.title.replace(/^\(\d+\)\s*/, '');
                    } else if (!count) {
                        document.title = document.title.replace(/^\(\d+\)\s*/, '');
                    }
                }
            } catch (e) {}
        }

        // Mark notification as read inline when clicking
        document.addEventListener('click', async function(e) {
            const item = e.target.closest('.notif-item');
            if (!item) return;
            const notifId = item.dataset.notifId;
            if (!notifId || item.dataset.reading) return;
            item.dataset.reading = '1';
            try {
                await fetch('/notifications/' + notifId + '/read', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });
                // Visually mark as read without waiting for server
                item.classList.remove('bg-light');
                const dot = item.querySelector('.badge.bg-primary');
                if (dot) dot.remove();
            } catch (e) {}
        });

        // Refresh dropdown content on open
        document.getElementById('headerNotifContainer')?.addEventListener('show.bs.dropdown', function() {
            pollUnread();
        });

        setInterval(pollUnread, 30000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) pollUnread();
        });

        // Initial bell state
        pollUnread();
    })();
    </script>
    @endauth
</body>

</html>
