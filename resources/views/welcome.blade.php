<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevDoko - The Ultimate Social Platform for Developers</title>
    <meta name="description"
        content="Connect with developers worldwide. Share code, join groups, chat, collaborate on projects, and build your developer portfolio. The first complete social network built exclusively for developers.">
    <link rel="icon" href="{{ asset('assets/devdeko.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body style="font-family: 'Inter', sans-serif; background: #ffffff; overflow-x: hidden;">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3"
        style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); z-index: 1000;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('assets/devdoko.png') }}" width="45" height="45" class="rounded-circle me-2"
                    alt="DevDoko">
                <span
                    style="font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">DevDoko</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link text-white-50 px-3 fw-500" href="#features">Features</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white-50 px-3 fw-500" href="#how-it-works">How It
                            Works</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50 px-3 fw-500" href="#demo">Interface</a>
                    </li>
                </ul>

                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-4 py-2 me-2 rounded-pill">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn px-4 py-2 rounded-pill"
                        style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: white; box-shadow: 0 10px 20px rgba(102,126,234,0.3);">
                        <i class="bi bi-person-plus me-2"></i>Sign Up Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section
        style="min-height: 100vh; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2d3748 100%); position: relative; overflow: hidden;">
        <!-- Animated Background Elements -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1;">
            <div
                style="position: absolute; top: 10%; left: 5%; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, #667eea, transparent); animation: pulse 4s infinite;">
            </div>
            <div
                style="position: absolute; bottom: 20%; right: 10%; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, #764ba2, transparent); animation: pulse 6s infinite;">
            </div>
        </div>

        <div class="container position-relative" style="padding-top: 50px; z-index: 2;">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <span class="badge px-4 mb-4 rounded-pill"
                        style="background: rgba(102,126,234,0.2); color: #667eea; border: 1px solid rgba(102,126,234,0.3);">
                        <i class="bi bi-code-slash me-2"></i>Built by developers, for developers
                    </span>

                    <h1 style="font-size: 4rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem;">
                        <span style="color: white;">Where Developers</span><br>
                        <span
                            style="background: linear-gradient(135deg, #667eea, #764ba2, #f093fb); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Connect, Code & Grow
                        </span>
                    </h1>

                    <p
                        style="font-size: 1.25rem; color: #94a3b8; line-height: 1.8; margin-bottom: 2rem; max-width: 600px;">
                        The first complete social platform built exclusively for developers.
                        Share code, join tech groups, chat in real-time, collaborate on projects,
                        and build your professional network.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="{{ route('register') }}" class="btn btn-lg px-5 py-3 rounded-pill"
                            style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: white; font-weight: 600; box-shadow: 0 20px 30px rgba(102,126,234,0.4);">
                            <i class="bi bi-rocket-takeoff fs-5 me-2"></i>Register Free
                        </a>
                        <a href="#features" class="btn btn-lg px-5 py-3 rounded-pill btn-outline-light"
                            style="font-weight: 600;">
                            <i class="bi bi-play-circle fs-5 me-2"></i>Features
                        </a>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000>
                    <div class=" position-relative hero-image-wrapper">

                    <!-- Main Image Card -->
                    <div class="hero-image-card">
                        <img src="{{ asset('/assets/home.png') }}" alt="DevDoko Platform Preview" class="img-fluid"
                            style="border-radius: 20px; height: 320px;">
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>

    <section id="features" style="padding: 100px 0; background: #f8fafc;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge px-4 py-2 mb-3 rounded-pill"
                    style="background: rgba(102,126,234,0.1); color: #667eea; font-weight: 600;">
                    Everything You Need
                </span>
                <h2 style="font-size: 3rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Complete Social
                    Platform for Developers</h2>
                <p style="font-size: 1.25rem; color: #64748b; max-width: 700px; margin: 0 auto;">
                    From posting code to group chats, DevDoko has all the features you expect from a modern social
                    platform,
                    plus developer-specific tools you won't find anywhere else.
                </p>
            </div>

            <!-- Feature Categories -->
            <div class="row g-4">
                <!-- Post Management Features -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div
                        style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <div style="display: flex; align-items: center; margin-bottom: 30px;">
                            <span
                                style="background: linear-gradient(135deg, #667eea20, #764ba220); padding: 15px; border-radius: 18px; margin-right: 20px;">
                                <i class="bi bi-file-text-fill" style="font-size: 2rem; color: #667eea;"></i>
                            </span>
                            <h3 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin: 0;">Post Management
                            </h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-pencil-square"
                                        style="font-size: 1.5rem; color: #667eea; margin-bottom: 10px;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a;">Create Posts</h5>
                                    <p style="color: #64748b; font-size: 0.9rem;">Share text, code, images, videos,
                                        links, questions, and project updates</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-trash"
                                        style="font-size: 1.5rem; color: #ef4444; margin-bottom: 10px;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a;">Delete & Edit</h5>
                                    <p style="color: #64748b; font-size: 0.9rem;">Full control over your content with
                                        edit and delete options</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-pin-angle-fill"
                                        style="font-size: 1.5rem; color: #f59e0b; margin-bottom: 10px;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a;">Pin Important Posts</h5>
                                    <p style="color: #64748b; font-size: 0.9rem;">Highlight important announcements in
                                        your profile</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-send"
                                        style="font-size: 1.5rem; color: #10b981; margin-bottom: 10px;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a;">Share & Repost</h5>
                                    <p style="color: #64748b; font-size: 0.9rem;">Share interesting posts with your
                                        network</p>
                                </div>
                            </div>
                        </div>

                        <div
                            style="margin-top: 20px; padding: 15px; background: linear-gradient(135deg, #667eea10, #764ba210); border-radius: 12px;">
                            <span style="font-weight: 600; color: #0f172a;"><i class="bi bi-info-circle me-2"
                                    style="color: #667eea;"></i>Post Types: </span>
                            <span class="badge bg-light text-dark me-2">Text</span>
                            <span class="badge bg-light text-dark me-2">Code</span>
                            <span class="badge bg-light text-dark me-2">Images</span>
                            <span class="badge bg-light text-dark me-2">Videos</span>
                            <span class="badge bg-light text-dark me-2">Links</span>
                            <span class="badge bg-light text-dark me-2">Questions</span>
                            <span class="badge bg-light text-dark me-2">Projects</span>
                            <span class="badge bg-light text-dark me-2">Articles</span>
                            <span class="badge bg-light text-dark me-2">Status Updates</span>
                        </div>
                    </div>
                </div>

                <!-- Social Interactions -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div
                        style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <div style="display: flex; align-items: center; margin-bottom: 30px;">
                            <span
                                style="background: linear-gradient(135deg, #f59e0b20, #d9770620); padding: 15px; border-radius: 18px; margin-right: 20px;">
                                <i class="bi bi-heart-fill" style="font-size: 2rem; color: #f59e0b;"></i>
                            </span>
                            <h3 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin: 0;">Social
                                Interactions</h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4 text-center">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-heart-fill" style="font-size: 2rem; color: #ef4444;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a; margin: 10px 0 5px;">Likes</h5>
                                    <p style="color: #64748b; font-size: 0.8rem;">Show appreciation</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-chat-fill" style="font-size: 2rem; color: #3b82f6;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a; margin: 10px 0 5px;">Comments</h5>
                                    <p style="color: #64748b; font-size: 0.8rem;">Engage in discussions</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-bookmark-fill" style="font-size: 2rem; color: #10b981;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a; margin: 10px 0 5px;">Saves</h5>
                                    <p style="color: #64748b; font-size: 0.8rem;">Bookmark for later</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-share-fill" style="font-size: 2rem; color: #8b5cf6;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a; margin: 10px 0 5px;">Shares</h5>
                                    <p style="color: #64748b; font-size: 0.8rem;">Spread the word</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-flag-fill" style="font-size: 2rem; color: #ef4444;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a; margin: 10px 0 5px;">Report</h5>
                                    <p style="color: #64748b; font-size: 0.8rem;">Keep community safe</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div style="padding: 20px; background: #f8fafc; border-radius: 16px;">
                                    <i class="bi bi-reply-fill" style="font-size: 2rem; color: #06b6d4;"></i>
                                    <h5 style="font-weight: 600; color: #0f172a; margin: 10px 0 5px;">Replies</h5>
                                    <p style="color: #64748b; font-size: 0.8rem;">Threaded comments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row - Groups & Community -->
            <div class="row g-4 mt-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div
                        style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <div style="display: flex; align-items: center; margin-bottom: 30px;">
                            <span
                                style="background: linear-gradient(135deg, #10b98120, #05966920); padding: 15px; border-radius: 18px; margin-right: 20px;">
                                <i class="bi bi-people-fill" style="font-size: 2rem; color: #10b981;"></i>
                            </span>
                            <h3 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin: 0;">Developer Groups &
                                Communities</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled" style="color: #475569;">
                                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"
                                            style="color: #10b981;"></i> Create groups by tech stack</li>
                                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"
                                            style="color: #10b981;"></i> Location-based communities</li>
                                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"
                                            style="color: #10b981;"></i> Project collaboration teams</li>
                                    <li class="mb-3"><i class="bi bi-check-circle-fill me-2"
                                            style="color: #10b981;"></i> Learning circles</li>
                                </ul>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <span class="badge px-4 py-2" style="background: #10b981; color: white;">Group Features:
                            </span>
                            <span class="badge bg-light text-dark me-2 mt-2">Group Posts</span>
                            <span class="badge bg-light text-dark me-2 mt-2">Resources Library</span>
                            <span class="badge bg-light text-dark me-2 mt-2">Group Events</span>
                            <span class="badge bg-light text-dark me-2 mt-2">Member Directory</span>
                            <span class="badge bg-light text-dark me-2 mt-2">Admin Roles</span>
                            <span class="badge bg-light text-dark me-2 mt-2">Invitations</span>
                        </div>
                    </div>
                </div>

                <!-- Chat & Messaging -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div
                        style="background: white; border-radius: 24px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <div style="display: flex; align-items: center; margin-bottom: 30px;">
                            <span
                                style="background: linear-gradient(135deg, #3b82f620, #1d4ed820); padding: 15px; border-radius: 18px; margin-right: 20px;">
                                <i class="bi bi-chat-dots-fill" style="font-size: 2rem; color: #3b82f6;"></i>
                            </span>
                            <h3 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin: 0;">Real-time Chat &
                                Messaging</h3>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div style="border-radius: 16px; padding: 20px;">
                                    <h5 style="color: #0f172a;">Messaging Features</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"
                                                style="color: #3b82f6;"></i> One-on-one private messages</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 "
                                                style="color: #3b82f6;"></i> Group chats with up to 500 members</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"
                                                style="color: #3b82f6;"></i> Code sharing in messages</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"
                                                style="color: #3b82f6;"></i> File attachments</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2"
                                                style="color: #3b82f6;"></i> Read receipts</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row - Follow System & Notifications -->
            <div class="row g-4 mt-4">
                <div class="col-lg-4" data-aos="fade-up">
                    <div
                        style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <i class="bi bi-person-plus-fill"
                            style="font-size: 2.5rem; color: #8b5cf6; margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 700; color: #0f172a;">Follow System</h4>
                        <p style="color: #64748b;">Build your network by following developers who inspire you.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>
                                Follow/unfollow developers</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>
                                Followers & following lists</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>
                                Suggested developers</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>
                                Activity feed from followed users</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div
                        style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <i class="bi bi-bell-fill" style="font-size: 2.5rem; color: #f59e0b; margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 700; color: #0f172a;">Smart Notifications</h4>
                        <p style="color: #64748b;">Stay updated with what matters.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>
                                Like notifications</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>
                                Comment alerts</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>
                                Follow notifications</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>
                                Message notifications</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #f59e0b;"></i>
                                Group activity alerts</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div
                        style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); height: 100%;">
                        <i class="bi bi-bookmark-star-fill"
                            style="font-size: 2.5rem; color: #10b981; margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 700; color: #0f172a;">Collections & Saves</h4>
                        <p style="color: #64748b;">Organize content you love.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>
                                Create custom collections</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>
                                Save posts for later</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>
                                Organize by topic/project</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #10b981;"></i>
                                Share collections</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Fourth Row - Developer Specific Features -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12">
                    <div
                        style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 24px; padding: 50px; color: white;">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 20px;">Developer-Specific
                                    Features</h3>
                                <p style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 30px;">Tools you won't find
                                    on other social platforms</p>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex">
                                            <i class="bi bi-code-slash me-3"
                                                style="color: #667eea; font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 style="color: white;">Code Snippets</h5>
                                                <p style="color: #94a3b8;">Syntax highlighting for 50+ languages</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex">
                                            <i class="bi bi-connection me-3"
                                                style="color: #94a3b8; font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 style="color: white;">Connection</h5>
                                                <p style="color: #94a3b8;">Improve your network</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex">
                                            <i class="bi bi-briefcase me-3"
                                                style="color: #10b981; font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 style="color: white;">Portfolio Builder</h5>
                                                <p style="color: #94a3b8;">Showcase your best work</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex">
                                            <i class="bi bi-code-square me-3"
                                                style="color: #f59e0b; font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 style="color: white;">Code Reviews</h5>
                                                <p style="color: #94a3b8;">Get feedback from senior devs</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4" data-aos="fade-left">
                                <div class="position-relative">

                                    <!-- Glow Background Effect -->
                                    <div class="position-absolute top-50 start-50 translate-middle"
                                        style="width: 380px; height: 380px;">
                                    </div>

                                    <!-- Image Card -->
                                    <div class="card border-0 shadow-lg" style="border-radius: 24px;
                    transition: 0.4s ease;">

                                        <img src="{{ asset('/assets/explore.png') }}" class="img-fluid"
                                            alt="Developer Feed UI" style="height: 400px; padding: 20px;
                        width: 100%;
                        object-fit: cover;">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" style="padding: 100px 0; background: white;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 style="font-size: 3rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Start Your Developer
                    Journey</h2>
                <p style="font-size: 1.25rem; color: #64748b; max-width: 700px; margin: 0 auto;">
                    From signup to your first connection in less than 2 minutes
                </p>
            </div>

            <div class="row position-relative">
                <div class="col-md-4 text-center mb-4" data-aos="fade-right">
                    <div style="position: relative; z-index: 1;">
                        <span
                            style="display: inline-block; width: 100px; height: 100px; line-height: 100px; background: linear-gradient(135deg, #667eea20, #764ba220); border-radius: 50%; font-size: 2.5rem; font-weight: 800; color: #667eea; margin-bottom: 30px; border: 3px solid #667eea;">1</span>
                        <h4 style="font-weight: 700; color: #0f172a;">Create Profile</h4>
                        <p style="color: #64748b;">Sign up in 30 seconds. Connect GitHub, add your tech stack, and
                            customize your developer profile.</p>
                        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-top: 20px;">
                            <i class="bi bi-github me-2"></i> <span style="color: #475569;">Sync your
                                repositories</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center mb-4" data-aos="fade-up">
                    <div style="position: relative; z-index: 1;">
                        <span
                            style="display: inline-block; width: 100px; height: 100px; line-height: 100px; background: linear-gradient(135deg, #10b98120, #05966920); border-radius: 50%; font-size: 2.5rem; font-weight: 800; color: #10b981; margin-bottom: 30px; border: 3px solid #10b981;">2</span>
                        <h4 style="font-weight: 700; color: #0f172a;">Connect & Share</h4>
                        <p style="color: #64748b;">Post your first code snippet, join groups matching your interests,
                            and follow inspiring developers.</p>
                        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-top: 20px;">
                            <i class="bi bi-people-fill me-2" style="color: #10b981;"></i> <span
                                style="color: #475569;">Join Laravel, React, Python groups</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center mb-4" data-aos="fade-left">
                    <div style="position: relative; z-index: 1;">
                        <span
                            style="display: inline-block; width: 100px; height: 100px; line-height: 100px; background: linear-gradient(135deg, #f59e0b20, #d9770620); border-radius: 50%; font-size: 2.5rem; font-weight: 800; color: #f59e0b; margin-bottom: 30px; border: 3px solid #f59e0b;">3</span>
                        <h4 style="font-weight: 700; color: #0f172a;">Grow & Collaborate</h4>
                        <p style="color: #64748b;">Participate in discussions, get code reviews, collaborate on
                            projects, and build your reputation.</p>
                        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-top: 20px;">
                            <i class="bi bi-trophy-fill me-2" style="color: #f59e0b;"></i> <span
                                style="color: #475569;">Earn badges & grow your network</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="demo" style="padding: 100px 0; background: #f8fafc;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="badge px-4 py-2 mb-3 rounded-pill"
                        style="background: rgba(102,126,234,0.1); color: #667eea; font-weight: 600;">
                        See It In Action
                    </span>
                    <h2 style="font-size: 2.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">A Familiar
                        Interface, Built for Developers</h2>
                    <p style="color: #64748b; font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                        DevDoko combines the best of social media platforms like Facebook, Instagram, and LinkedIn,
                        but adds developer-specific features you won't find anywhere else.
                    </p>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="bi bi-facebook fs-2 me-3" style="color: #1877f2;"></i>
                                <div>
                                    <h5 style="font-weight: 600;">Like Facebook</h5>
                                    <p style="color: #64748b;">News feed, groups, events, and social interactions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="bi bi-instagram fs-2 me-3" style="color: #e4405f;"></i>
                                <div>
                                    <h5 style="font-weight: 600;">Like Instagram</h5>
                                    <p style="color: #64748b;">Visual posts, stories, and creative showcases</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="bi bi-linkedin fs-2 me-3" style="color: #0a66c2;"></i>
                                <div>
                                    <h5 style="font-weight: 600;">Like LinkedIn</h5>
                                    <p style="color: #64748b;">Professional networking, portfolio building</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="bi bi-github fs-2 me-3" style="color: #24292e;"></i>
                                <div>
                                    <h5 style="font-weight: 600;">+ Developer Tools</h5>
                                    <p style="color: #64748b;">Code snippets, reviews, GitHub sync</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <a href="{{ route('register') }}" class="btn btn-lg px-5 py-3 rounded-pill"
                            style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: white; font-weight: 600;">
                            <i class="bi bi-rocket-takeoff me-2"></i>Start Your Journey
                        </a>
                    </div>
                </div>


                <div class="col-lg-6 mb-4" data-aos="fade-left">
                    <div class="card border-0 shadow-lg h-100"
                        style="border-radius: 20px; overflow: hidden; transition: 0.3s;">

                        <!-- Project Image -->
                        <img src="{{ asset('/assets/groups.png') }}" class="card-img-top" alt="Developer Feed Project"
                            style="height: 350px;">
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section style="padding: 100px 0; background: linear-gradient(135deg, #0f172a, #1e293b);">
        <div class="container text-center" data-aos="zoom-in">
            <h2 style="font-size: 3rem; font-weight: 800; color: white; margin-bottom: 1.5rem;">
                Join Developers on DevDoko
            </h2>
            <p style="font-size: 1.25rem; color: #94a3b8; max-width: 700px; margin: 0 auto 40px;">
                Stop coding alone. Join the community, share your knowledge, and accelerate your developer career.
            </p>

            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="btn btn-lg px-5 py-3 rounded-pill"
                    style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; color: white; font-weight: 600; box-shadow: 0 20px 30px rgba(102,126,234,0.4);">
                    <i class="bi bi-rocket-takeoff fs-5 me-2"></i>Create Your Free Account
                </a>
                <a href="#features" class="btn btn-lg px-5 py-3 rounded-pill btn-outline-light"
                    style="font-weight: 600;">
                    <i class="bi bi-play-circle fs-5 me-2"></i>See All Features
                </a>
            </div>
        </div>
    </section>

    {{-- <footer style="background: #0f172a; padding: 60px 0 30px; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('assets/devdoko.png') }}" width="40" height="40" class="rounded-circle me-2"
                            alt="DevDoko">
                        <span
                            style="font-size: 1.5rem; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">DevDoko</span>
                    </div>
                    <p style="color: #94a3b8; margin-bottom: 20px;">The complete social platform for developers.
                        Connect, share code, and grow together.</p>
                </div>
            </div>

            <hr style="background: rgba(255,255,255,0.1); margin: 40px 0 20px;">

            <div class="row">
                <div class="col-md-6">
                    <p style="color: #64748b; margin: 0;">
                        <i class="bi bi-c-circle me-1"></i> {{ date('Y') }} DevDoko. All rights reserved. Made with <i
                            class="bi bi-heart-fill" style="color: #ef4444;"></i> for developers worldwide.
                    </p>
                </div>
            </div>
        </div>
    </footer> --}}
    <footer style="background: #0f172a; padding: 20px 20px 30px; border-top: 1px solid black;">
        <div class="container text-center">

            <!-- Brand & About -->
            <div class="mb-4">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <img src="{{ asset('assets/devdoko.png') }}" width="50" height="50" class="rounded-circle me-2"
                        alt="DevDoko">
                    <span style="
                    font-size: 1.7rem; color: white; font-family: cursive;
                    ">
                        <t>DevDoko
                    </span>
                </div>

                <p style=" color: #94a3b8; margin: 0 auto; line-height: 1.6;">
                    DevDoko is the ultimate social platform designed exclusively for developers.
                    Share your code, collaborate with peers, participate in tech communities,
                    and grow your professional network all in one place. Build your portfolio,
                    discover projects, and connect with like-minded developers worldwide.
                </p>
            </div>

            <!-- Divider -->
            {{--
            <hr style="background: rgba(255,255,255,0.1); margin: 40px auto 20px; max-width: 800px;"> --}}

            <!-- Bottom Copyright -->
            <div>
                <p style="color: #64748b; margin: 0; font-size: 0.95rem;">
                    <i class="bi bi-c-circle me-1"></i> {{ date('Y') }} DevDoko. All rights reserved.
                </p>
            </div>

        </div>
    </footer>




    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Smooth scrolling for anchor links
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

    // Navbar background change on scroll
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            nav.style.background = 'rgba(15, 23, 42, 0.98)';
            nav.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
        } else {
            nav.style.background = 'rgba(15, 23, 42, 0.95)';
            nav.style.boxShadow = 'none';
        }
    });
    </script>
</body>

</html>