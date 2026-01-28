<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DevDoko') - Developer Social Network</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 245px;
            border-right: 1px solid #dbdbdb;
            padding: 20px 12px;
            background: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .main-content {
            margin-left: 245px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }

        .suggestions-sidebar {
            position: fixed;
            top: 100px;
            right: 20px;
            width: 319px;
        }

        .post-card {
            border: 1px solid #dbdbdb;
            border-radius: 8px;
            background: white;
            margin-bottom: 24px;
        }

        .post-image {
            width: 100%;
            max-height: 585px;
            object-fit: cover;
        }

        .story-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            padding: 2px;
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        .footer-links {
            font-size: 12px;
            color: #c7c7c7;
            line-height: 1.8;
        }

        .footer-links a {
            text-decoration: none;
            color: #c7c7c7;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1264px) {
            .suggestions-sidebar {
                display: none;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 0;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 0;
                margin-bottom: 60px;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Bottom Navigation -->
    @auth
    <nav class="navbar navbar-light bg-white d-lg-none fixed-bottom border-top">
        <div class="container-fluid justify-content-around">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-house-door-fill fs-4"></i>
            </a>
            <a href="{{ route('explore') }}" class="text-dark">
                <i class="bi bi-search fs-4"></i>
            </a>
            <a href="{{ route('posts.create') }}" class="text-dark">
                <i class="bi bi-plus-square fs-4"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="text-dark position-relative">
                <i class="bi bi-heart fs-4"></i>
            </a>
            <a href="{{ route('profile.show', auth()->user()->profile->username) }}" class="text-dark">
                @if(auth()->user()->profile->avatar)
                <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle"
                    style="width: 24px; height: 24px; object-fit: cover;">
                @else
                <i class="bi bi-person-circle fs-4"></i>
                @endif
            </a>
        </div>
    </nav>
    @endauth

    <div class="container-fluid">
        <!-- Desktop Sidebar -->
        @auth
        <div class="sidebar d-none d-lg-block">
            <div class="mb-5">
                <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                    <h4 class="mb-4">
                        <i class="bi bi-code-slash me-2"></i>
                        <span class="fw-bold">DevDoko</span>
                    </h4>
                </a>

                <nav class="nav flex-column">
                    <a class="nav-link py-3 {{ request()->routeIs('home') ? 'active fw-bold' : '' }}"
                        href="{{ route('home') }}">
                        <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} me-3 fs-5"></i>
                        Home
                    </a>

                    <a class="nav-link py-3 {{ request()->routeIs('explore') ? 'active fw-bold' : '' }}"
                        href="{{ route('explore') }}">
                        <i class="bi bi-compass me-3 fs-5"></i>
                        Explore
                    </a>

                    <a class="nav-link py-3" href="{{ route('notifications.index') }}">
                        <i class="bi bi-heart me-3 fs-5"></i>
                        Notifications
                    </a>

                    <a class="nav-link py-3" href="{{ route('messages.index') }}">
                        <i class="bi bi-chat me-3 fs-5"></i>
                        Messages
                    </a>

                    <a class="nav-link py-3" href="{{ route('posts.create') }}">
                        <i class="bi bi-plus-square me-3 fs-5"></i>
                        Create
                    </a>

                    <a class="nav-link py-3 {{ request()->routeIs('profile.show') ? 'active fw-bold' : '' }}"
                        href="{{ route('profile.show', auth()->user()->profile->username) }}">
                        @if(auth()->user()->profile->avatar)
                        <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle me-2" width="24"
                            height="24" style="object-fit: cover;">
                        @else
                        <i class="bi bi-person-circle me-3 fs-5"></i>
                        @endif
                        Profile
                    </a>
                </nav>
            </div>

            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link py-3 w-100 text-start bg-transparent border-0 text-dark">
                        <i class="bi bi-box-arrow-right me-3 fs-5"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth

        <!-- Main Content -->
        <main class="main-content">
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
        </main>

        <!-- Right Sidebar Suggestions (Desktop Only) -->
        @auth
        <div class="suggestions-sidebar d-none d-xl-block">
            <!-- Current User Profile -->
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle me-3" width="56" height="56" style="object-fit: cover;">
                        <div class="flex-grow-1">
                            <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                                class="text-decoration-none text-dark fw-bold">
                                {{ auth()->user()->profile->username }}
                            </a>
                            <div class="text-muted small">{{ auth()->user()->name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestions For You -->
            @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
            <div class="card border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-muted">Suggestions For You</div>
                    <a href="{{ route('explore') }}" class="text-decoration-none fw-bold small">See All</a>
                </div>
                <div class="card-body">
                    @foreach($suggestedUsers as $user)
                    <div class="d-flex align-items-center mb-3 suggestion-user">
                        <a href="{{ route('profile.show', $user->profile->username) }}" class="text-decoration-none">
                            <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}"
                                class="rounded-circle me-3" width="32" height="32" style="object-fit: cover;">
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ route('profile.show', $user->profile->username) }}"
                                class="text-decoration-none text-dark fw-bold d-block">
                                {{ $user->profile->username }}
                            </a>
                            <div class="text-muted small">
                                {{ $user->followers_count ?? 0 }} followers
                            </div>
                        </div>
                        <form action="{{ route('users.follow', $user) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-primary text-decoration-none fw-bold small btn btn-link p-0">
                                Follow
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Footer Links -->
            <div class="mt-4">
                <div class="footer-links">
                    <a href="#">About</a> ·
                    <a href="#">Help</a> ·
                    <a href="#">Press</a> ·
                    <a href="#">API</a> ·
                    <a href="#">Jobs</a> ·
                    <a href="#">Privacy</a> ·
                    <a href="#">Terms</a> ·
                    <a href="#">Locations</a> ·
                    <a href="#">Language</a> ·
                    <a href="#">Meta Verified</a>
                    <div class="mt-2 text-muted">
                        © {{ date('Y') }} DevDoko from Meta
                    </div>
                </div>
            </div>
        </div>
        @endauth
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('scripts')
</body>

</html>