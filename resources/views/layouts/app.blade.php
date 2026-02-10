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

    <style>
        :root {
            --primary: #0095f6;
            --secondary: #8e8e8e;
            --border: #dbdbdb;
            --bg-light: #fafafa;
            --text-dark: #262626;
            --text-light: #8e8e8e;
        }

        body {
            background-color: var(--bg-light);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--text-dark);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Main Layout */
        .main-container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Navigation Sidebar */
        .sidebar-left {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 244px;
            border-right: 1px solid var(--border);
            background: white;
            z-index: 1000;
            padding: 30px 12px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-left: 12px;
        }

        .logo a {
            color: var(--text-dark);
            text-decoration: none;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background-color: #f2f2f2;
        }

        .nav-link.active {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .nav-icon {
            font-size: 24px;
            margin-right: 16px;
            width: 24px;
            text-align: center;
        }

        .nav-text {
            font-size: 16px;
        }

        /* Content Area */
        .content-area {
            margin-left: 244px;
            padding: 0;
            min-height: 100vh;
        }

        /* Mobile Navigation */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid var(--border);
            z-index: 1000;
            padding: 12px 0;
        }

        .mobile-nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .mobile-nav-icon {
            font-size: 24px;
            color: var(--text-dark);
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 1260px) {
            .sidebar-left {
                width: 72px;
            }

            .nav-text,
            .logo span {
                display: none;
            }

            .nav-icon {
                margin-right: 0;
            }

            .content-area {
                margin-left: 72px;
            }
        }

        @media (max-width: 768px) {
            .sidebar-left {
                display: none;
            }

            .content-area {
                margin-left: 0;
                padding-bottom: 60px;
            }

            .mobile-nav {
                display: block;
            }
        }

        /* Post Styles */
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .post-content pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin-bottom: 1rem;
        }

        .post-content code {
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        /* Story Circles */
        .story-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        .story-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid white;
            overflow: hidden;
        }

        /* Card Styles */
        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Badge Styles */
        .badge {
            border-radius: 10px;
            padding: 4px 8px;
            font-weight: 500;
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        /* Form Controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 149, 246, 0.25);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            border-radius: 4px;
            margin: 2px;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
        }

        /* Pagination */
        .pagination .page-link {
            border-radius: 6px;
            margin: 0 2px;
        }

        /* Alert Styles */
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>

<body>
    <!-- Desktop Navigation Sidebar -->
    <div class="sidebar-left d-none d-md-block">
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko"
                    style="width: 70px; border:2px solid black; border-radius: 50%;">
                <span class="ms-2">DevDoko</span>
            </a>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} nav-icon"></i>
                    <span class="nav-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('search') }}" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                    <i class="bi bi-search nav-icon"></i>
                    <span class="nav-text">Search</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('explore') }}" class="nav-link {{ request()->routeIs('explore') ? 'active' : '' }}">
                    <i class="bi bi-compass nav-icon"></i>
                    <span class="nav-text">Explore</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('messages.index') }}"
                    class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }} nav-icon"></i>
                    <span class="nav-text">Messages</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('notifications.index') }}"
                    class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-heart{{ request()->routeIs('notifications.*') ? '-fill' : '' }} nav-icon"></i>
                    <span class="nav-text">Notifications</span>
                    @php
                    $unreadCount = auth()->user()->notifications()->where('read_at', null)->count();
                    @endphp
                    @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto" style="font-size: 10px;">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('posts.create') }}"
                    class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }} nav-icon"></i>
                    <span class="nav-text">Create</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                    class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                    @if(auth()->user()->profile->avatar)
                    <img src="{{ auth()->user()->profile->avatar_url }}" class="nav-icon rounded-circle"
                        style="width: 24px; height: 24px; object-fit: cover;">
                    @else
                    <i class="bi bi-person-circle nav-icon"></i>
                    @endif
                    <span class="nav-text">Profile</span>
                </a>
            </li>
        </ul>

        <div style="position: absolute; bottom: 30px; width: calc(100% - 24px);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 text-start bg-transparent border-0 p-0">
                    <i class="bi bi-box-arrow-right nav-icon"></i>
                    <span class="nav-text">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav d-md-none">
        <div class="mobile-nav-items">
            <a href="{{ route('home') }}" class="mobile-nav-icon">
                <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }}"></i>
            </a>
            <a href="{{ route('search') }}" class="mobile-nav-icon">
                <i class="bi bi-search"></i>
            </a>
            <a href="{{ route('posts.create') }}" class="mobile-nav-icon">
                <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }}"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="mobile-nav-icon position-relative">
                <i class="bi bi-heart{{ request()->routeIs('notifications.*') ? '-fill' : '' }}"></i>
                @php
                $unreadCount = auth()->user()->notifications()->where('read_at', null)->count();
                @endphp
                @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 8px;">
                    {{ $unreadCount }}
                </span>
                @endif
            </a>
            <a href="{{ route('profile.show', auth()->user()->profile->username) }}" class="mobile-nav-icon">
                @if(auth()->user()->profile->avatar)
                <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle"
                    style="width: 24px; height: 24px; object-fit: cover;">
                @else
                <i class="bi bi-person-circle"></i>
                @endif
            </a>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Highlight.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize highlight.js
            if (typeof hljs !== 'undefined') {
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>