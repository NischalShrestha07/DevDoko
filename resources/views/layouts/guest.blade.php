<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevDoko')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('assets/devdokoIcon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        [data-bs-theme="dark"] body {
            background: #0d1117;
        }

        .auth-split-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Left branding panel */
        .auth-brand-panel {
            flex: 0 0 45%;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2d3748 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .auth-brand-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(102, 126, 234, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
            animation: auth-bg-float 20s ease-in-out infinite;
        }

        @keyframes auth-bg-float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-2%, -1%); }
        }

        .auth-brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 400px;
        }

        .auth-brand-logo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            margin: 0 auto 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .auth-brand-title {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .auth-brand-subtitle {
            color: #94a3b8;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .auth-brand-features {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .auth-brand-feature {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .auth-brand-feature i {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.15);
            border-radius: 10px;
            color: #667eea;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Right form panel */
        .auth-form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #ffffff;
        }

        [data-bs-theme="dark"] .auth-form-panel {
            background: #161b22;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .auth-form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-form-header .logo-mobile {
            display: none;
            width: 56px;
            height: 56px;
            border-radius: 14px;
            margin: 0 auto 1rem;
        }

        .auth-form-header h1 {
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        [data-bs-theme="dark"] .auth-form-header h1 {
            color: #e6edf3;
        }

        .auth-form-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Form inputs */
        .auth-input-group {
            margin-bottom: 1.25rem;
        }

        .auth-input-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        [data-bs-theme="dark"] .auth-input-group label {
            color: #c9d1d9;
        }

        .auth-input-wrapper {
            position: relative;
        }

        .auth-input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            z-index: 2;
        }

        .auth-input-wrapper .form-control {
            padding-left: 42px;
            height: 48px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #f8fafc;
        }

        [data-bs-theme="dark"] .auth-input-wrapper .form-control {
            background: #0d1117;
            border-color: #30363d;
            color: #c9d1d9;
        }

        .auth-input-wrapper .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            background: #ffffff;
        }

        [data-bs-theme="dark"] .auth-input-wrapper .form-control:focus {
            background: #0d1117;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .auth-input-wrapper .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Password toggle */
        .auth-password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            z-index: 2;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .auth-password-toggle:hover {
            color: #667eea;
        }

        /* Checkbox */
        .auth-check-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #475569;
            cursor: pointer;
        }

        [data-bs-theme="dark"] .auth-check-label {
            color: #8b949e;
        }

        .auth-check-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
            cursor: pointer;
        }

        /* Submit button */
        .auth-submit-btn {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
        }

        .auth-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.45);
        }

        .auth-submit-btn:active {
            transform: translateY(0);
        }

        .auth-submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        [data-bs-theme="dark"] .auth-divider::before,
        [data-bs-theme="dark"] .auth-divider::after {
            background: #30363d;
        }

        /* Social buttons */
        .auth-social-btn {
            width: 100%;
            height: 44px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #374151;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }

        [data-bs-theme="dark"] .auth-social-btn {
            background: #0d1117;
            border-color: #30363d;
            color: #c9d1d9;
        }

        .auth-social-btn:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        [data-bs-theme="dark"] .auth-social-btn:hover {
            background: #161b22;
        }

        .auth-social-btn i {
            font-size: 1.15rem;
        }

        /* Footer links */
        .auth-footer-text {
            text-align: center;
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 1.5rem;
        }

        [data-bs-theme="dark"] .auth-footer-text {
            color: #8b949e;
        }

        .auth-footer-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer-text a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .auth-helper-text {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 0.3rem;
        }

        /* Back to home */
        .auth-back-home {
            position: fixed;
            top: 1.25rem;
            left: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 10;
            transition: color 0.2s;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
        }

        [data-bs-theme="dark"] .auth-back-home {
            background: rgba(22, 27, 34, 0.8);
            color: #8b949e;
        }

        .auth-back-home:hover {
            color: #667eea;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .auth-brand-panel {
                display: none;
            }

            .auth-form-panel {
                min-height: 100vh;
            }

            .auth-form-header .logo-mobile {
                display: block;
            }
        }

        /* Error styling */
        .auth-input-wrapper .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 0.35rem;
            display: block;
        }

        .auth-input-wrapper .form-control.is-invalid {
            padding-right: 42px;
        }

        /* Alert styling */
        .auth-alert {
            border-radius: 12px;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
    <a href="{{ route('welcome') }}" class="auth-back-home">
        <i class="bi bi-arrow-left"></i>
        Back to Home
    </a>

    <div class="auth-split-layout">
        <!-- Left Branding Panel -->
        <div class="auth-brand-panel">
            <div class="auth-brand-content">
                <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko" class="auth-brand-logo">
                <h2 class="auth-brand-title">DevDoko</h2>
                <p class="auth-brand-subtitle">
                    The social platform built exclusively for developers. Share code, connect with peers, and grow your career.
                </p>

                <div class="auth-brand-features">
                    <div class="auth-brand-feature">
                        <i class="bi bi-code-slash"></i>
                        <span>Share code snippets with syntax highlighting</span>
                    </div>
                    <div class="auth-brand-feature">
                        <i class="bi bi-people-fill"></i>
                        <span>Join developer communities by tech stack</span>
                    </div>
                    <div class="auth-brand-feature">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Real-time messaging and group chats</span>
                    </div>
                    <div class="auth-brand-feature">
                        <i class="bi bi-briefcase-fill"></i>
                        <span>Build your portfolio and find opportunities</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="auth-form-panel">
            @if(session('success'))
            <div class="auth-alert alert-success" role="alert" style="position: fixed; top: 1.25rem; right: 1.25rem; z-index: 10; background: #dcfce7; color: #166534; border: 1px solid #86efac;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size: 0.7rem;"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="auth-alert alert-danger" role="alert" style="position: fixed; top: 1.25rem; right: 1.25rem; z-index: 10; background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size: 0.7rem;"></button>
            </div>
            @endif

            <div class="auth-form-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.auth-back-home').forEach(function(el) {
                if (window.innerWidth <= 991) {
                    el.style.background = 'rgba(15, 23, 42, 0.9)';
                    el.style.color = '#94a3b8';
                }
            });

            window.addEventListener('resize', function() {
                document.querySelectorAll('.auth-back-home').forEach(function(el) {
                    if (window.innerWidth <= 991) {
                        el.style.background = 'rgba(15, 23, 42, 0.9)';
                        el.style.color = '#94a3b8';
                    } else {
                        el.style.background = '';
                        el.style.color = '';
                    }
                });
            });
        });
    </script>
</body>

</html>
