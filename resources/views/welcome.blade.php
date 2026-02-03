<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DevDoko')</title>

    <link rel="icon" href="{{ asset('assets/devdeko.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            --code-gradient: linear-gradient(45deg, #1a2980, #26d0ce);
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--dark-bg);
            color: white;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.98)),
                url('https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
        }

        /* Navigation */
        .nav-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px 40px;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-link {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: white;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.2);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-light:hover {
            border-color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Hero Content */
        .hero-content {
            padding-top: 150px;
            padding-bottom: 100px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 24px;
            background: linear-gradient(45deg, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #94a3b8;
            margin-bottom: 40px;
            max-width: 600px;
        }

        /* Code Preview */
        .code-preview {
            background: rgba(30, 41, 59, 0.8);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .code-title {
            font-size: 14px;
            color: #94a3b8;
        }

        .code-buttons {
            display: flex;
            gap: 8px;
        }

        .code-button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .code-button.red {
            background: #ff5f56;
        }

        .code-button.yellow {
            background: #ffbd2e;
        }

        .code-button.green {
            background: #27ca3f;
        }

        .code-content {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            line-height: 1.6;
            color: #e2e8f0;
        }

        .code-keyword {
            color: #ff79c6;
        }

        .code-function {
            color: #50fa7b;
        }

        .code-string {
            color: #f1fa8c;
        }

        .code-comment {
            color: #6272a4;
        }

        .code-number {
            color: #bd93f9;
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: var(--light-bg);
        }

        .section-title {
            text-align: center;
            color: #0f172a;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 60px;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .feature-icon i {
            font-size: 32px;
            color: white;
        }

        .feature-title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .feature-desc {
            color: #64748b;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: var(--primary-gradient);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Testimonials */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: #e2e8f0;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid white;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: var(--dark-bg);
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 24px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cta-subtitle {
            color: #94a3b8;
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        /* Footer */
        .footer {
            background: rgba(15, 23, 42, 0.95);
            padding: 60px 0 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-link:hover {
            color: white;
        }

        .footer-copyright {
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .nav-bar {
                padding: 15px 20px;
            }

            .nav-links {
                gap: 15px;
            }

            .hero-content {
                padding-top: 120px;
            }
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="nav-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" class="logo">
                    <img width="75" style="border-radius:40px;" src="{{asset('/assets/devdoko.png')}}" alt=""> DevDoko
                </a>
                <div class="nav-links">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#testimonials" class="nav-link">Testimonials</a>
                    <a href="#about" class="nav-link">About</a>
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Join Free</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title animate__animated animate__fadeInUp">
                            Where Developers<br>
                            <span
                                style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                Connect & Share Code
                            </span>
                        </h1>
                        <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                            The first social platform built exclusively for developers.
                            Share code snippets, collaborate on projects, build your portfolio,
                            and connect with developers worldwide.
                        </p>
                        <div class="d-flex flex-wrap gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="bi bi-rocket-takeoff"></i> Start Coding Together
                            </a>
                            <a href="#features" class="btn btn-outline-light">
                                <i class="bi bi-play-circle"></i> See Features
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="section-title">Built for Developers</h2>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <h3 class="feature-title">Code Snippets</h3>
                        <p class="feature-desc">
                            Share code with syntax highlighting. Supports 50+ languages.
                            Perfect for sharing solutions, debugging, and learning.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="feature-title">Developer Network</h3>
                        <p class="feature-desc">
                            Connect with developers who share your tech stack.
                            Follow, collaborate, and learn from experts worldwide.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <h3 class="feature-title">Portfolio Builder</h3>
                        <p class="feature-desc">
                            Showcase your projects to potential employers.
                            Get discovered by tech companies looking for talent.
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <h3 class="feature-title">Tech Discussions</h3>
                        <p class="feature-desc">
                            Engage in meaningful discussions about programming,
                            frameworks, best practices, and new technologies.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Second Row of Features -->
            <div class="row g-4 mt-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(45deg, #f09433, #e6683c);">
                            <i class="bi bi-github"></i>
                        </div>
                        <h3 class="feature-title">GitHub Integration</h3>
                        <p class="feature-desc">
                            Connect your GitHub account. Show off your repositories
                            and contributions. Automatically sync your projects.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(45deg, #007acc, #00b4ff);">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3 class="feature-title">Smart Search</h3>
                        <p class="feature-desc">
                            Find code snippets by language, framework, or problem.
                            Search across millions of developer-shared solutions.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(45deg, #00d2ff, #3a7bd5);">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h3 class="feature-title">Real-time Collab</h3>
                        <p class="feature-desc">
                            Code together in real-time. Pair programming,
                            code reviews, and collaborative debugging made easy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section style="padding: 80px 0; background: var(--light-bg);">
        <div class="container">
            <h2 class="text-center mb-5" style="color: #0f172a; font-size: 2rem; font-weight: 700;">
                Supported Technologies
            </h2>

            <div class="d-flex flex-wrap justify-content-center gap-4">
                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-filetype-php" style="font-size: 2rem; color: #3776ab;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">Laravel</div>
                </div>
                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-filetype-html" style="font-size: 2rem; color: #007396;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">HTML</div>
                </div>
                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-filetype-css" style="font-size: 2rem; color: #a9ef07;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">CSS</div>
                </div>
                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-filetype-js" style="font-size: 2rem; color: #f7df1e;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">JavaScript</div>
                </div>

                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-filetype-php" style="font-size: 2rem; color: #777bb4;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">PHP</div>
                </div>

                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-git" style="font-size: 2rem; color: #f1502f;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">Git</div>
                </div>
                <div
                    style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: var(--card-shadow);">
                    <i class="bi bi-database" style="font-size: 2rem; color: #4479a1;"></i>
                    <div style="color: #0f172a;" class="mt-2 fw-bold">SQL</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Start Your Developer Journey</h2>
            <p class="cta-subtitle">
                Join thousands of developers who are already sharing code,
                building portfolios, and advancing their careers.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Create Free Account
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </a>
            </div>

            <div class="mt-5">
                <small style="color: #94a3b8;">
                    <i class="bi bi-shield-check"></i> Free forever • No credit card required • Join in 30 seconds
                </small>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-copyright">
                <i class="bi bi-code-slash"></i> DevDoko © {{ date('Y') }} • The Developer's Social Network
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>

</html>