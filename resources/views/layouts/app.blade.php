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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div id="toast-container" class="toast-container"></div>

    @php $unreadCount = 0; @endphp

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-question-circle text-warning display-4 mb-3 d-block"></i>
                    <p class="confirm-message mb-0 fw-semibold">Are you sure?</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-confirm-no" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-confirm-yes">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div class="offcanvas offcanvas-start mobile-nav-drawer" tabindex="-1" id="mobileNavDrawer" aria-labelledby="mobileNavDrawerLabel">
        <div class="offcanvas-header border-bottom">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none" id="mobileNavDrawerLabel">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover;">
                <span class="fw-bold fs-5 app-text-primary">DevDoko</span>
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
            <div class="flex-grow-1 overflow-auto py-2">
                <div class="px-2 mb-3">
                    <div class="small app-text-muted text-uppercase fw-semibold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Main</div>
                    <a href="{{ route('home') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        @if(request()->routeIs('home'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5 me-3"></i>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('search') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
                        @if(request()->routeIs('search'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-search fs-5 me-3"></i>
                        <span>Search</span>
                    </a>
                    <a href="{{ route('explore') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('explore') ? 'active' : '' }}">
                        @if(request()->routeIs('explore'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-compass fs-5 me-3"></i>
                        <span>Explore</span>
                    </a>
                    <a href="{{ route('posts.index', ['type' => 'article']) }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('posts.index') && request('type') === 'article' ? 'active' : '' }}">
                        @if(request()->routeIs('posts.index') && request('type') === 'article')<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-file-text fs-5 me-3"></i>
                        <span>Articles</span>
                    </a>
                    <a href="{{ route('jobs.index') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
                        @if(request()->routeIs('jobs.*'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-briefcase fs-5 me-3"></i>
                        <span>Jobs</span>
                    </a>
                    <a href="{{ route('messages.index') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                        @if(request()->routeIs('messages.*'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }} fs-5 me-3"></i>
                        <span>Messages</span>
                    </a>
                    @auth
                    <a href="{{ route('notifications.index') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        @if(request()->routeIs('notifications.index'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-bell{{ request()->routeIs('notifications.*') ? '-fill' : '' }} fs-5 me-3"></i>
                        <span class="flex-grow-1">Notifications</span>
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
                    </a>
                    @endauth
                    <a href="{{ route('posts.create') }}" data-composer-open="image"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                        @if(request()->routeIs('posts.create'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }} fs-5 me-3"></i>
                        <span>Create</span>
                    </a>
                    <a href="{{ route('posts.drafts') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('posts.drafts') ? 'active' : '' }}">
                        @if(request()->routeIs('posts.drafts'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-file-earmark-lock{{ request()->routeIs('posts.drafts') ? '-fill' : '' }} fs-5 me-3"></i>
                        <span>My Drafts</span>
                    </a>
                    <a href="{{ route('developers.index') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('developers.*') ? 'active' : '' }}">
                        @if(request()->routeIs('developers.*'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-people fs-5 me-3"></i>
                        <span>Developers</span>
                    </a>
                </div>

                <div class="px-2 mb-3">
                    <div class="small app-text-muted text-uppercase fw-semibold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Community</div>
                    <a href="{{ route('groups.index') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                        @if(request()->routeIs('groups.*'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-people-fill fs-5 me-3"></i>
                        <span>Groups</span>
                    </a>
                </div>

                @auth
                <div class="px-2 mb-3">
                    <div class="small app-text-muted text-uppercase fw-semibold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Marketplace</div>
                    <a href="{{ route('marketplace.index') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('marketplace.index') ? 'active' : '' }}">
                        @if(request()->routeIs('marketplace.index'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-shop fs-5 me-3"></i>
                        <span>Browse</span>
                    </a>
                    <a href="{{ route('marketplace.my-listings') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('marketplace.my-listings') ? 'active' : '' }}">
                        @if(request()->routeIs('marketplace.my-listings'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-bag fs-5 me-3"></i>
                        <span class="flex-grow-1">My Listings</span>
                    </a>
                    <a href="{{ route('marketplace.saved') }}"
                        class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('marketplace.saved') ? 'active' : '' }}">
                        @if(request()->routeIs('marketplace.saved'))<span class="nav-active-indicator"></span>@endif
                        <i class="bi bi-bookmark fs-5 me-3"></i>
                        <span>Saved</span>
                    </a>
                    <a href="{{ route('marketplace.create') }}"
                        class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 mt-1 app-nav-item app-accent-text">
                        <i class="bi bi-plus-circle fs-5 me-3"></i>
                        <span>Sell Something</span>
                    </a>
                </div>
                @endauth
            </div>

            <div class="p-3 border-top">
                <button type="button" class="btn app-btn-outline w-100 mb-2" onclick="DevDoko.toggleTheme()">
                    <i class="bi bi-moon-fill me-2"></i> Theme
                </button>
                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 mb-2 app-nav-item">
                    <i class="bi bi-shield-check fs-5 me-3"></i>
                    <span>Admin</span>
                </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="d-flex align-items-center w-100 px-3 py-2 app-text-primary bg-transparent border-0 rounded-3 app-nav-item">
                        <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
                @endauth
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
        $unreadCount = auth()->user()->unreadNotificationsCount();
    @endphp
    @endauth

    <!-- Top Header Bar -->
    <header class="top-header d-flex align-items-center justify-content-between px-2 px-md-3 py-0">
        <div class="d-flex align-items-center flex-grow-1 min-width-0">
            <button type="button" class="btn btn-sm d-md-none me-2 mobile-nav-toggle app-nav-toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileNavDrawer" aria-controls="mobileNavDrawer" aria-label="Open navigation menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none fw-bold fs-6 app-text-primary">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko" class="rounded-circle me-2 d-md-none"
                    style="width: 32px; height: 32px; object-fit: cover;">
                <span class="d-md-none">DevDoko</span>
            </a>
            {{-- Desktop search bar --}}
            <div class="d-none d-md-flex flex-grow-1 ms-4 position-relative" style="max-width: 420px;" id="quickSearchWrap">
                <div class="input-group input-group-sm">
                    <span class="input-group-text app-input-icon border-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control app-search-input border-0" id="quickSearchInput"
                        placeholder="Search developers, posts, tags..." autocomplete="off" style="border-radius: 10px;">
                </div>
                <div class="app-dropdown shadow border-0 rounded-3 d-none" id="quickSearchResults"
                    style="position: absolute; top: 100%; left: 0; right: 0; margin-top: 6px; max-height: 70vh; overflow-y: auto; z-index: 1050;"></div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-1 gap-md-2 flex-shrink-0">
            @auth
            <div class="dropdown" id="headerNotifContainer">
                <a href="#" class="btn btn-sm position-relative app-icon-btn border-0 notification-bell-btn" data-bs-toggle="dropdown" aria-expanded="false" id="headerNotifBell">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge-header" id="notifBadgeHeader" style="font-size: 9px;">{{ $unreadCount }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 app-dropdown" id="notifDropdown">
                    <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                        <strong class="fs-6 app-text-primary"><i class="bi bi-bell-fill me-2 app-accent-text"></i>Notifications</strong>
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link app-accent-text text-decoration-none p-0 small">Mark all read</button>
                        </form>
                    </div>
                    <div class="dropdown-divider my-0"></div>
                    @forelse($recentNotifications as $notif)
                    <a href="{{ $notif->action_url ?? '#' }}" class="dropdown-item px-3 py-3 notif-item {{ !$notif->read_at ? 'app-notif-unread' : '' }}" data-notif-id="{{ $notif->id }}">
                        <div class="d-flex gap-2 align-items-start">
                            <div class="position-relative flex-shrink-0">
                                @if($notif->fromUser)
                                <img src="{{ $notif->fromUser->avatar_url }}" alt="" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                                @else
                                <div class="rounded-circle app-bg-secondary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person app-text-muted" style="font-size: 14px;"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <small class="app-text-muted" style="font-size: 11px; white-space: nowrap;">{{ $notif->time_ago }}</small>
                                    @if(!$notif->read_at)
                                    <span class="badge bg-primary rounded-pill" style="font-size: 8px; width: 8px; height: 8px; padding: 0;"></span>
                                    @endif
                                </div>
                                <div class="app-text-primary" style="font-size: 13px; line-height: 1.3; word-break: break-word;">{{ Str::limit($notif->message ?? 'New notification', 80) }}</div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-4 px-3">
                        <i class="bi bi-bell-slash app-text-muted" style="font-size: 24px;"></i>
                        <p class="app-text-muted small mt-2 mb-0">No notifications yet</p>
                    </div>
                    @endforelse
                    <div class="dropdown-divider my-0"></div>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center py-2 fw-semibold app-accent-text">
                        View All Notifications <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endauth
            <button id="darkModeToggleHeader" onclick="DevDoko.toggleTheme()"
                class="btn btn-sm app-icon-btn border-0"
                aria-label="Toggle dark mode">
                <i class="bi bi-moon-fill fs-6"></i>
            </button>
            @auth
            <a href="{{ route('profile.show', auth()->user()->profile->username ?? '') }}" class="d-none d-md-flex align-items-center text-decoration-none ms-1">
                <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                    class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; border: 2px solid var(--brand-purple);">
            </a>
            @endauth
        </div>
    </header>

    <!-- Desktop Sidebar -->
    <aside class="d-none d-md-flex flex-column app-sidebar border-end vh-100 position-fixed">
        <!-- Logo -->
        <div class="p-3 border-bottom">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko" class="rounded-circle"
                    style="width: 40px; height: 40px; object-fit: cover;">
                <span class="ms-2 fw-bold fs-5 app-text-primary">DevDoko</span>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex-grow-1 overflow-auto py-2">
            <div class="px-2 mb-3">
                <div class="small app-text-muted text-uppercase fw-semibold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Main</div>
                <a href="{{ route('home') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    @if(request()->routeIs('home'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('search') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
                    @if(request()->routeIs('search'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-search fs-5 me-3"></i>
                    <span>Search</span>
                </a>
                <a href="{{ route('explore') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('explore') ? 'active' : '' }}">
                    @if(request()->routeIs('explore'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-compass fs-5 me-3"></i>
                    <span>Explore</span>
                </a>
                <a href="{{ route('jobs.index') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
                    @if(request()->routeIs('jobs.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-briefcase fs-5 me-3"></i>
                    <span>Jobs</span>
                </a>
                <a href="{{ route('messages.index') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    @if(request()->routeIs('messages.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Messages</span>
                </a>
                @auth
                <a href="{{ route('notifications.index') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    @if(request()->routeIs('notifications.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-bell{{ request()->routeIs('notifications.*') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Notifications</span>
                    <span class="badge bg-danger rounded-pill ms-auto" id="notifBadge">{{ $unreadCount }}</span>
                </a>
                @endauth
                <a href="{{ route('posts.create') }}" data-composer-open="image"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                    @if(request()->routeIs('posts.create'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Create</span>
                </a>
                <a href="{{ route('developers.index') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('developers.*') ? 'active' : '' }}">
                    @if(request()->routeIs('developers.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-people fs-5 me-3"></i>
                    <span>Developers</span>
                </a>
            </div>

            <!-- Groups -->
            <div class="px-2 mb-3">
                <div class="small app-text-muted text-uppercase fw-semibold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Community</div>
                <a href="{{ route('groups.index') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                    @if(request()->routeIs('groups.*'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-people-fill fs-5 me-3"></i>
                    <span>Groups</span>
                </a>
            </div>

            @auth
            <!-- Marketplace -->
            <div class="px-2 mb-3">
                <div class="small app-text-muted text-uppercase fw-semibold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Marketplace</div>
                <a href="{{ route('marketplace.index') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('marketplace.index') ? 'active' : '' }}">
                    @if(request()->routeIs('marketplace.index'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-shop fs-5 me-3"></i>
                    <span>Browse</span>
                </a>
                <a href="{{ route('marketplace.my-listings') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('marketplace.my-listings') ? 'active' : '' }}">
                    @if(request()->routeIs('marketplace.my-listings'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-bag fs-5 me-3"></i>
                    <span class="flex-grow-1">My Listings</span>
                </a>
                <a href="{{ route('marketplace.saved') }}"
                    class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 position-relative app-nav-item {{ request()->routeIs('marketplace.saved') ? 'active' : '' }}">
                    @if(request()->routeIs('marketplace.saved'))<span class="nav-active-indicator"></span>@endif
                    <i class="bi bi-bookmark fs-5 me-3"></i>
                    <span>Saved</span>
                </a>
                <a href="{{ route('marketplace.create') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 mt-1 app-nav-item app-accent-text">
                    <i class="bi bi-plus-circle fs-5 me-3"></i>
                    <span>Sell Something</span>
                </a>
            </div>
            @endauth
        </div>

        <!-- Bottom -->
        <div class="p-3 border-top">
            @auth
            <a href="{{ route('blocks.index') }}"
                class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 mb-1 app-nav-item {{ request()->routeIs('blocks.*') ? 'active' : '' }}">
                <i class="bi bi-slash-circle fs-5 me-3"></i>
                <span>Blocked</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="d-flex align-items-center px-3 py-2 app-text-primary text-decoration-none rounded-3 mb-1 app-nav-item">
                <i class="bi bi-shield-check fs-5 me-3"></i>
                <span>Admin</span>
            </a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="d-flex align-items-center w-100 px-3 py-2 app-text-primary bg-transparent border-0 rounded-3 app-nav-item">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    <span>Logout</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

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
    <nav class="d-md-none fixed-bottom app-bottom-nav border-top py-2 px-3">
        <div class="d-flex justify-content-around align-items-center">
            <a href="{{ route('home') }}" class="app-bottom-nav-item text-center {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5"></i>
                <small>Home</small>
            </a>
            <a href="{{ route('search') }}" class="app-bottom-nav-item text-center {{ request()->routeIs('search') ? 'active' : '' }}">
                <i class="bi bi-search fs-5"></i>
                <small>Search</small>
            </a>
            <a href="{{ route('posts.create') }}" data-composer-open="image" class="app-bottom-nav-item text-center app-accent-text">
                <div class="app-create-btn-mobile mx-auto">
                    <i class="bi bi-plus-lg fs-5"></i>
                </div>
                <small>Create</small>
            </a>
            <a href="{{ route('messages.index') }}" class="app-bottom-nav-item text-center {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }} fs-5"></i>
                <small>Chat</small>
            </a>
            @auth
            <a href="{{ route('profile.show', auth()->user()->profile->username ?? '') }}" class="app-bottom-nav-item text-center">
                @if(auth()->user()->profile->avatar ?? null)
                <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle"
                    style="width: 22px; height: 22px; object-fit: cover;">
                @else
                <i class="bi bi-person-circle fs-5"></i>
                @endif
                <small>Profile</small>
            </a>
            @else
            <a href="{{ route('login') }}" class="app-bottom-nav-item text-center">
                <i class="bi bi-person-circle fs-5"></i>
                <small>Login</small>
            </a>
            @endauth
        </div>
    </nav>

    @auth
    @include('posts.partials.composer')
    @endauth

    <script>
    (function() {
        const input = document.getElementById('quickSearchInput');
        const results = document.getElementById('quickSearchResults');
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
            if (!e.target.closest('#quickSearchWrap')) results.classList.add('d-none');
        });
    })();
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    @auth
    <script>
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
                    const bellBtn = document.querySelector('.notification-bell-btn');

                    [badge, headerBadge].forEach(el => {
                        if (el) {
                            el.textContent = count || '';
                            el.style.display = count > 0 ? '' : 'none';
                        }
                    });

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
                item.classList.remove('app-notif-unread');
                const dot = item.querySelector('.badge.bg-primary');
                if (dot) dot.remove();
            } catch (e) {}
        });

        document.getElementById('headerNotifContainer')?.addEventListener('show.bs.dropdown', function() {
            pollUnread();
        });

        setInterval(pollUnread, 30000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) pollUnread();
        });

        pollUnread();
    })();
    </script>
    @endauth
</body>

</html>
