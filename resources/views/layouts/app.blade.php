<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevDoko')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Highlight.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
</head>

<body class="bg-light"
    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #262626;">

    <!-- Desktop Navigation Sidebar -->
    <div class="d-none d-md-block position-fixed start-0 top-0 h-100 bg-white border-end"
        style="width: 244px; z-index: 1000; padding: 30px 12px; border-color: #dbdbdb !important;">

        <!-- Logo -->
        <div class="d-flex align-items-center mb-4 px-2">
            <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko"
                style="width: 70px; border: 2px solid #000; border-radius: 50%;">
            <span class="ms-2 fw-bold" style="font-size: 24px; color: #262626;">DevDoko</span>
        </div>

        <!-- Navigation Menu -->
        <ul class="list-unstyled">
            <!-- Home -->
            <li class="mb-2">
                <a href="{{ route('home') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('home') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('home') ? '#f2f2f2' : 'transparent' }}'">
                    <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }}"
                        style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Home</span>
                </a>
            </li>

            <!-- Search -->
            <li class="mb-2">
                <a href="{{ route('search') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('search') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('search') ? '#f2f2f2' : 'transparent' }}'">
                    <i class="bi bi-search" style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Search</span>
                </a>
            </li>

            <!-- Explore -->
            <li class="mb-2">
                <a href="{{ route('explore') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('explore') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('explore') ? '#f2f2f2' : 'transparent' }}'">
                    <i class="bi bi-compass" style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Explore</span>
                </a>
            </li>

            <!-- Messages -->
            <li class="mb-2">
                <a href="{{ route('messages.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('messages.*') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('messages.*') ? '#f2f2f2' : 'transparent' }}'">
                    <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }}"
                        style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Messages</span>
                </a>
            </li>

            <!-- Notifications -->
            <li class="mb-2">
                <a href="{{ route('notifications.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('notifications.*') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('notifications.*') ? '#f2f2f2' : 'transparent' }}'">
                    <i class="bi bi-heart{{ request()->routeIs('notifications.*') ? '-fill' : '' }}"
                        style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Notifications</span>
                    @php
                    $unreadCount = auth()->user()->notifications()->where('read_at', null)->count();
                    @endphp
                    @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto rounded-pill" style="font-size: 10px;">{{ $unreadCount
                        }}</span>
                    @endif
                </a>
            </li>

            <!-- Create -->
            <li class="mb-2">
                <a href="{{ route('posts.create') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('posts.create') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('posts.create') ? '#f2f2f2' : 'transparent' }}'">
                    <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }}"
                        style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Create</span>
                </a>
            </li>

            <!-- Profile -->
            <li class="mb-2">
                <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('profile.show') ? 'bg-light fw-semibold' : '' }}"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='{{ request()->routeIs('profile.show') ? '#f2f2f2' : 'transparent' }}'">
                    @if(auth()->user()->profile->avatar)
                    <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle"
                        style="width: 24px; height: 24px; object-fit: cover;">
                    @else
                    <i class="bi bi-person-circle" style="font-size: 24px; width: 24px;"></i>
                    @endif
                    <span class="ms-3" style="font-size: 16px;">Profile</span>
                </a>
            </li>

            <!-- Groups Dropdown -->
            <li class="mb-2">
                <div class="dropdown">
                    <a href="#"
                        class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 dropdown-toggle"
                        style="color: #262626; transition: all 0.2s;" data-bs-toggle="dropdown" aria-expanded="false"
                        onmouseover="this.style.backgroundColor='#f2f2f2'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <i class="bi bi-people-fill" style="font-size: 24px; width: 24px;"></i>
                        <span class="ms-3" style="font-size: 16px;">Groups</span>
                    </a>
                    <ul class="dropdown-menu w-100 mt-1 border-0 shadow-sm" style="border-radius: 8px;">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('groups.index') }}">
                                <i class="bi bi-compass me-2"></i> Discover Groups
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('groups.my-groups') }}">
                                <i class="bi bi-bookmark-check me-2"></i> My Groups
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('groups.create') }}">
                                <i class="bi bi-plus-circle me-2"></i> Create Group
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Pending Requests Badge -->
            @auth
            @php
            $pendingRequests = \App\Models\Group::whereHas('members', function($q) {
            $q->where('user_id', auth()->id())
            ->where('status', 'pending');
            })->count();
            @endphp
            @if($pendingRequests > 0)
            <li class="mb-2">
                <a href="{{ route('groups.my-groups') }}"
                    class="d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 position-relative"
                    style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <i class="bi bi-bell" style="font-size: 24px; width: 24px;"></i>
                    <span class="ms-3" style="font-size: 16px;">Requests</span>
                    <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingRequests }}</span>
                </a>
            </li>
            @endif
            @endauth

            <!-- Logout -->
            <li class="position-absolute bottom-0 mb-4" style="width: calc(100% - 24px);">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="d-flex align-items-center w-100 px-3 py-2 text-start bg-transparent border-0 rounded-3"
                        style="color: #262626; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f2f2f2'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <i class="bi bi-box-arrow-right" style="font-size: 24px; width: 24px;"></i>
                        <span class="ms-3" style="font-size: 16px;">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="content-area" style="margin-left: 244px; min-height: 100vh;">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"
                style="border-radius: 12px; background-color: #d4edda; color: #155724;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert"
                style="border-radius: 12px; background-color: #f8d7da; color: #721c24;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        @yield('content')
    </div>

    <!-- Mobile Navigation -->
    <nav class="d-md-none position-fixed bottom-0 start-0 end-0 bg-white border-top py-3 px-4"
        style="z-index: 1000; border-color: #dbdbdb !important;">
        <div class="d-flex justify-content-around align-items-center">
            <!-- Home -->
            <a href="{{ route('home') }}" class="text-decoration-none" style="color: #262626;">
                <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-4"></i>
            </a>

            <!-- Search -->
            <a href="{{ route('search') }}" class="text-decoration-none" style="color: #262626;">
                <i class="bi bi-search fs-4"></i>
            </a>

            <!-- Create -->
            <a href="{{ route('posts.create') }}" class="text-decoration-none" style="color: #262626;">
                <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }} fs-4"></i>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" class="text-decoration-none position-relative"
                style="color: #262626;">
                <i class="bi bi-heart{{ request()->routeIs('notifications.*') ? '-fill' : '' }} fs-4"></i>
                @php
                $unreadCount = auth()->user()->notifications()->where('read_at', null)->count();
                @endphp
                @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 10px;">{{ $unreadCount }}</span>
                @endif
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.show', auth()->user()->profile->username) }}" class="text-decoration-none">
                @if(auth()->user()->profile->avatar)
                <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle"
                    style="width: 28px; height: 28px; object-fit: cover;">
                @else
                <i class="bi bi-person-circle fs-4" style="color: #262626;"></i>
                @endif
            </a>
        </div>
    </nav>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Highlight.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof hljs !== 'undefined') {
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            }

            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
</body>

</html>