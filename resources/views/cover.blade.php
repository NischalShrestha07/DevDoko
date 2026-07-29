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
            margin-bottom: 0.75rem;
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
            margin-bottom: 0.7rem;
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
            margin: 0 0 0.85rem;
            max-width: 46ch;
        }

        /* feature chips replace the long paragraph */
        .features {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.5rem;
            margin-bottom: 0.85rem;
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
            margin-bottom: 0.9rem;
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
        .team-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            background: var(--card);
            margin-bottom: 0.85rem;
        }

        .team-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1.05rem;
            background: rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid var(--border);
        }

        .team-head h2 {
            margin: 0;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #fff;
        }

        .team-head .count {
            font-size: 0.76rem;
            color: var(--muted);
            font-weight: 500;
        }

        table.team {
            width: 100%;
            border-collapse: collapse;
        }

        table.team td {
            padding: 0.6rem 1.05rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            vertical-align: middle;
        }

        table.team tr:last-child td { border-bottom: none; }
        table.team tbody tr:hover td { background: rgba(255, 255, 255, 0.035); }

        .member {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            letter-spacing: 0.02em;
        }

        .member-name {
            font-size: clamp(0.95rem, 1.3vw, 1.08rem);
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.01em;
        }

        td.symbol-cell { text-align: right; white-space: nowrap; width: 1%; }

        .symbol {
            display: inline-block;
            padding: 0.25rem 0.7rem;
            border-radius: 8px;
            background: rgba(102, 126, 234, 0.15);
            border: 1px solid rgba(102, 126, 234, 0.34);
            color: #c3cdf8;
            font-size: clamp(0.92rem, 1.25vw, 1.05rem);
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.03em;
        }

        /* ---------- supervisor ---------- */
        .sup-card {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.68rem 1.05rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--card);
            margin-bottom: 1rem;
        }

        .sup-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .sup-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--muted);
            font-weight: 600;
        }

        .sup-name {
            font-size: clamp(0.98rem, 1.35vw, 1.12rem);
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.01em;
        }

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
        @media (min-width: 992px) and (max-height: 800px) {
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

            @php
                // initials precomputed here rather than inline in the markup
                $members = [
                    ['name' => 'Narayan Shrestha', 'symbol' => '780627', 'initials' => 'NS', 'grad' => 'linear-gradient(135deg,#667eea,#764ba2)'],
                    ['name' => 'Priyanka Karki',   'symbol' => '780637', 'initials' => 'PK', 'grad' => 'linear-gradient(135deg,#06b6d4,#3b82f6)'],
                    ['name' => 'Simran Baral',     'symbol' => '780646', 'initials' => 'SB', 'grad' => 'linear-gradient(135deg,#10b981,#059669)'],
                    ['name' => 'Prekshya Rai',     'symbol' => '780635', 'initials' => 'PR', 'grad' => 'linear-gradient(135deg,#f472b6,#a78bfa)'],
                ];
            @endphp

            <div class="team-card anim d3">
                <div class="team-head">
                    <h2>Submitted By</h2>
                    <span class="count">{{ count($members) }} members</span>
                </div>
                <table class="team">
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td>
                                    <div class="member">
                                        <span class="avatar" style="background: {{ $member['grad'] }}">{{ $member['initials'] }}</span>
                                        <span class="member-name">{{ $member['name'] }}</span>
                                    </div>
                                </td>
                                <td class="symbol-cell">
                                    <span class="symbol">{{ $member['symbol'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sup-card anim d3">
                <span class="sup-icon"><i class="bi bi-person-badge-fill"></i></span>
                <div>
                    <div class="sup-label">Project Supervisor</div>
                    <div class="sup-name">Er. Hemant Kumar Goit</div>
                </div>
            </div>

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
