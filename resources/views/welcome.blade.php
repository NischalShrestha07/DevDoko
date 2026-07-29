<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevDoko — Where Developers Connect, Code & Grow</title>
    <meta name="description" content="The social platform built by developers, for developers. Share code snippets, join communities, collaborate on projects, find jobs, and grow your career — all in one place.">
    <link rel="icon" href="{{ asset('assets/devdokoIcon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand: #667eea;
            --brand-dark: #764ba2;
            --brand-light: #f093fb;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-bg: #0a0e1a;
            --dark-surface: #111827;
            --dark-card: #1a2236;
            --dark-muted: #94a3b8;
            --dark-text: #e2e8f0;
            --light-bg: #f8fafc;
            --light-text: #0f172a;
            --light-muted: #64748b;
            --light-border: #e2e8f0;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            overflow-x: hidden;
            color: var(--light-text);
            -webkit-font-smoothing: antialiased;
        }

        code, .mono { font-family: 'JetBrains Mono', monospace; }

        /* ===== NAVBAR ===== */
        .lp-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(10, 14, 26, 0.8);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .lp-nav.scrolled {
            background: rgba(10, 14, 26, 0.95);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .lp-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.65rem;
        }

        .lp-brand-text {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lp-nav-link {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .lp-nav-link:hover { color: #fff; background: rgba(255,255,255,0.07); }

        .lp-btn-outline {
            border: 1.5px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.55rem 1.4rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .lp-btn-outline:hover {
            border-color: rgba(255,255,255,0.45);
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .lp-btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: white;
            padding: 0.6rem 1.6rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(102,126,234,0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            position: relative;
            overflow: hidden;
        }

        .lp-btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lp-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(102,126,234,0.5);
            color: white;
        }

        .lp-btn-primary:hover::before { opacity: 1; }

        .lp-btn-lg {
            padding: 0.85rem 2.4rem;
            font-size: 1rem;
            border-radius: 12px;
        }

        .lp-btn-ghost {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
            padding: 0.6rem 1.6rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .lp-btn-ghost:hover {
            background: rgba(255,255,255,0.12);
            color: white;
        }

        /* ===== HERO ===== */
        .lp-hero {
            min-height: 100vh;
            background: var(--dark-bg);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding-top: 80px;
        }

        .lp-hero-mesh {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .lp-hero-mesh::before {
            content: '';
            position: absolute;
            top: -30%;
            left: -10%;
            width: 60%;
            height: 80%;
            background: radial-gradient(ellipse at center, rgba(102,126,234,0.12) 0%, transparent 70%);
            animation: meshFloat 12s ease-in-out infinite;
        }

        .lp-hero-mesh::after {
            content: '';
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 55%;
            height: 75%;
            background: radial-gradient(ellipse at center, rgba(118,75,162,0.1) 0%, transparent 70%);
            animation: meshFloat 15s ease-in-out infinite reverse;
        }

        @keyframes meshFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(3%, -4%) scale(1.05); }
            66% { transform: translate(-2%, 3%) scale(0.97); }
        }

        .lp-hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
        }

        .lp-hero-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
        }

        .lp-hero-glow-1 { top: 10%; left: 15%; background: var(--brand); }
        .lp-hero-glow-2 { bottom: 10%; right: 10%; background: var(--brand-dark); }

        .lp-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: rgba(102,126,234,0.12);
            color: rgba(102,126,234,0.9);
            border: 1px solid rgba(102,126,234,0.2);
            padding: 0.45rem 1.2rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin-bottom: 1.75rem;
            backdrop-filter: blur(8px);
        }

        .lp-hero-badge-dot {
            width: 7px;
            height: 7px;
            background: var(--success);
            border-radius: 50%;
            animation: dotPulse 2s ease-in-out infinite;
        }

        @keyframes dotPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        .lp-hero-title {
            font-size: clamp(2.8rem, 5.5vw, 4.2rem);
            font-weight: 900;
            line-height: 1.08;
            margin-bottom: 1.5rem;
            color: white;
            letter-spacing: -0.04em;
        }

        .lp-hero-title span {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 50%, var(--brand-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lp-hero-desc {
            font-size: 1.1rem;
            color: var(--dark-muted);
            line-height: 1.75;
            margin-bottom: 2.5rem;
            max-width: 520px;
        }

        .lp-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .lp-hero-proof {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .lp-hero-avatars {
            display: flex;
        }

        .lp-hero-avatars img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--dark-bg);
            object-fit: cover;
            margin-left: -10px;
        }

        .lp-hero-avatars img:first-child { margin-left: 0; }

        .lp-hero-proof-text {
            color: var(--dark-muted);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .lp-hero-proof-text strong { color: white; font-weight: 600; }

        /* ===== LIVE STATS BAR ===== */
        .lp-stats-bar {
            background: var(--dark-surface);
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 1.75rem 0;
            position: relative;
            z-index: 2;
        }

        .lp-stat-item {
            text-align: center;
        }

        .lp-stat-num {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .lp-stat-label {
            font-size: 0.78rem;
            color: var(--dark-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 500;
            margin-top: 0.2rem;
        }

        .lp-stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255,255,255,0.08);
            margin: 0 auto;
        }

        /* ===== SECTIONS ===== */
        .lp-section {
            padding: 100px 0;
        }

        .lp-section-dark {
            background: var(--dark-bg);
            color: white;
        }

        .lp-section-alt {
            background: var(--light-bg);
        }

        .lp-section-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }

        .lp-section-subtitle {
            font-size: 1.1rem;
            color: var(--light-muted);
            max-width: 640px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .lp-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(102,126,234,0.08);
            color: var(--brand);
            padding: 0.4rem 1.1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.02em;
            margin-bottom: 1rem;
            border: 1px solid rgba(102,126,234,0.15);
        }

        /* ===== PAIN POINTS ===== */
        .lp-pain-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            border: 1px solid var(--light-border);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .lp-pain-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand), var(--brand-dark));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lp-pain-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }

        .lp-pain-card:hover::before { opacity: 1; }

        .lp-pain-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .lp-pain-card h4 {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .lp-pain-card p {
            color: var(--light-muted);
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* ===== FEATURE CARDS ===== */
        .lp-feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid var(--light-border);
            height: 100%;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .lp-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.08);
        }

        .lp-feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.1rem;
            flex-shrink: 0;
        }

        .lp-feature-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem;
        }

        .lp-feature-card > p {
            color: var(--light-muted);
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .lp-feature-mini {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 1.1rem;
            border: 1px solid transparent;
            transition: all 0.25s;
        }

        .lp-feature-mini:hover {
            background: #eef2ff;
            border-color: rgba(102,126,234,0.15);
            transform: translateY(-2px);
        }

        .lp-feature-mini h5 {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.2rem;
        }

        .lp-feature-mini p {
            font-size: 0.78rem;
            color: var(--light-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* ===== SOCIAL CARD ===== */
        .lp-social-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid var(--light-border);
            height: 100%;
        }

        .lp-social-action {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .lp-social-action:last-child { border-bottom: none; padding-bottom: 0; }
        .lp-social-action:first-child { padding-top: 0; }

        .lp-social-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .lp-social-action h5 {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.1rem;
        }

        .lp-social-action p {
            font-size: 0.82rem;
            color: var(--light-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* ===== DARK BANNER ===== */
        .lp-dark-banner {
            background: linear-gradient(135deg, var(--dark-surface), var(--dark-card));
            border-radius: 24px;
            padding: 3rem;
            color: white;
            border: 1px solid rgba(255,255,255,0.06);
            position: relative;
            overflow: hidden;
        }

        .lp-dark-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(102,126,234,0.08) 0%, transparent 70%);
        }

        .lp-dark-banner h3 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.6rem;
        }

        .lp-dark-banner > p { color: var(--dark-muted); font-size: 1rem; }

        .lp-dark-feature {
            display: flex;
            gap: 0.9rem;
            margin-bottom: 1.1rem;
        }

        .lp-dark-feature i {
            font-size: 1.2rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .lp-dark-feature h5 {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.1rem;
        }

        .lp-dark-feature p {
            color: var(--dark-muted);
            font-size: 0.8rem;
            margin: 0;
        }

        /* ===== DEVELOPER CARDS ===== */
        .lp-dev-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--light-border);
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .lp-dev-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }

        .lp-dev-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--light-bg);
            margin-bottom: 0.75rem;
        }

        .lp-dev-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--light-text);
            margin-bottom: 0.15rem;
        }

        .lp-dev-username {
            color: var(--brand);
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .lp-dev-bio {
            color: var(--light-muted);
            font-size: 0.8rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .lp-dev-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.3rem;
            justify-content: center;
        }

        .lp-dev-tag {
            background: rgba(102,126,234,0.08);
            color: var(--brand);
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* ===== POST CARDS ===== */
        .lp-post-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--light-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .lp-post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.06);
        }

        .lp-post-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .lp-post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .lp-post-author {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--light-text);
        }

        .lp-post-time {
            font-size: 0.75rem;
            color: var(--light-muted);
        }

        .lp-post-body {
            color: var(--light-text);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        .lp-post-footer {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            color: var(--light-muted);
            font-size: 0.8rem;
        }

        .lp-post-footer i { font-size: 0.95rem; }

        /* ===== HOW IT WORKS ===== */
        .lp-step-card {
            text-align: center;
            padding: 1.5rem;
        }

        .lp-step-num {
            width: 72px;
            height: 72px;
            line-height: 72px;
            border-radius: 20px;
            font-size: 1.6rem;
            font-weight: 900;
            display: inline-block;
            margin-bottom: 1.25rem;
            position: relative;
        }

        .lp-step-num::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 24px;
            border: 2px dashed;
            opacity: 0.2;
        }

        .lp-step-card h4 {
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.6rem;
        }

        .lp-step-card p {
            color: var(--light-muted);
            line-height: 1.6;
            font-size: 0.9rem;
        }

        .lp-step-hint {
            background: var(--light-bg);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            margin-top: 1rem;
            font-size: 0.8rem;
            color: var(--light-muted);
            border: 1px solid var(--light-border);
        }

        /* ===== COMPARISON ===== */
        .lp-compare-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.3rem;
            padding: 1rem;
            border-radius: 12px;
            transition: background 0.2s;
        }

        .lp-compare-item:hover { background: rgba(102,126,234,0.04); }

        .lp-compare-item i {
            font-size: 1.8rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .lp-compare-item h5 {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.15rem;
        }

        .lp-compare-item p {
            color: var(--light-muted);
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.5;
        }

        /* ===== MARKETPLACE TEASER ===== */
        .lp-marketplace-card {
            background: linear-gradient(135deg, #1a2236 0%, #111827 100%);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid rgba(255,255,255,0.06);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .lp-marketplace-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(6,182,212,0.1) 0%, transparent 70%);
        }

        .lp-marketplace-item {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.75rem;
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .lp-marketplace-item:hover {
            background: rgba(255,255,255,0.08);
        }

        .lp-marketplace-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .lp-marketplace-item h5 {
            font-weight: 600;
            font-size: 0.88rem;
            margin-bottom: 0.05rem;
        }

        .lp-marketplace-item p {
            font-size: 0.75rem;
            color: var(--dark-muted);
            margin: 0;
        }

        /* ===== CTA ===== */
        .lp-cta {
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-surface) 50%, #1a1040 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .lp-cta::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(102,126,234,0.06) 0%, transparent 60%);
        }

        .lp-cta h2 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            color: white;
        }

        .lp-cta p {
            font-size: 1.1rem;
            color: var(--dark-muted);
            max-width: 600px;
            margin: 0 auto 2.5rem;
        }

        .lp-cta-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 1.5rem;
            max-width: 650px;
            margin: 0 auto 3rem;
        }

        /* ===== FOOTER ===== */
        .lp-footer {
            background: var(--dark-bg);
            padding: 3rem 0 2rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .lp-footer-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-bottom: 0.75rem;
        }

        .lp-footer-brand span {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
        }

        .lp-footer-desc {
            color: var(--dark-muted);
            text-align: center;
            max-width: 500px;
            margin: 0 auto 1.5rem;
            line-height: 1.7;
            font-size: 0.9rem;
        }

        .lp-footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .lp-footer-links a {
            color: var(--dark-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .lp-footer-links a:hover { color: white; }

        .lp-footer-copy {
            color: #374151;
            font-size: 0.8rem;
            text-align: center;
            margin: 0;
        }

        /* ===== IMAGE CARDS ===== */
        .lp-img-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border: 1px solid var(--light-border);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 12px;
        }

        .lp-img-card:hover { transform: translateY(-6px) scale(1.01); }

        .lp-img-card img {
            width: 100%;
            height: auto;
            max-height: 420px;
            object-fit: contain;
            display: block;
            border-radius: 14px;
        }

        .lp-hero-image-card {
            background: rgba(26, 34, 54, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 12px;
        }

        .lp-hero-image-card:hover { transform: translateY(-8px) scale(1.02); }

        .lp-hero-image-card img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            display: block;
            border-radius: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .lp-section { padding: 60px 0; }
            .lp-dark-banner { padding: 2rem; }

        }

        @media (max-width: 767.98px) {
            .lp-hero-actions { flex-direction: column; }
            .lp-hero-actions a { width: 100%; text-align: center; justify-content: center; }
            .lp-hero-image-card img { max-height: 260px; }
            .lp-img-card img { max-height: 260px; }
            .lp-cta-stat-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* ===== UTILITY ===== */
        .text-gradient {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--brand), var(--brand-dark));
            border-radius: 3px;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="lp-nav py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="#" class="lp-brand">
                <img src="{{ asset('assets/devdoko.png') }}" width="38" height="38" class="rounded-circle" alt="DevDoko">
                <span class="lp-brand-text">DevDoko</span>
            </a>

            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="#features" class="lp-nav-link">Features</a>
                <a href="#how-it-works" class="lp-nav-link">How It Works</a>
                <a href="#community" class="lp-nav-link">Community</a>
                <a href="#marketplace" class="lp-nav-link">Marketplace</a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="lp-btn-outline d-none d-sm-inline-flex">
                    <i class="bi bi-box-arrow-in-right"></i> Log In
                </a>
                <a href="{{ route('register') }}" class="lp-btn-primary">
                    Get Started <i class="bi bi-arrow-right"></i>
                </a>
                <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#lpMobileNav">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>
            </div>
        </div>
        <div class="collapse d-lg-none" id="lpMobileNav">
            <div class="container py-3 d-flex flex-column gap-1">
                <a href="#features" class="lp-nav-link">Features</a>
                <a href="#how-it-works" class="lp-nav-link">How It Works</a>
                <a href="#community" class="lp-nav-link">Community</a>
                <a href="#marketplace" class="lp-nav-link">Marketplace</a>
                <a href="{{ route('login') }}" class="lp-nav-link">Log In</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="lp-hero">
        <div class="lp-hero-mesh"></div>
        <div class="lp-hero-grid"></div>
        <div class="lp-hero-glow lp-hero-glow-1"></div>
        <div class="lp-hero-glow lp-hero-glow-2"></div>

        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="lp-hero-badge">
                        <span class="lp-hero-badge-dot"></span> Open source • Free forever
                    </div>

                    <h1 class="lp-hero-title">
                        The Dev Social<br>
                        Platform That<br>
                        <span>Actually Gets You</span>
                    </h1>

                    <p class="lp-hero-desc">
                        Code snippets with syntax highlighting. Communities around your stack.
                        Real-time chat. Job boards. Project showcases. Everything a developer needs,
                        nothing a developer doesn't.
                    </p>

                    <div class="lp-hero-actions">
                        <a href="{{ route('register') }}" class="lp-btn-primary lp-btn-lg">
                            <i class="bi bi-rocket-takeoff"></i> Join DevDoko — It's Free
                        </a>
                        <a href="#features" class="lp-btn-ghost lp-btn-lg">
                            <i class="bi bi-play-circle"></i> Explore Features
                        </a>
                    </div>

                    <div class="lp-hero-proof">
                        <div class="lp-hero-avatars">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=dev1" alt="">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=dev2" alt="">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=dev3" alt="">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=dev4" alt="">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=dev5" alt="">
                        </div>
                        <div class="lp-hero-proof-text">
                            <strong>{{ number_format($stats['total_users']) }}+</strong> developers already here<br>
                            {{ number_format($stats['active_today']) }} active this week
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1200">
                    <div class="lp-hero-image-card">
                        <img src="{{ asset('/assets/home.png') }}" alt="DevDoko Home Feed">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LIVE STATS BAR -->
    <div class="lp-stats-bar">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center gap-3">
                    <div class="lp-stat-item">
                        <div class="lp-stat-num" data-count="{{ $stats['total_users'] }}">0</div>
                        <div class="lp-stat-label">Developers</div>
                    </div>
                    <div class="lp-stat-divider d-none d-md-block"></div>
                </div>
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center gap-3">
                    <div class="lp-stat-item">
                        <div class="lp-stat-num" data-count="{{ $stats['total_posts'] }}">0</div>
                        <div class="lp-stat-label">Posts Shared</div>
                    </div>
                    <div class="lp-stat-divider d-none d-md-block"></div>
                </div>
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center gap-3">
                    <div class="lp-stat-item">
                        <div class="lp-stat-num" data-count="{{ $stats['code_snippets'] }}">0</div>
                        <div class="lp-stat-label">Code Snippets</div>
                    </div>
                    <div class="lp-stat-divider d-none d-md-block"></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="lp-stat-item">
                        <div class="lp-stat-num" data-count="{{ $stats['active_today'] }}">0</div>
                        <div class="lp-stat-label">Active This Week</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WHY DEV DOKO -->
    <section class="lp-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="lp-badge"><i class="bi bi-lightning-charge-fill"></i> Why DevDoko</span>
                <h2 class="lp-section-title">Tired of Scattered Developer Tools?</h2>
                <p class="lp-section-subtitle">
                    Your code lives on GitHub. Your network on LinkedIn. Your discussions on Discord.
                    DevDoko brings it all together — finally.
                </p>
            </div>

            <div class="row g-3">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="lp-pain-card">
                        <div class="lp-pain-icon" style="background: rgba(239,68,68,0.08); color: var(--danger);">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h4>Context Switching</h4>
                        <p>Juggling 5+ apps just to share code, discuss architecture, and find opportunities.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="lp-pain-card">
                        <div class="lp-pain-icon" style="background: rgba(239,68,68,0.08); color: var(--danger);">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h4>No Code-First Social</h4>
                        <p>LinkedIn doesn't render code. Twitter truncates snippets. Neither understand syntax.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="lp-pain-card">
                        <div class="lp-pain-icon" style="background: rgba(239,68,68,0.08); color: var(--danger);">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h4>Fragmented Communities</h4>
                        <p>Dev communities scattered across Reddit, Slack, and Discord — no unified home.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="lp-pain-card">
                        <div class="lp-pain-icon" style="background: rgba(239,68,68,0.08); color: var(--danger);">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h4>Generic Job Boards</h4>
                        <p>Apply through HR gatekeepers. No way to showcase your actual work to employers.</p>
                    </div>
                </div>
            </div>

            <!-- Transition arrow -->
            <div class="text-center my-4" data-aos="zoom-in">
                <div style="display: inline-flex; align-items: center; gap: 0.75rem; background: linear-gradient(135deg, var(--brand), var(--brand-dark)); color: white; padding: 0.6rem 1.5rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem; box-shadow: 0 4px 20px rgba(102,126,234,0.4);">
                    <i class="bi bi-arrow-down"></i> DevDoko fixes all of this
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="lp-section lp-section-alt">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="lp-badge"><i class="bi bi-grid-3x3-gap-fill"></i> Platform Features</span>
                <h2 class="lp-section-title">Everything You Need, Nothing You Don't</h2>
                <p class="lp-section-subtitle">
                    Built by developers who got frustrated with the status quo.
                    Every feature exists because someone actually needed it.
                </p>
            </div>

            <!-- Posts + Social -->
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="lp-feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="lp-feature-icon" style="background: rgba(102,126,234,0.1); color: var(--brand);">
                                <i class="bi bi-file-text-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Rich Post Creation</h3>
                                <p class="mt-1 mb-0" style="color: var(--light-muted); font-size: 0.85rem;">Express ideas in any format</p>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-code-slash me-2" style="color: var(--accent);"></i>Code Snippets</h5>
                                    <p>Syntax highlighting for 50+ languages, copy-to-clipboard, inline rendering</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-image me-2" style="color: var(--brand);"></i>Rich Media</h5>
                                    <p>Share images, embed videos, post links, ask questions, publish articles</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-pin-angle me-2" style="color: var(--warning);"></i>Pin & Organize</h5>
                                    <p>Pin top posts to your profile, tag for discoverability, control visibility</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="lp-feature-mini">
                                    <h5><i class="bi bi-pencil me-2" style="color: var(--success);"></i>Full Control</h5>
                                    <p>Edit, delete, draft, or publish any content — your posts, your rules</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lp-social-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="lp-feature-icon" style="background: rgba(239,68,68,0.1); color: var(--danger);">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Social Interactions</h3>
                                <p class="mt-1 mb-0" style="color: var(--light-muted); font-size: 0.85rem;">Engage like the best social platforms</p>
                            </div>
                        </div>

                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(239,68,68,0.08); color: var(--danger);">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div>
                                <h5>Like with Animation</h5>
                                <p>Satisfying heart animation. Real-time counts. Toggle unlike instantly.</p>
                            </div>
                        </div>
                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(59,130,246,0.08); color: #3b82f6;">
                                <i class="bi bi-chat-fill"></i>
                            </div>
                            <div>
                                <h5>Threaded Comments</h5>
                                <p>Inline comments with nested replies. Build real conversations.</p>
                            </div>
                        </div>
                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(139,92,246,0.08); color: #8b5cf6;">
                                <i class="bi bi-send-fill"></i>
                            </div>
                            <div>
                                <h5>Share with Commentary</h5>
                                <p>Repost with your take. Copy link or send to connections directly.</p>
                            </div>
                        </div>
                        <div class="lp-social-action">
                            <div class="lp-social-action-icon" style="background: rgba(16,185,129,0.08); color: var(--success);">
                                <i class="bi bi-bookmark-fill"></i>
                            </div>
                            <div>
                                <h5>Save & Collect</h5>
                                <p>Bookmark any post. Build curated reading lists. Always accessible.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groups + Messaging -->
            <div class="row g-4 mt-2">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="lp-feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="lp-feature-icon" style="background: rgba(16,185,129,0.1); color: var(--success);">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Groups & Communities</h3>
                                <p class="mt-1 mb-0" style="color: var(--light-muted); font-size: 0.85rem;">Find your tribe</p>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-3" style="color: #475569;">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i>Create groups by tech stack, location, or interest</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i>Share posts, resources, and events within groups</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i>Admin roles, member directories, invitation system</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i>Learning circles and project collaboration teams</li>
                        </ul>
                        <div>
                            <span class="badge me-1 mb-1" style="background: var(--success); color: white;">Group Posts</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Resources</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Events</span>
                            <span class="badge me-1 mb-1 bg-light text-dark">Members</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lp-feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="lp-feature-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0">Real-time Messaging</h3>
                                <p class="mt-1 mb-0" style="color: var(--light-muted); font-size: 0.85rem;">Talk directly, anytime</p>
                            </div>
                        </div>
                        <ul class="list-unstyled" style="color: #475569;">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>One-on-one private messages with any developer</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>Share code snippets and file attachments in chat</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>Message reactions and emoji responses</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #3b82f6;"></i>Read receipts and star important conversations</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Follow, Notifications, Saves -->
            <div class="row g-4 mt-2">
                <div class="col-lg-4" data-aos="fade-up">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <h4>Follow System</h4>
                        <p style="color: var(--light-muted); font-size: 0.88rem;">Build a feed that matters. Follow developers whose work inspires you.</p>
                        <ul class="list-unstyled mt-3" style="font-size: 0.85rem;">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>Follow/unfollow with one tap</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>Personalized "Following" feed tab</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: #8b5cf6;"></i>Discover suggested developers</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background: rgba(245,158,11,0.1); color: var(--warning);">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <h4>Live Notifications</h4>
                        <p style="color: var(--light-muted); font-size: 0.88rem;">Never miss what matters. Real-time alerts for every interaction.</p>
                        <ul class="list-unstyled mt-3" style="font-size: 0.85rem;">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: var(--warning);"></i>Like, comment, and mention alerts</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: var(--warning);"></i>New follower notifications</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: var(--warning);"></i>Group activity updates</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background: rgba(6,182,212,0.1); color: var(--accent);">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <h4>Job Board</h4>
                        <p style="color: var(--light-muted); font-size: 0.88rem;">Find roles posted by the community. No HR gatekeepers, real opportunities.</p>
                        <ul class="list-unstyled mt-3" style="font-size: 0.85rem;">
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: var(--accent);"></i>Browse and post developer jobs</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: var(--accent);"></i>Showcase your work to employers</li>
                            <li class="mb-2"><i class="bi bi-arrow-right-circle-fill me-2" style="color: var(--accent);"></i>Apply with your DevDoko profile</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Developer-Specific Banner -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12">
                    <div class="lp-dark-banner">
                        <div class="row align-items-center gy-4">
                            <div class="col-lg-6" style="position: relative; z-index: 1;">
                                <h3>Built for How You Actually Code</h3>
                                <p class="mb-4" style="color: var(--dark-muted);">Tools that other social platforms don't have — because they weren't built by developers</p>
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
                                                <p>Showcase your tech stack and portfolio</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lp-dark-feature">
                                            <i class="bi bi-diagram-3" style="color: var(--warning);"></i>
                                            <div>
                                                <h5>Project Showcase</h5>
                                                <p>Display your work with links and screenshots</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="lp-dark-feature">
                                            <i class="bi bi-shop" style="color: var(--accent);"></i>
                                            <div>
                                                <h5>Developer Marketplace</h5>
                                                <p>Buy, sell, and trade dev tools and services</p>
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
                <span class="lp-badge"><i class="bi bi-123"></i> Getting Started</span>
                <h2 class="lp-section-title">Up and Running in 2 Minutes</h2>
                <p class="lp-section-subtitle">From sign-up to your first post — no friction, no paywalls, no BS</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-right">
                    <div class="lp-step-card">
                        <span class="lp-step-num" style="background: rgba(102,126,234,0.1); color: var(--brand); border-color: rgba(102,126,234,0.3);">1</span>
                        <h4>Create Your Profile</h4>
                        <p>Sign up, pick a username, add your tech stack. Optionally link GitHub to import your repos.</p>
                        <div class="lp-step-hint">
                            <i class="bi bi-github me-2"></i> Optional GitHub integration
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up">
                    <div class="lp-step-card">
                        <span class="lp-step-num" style="background: rgba(16,185,129,0.1); color: var(--success); border-color: rgba(16,185,129,0.3);">2</span>
                        <h4>Join & Connect</h4>
                        <p>Follow devs you admire. Join groups matching your stack — Laravel, React, Python, anything.</p>
                        <div class="lp-step-hint">
                            <i class="bi bi-people-fill me-2" style="color: var(--success);"></i> Active tech communities
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-left">
                    <div class="lp-step-card">
                        <span class="lp-step-num" style="background: rgba(245,158,11,0.1); color: var(--warning); border-color: rgba(245,158,11,0.3);">3</span>
                        <h4>Share & Grow</h4>
                        <p>Post your first snippet, answer questions, get feedback, watch your developer reputation grow.</p>
                        <div class="lp-step-hint">
                            <i class="bi bi-trophy-fill me-2" style="color: var(--warning);"></i> Earn visibility through contributions
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COMMUNITY / SOCIAL PROOF -->
    <section id="community" class="lp-section lp-section-alt">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="lp-badge"><i class="bi bi-people-fill"></i> Community</span>
                <h2 class="lp-section-title">Meet the Developers Building Here</h2>
                <p class="lp-section-subtitle">
                    Real developers sharing real code. No influencer fluff — just people who ship.
                </p>
            </div>

            <!-- Featured Developers -->
            @if($topDevelopers->count())
            <div class="row g-3 mb-5">
                @foreach($topDevelopers as $dev)
                @if($dev->profile)
                <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('profile.show', $dev->profile->username) }}" class="text-decoration-none">
                        <div class="lp-dev-card">
                            <img src="{{ $dev->avatar_url }}" alt="{{ $dev->name ?? $dev->profile->username }}" class="lp-dev-avatar">
                            <div class="lp-dev-name">{{ $dev->name ?? $dev->profile->username }}</div>
                            <div class="lp-dev-username">{{ '@' . $dev->profile->username }}</div>
                            @if($dev->profile->bio && $dev->profile->bio !== 'Hello! I\'m new to DevDoko.')
                                <div class="lp-dev-bio">{{ $dev->profile->bio }}</div>
                            @endif
                            @php
                                $techTags = $dev->profile->techTags->take(3);
                            @endphp
                            @if($techTags->count())
                                <div class="lp-dev-tags">
                                    @foreach($techTags as $tech)
                                        <span class="lp-dev-tag">{{ $tech->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
                @endif
                @endforeach
            </div>
            @endif

            <!-- Featured Posts -->
            @if($featuredPosts->count())
            <div class="text-center mb-4" data-aos="fade-up">
                <h3 style="font-weight: 800; font-size: 1.5rem; letter-spacing: -0.02em;">Trending Right Now</h3>
            </div>
            <div class="row g-3">
                @foreach($featuredPosts->take(3) as $post)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="lp-post-card">
                        <div class="lp-post-header">
                            <img src="{{ $post->user->avatar_url }}" alt="" class="lp-post-avatar">
                            <div>
                                <div class="lp-post-author">{{ $post->user->name ?? $post->user->profile->username }}</div>
                                <div class="lp-post-time">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="lp-post-body">
                            @if($post->type === 'code')
                                <span class="mono" style="font-size: 0.8rem; color: var(--accent);"><i class="bi bi-code-slash me-1"></i> Code Snippet</span><br>
                            @endif
                            {{ Str::limit(strip_tags($post->body), 120) }}
                        </div>
                        <div class="lp-post-footer">
                            <span><i class="bi bi-heart me-1"></i> {{ $post->likes_count ?? 0 }}</span>
                            <span><i class="bi bi-chat me-1"></i> {{ $post->comments_count ?? 0 }}</span>
                            <span><i class="bi bi-eye me-1"></i> {{ $post->views_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- DEMO / COMPARISON -->
    <section id="demo" class="lp-section">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="lp-badge"><i class="bi bi-grid-1x2-fill"></i> How It Compares</span>
                    <h2 class="lp-section-title" style="text-align: left;">Familiar Patterns, Developer-First Execution</h2>
                    <p style="color: var(--light-muted); font-size: 1rem; line-height: 1.8; margin-bottom: 2rem;">
                        We took the best interaction patterns from platforms you already use
                        and reimagined them for how developers actually communicate.
                    </p>

                    <div class="lp-compare-item">
                        <i class="bi bi-facebook" style="color: #1877f2;"></i>
                        <div>
                            <h5>Like Facebook, But Better</h5>
                            <p>News feed with "For You", "Following", "Popular", and "Latest" tabs. Groups with posts, events, and member directories.</p>
                        </div>
                    </div>
                    <div class="lp-compare-item">
                        <i class="bi bi-linkedin" style="color: #0a66c2;"></i>
                        <div>
                            <h5>Professional Growth Like LinkedIn</h5>
                            <p>Developer profiles with tech stacks, follower counts, and a dedicated job board — but with actual code.</p>
                        </div>
                    </div>
                    <div class="lp-compare-item">
                        <i class="bi bi-github" style="color: #24292e;"></i>
                        <div>
                            <h5>Code-First Like GitHub</h5>
                            <p>Syntax-highlighted snippets with 50+ language support, copy-to-clipboard, and inline rendering in the feed.</p>
                        </div>
                    </div>
                    <div class="lp-compare-item">
                        <i class="bi bi-discord" style="color: #5865f2;"></i>
                        <div>
                            <h5>Community Like Discord</h5>
                            <p>Real-time messaging, group chats, reactions, file sharing — but with profiles that actually showcase your work.</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('register') }}" class="lp-btn-primary lp-btn-lg">
                            <i class="bi bi-rocket-takeoff"></i> Start Your Journey
                        </a>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="lp-img-card">
                        <img src="{{ asset('/assets/groups.png') }}" alt="DevDoko Groups showing developer communities">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MARKETPLACE TEASER -->
    <section id="marketplace" class="lp-section lp-section-alt">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="lp-marketplace-card">
                        <div style="position: relative; z-index: 1;">
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(6,182,212,0.15); color: var(--accent); padding: 0.35rem 0.9rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem; border: 1px solid rgba(6,182,212,0.25);">
                                <i class="bi bi-shop"></i> NEW
                            </span>
                            <h3 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 0.5rem;">Developer Marketplace</h3>
                            <p style="color: var(--dark-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">Buy, sell, and trade developer tools, templates, and services within the community.</p>

                            <div class="lp-marketplace-item">
                                <div class="lp-marketplace-item-icon" style="background: rgba(102,126,234,0.15); color: var(--brand);">
                                    <i class="bi bi-code-square"></i>
                                </div>
                                <div>
                                    <h5>Templates & Boilerplates</h5>
                                    <p>Skip the setup. Start with production-ready starters.</p>
                                </div>
                            </div>
                            <div class="lp-marketplace-item">
                                <div class="lp-marketplace-item-icon" style="background: rgba(16,185,129,0.15); color: var(--success);">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div>
                                    <h5>Developer Tools</h5>
                                    <p>CLI tools, plugins, and extensions built by the community.</p>
                                </div>
                            </div>
                            <div class="lp-marketplace-item">
                                <div class="lp-marketplace-item-icon" style="background: rgba(245,158,11,0.15); color: var(--warning);">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <div>
                                    <h5>Courses & Mentoring</h5>
                                    <p>Learn from devs who've shipped real products.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <span class="lp-badge"><i class="bi bi-shop"></i> Marketplace</span>
                    <h2 class="lp-section-title" style="text-align: left;">Built by Developers,<br>For Developers</h2>
                    <p style="color: var(--light-muted); font-size: 1rem; line-height: 1.8; margin-bottom: 1.5rem;">
                        Skip the generic marketplaces. Trade tools, templates, and services
                        with people who actually understand what you're building.
                    </p>
                    <ul class="list-unstyled" style="color: #475569;">
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i><strong>Express Interest</strong> — tell sellers what you need</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i><strong>Direct Messaging</strong> — negotiate and collaborate</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i><strong>Save & Track</strong> — bookmark listings for later</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i><strong>Category Browse</strong> — find exactly what you need</li>
                    </ul>
                    <a href="{{ route('marketplace.index') }}" class="lp-btn-primary mt-3" style="background: linear-gradient(135deg, var(--accent), #0891b2); box-shadow: 0 4px 20px rgba(6,182,212,0.35);">
                        <i class="bi bi-shop"></i> Browse Marketplace
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="lp-cta">
        <div class="container text-center" style="position: relative; z-index: 1;" data-aos="zoom-in">
            <h2>Join the Developer Community<br>That Gets You</h2>
            <p>Whether you're shipping your first Hello World or leading a team of 50 — DevDoko is where developers come to connect, learn, and build together.</p>

            <div class="lp-cta-stat-grid">
                <div class="lp-stat-item">
                    <div class="lp-stat-num" style="font-size: 2rem; background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ number_format($stats['total_users']) }}+</div>
                    <div class="lp-stat-label">Developers</div>
                </div>
                <div class="lp-stat-item">
                    <div class="lp-stat-num" style="font-size: 2rem; background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ number_format($stats['total_posts']) }}+</div>
                    <div class="lp-stat-label">Posts Shared</div>
                </div>
                <div class="lp-stat-item">
                    <div class="lp-stat-num" style="font-size: 2rem; background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ number_format($stats['code_snippets']) }}+</div>
                    <div class="lp-stat-label">Code Snippets</div>
                </div>
                <div class="lp-stat-item">
                    <div class="lp-stat-num" style="font-size: 2rem; background: linear-gradient(135deg, #fff, rgba(255,255,255,0.7)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">100%</div>
                    <div class="lp-stat-label">Free Forever</div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="lp-btn-primary lp-btn-lg">
                    <i class="bi bi-rocket-takeoff"></i> Create Your Free Account
                </a>
                <a href="#features" class="lp-btn-ghost lp-btn-lg">
                    <i class="bi bi-play-circle"></i> See All Features
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="lp-footer">
        <div class="container">
            <div class="lp-footer-brand">
                <img src="{{ asset('assets/devdoko.png') }}" width="36" height="36" class="rounded-circle" alt="DevDoko">
                <span>DevDoko</span>
            </div>
            <p class="lp-footer-desc">
                The complete social platform for developers. Share code, join communities,
                message in real-time, find jobs, and grow your career — all in one place.
            </p>
            <div class="lp-footer-links">
                <a href="{{ route('explore') }}">Explore</a>
                <a href="{{ route('jobs.index') }}">Jobs</a>
                <a href="{{ route('marketplace.index') }}">Marketplace</a>
                <a href="{{ route('groups.index') }}">Groups</a>
                <a href="{{ route('login') }}">Log In</a>
                <a href="{{ route('register') }}">Sign Up</a>
            </div>
            <p class="lp-footer-copy">
                <i class="bi bi-c-circle me-1"></i> {{ date('Y') }} DevDoko. All rights reserved. Made with <i class="bi bi-heart-fill" style="color: #ef4444;"></i> for developers worldwide.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 80, easing: 'ease-out-cubic' });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    var offset = 80;
                    var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
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

        // Animated counters
        function animateCounters() {
            document.querySelectorAll('[data-count]').forEach(function(el) {
                var target = parseInt(el.getAttribute('data-count'));
                var duration = 2000;
                var start = 0;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toLocaleString();
                    }
                }

                requestAnimationFrame(step);
            });
        }

        // Intersection observer for counters
        var statsBar = document.querySelector('.lp-stats-bar');
        if (statsBar) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(statsBar);
        }
    </script>
</body>

</html>
