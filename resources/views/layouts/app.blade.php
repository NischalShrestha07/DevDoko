<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevDoko') </title>

    <!-- Bootstrap 5 CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        :root {
            --primary-color: #0095f6;
            --border-color: #dbdbdb;
            --bg-color: #fafafa;
            --text-color: #262626;
        }

        body {
            background-color: var(--bg-color);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        /* Main Layout */
        .app-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px 0;
        }

        /* Left Sidebar - Navigation */
        .sidebar-left {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 245px;
            border-right: 1px solid var(--border-color);
            padding: 30px 20px;
            background: white;
            z-index: 10;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            padding-left: 7px;
        }

        .logo a {
            color: var(--text-color);
            text-decoration: none;
        }

        .logo a:hover {
            color: var(--text-color);
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
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #f2f2f2;
            color: var(--text-color);
        }

        .nav-icon {
            font-size: 24px;
            margin-right: 16px;
            width: 24px;
            text-align: center;
        }

        .nav-text {
            font-size: 16px;
            font-weight: 400;
        }

        .nav-link.active .nav-text {
            font-weight: 600;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 245px;
            flex: 1;
            max-width: 630px;
            min-height: 100vh;
        }

        /* Right Sidebar - Suggestions */
        .sidebar-right {
            position: fixed;
            top: 30px;
            right: 20px;
            width: 319px;
        }

        /* Mobile Navigation */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid var(--border-color);
            z-index: 100;
        }

        .mobile-nav-items {
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
        }

        .mobile-nav-icon {
            font-size: 24px;
            color: var(--text-color);
        }

        /* Responsive */
        @media (max-width: 1260px) {
            .sidebar-right {
                display: none;
            }
        }

        @media (max-width: 1000px) {
            .sidebar-left {
                width: 72px;
                padding: 30px 12px;
            }

            .nav-text {
                display: none;
            }

            .nav-icon {
                margin-right: 0;
            }

            .logo span {
                display: none;
            }

            .main-content {
                margin-left: 72px;
            }
        }

        @media (max-width: 768px) {
            .sidebar-left {
                display: none;
            }

            .main-content {
                margin-left: 0;
                margin-bottom: 60px;
                padding: 0 16px;
            }

            .mobile-nav {
                display: block;
            }
        }

        /* Post Card Styles */
        .post-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .post-header {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .post-user {
            display: flex;
            align-items: center;
        }

        .post-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .post-username {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-color);
            text-decoration: none;
        }

        .post-username:hover {
            color: var(--text-color);
            text-decoration: underline;
        }

        .post-more {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-color);
            cursor: pointer;
        }

        .post-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .post-actions {
            padding: 12px 16px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .action-left {
            display: flex;
            gap: 16px;
        }

        .post-action-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-color);
            cursor: pointer;
            padding: 0;
        }

        .post-action-btn.liked {
            color: #ed4956;
        }

        .post-likes {
            padding: 0 16px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .post-caption {
            padding: 0 16px;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .post-caption-user {
            font-weight: 600;
            margin-right: 4px;
        }

        .post-comments {
            padding: 0 16px;
            color: #8e8e8e;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .post-timestamp {
            padding: 0 16px 12px;
            color: #8e8e8e;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 0;
        }

        .add-comment {
            padding: 12px 16px;
            border-top: 1px solid var(--border-color);
        }

        .comment-form {
            display: flex;
            align-items: center;
        }

        .comment-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            padding: 0;
        }

        .comment-submit {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
            opacity: 0.3;
            cursor: default;
        }

        .comment-submit.active {
            opacity: 1;
            cursor: pointer;
        }

        /* Stories */
        .stories-container {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .story-item {
            display: inline-block;
            text-align: center;
            margin-right: 20px;
            cursor: pointer;
        }

        .story-avatar {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
            margin-bottom: 6px;
        }

        .story-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }

        .story-username {
            font-size: 12px;
            color: var(--text-color);
        }

        /* Create Post Card */
        .create-post-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .create-post-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .create-post-input {
            flex: 1;
            margin-left: 12px;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            background: #fafafa;
            font-size: 14px;
            color: #8e8e8e;
            cursor: pointer;
        }

        .create-post-options {
            display: flex;
            justify-content: space-around;
            border-top: 1px solid var(--border-color);
            padding-top: 16px;
        }

        .create-post-option {
            display: flex;
            align-items: center;
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
        }

        .create-post-option i {
            margin-right: 8px;
            font-size: 20px;
        }

        .create-post-option.photo i {
            color: #45bd62;
        }

        .create-post-option.video i {
            color: #f02849;
        }

        .create-post-option.code i {
            color: #f7b928;
        }

        .create-post-option.article i {
            color: #0095f6;
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .profile-info {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .profile-stats {
            display: flex;
            justify-content: space-around;
            text-align: center;
            padding: 16px 0;
            border-top: 1px solid var(--border-color);
        }

        .stat-value {
            font-weight: 600;
            font-size: 16px;
            display: block;
        }

        .stat-label {
            font-size: 12px;
            color: #8e8e8e;
        }

        /* Suggestions */
        .suggestions-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .suggestions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .suggestion-user {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .suggestion-info {
            flex: 1;
            margin-left: 12px;
        }

        .suggestion-username {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-color);
            text-decoration: none;
        }

        .suggestion-text {
            font-size: 12px;
            color: #8e8e8e;
        }

        .follow-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
        }

        /* Footer Links */
        .footer-links {
            margin-top: 16px;
        }

        .footer-links a {
            color: #c7c7c7;
            font-size: 12px;
            text-decoration: none;
            margin-right: 8px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .footer-copyright {
            color: #c7c7c7;
            font-size: 12px;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <!-- Desktop Navigation Sidebar -->
    <div class="sidebar-left">
        <div class="logo">
            <a href="{{ route('home') }}">
                {{-- <i class="bi bi-code-slash"></i> --}}
                <img src="{{ asset('/assets/devdoko.png') }}" style="border-radius: 40px; border: 2px solid black;"
                    width="70" alt="">
                <span>DevDoko</span>
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
                    <i class="bi bi-chat nav-icon"></i>
                    <span class="nav-text">Messages</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('notifications.index') }}"
                    class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-heart nav-icon"></i>
                    <span class="nav-text">Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('posts.create') }}"
                    class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-square nav-icon"></i>
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

        <div style="position: absolute; bottom: 30px; width: calc(100% - 40px);">
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
    <div class="app-container">
        <div class="main-content">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>

        <!-- Right Sidebar (Desktop Only) -->
        <div class="sidebar-right">
            <!-- Current User Profile -->
            <div class="profile-card">
                <div class="profile-info">
                    <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                    <div style="margin-left: 12px;">
                        <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                            class="text-decoration-none text-dark fw-bold d-block">
                            {{ auth()->user()->profile->username }}
                        </a>
                        <small class="text-muted">{{ auth()->user()->name }}</small>
                    </div>
                </div>
                <div class="profile-stats">
                    <div>
                        <span class="stat-value">{{ auth()->user()->posts->count() ?? 0 }}</span>
                        <span class="stat-label">Posts</span>
                    </div>
                    <div>
                        <span class="stat-value">{{ auth()->user()->followers->count() ?? 0 }}</span>
                        <span class="stat-label">Followers</span>
                    </div>
                    <div>
                        <span class="stat-value">{{ auth()->user()->following->count() ?? 0 }}</span>
                        <span class="stat-label">Following</span>
                    </div>
                </div>
            </div>

            <!-- Suggestions -->
            @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
            <div class="suggestions-card">
                <div class="suggestions-header">
                    <span class="fw-bold text-muted">Suggestions For You</span>
                    <a href="{{ route('explore') }}" class="text-decoration-none fw-bold small">See All</a>
                </div>

                @foreach($suggestedUsers as $user)
                <div class="suggestion-user">
                    <a href="{{ route('profile.show', $user->profile->username) }}" class="text-decoration-none">
                        <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle"
                            style="width: 32px; height: 32px; object-fit: cover;">
                    </a>
                    <div class="suggestion-info">
                        <a href="{{ route('profile.show', $user->profile->username) }}" class="suggestion-username">
                            {{ $user->profile->username }}
                        </a>
                        <div class="suggestion-text">
                            {{ $user->followers->count() ?? 0 }} followers
                        </div>
                    </div>
                    <form action="{{ route('users.follow', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="follow-btn">Follow</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Footer Links -->
            <div class="footer-links">
                <div class="footer-copyright text-center">
                    © {{ date('Y') }} DevDoko
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <div class="mobile-nav-items">
            <a href="{{ route('home') }}" class="mobile-nav-icon">
                <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }}"></i>
            </a>
            <a href="{{ route('explore') }}" class="mobile-nav-icon">
                <i class="bi bi-search"></i>
            </a>
            <a href="{{ route('posts.create') }}" class="mobile-nav-icon">
                <i class="bi bi-plus-square"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="mobile-nav-icon">
                <i class="bi bi-heart"></i>
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
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>