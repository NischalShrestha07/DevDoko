<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevDoko - The Ultimate Social Platform for Developers</title>
    <meta name="description" content="Connect with developers worldwide. Share code, join groups, chat, collaborate on projects, and build your developer portfolio.">
    <link rel="icon" href="{{ asset('assets/devdeko.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --brand: #667eea;
            --brand-dark: #764ba2;
            --brand-light: #f093fb;
            --dark-bg: #0f172a;
            --dark-surface: #1e293b;
            --dark-muted: #94a3b8;
            --dark-text: #e2e8f0;
            --light-bg: #f8fafc;
            --light-text: #0f172a;
            --light-muted: #64748b;
            --light-border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            overflow-x: hidden;
            margin: 0;
            color: var(--light-text);
        }

        /* ===== NAVBAR ===== */
        .lp-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            transition: background 0.3s, box-shadow 0.3s;
        }

        .lp-nav.scrolled {
            background: rgba(15, 23, 42, 0.98);
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
        }

        .lp-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.6rem;
        }

        .lp-brand-text {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lp-nav-link {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
            font-size: 0.95rem;
        }

        .lp-nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }

        .lp-btn-outline {
            border: 1.5px solid rgba(255,255,255,0.25);
            color: white;
            padding: 0.55rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .lp-btn-outline:hover {
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .lp-btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: white;
            padding: 0.55rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .lp-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
            color: white;
        }

        /* ===== HERO ===== */
        .lp-hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-surface) 50%, #2d3748 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .lp-hero-orb {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
        }

        .lp-hero-orb-1 {
            top: 10%;
            left: 5%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, var(--brand), transparent);
            animation: lp-pulse 5s ease-in-out infinite;
        }

        .lp-hero-orb-2 {
            bottom: 15%;
            right: 8%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, var(--brand-dark), transparent);
            animation: lp-pulse 7s ease-in-out infinite;
        }

        @keyframes lp-pulse {
            0%, 100% { transform: scale(1); opacity: 0.08; }
            50% { transform: scale(1.1); opacity: 0.12; }
        }

        .lp-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(102,126,234,0.15);
            color: var(--brand);
            border: 1px solid rgba(102,126,234,0.25);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .lp-hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: white;
        }

        .lp-hero-title span {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark), var(--brand-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lp-hero-desc {
            font-size: 1.15rem;
            color: var(--dark-muted);
            line-height: 1.8;
            margin-bottom: 2.5rem;
            max-width: 550px;
        }

        .lp-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .lp-hero-btn-lg {
            padding: 0.85rem 2.5rem;
            font-size: 1.05rem;
            border-radius: 12px;
        }

        .lp-hero-image-wrapper {
            position: relative;
        }

        .lp-hero-image-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            transition: transform 0.4s ease;
            padding: 16px;
        }

        .lp-hero-image-card:hover {
            transform: translateY(-8px);
        }

        .lp-hero-image-card img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            display: block;
            border-radius: 12px;
        }

        /* ===== SECTIONS ===== */
        .lp-section {
            padding: 100px 0;
        }

        .lp-section-alt {
            background: var(--light-bg);
        }

        .lp-section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--light-text);
            margin-bottom: 1rem;
        }

        .lp-section-subtitle {
            font-size: 1.15rem;
            color: var(--light-muted);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .lp-badge {
            display: inline-block;
            background: rgba(102,126,234,0.1);
            color: var(--brand);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        /* ===== FEATURE CARDS ===== */
        .lp-feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.25rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid var(--light-border);
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .lp-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .lp-feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            flex-shrink: 0;
        }

        .lp-feature-card h3 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--light-text);
            margin-bottom: 0.5rem;
        }

        .lp-feature-card p {
            color: var(--light-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .lp-feature-mini {
            background: var(--light-bg);
            border-radius: 14px;
            padding: 1.25rem;
            border: 1px solid var(--light-border);
            transition: background 0.2s, transform 0.2s;
        }

        .lp-feature-mini:hover {
            background: #eef2ff;
            transform: translateY(-2px);
        }

        .lp-feature-mini h5 {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--light-text);
            margin-bottom: 0.25rem;
        }

        .lp-feature-mini p {
            font-size: 0.8rem;
            color: var(--light-muted);
            margin: 0;
        }

        /* ===== SOCIAL INTERACTION CARD ===== */
        .lp-social-card {
            background: white;
            border-radius: 20px;
            padding: 2.25rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid var(--light-border);
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .lp-social-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .lp-social-action {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-border);
        }

        .lp-social-action:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .lp-social-action:first-child {
            padding-top: 0;
        }

        .lp-social-action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .lp-social-action h5 {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--light-text);
            margin-bottom: 0.15rem;
        }

        .lp-social-action p {
            font-size: 0.85rem;
            color: var(--light-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* ===== DARK BANNER ===== */
        .lp-dark-banner {
            background: linear-gradient(135deg, var(--dark-bg), var(--dark-surface));
            border-radius: 24px;
            padding: 3.5rem;
            color: white;
        }

        .lp-dark-banner h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .lp-dark-banner p {
            color: var(--dark-muted);
            font-size: 1.05rem;
        }

        .lp-dark-feature {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .lp-dark-feature i {
            font-size: 1.3rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .lp-dark-feature h5 {
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.15rem;
        }

        .lp-dark-feature p {
            color: var(--dark-muted);
            font-size: 0.85rem;
            margin: 0;
        }

        /* ===== HOW IT WORKS ===== */
        .lp-step-num {
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            font-size: 2rem;
            font-weight: 800;
            display: inline-block;
            margin-bottom: 1.5rem;
            border: 3px solid;
        }

        .lp-step-card h4 {
            font-weight: 700;
            color: var(--light-text);
            margin-bottom: 0.75rem;
        }

        .lp-step-card p {
            color: var(--light-muted);
            line-height: 1.6;
        }

        .lp-step-hint {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-top: 1.25rem;
            font-size: 0.85rem;
            color: var(--light-muted);
            border: 1px solid var(--light-border);
        }

        /* ===== COMPARISON ===== */
        .lp-compare-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .lp-compare-item i {
            font-size: 2rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .lp-compare-item h5 {
            font-weight: 600;
            color: var(--light-text);
            margin-bottom: 0.2rem;
        }

        .lp-compare-item p {
            color: var(--light-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        /* ===== CTA ===== */
        .lp-cta {
            background: linear-gradient(135deg, var(--dark-bg), var(--dark-surface));
            padding: 100px 0;
        }

        .lp-cta h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1.25rem;
        }

        .lp-cta p {
            font-size: 1.15rem;
            color: var(--dark-muted);
            max-width: 650px;
            margin: 0 auto 2.5rem;
        }

        .lp-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.5rem;
            max-width: 700px;
            margin: 0 auto 3rem;
        }

        .lp-stat-item {
            text-align: center;
        }

        .lp-stat-num {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .lp-stat-label {
            font-size: 0.85rem;
            color: var(--dark-muted);
            margin-top: 0.25rem;
        }

        /* ===== FOOTER ===== */
        .lp-footer {
            background: var(--dark-bg);
            padding: 3rem 0 2rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .lp-footer-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .lp-footer-brand span {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .lp-footer-desc {
            color: var(--dark-muted);
            text-align: center;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.7;
        }

        .lp-footer-copy {
            color: #475569;
            font-size: 0.85rem;
            text-align: center;
            margin: 0;
        }

        /* ===== IMAGE CARD ===== */
        .lp-img-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid var(--light-border);
            transition: transform 0.3s;
            padding: 16px;
        }

        .lp-img-card:hover {
            transform: translateY(-4px);
        }

        .lp-img-card img {
            width: 100%;
            height: auto;
            max-height: 450px;
            object-fit: contain;
            display: block;
            border-radius: 12px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .lp-section { padding: 60px 0; }
            .lp-hero { padding-top: 80px; }
            .lp-dark-banner { padding: 2rem; }
        }

        @media (max-width: 767.98px) {
            .lp-hero-actions { flex-direction: column; }
            .lp-hero-actions a { width: 100%; text-align: center; justify-content: center; }
            .lp-hero-image-card img { max-height: 280px; }
            .lp-img-card img { max-height: 280px; }
            .lp-stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="lp-nav py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="#" class="lp-brand">
                <img src="{{ asset('assets/devdoko.png') }}" width="40" height="40" class="rounded-circle" alt="DevDoko">
                <span class="lp-brand-text">DevDoko</span>
            </a>

            <div class="d-none d-lg-flex align-items-center gap-2">
                <a href="#features" class="lp-nav-link">Features</a>
                <a href="#how-it-works" class="lp-nav-link">How It Works</a>
                <a href="#demo" class="lp-nav-link">Interface</a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="lp-btn-outline d-none d-sm-inline-flex">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="{{ route('register') }}" class="lp-btn-primary">
                    <i class="bi bi-person-plus"></i> Sign Up Free
                </a>
                <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#lpMobileNav">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>
            </div>
        </div>
        <div class="collapse d-lg-none" id="lpMobileNav">
            <div class="container py-3 d-flex flex-column gap-2">
                <a href="#features" class="lp-nav-link">Features</a>
                <a href="#how-it-works" class="lp-nav-link">How It Works</a>
                <a href="#demo" class="lp-nav-link">Interface</a>
                <a href="{{ route('login') }}" class="lp-nav-link">Login</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="lp-hero">
        <div class="lp-hero-orb lp-hero-orb-1"></div>
        <div class="lp-hero-orb lp-hero-orb-2"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="lp-hero-badge">
                        <i class="bi bi-code-slash"></i> Built by developers, for developers
                    </div>

                    <h1 class="lp-hero-title">
                        Where Developers<br>
                        <span>Connect, Code & Grow</span>
                    </h1>

                    <p class="lp-hero-desc">
                        Stop juggling between GitHub, LinkedIn, and Discord. DevDoko brings
                        code sharing, developer communities, real-time messaging, and career
                        growth into one platform built the way you actually work.
                    </p>

                    <div class="lp-hero-actions">
                        <a href="{{ route('register') }}" class="lp-btn-primary lp-hero-btn-lg">
                            <i class="bi bi-rocket-takeoff"></i> Get Started Free
                        </a>
                        <a href="#features" class="lp-btn-outline lp-hero-btn-lg">
                            <i class="bi bi-play-circle"></i> See How It Works
                        </a>
                    </div>

                    <div class="d-flex align-items-center gap-4" style="color: var(--dark-muted); font-size: 0.85rem;">
                        <span><i class="bi bi-check-circle-fill me-1" style="color: #10b981;"></i> Free forever</span>
                        <span><i class="bi bi-check-circle-fill me-1" style="color: #10b981;"></i> No ads</span>
                        <span><i class="bi bi-check-circle-fill me-1" style="color: #10b981;"></i> Open community</span>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="lp-hero-image-wrapper">
                        <div class="lp-hero-image-card">
                            <img src="{{ asset('/assets/home.png') }}" alt="DevDoko Home Feed showing posts, code snippets, and developer interactions">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="lp-section lp-section-alt">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="lp-badge">Everything You Need</span>
                <h2 class="lp-section-title">A Social Network That Speaks Your Language</h2>
                <p class="lp-section-subtitle">
                    DevDoko combines the social features you love with developer-first tools —
                    so you can share code, discuss architecture, and grow your career without leaving the platform.
                </p>
            </div>

            <!-- Post Management Card -->
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="lp-feature-card">
                        <div class="d-flex align-items-center mb-4">
                            <div class="lp-feature-icon" style="background: rgba(102,126,234,0.1); color: var(--brand);">
                                <i class="bi bi-file-text-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Rich Post Creation</h3>
                                <p class="mt-1 mb-0">Express ideas in any format</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-pencil-square me-2" style="color: var(--brand);"></i>Multi-Format Posts</h5>
                                    <p>Write text, share code with syntax highlighting, upload images, embed videos, post links, ask questions, or publish articles</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-code-slash me-2" style="color: #06b6d4;"></i>Code Snippets</h5>
                                    <p>Share code with syntax highlighting for 50+ languages, copy-to-clipboard, and inline rendering in the feed</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-pin-angle-fill me-2" style="color: #f59e0b;"></i>Pin & Organize</h5>
                                    <p>Pin important posts to your profile, add tags for discoverability, and control who sees each post</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-pencil me-2" style="color: #10b981;"></i>Full Edit Control</h5>
                                    <p>Edit, delete, or repost any of your content. Your posts, your rules</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 p-2 rounded-3" style="background: rgba(102,126,234,0.05);">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1" style="color: var(--brand);"></i>
                                Post types:
                                <span class="badge bg-light text-dark ms-1">Text</span>
                                <span class="badge bg-light text-dark">Code</span>
                                <span class="badge bg-light text-dark">Images</span>
                                <span class="badge bg-light text-dark">Videos</span>
                                <span class="badge bg-light text-dark">Links</span>
                                <span class="badge bg-light text-dark">Questions</span>
                                <span class="badge bg-light text-dark">Projects</span>
                                <span class="badge bg-light text-dark">Articles</span>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Social Interactions Card — matches actual app behavior -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lp-social-card">
                        <div class="d-flex align-items-center mb-4">
                            <div class="lp-feature-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Social Interactions</h3>
                                <p class="mt-1 mb-0">Engage with the developer community</p>
                            </div>
                        </div>

                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div>
                                <h5>Like with Heart Animation</h5>
                                <p>Tap to like with a satisfying animation. Unlike just as easily. See real-time like counts on every post.</p>
                            </div>
                        </div>

                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                <i class="bi bi-chat-fill"></i>
                            </div>
                            <div>
                                <h5>Threaded Comments & Replies</h5>
                                <p>Start discussions with inline comments. Reply to specific comments to build threaded conversations. Expand to view full threads.</p>
                            </div>
                        </div>

                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                                <i class="bi bi-send-fill"></i>
                            </div>
                            <div>
                                <h5>Share with a Message</h5>
                                <p>Share posts to your feed with an optional commentary. Copy link or send directly to connections.</p>
                            </div>
                        </div>

                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                                <i class="bi bi-bookmark-fill"></i>
                            </div>
                            <div>
                                <h5>Save & Organize</h5>
                                <p>Bookmark posts to read later or build curated collections. Toggle save with one click — your reading list, always accessible.</p>
                            </div>
                        </div>

                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                            <div>
                                <h5>Report & Moderate</h5>
                                <p>Help keep the community safe. Report posts with a reason and details. Admins review every report.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groups & Chat Row -->
            <div class="row g-4 mt-2">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="lp-feature-card">
                        <div class="d-flex align-items-center mb-4">
                            <div class="lp-feature-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Groups & Communities</h3>
                                <p class="mt-1 mb-0">Find your tribe</p>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-3" style="color: #475569;">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>Create groups by tech stack, location, or interest</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>Share posts, resources, and events within groups</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>Admin roles, member directories, and invitation system</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>Learning circles and project collaboration teams</li>
                        </ul>
                        <div>
                            <span class="badge me-1 mb-1" style="background: #10b981; color: white;">Group Posts</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Resources Library</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Events</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Member Directory</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Admin Roles</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lp-feature-card">
                        <div class="d-flex align-items-center mb-4">
                            <div class="lp-feature-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Real-time Messaging</h3>
                                <p class="mt-1 mb-0">Talk directly, anytime</p>
                            </div>
                        </div>
                        <ul class="list-unstyled" style="color: #475569;">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>One-on-one private messages with any developer</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>Group chats with up to 500 members for team coordination</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>Share code snippets and file attachments directly in chat</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>Read receipts so you know when messages are seen</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Follow, Notifications, Saves Row -->
            <div class="row g-4 mt-2">
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="lp-feature-card">
                        <i class="bi bi-person-plus-fill" style="font-size: 2rem; color: #8b5cf6; margin-bottom: 1rem;"></i>
                        <h4 style="font-weight: 700; color: var(--light-text);">Follow System</h4>
                        <p style="color: var(--light-muted);">Build a feed that matters. Follow developers whose work inspires you and see their posts in your personalized timeline.</p>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>Follow/unfollow with one tap</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>See follower and following counts on profiles</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>Discover suggested developers to follow</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>Personalized "Following" feed tab</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="lp-feature-card">
                        <i class="bi bi-bell-fill" style="font-size: 2rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                        <h4 style="font-weight: 700; color: var(--light-text);">Live Notifications</h4>
                        <p style="color: var(--light-muted);">Never miss what matters. Real-time alerts for every interaction on your posts and profile.</p>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>Like and comment notifications</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>New follower alerts</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>Direct message notifications</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>Group activity and mention alerts</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="lp-feature-card">
                        <i class="bi bi-bookmark-star-fill" style="font-size: 2rem; color: #10b981; margin-bottom: 1rem;"></i>
                        <h4 style="font-weight: 700; color: var(--light-text);">Saves & Collections</h4>
                        <p style="color: var(--light-muted);">Build your personal knowledge base. Save posts, organize by topic, and revisit anytime.</p>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>One-click save on any post</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>Toggle between saved/unsaved states</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>View save counts on posts</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>Access your saved posts anytime</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Developer-Specific Banner -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12">
                    <div class="lp-dark-banner">
                        <div class="row align-items-center gy-4">
                            <div class="col-lg-6">
                                <h3>Built for How You Actually Code</h3>
                                <p class="mb-4">Tools that other social platforms don't have — because they weren't built by developers</p>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="lp-dark-feature">
                                            <i class="bi bi-code-slash" style="color: var(--brand);"></i>
                                            <div>
                                                <h5>Syntax-Highlighted Snippets</h5>
                                                <p>50+ languages with dark theme code blocks</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lp-dark-feature">
                                            <i class="bi bi-person-workspace" style="color: var(--dark-muted);"></i>
                                            <div>
                                                <h5>Developer Profiles</h5>
                                                <p>Showcase your tech stack, bio, and portfolio</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lp-dark-feature">
                                            <i class="bi bi-briefcase" style="color: #10b981;"></i>
                                            <div>
                                                <h5>Job Board</h5>
                                                <p>Find roles posted by the community</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lp-dark-feature">
                                            <i class="bi bi-diagram-3" style="color: #f59e0b;"></i>
                                            <div>
                                                <h5>Project Showcase</h5>
                                                <p>Display your work with links and screenshots</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" data-aos="fade-left">
                                <div class="lp-hero-image-card">
                                    <img src="{{ asset('/assets/explore.png') }}" alt="DevDoko Explore page showing trending developers and posts">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" class="lp-section" style="background: white;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="lp-section-title">Up and Running in 2 Minutes</h2>
                <p class="lp-section-subtitle">From sign-up to your first post — no friction, no paywalls</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-right">
                    <div class="lp-step-card text-center">
                        <span class="lp-step-num" style="background: rgba(102,126,234,0.1); color: var(--brand); border-color: var(--brand);">1</span>
                        <h4>Create Your Profile</h4>
                        <p>Sign up with your email, pick a username, and add your tech stack. Optionally link your GitHub to import repositories.</p>
                        <div class="lp-step-hint">
                            <i class="bi bi-github me-2"></i> Optional GitHub integration
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up">
                    <div class="lp-step-card text-center">
                        <span class="lp-step-num" style="background: rgba(16,185,129,0.1); color: #10b981; border-color: #10b981;">2</span>
                        <h4>Join & Connect</h4>
                        <p>Follow developers you admire, join groups that match your stack — whether it's Laravel, React, Python, or anything else.</p>
                        <div class="lp-step-hint">
                            <i class="bi bi-people-fill me-2" style="color: #10b981;"></i> 50+ active tech communities
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-left">
                    <div class="lp-step-card text-center">
                        <span class="lp-step-num" style="background: rgba(245,158,11,0.1); color: #f59e0b; border-color: #f59e0b;">3</span>
                        <h4>Share & Grow</h4>
                        <p>Post your first code snippet, answer questions, get feedback from seniors, and watch your developer reputation grow.</p>
                        <div class="lp-step-hint">
                            <i class="bi bi-trophy-fill me-2" style="color: #f59e0b;"></i> Earn visibility through contributions
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DEMO / COMPARISON -->
    <section id="demo" class="lp-section lp-section-alt">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="lp-badge">See It In Action</span>
                    <h2 class="lp-section-title" style="text-align: left;">Familiar Social Features, Developer-First Execution</h2>
                    <p style="color: var(--light-muted); font-size: 1.05rem; line-height: 1.8; margin-bottom: 2rem;">
                        DevDoko takes the best interaction patterns from the platforms you already use
                        and reimagines them for how developers actually communicate and collaborate.
                    </p>

                    <div class="lp-compare-item">
                        <i class="bi bi-facebook" style="color: #1877f2;"></i>
                        <div>
                            <h5>Like Facebook, But Better</h5>
                            <p>News feed with "For You", "Following", "Popular", and "Latest" tabs. Groups with posts, events, and member directories.</p>
                        </div>
                    </div>
                    <div class="lp-compare-item">
                        <i class="bi bi-instagram" style="color: #e4405f;"></i>
                        <div>
                            <h5>Visual Sharing Like Instagram</h5>
                            <p>Share images, screenshots, and project demos. Profile grids to showcase your visual work.</p>
                        </div>
                    </div>
                    <div class="lp-compare-item">
                        <i class="bi bi-linkedin" style="color: #0a66c2;"></i>
                        <div>
                            <h5>Professional Growth Like LinkedIn</h5>
                            <p>Developer profiles with tech stacks, follower counts, verified badges, and a dedicated job board.</p>
                        </div>
                    </div>
                    <div class="lp-compare-item">
                        <i class="bi bi-github" style="color: #24292e;"></i>
                        <div>
                            <h5>Code-First Like GitHub</h5>
                            <p>Syntax-highlighted snippets with 50+ language support, copy-to-clipboard, and inline rendering in the feed.</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('register') }}" class="lp-btn-primary lp-hero-btn-lg">
                            <i class="bi bi-rocket-takeoff"></i> Start Your Journey
                        </a>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lp-img-card">
                        <img src="{{ asset('/assets/groups.png') }}" alt="DevDoko Groups showing developer communities and discussions">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="lp-cta">
        <div class="container text-center" data-aos="zoom-in">
            <h2>Join the Developer Community<br>That Gets You</h2>
            <p>Whether you're shipping your first Hello World or leading a team of 50 — DevDoko is where developers come to connect, learn, and build together.</p>

            <div class="lp-stat-grid">
                <div class="lp-stat-item">
                    <div class="lp-stat-num">9+</div>
                    <div class="lp-stat-label">Post Types</div>
                </div>
                <div class="lp-stat-item">
                    <div class="lp-stat-num">50+</div>
                    <div class="lp-stat-label">Languages</div>
                </div>
                <div class="lp-stat-item">
                    <div class="lp-stat-num">Real-time</div>
                    <div class="lp-stat-label">Messaging</div>
                </div>
                <div class="lp-stat-item">
                    <div class="lp-stat-num">100%</div>
                    <div class="lp-stat-label">Free Forever</div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="lp-btn-primary lp-hero-btn-lg">
                    <i class="bi bi-rocket-takeoff"></i> Create Your Free Account
                </a>
                <a href="#features" class="lp-btn-outline lp-hero-btn-lg">
                    <i class="bi bi-play-circle"></i> See All Features
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="lp-footer">
        <div class="container">
            <div class="lp-footer-brand">
                <img src="{{ asset('assets/devdoko.png') }}" width="40" height="40" class="rounded-circle" alt="DevDoko">
                <span>DevDoko</span>
            </div>
            <p class="lp-footer-desc">
                The complete social platform for developers. Share code, join communities,
                message in real-time, find jobs, and grow your career — all in one place.
            </p>
            <p class="lp-footer-copy">
                <i class="bi bi-c-circle me-1"></i> {{ date('Y') }} DevDoko. All rights reserved. Made with <i class="bi bi-heart-fill" style="color: #ef4444;"></i> for developers worldwide.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            var nav = document.querySelector('.lp-nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>
