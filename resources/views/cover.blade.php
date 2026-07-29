<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Platform (DevDoko) — Project</title>

    <link rel="icon" href="{{ asset('assets/devdokoIcon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand: #667eea;
            --brand-dark: #764ba2;
            --brand-light: #f093fb;
            --accent: #06b6d4;
            --bg: #0a0e1a;
            --muted: #94a3b8;
            --text: #e2e8f0;
            --border: rgba(255, 255, 255, 0.12);
            --card: rgba(255, 255, 255, 0.035);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden; /* single, non-scrolling screen */
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 32px;
        }

        /* ambient background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 12% 15%, rgba(102, 126, 234, 0.22), transparent 42%),
                radial-gradient(circle at 88% 80%, rgba(118, 75, 162, 0.22), transparent 42%),
                radial-gradient(circle at 60% 8%, rgba(6, 182, 212, 0.10), transparent 38%);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.022) 1px, transparent 1px);
            background-size: 54px 54px;
            mask-image: radial-gradient(ellipse at center, #000 35%, transparent 78%);
            -webkit-mask-image: radial-gradient(ellipse at center, #000 35%, transparent 78%);
            pointer-events: none;
        }

        .wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1240px;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
            gap: clamp(24px, 4vw, 56px);
            align-items: center;
        }

        /* ---------- left ---------- */
        .brand-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .brand-row img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        .brand-row span {
            font-weight: 700;
            font-size: 1.05rem;
            color: #fff;
            letter-spacing: -0.01em;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(102, 126, 234, 0.4);
            background: rgba(102, 126, 234, 0.12);
            color: #b9c4f5;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            margin-bottom: 0.85rem;
        }

        h1 {
            font-size: clamp(1.5rem, 2.9vw, 2.35rem);
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #fff;
            margin: 0 0 0.45rem;
            line-height: 1.15;
        }

        h1 .accent {
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tagline {
            color: var(--muted);
            font-size: clamp(0.82rem, 1.2vw, 0.95rem);
            line-height: 1.55;
            margin: 0 0 1.1rem;
            max-width: 46ch;
        }

        /* feature chips replace the long paragraph */
        .features {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.5rem;
            margin-bottom: 1.1rem;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.6rem;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--card);
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .feature i { font-size: 0.95rem; flex-shrink: 0; }

        .stack {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-bottom: 1.15rem;
        }

        .stack span {
            padding: 0.22rem 0.62rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border);
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: 0.02em;
        }

        /* ---------- team table ---------- */
        table.team {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0.7rem;
        }

        table.team th,
        table.team td {
            border: 1px solid var(--border);
            padding: 0.42rem 0.8rem;
            text-align: left;
            font-size: clamp(0.74rem, 1.05vw, 0.85rem);
        }

        table.team th {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        table.team td { color: var(--text); }

        table.team td:last-child {
            color: var(--muted);
            font-variant-numeric: tabular-nums;
            text-align: right;
            width: 34%;
        }

        table.team tbody tr:hover td { background: rgba(255, 255, 255, 0.03); }

        .supervisor {
            color: var(--muted);
            font-size: 0.78rem;
            margin: 0 0 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .supervisor strong { color: var(--text); font-weight: 600; }

        .cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.68rem 1.5rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.28);
        }

        .cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.42);
        }

        .cta i { transition: transform 0.2s ease; }
        .cta:hover i { transform: translateX(3px); }

        /* ---------- right: screenshot stack ---------- */
        .shots {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3.4;
            perspective: 1400px;
        }

        .shot {
            position: absolute;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55);
            object-fit: cover;
            object-position: top left;
            background: #111827;
        }

        .shot-main {
            width: 82%;
            top: 6%;
            left: 0;
            z-index: 3;
            transform: rotate(-2deg);
            transition: transform 0.4s ease;
        }

        .shot-back {
            width: 62%;
            top: 0;
            right: 0;
            z-index: 2;
            opacity: 0.9;
            transform: rotate(3deg);
            transition: transform 0.4s ease;
        }

        .shot-front {
            width: 56%;
            bottom: 0;
            right: 2%;
            z-index: 4;
            transform: rotate(2deg);
            transition: transform 0.4s ease;
        }

        .shots:hover .shot-main  { transform: rotate(-1deg) translateY(-5px); }
        .shots:hover .shot-back  { transform: rotate(2deg) translateY(-5px); }
        .shots:hover .shot-front { transform: rotate(1deg) translateY(-5px); }

        .shot-glow {
            position: absolute;
            inset: 8% 4%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.32), transparent 68%);
            filter: blur(46px);
            z-index: 1;
        }

        /* entrance */
        @keyframes rise {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .anim { animation: rise 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .d1 { animation-delay: 0.05s; }
        .d2 { animation-delay: 0.12s; }
        .d3 { animation-delay: 0.19s; }
        .d4 { animation-delay: 0.26s; }

        @media (prefers-reduced-motion: reduce) {
            .anim { animation: none; }
            .shots:hover .shot-main,
            .shots:hover .shot-back,
            .shots:hover .shot-front { transform: none; }
        }

        /* ---------- responsive ---------- */
        @media (max-width: 991.98px) {
            html, body { overflow: auto; height: auto; }
            body { min-height: 100%; padding: 32px 20px; }
            .wrap { grid-template-columns: 1fr; gap: 32px; }
            .shots { order: -1; max-width: 520px; margin: 0 auto; }
        }

        @media (max-width: 575.98px) {
            .features { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            table.team th, table.team td { padding: 0.4rem 0.55rem; }
        }

        /* short desktop screens: let it scroll rather than clip the content */
        @media (min-width: 992px) and (max-height: 700px) {
            html, body { overflow: auto; height: auto; }
            body { min-height: 100%; }
        }
    </style>
</head>

<body>
    <div class="wrap">

        <!-- ---------- project info ---------- -->
        <section>
            <div class="brand-row anim d1">
                <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko logo">
                <span>DevDoko</span>
            </div>

            <div class="badge-pill anim d1"><i class="bi bi-mortarboard-fill"></i> Project No. 11</div>

            <h1 class="anim d1">Social Media Platform <span class="accent">(DevDoko)</span></h1>

            <p class="tagline anim d2">
                A social network built for developers — share code, find work, and build together.
            </p>

            <div class="features anim d2">
                @foreach ([
                    ['bi-code-slash',      'Code Posts',   '#06b6d4'],
                    ['bi-people-fill',     'Groups',       '#10b981'],
                    ['bi-chat-dots-fill',  'Messaging',    '#f59e0b'],
                    ['bi-briefcase-fill',  'Jobs',         '#ef4444'],
                    ['bi-shop',            'Marketplace',  '#a78bfa'],
                    ['bi-camera-reels-fill','Stories',     '#f472b6'],
                ] as [$icon, $label, $color])
                    <div class="feature">
                        <i class="bi {{ $icon }}" style="color: {{ $color }}"></i>{{ $label }}
                    </div>
                @endforeach
            </div>

            <div class="stack anim d2">
                @foreach (['Laravel', 'MySQL', 'Bootstrap', 'JavaScript', 'Blade'] as $tech)
                    <span>{{ $tech }}</span>
                @endforeach
            </div>

            <table class="team anim d3">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th style="text-align: right;">Symbol No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['Narayan Shrestha', '780627'],
                        ['Priyanka Karki',   '780637'],
                        ['Simran Baral',     '780646'],
                        ['Prekshya Rai',     '780635'],
                    ] as [$name, $symbolNo])
                        <tr>
                            <td>{{ $name }}</td>
                            <td>{{ $symbolNo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="supervisor anim d3">
                <i class="bi bi-person-badge"></i>
                Supervisor: <strong>Er. Hemant Kumar Goit</strong>
            </p>

            <a href="{{ auth()->check() ? route('home') : route('welcome') }}" class="cta anim d4">
                Continue to DevDoko <i class="bi bi-arrow-right"></i>
            </a>
        </section>

        <!-- ---------- app screenshots ---------- -->
        <section class="shots anim d3" aria-label="Screenshots of the DevDoko platform">
            <div class="shot-glow"></div>
            <img class="shot shot-back"  src="{{ asset('assets/explore.png') }}" alt="Explore page">
            <img class="shot shot-main"  src="{{ asset('assets/home.png') }}"    alt="Home feed">
            <img class="shot shot-front" src="{{ asset('assets/groups.png') }}"  alt="Groups page">
        </section>

    </div>
</body>

</html>
