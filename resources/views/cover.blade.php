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
            --bg: #0a0e1a;
            --muted: #94a3b8;
            --text: #e2e8f0;
            --border: rgba(255, 255, 255, 0.14);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            /* the cover is a single, non-scrolling screen */
            overflow: hidden;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 18% 18%, rgba(102, 126, 234, 0.20), transparent 45%),
                radial-gradient(circle at 82% 78%, rgba(118, 75, 162, 0.20), transparent 45%);
            pointer-events: none;
        }

        .cover {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 780px;
            text-align: center;
        }

        .cover-logo {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            margin-bottom: 0.9rem;
            object-fit: cover;
        }

        .cover-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.05);
            color: var(--muted);
            font-size: 0.72rem;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            margin-bottom: 0.85rem;
        }

        .cover-title {
            font-size: clamp(1.6rem, 4vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
            margin: 0 0 0.5rem;
            line-height: 1.2;
        }

        .cover-title .accent {
            background: linear-gradient(135deg, var(--brand), var(--brand-light, #f093fb));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cover-desc {
            color: var(--muted);
            font-size: clamp(0.82rem, 1.5vw, 0.95rem);
            line-height: 1.65;
            max-width: 620px;
            margin: 0 auto 1.4rem;
        }

        table.team {
            width: 100%;
            max-width: 620px;
            margin: 0 auto 1.1rem;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.03);
        }

        table.team th,
        table.team td {
            border: 1px solid var(--border);
            padding: 0.55rem 0.9rem;
            text-align: center;
            font-size: clamp(0.78rem, 1.4vw, 0.92rem);
        }

        table.team th {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
            font-weight: 700;
        }

        table.team td { color: var(--text); }

        table.team td:last-child {
            color: var(--muted);
            font-variant-numeric: tabular-nums;
        }

        .supervisor {
            color: var(--muted);
            font-size: clamp(0.78rem, 1.4vw, 0.9rem);
            margin: 0 0 1.5rem;
        }

        .supervisor strong { color: var(--text); font-weight: 600; }

        .cover-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.6rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cover-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(102, 126, 234, 0.35);
        }

        /* Very short screens: let it scroll instead of clipping the table. */
        @media (max-height: 600px) {
            html, body { overflow: auto; height: auto; }
            body { min-height: 100%; }
        }

        @media (max-width: 575.98px) {
            table.team th,
            table.team td { padding: 0.45rem 0.4rem; }
        }
    </style>
</head>

<body>
    <main class="cover">
        <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko" class="cover-logo">

        <div class="cover-badge"><i class="bi bi-mortarboard-fill"></i> Project No. 11</div>

        <h1 class="cover-title">Social Media Platform <span class="accent">(DevDoko)</span></h1>

        <p class="cover-desc">
            A social networking platform built for developers. It brings what a developer community
            needs into one place — posts with syntax-highlighted code snippets, following and feeds,
            discussion groups, direct messaging, stories, job listings with applications, a
            marketplace, and project showcases. Built with Laravel, MySQL and Bootstrap.
        </p>

        <table class="team">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Registration No.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ([
                    ['Narayan Shrestha', '780627'],
                    ['Priyanka Karki',   '780637'],
                    ['Simran Baral',     '780646'],
                    ['Prekshya Rai',     '780635'],
                ] as [$name, $regNo])
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $regNo }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="supervisor">Supervisor: <strong>Er. Hemant Kumar Goit</strong></p>

        @auth
            <a href="{{ route('home') }}" class="cover-cta">
                Continue to DevDoko <i class="bi bi-arrow-right"></i>
            </a>
        @else
            <a href="{{ route('welcome') }}" class="cover-cta">
                Continue to DevDoko <i class="bi bi-arrow-right"></i>
            </a>
        @endauth
    </main>
</body>

</html>
