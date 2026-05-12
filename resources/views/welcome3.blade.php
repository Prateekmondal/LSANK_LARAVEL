@extends('layouts.app')
@push('pagejs')
    <script src="{{ asset('/static/js/chart.js') }}"></script>
@endpush
@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --black: #080a0d;
            --deep: #0d1117;
            --surface: #111820;
            --panel: #16202c;
            --border: rgba(255,140,0,0.15);
            --orange: #ff8c00;
            --amber: #ffb300;
            --gold: #f5a623;
            --rust: #c85a00;
            --white: #f0f2f5;
            --muted: #8a9ab0;
            --dim: #3d5066;
            --green: #00e5a0;
            --teal: #0dd6c8;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--black);
            color: var(--white);
            font-family: 'Syne', sans-serif;
            overflow-x: hidden;
        }

        /* ── NOISE TEXTURE OVERLAY ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.35;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 3rem;
            height: 64px;
            background: rgba(8,10,13,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            letter-spacing: 0.12em;
            color: var(--orange);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            color: var(--muted);
            text-decoration: none;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: var(--orange); }

        .nav-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-ghost {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.5rem 1.2rem;
            border: 1px solid var(--dim);
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-ghost:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        .btn-primary {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.5rem 1.4rem;
            background: var(--orange);
            color: var(--black);
            border: none;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--amber);
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: flex-end;
            padding: 0 5vw 8vh;
            overflow: hidden;
            perspective: 1200px;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 60% 40%, rgba(255,140,0,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 40% 50% at 85% 70%, rgba(200,90,0,0.12) 0%, transparent 60%),
                linear-gradient(160deg, #080a0d 0%, #0d1820 60%, #0a1208 100%);
        }

        /* 3D Grid floor */
        .hero-grid {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 55%;
            background-image:
                linear-gradient(rgba(255,140,0,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,140,0,0.07) 1px, transparent 1px);
            background-size: 60px 60px;
            transform: perspective(800px) rotateX(65deg);
            transform-origin: bottom center;
            mask-image: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 100%);
        }

        /* Glowing scan line */
        .hero-scanline {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--orange), transparent);
            animation: scan 6s linear infinite;
            opacity: 0.4;
        }

        @keyframes scan {
            0% { top: 64px; opacity: 0; }
            5% { opacity: 0.4; }
            95% { opacity: 0.4; }
            100% { top: 100%; opacity: 0; }
        }

        /* Depth columns in hero */
        .hero-columns {
            position: absolute;
            right: 6vw;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 16px;
            align-items: flex-end;
            height: 70vh;
        }

        .depth-col {
            width: 28px;
            border-radius: 3px 3px 0 0;
            position: relative;
            overflow: hidden;
            animation: depthPulse 3s ease-in-out infinite;
        }

        .depth-col::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.15), transparent);
        }

        .depth-col:nth-child(1) { height: 82%; background: linear-gradient(to top, #ff6600, #ff8c00, #1a3a5c); animation-delay: 0s; }
        .depth-col:nth-child(2) { height: 65%; background: linear-gradient(to top, #00c896, #0096a0, #0d2a3d); animation-delay: 0.4s; }
        .depth-col:nth-child(3) { height: 90%; background: linear-gradient(to top, #8b5cf6, #4f46e5, #1a0a3d); animation-delay: 0.8s; }
        .depth-col:nth-child(4) { height: 55%; background: linear-gradient(to top, #f59e0b, #d97706, #2a1a00); animation-delay: 1.2s; }
        .depth-col:nth-child(5) { height: 75%; background: linear-gradient(to top, #ef4444, #b91c1c, #3d0000); animation-delay: 0.6s; }

        @keyframes depthPulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        /* Depth ruler */
        .depth-ruler {
            position: absolute;
            right: 4vw;
            top: 72px;
            height: calc(100% - 72px);
            width: 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem 0;
        }

        .ruler-mark {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            color: var(--dim);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ruler-mark::before {
            content: '';
            width: 12px;
            height: 1px;
            background: var(--dim);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 720px;
        }

        .hero-eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.25em;
            color: var(--orange);
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-eyebrow::before {
            content: '';
            width: 40px;
            height: 1px;
            background: var(--orange);
        }

        .hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(4rem, 9vw, 9rem);
            line-height: 0.92;
            letter-spacing: 0.02em;
            margin-bottom: 1.5rem;
        }

        .hero-title span {
            display: block;
            color: transparent;
            -webkit-text-stroke: 1px rgba(255,140,0,0.5);
        }

        .hero-sub {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            color: var(--muted);
            max-width: 480px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .hero-cta .btn-primary {
            padding: 0.9rem 2.5rem;
            font-size: 0.8rem;
        }

        .hero-cta .btn-ghost {
            padding: 0.9rem 2rem;
            font-size: 0.8rem;
        }

        /* Zone tags */
        .zone-tags {
            position: absolute;
            left: 2.5vw;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .zone-tag {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.1rem;
            letter-spacing: 0.15em;
            padding: 2rem 0.9rem;
            border-left: 3px solid;
            opacity: 0.6;
        }

        .zone-tag:nth-child(1) { color: #6ba3be; border-color: #6ba3be; background: rgba(107,163,190,0.05); }
        .zone-tag:nth-child(2) { color: #d4a847; border-color: #d4a847; background: rgba(212,168,71,0.05); }
        .zone-tag:nth-child(3) { color: #5cb85c; border-color: #5cb85c; background: rgba(92,184,92,0.05); }

        /* ── STATS TICKER ── */
        .ticker {
            background: var(--orange);
            padding: 0.7rem 0;
            overflow: hidden;
            position: relative;
        }

        .ticker-inner {
            display: flex;
            gap: 4rem;
            animation: ticker 25s linear infinite;
            white-space: nowrap;
        }

        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .ticker-item {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: var(--black);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .ticker-item::after {
            content: '◆';
            font-size: 0.5rem;
        }

        /* ── SECTION BASE ── */
        section { padding: 7rem 5vw; }

        .section-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.3em;
            color: var(--orange);
            text-transform: uppercase;
            margin-bottom: 0.8rem;
        }

        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            line-height: 1;
            letter-spacing: 0.04em;
            margin-bottom: 1.2rem;
        }

        .section-sub {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.75;
            max-width: 500px;
        }

        /* ── CHARTS SECTION ── */
        .charts-section {
            background: var(--deep);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 4rem;
        }

        .chart-card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, border-color 0.3s;
        }

        .chart-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255,140,0,0.4);
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, var(--orange), transparent);
        }

        .chart-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            letter-spacing: 0.08em;
            color: var(--orange);
            margin-bottom: 0.5rem;
        }

        .chart-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: var(--muted);
            margin-bottom: 2rem;
        }

        /* 3D Donut Chart */
        .donut-3d {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 2rem;
            transform-style: preserve-3d;
            perspective: 400px;
        }

        .donut-3d svg {
            transform: rotateX(20deg);
            filter: drop-shadow(0 12px 24px rgba(255,140,0,0.25));
        }

        .chart-legend {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-val {
            margin-left: auto;
            color: var(--white);
            font-weight: 500;
        }

        /* 3D Bar Chart */
        .bar-chart-3d {
            display: flex;
            align-items: flex-end;
            gap: 1.5rem;
            height: 180px;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
            perspective: 600px;
        }

        .bar-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .bar-3d {
            width: 100%;
            position: relative;
            transform-style: preserve-3d;
            transform: perspective(200px) rotateX(-5deg);
            border-radius: 3px 3px 0 0;
            transition: transform 0.3s;
        }

        .bar-3d:hover {
            transform: perspective(200px) rotateX(-5deg) scaleY(1.05);
        }

        .bar-3d-face-front {
            position: absolute;
            inset: 0;
            border-radius: 3px 3px 0 0;
        }

        .bar-3d-face-top {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 8px;
            transform: rotateX(90deg);
            transform-origin: top;
        }

        .bar-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            color: var(--muted);
            text-align: center;
            text-transform: uppercase;
        }

        /* ── ACHIEVEMENTS ── */
        .achievements-section {
            position: relative;
            overflow: hidden;
        }

        .achievements-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 100%, rgba(255,140,0,0.04) 0%, transparent 70%);
        }

        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5px;
            margin-top: 4rem;
            background: var(--border);
            border: 1px solid var(--border);
        }

        .achieve-card {
            background: var(--surface);
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
            transition: background 0.3s;
            text-align: center;
        }

        .achieve-card:hover {
            background: var(--panel);
        }

        .achieve-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--orange);
            transition: width 0.4s;
        }

        .achieve-card:hover::after { width: 80%; }

        .achieve-number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4.5rem;
            color: var(--orange);
            line-height: 1;
            display: block;
        }

        .achieve-unit {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--amber);
            vertical-align: top;
            line-height: 1.4;
        }

        .achieve-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--white);
            margin-top: 0.75rem;
            letter-spacing: 0.05em;
        }

        .achieve-desc {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--muted);
            line-height: 1.6;
            margin-top: 0.5rem;
        }

        /* ── SERVICES ── */
        .services-section {
            background: var(--deep);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1px;
            margin-top: 4rem;
            background: var(--border);
        }

        .service-card {
            background: var(--surface);
            padding: 3rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: background 0.3s;
            group: true;
        }

        .service-card:hover {
            background: var(--panel);
        }

        /* 3D hover depth effect */
        .service-card:hover .service-icon-3d {
            transform: perspective(300px) rotateY(-15deg) rotateX(10deg) translateZ(20px);
        }

        .service-icon-3d {
            width: 64px;
            height: 64px;
            background: rgba(255,140,0,0.1);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            transition: transform 0.4s;
            transform-style: preserve-3d;
        }

        .service-card::before {
            content: attr(data-number);
            position: absolute;
            top: 2rem; right: 2rem;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 5rem;
            color: rgba(255,140,0,0.04);
            line-height: 1;
        }

        .service-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 0.06em;
            margin-bottom: 1rem;
        }

        .service-desc {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            color: var(--muted);
            line-height: 1.8;
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.12em;
            color: var(--orange);
            text-transform: uppercase;
            text-decoration: none;
            transition: gap 0.2s;
        }

        .service-link:hover { gap: 1rem; }

        /* ── GALLERY ── */
        .gallery-section { background: var(--black); }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(2, 260px);
            gap: 1rem;
            margin-top: 4rem;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            background: var(--surface);
            border: 1px solid var(--border);
        }

        .gallery-item:nth-child(1) { grid-column: span 5; grid-row: span 2; }
        .gallery-item:nth-child(2) { grid-column: span 4; }
        .gallery-item:nth-child(3) { grid-column: span 3; }
        .gallery-item:nth-child(4) { grid-column: span 3; }
        .gallery-item:nth-child(5) { grid-column: span 4; }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s, filter 0.4s;
            filter: brightness(0.7) saturate(0.8);
        }

        .gallery-item:hover img {
            transform: scale(1.06);
            filter: brightness(0.9) saturate(1.1);
        }

        .gallery-label {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 1.5rem;
            background: linear-gradient(transparent, rgba(8,10,13,0.9));
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            color: var(--muted);
            text-transform: uppercase;
            transform: translateY(100%);
            transition: transform 0.3s;
        }

        .gallery-item:hover .gallery-label { transform: translateY(0); }

        /* Gallery placeholder visuals */
        .gallery-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }

        .gallery-ph-icon {
            font-size: 3rem;
            opacity: 0.15;
        }

        .gallery-ph-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--dim);
            letter-spacing: 0.1em;
        }

        /* ── CONTACT ── */
        .contact-section {
            background: var(--deep);
            border-top: 1px solid var(--border);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            margin-top: 4rem;
        }

        .contact-info-item {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            color: var(--orange);
        }

        .contact-info-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 0.15em;
            color: var(--dim);
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .contact-info-val {
            font-family: 'Syne', sans-serif;
            font-size: 0.9rem;
            color: var(--white);
            line-height: 1.5;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-input {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 1rem 1.2rem;
            color: var(--white);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: var(--orange);
        }

        .form-input::placeholder {
            color: var(--dim);
            letter-spacing: 0.05em;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 120px;
        }

        .form-submit {
            width: 100%;
            padding: 1rem;
            background: var(--orange);
            color: var(--black);
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.1rem;
            letter-spacing: 0.2em;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .form-submit:hover {
            background: var(--amber);
            transform: translateY(-2px);
        }

        /* ── FOOTER ── */
        footer {
            background: var(--black);
            border-top: 1px solid var(--border);
            padding: 5rem 5vw 2.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }

        .footer-brand-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            letter-spacing: 0.1em;
            color: var(--orange);
            margin-bottom: 1rem;
        }

        .footer-brand-desc {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: var(--muted);
            line-height: 1.8;
            max-width: 280px;
        }

        .footer-col-title {
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: var(--white);
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-links a {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--orange); }
        .footer-links a::before { content: '→'; font-size: 0.6rem; }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
        }

        .footer-copy {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--dim);
        }

        .footer-credit {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--dim);
        }

        .footer-credit span { color: var(--orange); }

        /* ── ANIMATIONS ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.7s ease forwards;
            opacity: 0;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.25s; }
        .delay-3 { animation-delay: 0.4s; }
        .delay-4 { animation-delay: 0.55s; }

        /* Intersection observer fade */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            nav { padding: 0 1.5rem; }
            .nav-links { display: none; }
            .charts-grid, .contact-grid { grid-template-columns: 1fr; }
            .achievements-grid { grid-template-columns: repeat(2, 1fr); }
            .services-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 2.5rem; }
            .gallery-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
            .gallery-item { grid-column: span 1 !important; grid-row: span 1 !important; height: 220px; }
            .zone-tags { display: none; }
            .hero-columns { right: 1vw; }
            .depth-ruler { display: none; }
        }
    </style>
@endpush

@section('content')
{{-- ── NAVIGATION ── --}}
<nav>
    <a href="#" class="nav-logo">LS // Ankleshwar</a>
    <ul class="nav-links">
        <li><a href="#services">Services</a></li>
        <li><a href="#stats">Stats</a></li>
        <li><a href="#gallery">Projects</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="#">JCR</a></li>
        <li><a href="#">Checklists</a></li>
        <li><a href="#">Time Register</a></li>
    </ul>
    <div class="nav-actions">
        <a href="{{ route('login') }}" class="btn-ghost">Login</a>
        <a href="{{ route('register') }}" class="btn-primary">Register</a>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="hero-scanline"></div>

    {{-- Stratigraphic zone labels --}}
    <div class="zone-tags">
        <div class="zone-tag">Uninvaded Zone</div>
        <div class="zone-tag">Transition Zone</div>
        <div class="zone-tag">Flushed Zone</div>
    </div>

    {{-- Log column visualization --}}
    <div class="hero-columns">
        <div class="depth-col"></div>
        <div class="depth-col"></div>
        <div class="depth-col"></div>
        <div class="depth-col"></div>
        <div class="depth-col"></div>
    </div>

    {{-- Depth ruler --}}
    <div class="depth-ruler">
        <div class="ruler-mark">200m</div>
        <div class="ruler-mark">400m</div>
        <div class="ruler-mark">600m</div>
        <div class="ruler-mark">800m</div>
        <div class="ruler-mark">1000m</div>
        <div class="ruler-mark">1200m</div>
    </div>

    <div class="hero-content">
        <div class="hero-eyebrow animate-in delay-1">Est. 1968 · Ankleshwar, Gujarat</div>
        <h1 class="hero-title animate-in delay-2">
            Well Logging
            <span>Services</span>
        </h1>
        <p class="hero-sub animate-in delay-3">
            Providing accurate and reliable borehole data collection and analysis for over 60 years in Ankleshwar and surrounding regions.
        </p>
        <div class="hero-cta animate-in delay-4">
            <a href="#contact" class="btn-primary">Contact Us</a>
            <a href="#services" class="btn-ghost">Our Services →</a>
        </div>
    </div>
</section>

{{-- ── TICKER ── --}}
<div class="ticker">
    <div class="ticker-inner">
        <div class="ticker-item">500+ Projects Completed</div>
        <div class="ticker-item">98% Accuracy Rate</div>
        <div class="ticker-item">20+ Years Experience</div>
        <div class="ticker-item">50+ Clients Served</div>
        <div class="ticker-item">FY 2024–25 Active</div>
        <div class="ticker-item">Oil · Gas · Water Sectors</div>
        <div class="ticker-item">500+ Projects Completed</div>
        <div class="ticker-item">98% Accuracy Rate</div>
        <div class="ticker-item">20+ Years Experience</div>
        <div class="ticker-item">50+ Clients Served</div>
        <div class="ticker-item">FY 2024–25 Active</div>
        <div class="ticker-item">Oil · Gas · Water Sectors</div>
    </div>
</div>

{{-- ── CHARTS SECTION ── --}}
<section class="charts-section" id="stats">
    <div class="section-label">Analytics · FY 2024–25</div>
    <h2 class="section-title">Logging Job Distribution</h2>

    <div class="charts-grid">

        {{-- Donut Chart --}}
        <div class="chart-card reveal">
            <div class="chart-title">Job Type Breakdown</div>
            <div class="chart-sub">Completed jobs for the Financial Year 2024-25</div>

            <div class="donut-3d">
                <svg viewBox="0 0 200 200" width="200" height="200">
                    <defs>
                        <filter id="shadow">
                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#ff8c00" flood-opacity="0.3"/>
                        </filter>
                    </defs>
                    {{-- Open Hole: 180 of 310 = 58% --}}
                    <circle cx="100" cy="100" r="70" fill="none" stroke="#1a3a5c" stroke-width="32"/>
                    <circle cx="100" cy="100" r="70" fill="none" stroke="#2196F3" stroke-width="32"
                        stroke-dasharray="254 186" stroke-dashoffset="0" filter="url(#shadow)"/>
                    <circle cx="100" cy="100" r="70" fill="none" stroke="#ff8c00" stroke-width="32"
                        stroke-dasharray="138 302" stroke-dashoffset="-254"/>
                    <circle cx="100" cy="100" r="70" fill="none" stroke="#f5a623" stroke-width="32"
                        stroke-dasharray="48 392" stroke-dashoffset="-392"/>
                    <circle cx="100" cy="100" r="54" fill="#111820"/>
                    <text x="100" y="95" text-anchor="middle" font-family="Bebas Neue, sans-serif" font-size="28" fill="#f0f2f5">310</text>
                    <text x="100" y="113" text-anchor="middle" font-family="JetBrains Mono, monospace" font-size="9" fill="#8a9ab0">TOTAL JOBS</text>
                </svg>
            </div>

            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#2196F3"></div>
                    Open Hole Logging
                    <span class="legend-val">180 jobs</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#ff8c00"></div>
                    Cased Hole Logging
                    <span class="legend-val">80 jobs</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#f5a623"></div>
                    Production Logging
                    <span class="legend-val">50 jobs</span>
                </div>
            </div>
        </div>

        {{-- Bar Chart --}}
        <div class="chart-card reveal">
            <div class="chart-title">Support Distribution</div>
            <div class="chart-sub">Completed jobs by agency type for FY 2024-25</div>

            <div class="bar-chart-3d">
                <div class="bar-group">
                    <div class="bar-3d" style="height:160px; background: linear-gradient(to top, #2196F3, #42a5f5);">
                        <div class="bar-3d-face-top" style="background:#64b5f6;"></div>
                    </div>
                    <div class="bar-label">Dept. Internal</div>
                </div>
                <div class="bar-group">
                    <div class="bar-3d" style="height:90px; background: linear-gradient(to top, #00c896, #26d9a8);">
                        <div class="bar-3d-face-top" style="background:#4de8bc;"></div>
                    </div>
                    <div class="bar-label">Contractual</div>
                </div>
            </div>

            <div class="chart-legend" style="margin-top:1.5rem;">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#2196F3"></div>
                    Departmental (Internal)
                    <span class="legend-val">~65%</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#00c896"></div>
                    Contractual
                    <span class="legend-val">~35%</span>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ── ACHIEVEMENTS ── --}}
<section class="achievements-section">
    <div class="achievements-bg"></div>
    <div class="section-label">Track Record</div>
    <h2 class="section-title">Our Achievements</h2>

    <div class="achievements-grid">
        <div class="achieve-card reveal">
            <span class="achieve-number">500<span class="achieve-unit">+</span></span>
            <div class="achieve-label">Projects Completed</div>
            <div class="achieve-desc">Successfully delivered logging services for over 500 projects across Gujarat</div>
        </div>
        <div class="achieve-card reveal">
            <span class="achieve-number">98<span class="achieve-unit">%</span></span>
            <div class="achieve-label">Accuracy Rate</div>
            <div class="achieve-desc">Consistently high accuracy in data collection and interpretation</div>
        </div>
        <div class="achieve-card reveal">
            <span class="achieve-number">20<span class="achieve-unit">+</span></span>
            <div class="achieve-label">Years Experience</div>
            <div class="achieve-desc">Two decades of expertise in well logging and borehole analysis</div>
        </div>
        <div class="achieve-card reveal">
            <span class="achieve-number">50<span class="achieve-unit">+</span></span>
            <div class="achieve-label">Clients Served</div>
            <div class="achieve-desc">Trusted by major companies in the oil, gas, and water sectors</div>
        </div>
    </div>
</section>

{{-- ── GALLERY ── --}}
<section class="gallery-section" id="gallery">
    <div class="section-label">Field Operations</div>
    <h2 class="section-title">Project Gallery</h2>
    <p class="section-sub">Explore our recent well logging projects and field operations across Gujarat and surrounding regions.</p>

    <div class="gallery-grid">
        <div class="gallery-item reveal">
            <div class="gallery-placeholder" style="background: linear-gradient(135deg, #0d1a2a, #1a3a5c);">
                <div class="gallery-ph-icon">🛢️</div>
                <div class="gallery-ph-text">Oil Well Drilling Site</div>
            </div>
            <div class="gallery-label">Oil Well Drilling Site</div>
        </div>
        <div class="gallery-item reveal">
            <div class="gallery-placeholder" style="background: linear-gradient(135deg, #1a2a0d, #2a4a1a);">
                <div class="gallery-ph-icon">📊</div>
                <div class="gallery-ph-text">Data Analysis Team</div>
            </div>
            <div class="gallery-label">Data Analysis Team</div>
        </div>
        <div class="gallery-item reveal">
            <div class="gallery-placeholder" style="background: linear-gradient(135deg, #2a1a0d, #4a2a1a);">
                <div class="gallery-ph-icon">🔧</div>
                <div class="gallery-ph-text">Logging Equipment</div>
            </div>
            <div class="gallery-label">Logging Equipment</div>
        </div>
        <div class="gallery-item reveal">
            <div class="gallery-placeholder" style="background: linear-gradient(135deg, #1a0d2a, #3a1a4a);">
                <div class="gallery-ph-icon">⚙️</div>
                <div class="gallery-ph-text">Field Team in Action</div>
            </div>
            <div class="gallery-label">Field Team in Action</div>
        </div>
        <div class="gallery-item reveal">
            <div class="gallery-placeholder" style="background: linear-gradient(135deg, #0d2a2a, #1a4a4a);">
                <div class="gallery-ph-icon">📡</div>
                <div class="gallery-ph-text">Borehole Operations</div>
            </div>
            <div class="gallery-label">Borehole Operations</div>
        </div>
    </div>
</section>

{{-- ── SERVICES ── --}}
<section class="services-section" id="services">
    <div class="section-label">What We Do</div>
    <h2 class="section-title">Our Services</h2>
    <p class="section-sub">Comprehensive well logging solutions tailored to your needs across oil, gas, and water sectors.</p>

    <div class="services-grid" style="margin-top: 4rem;">
        <div class="service-card reveal" data-number="01">
            <div class="service-icon-3d">🕳️</div>
            <div class="service-name">Borehole Logging</div>
            <p class="service-desc">Comprehensive logging of subsurface formations using advanced geophysical tools. Measures porosity, gamma ray, caliper log, cement logging and continuous along with borehole geometry.</p>
            <a href="#" class="service-link">Learn More →</a>
        </div>
        <div class="service-card reveal" data-number="02">
            <div class="service-icon-3d">📈</div>
            <div class="service-name">Data Interpretation</div>
            <p class="service-desc">Expert analysis and interpretation of logging data with detailed reports. Transforming raw borehole data into actionable geological insights for informed decision making.</p>
            <a href="#" class="service-link">Learn More →</a>
        </div>
        <div class="service-card reveal" data-number="03">
            <div class="service-icon-3d">⛽</div>
            <div class="service-name">Oil & Gas Logging</div>
            <p class="service-desc">Advanced logging services for oil and gas subsurface layers and reservoir evaluation. Specialized formation evaluation and production optimization across exploration wells.</p>
            <a href="#" class="service-link">Learn More →</a>
        </div>
        <div class="service-card reveal" data-number="04">
            <div class="service-icon-3d">🔩</div>
            <div class="service-name">Equipment Rental</div>
            <p class="service-desc">High-quality logging equipment available for rent with full technical support and on-site assistance. State-of-the-art tools calibrated for precision subsurface measurement.</p>
            <a href="#" class="service-link">Learn More →</a>
        </div>
    </div>
</section>

{{-- ── CONTACT ── --}}
<section class="contact-section" id="contact">
    <div class="section-label">Get In Touch</div>
    <h2 class="section-title">Contact Us</h2>
    <p class="section-sub">Get in touch for your well logging requirements. We're here to help.</p>

    <div class="contact-grid">

        {{-- Info --}}
        <div>
            <div class="contact-info-item reveal">
                <div class="contact-icon">📍</div>
                <div>
                    <div class="contact-info-label">Office Address</div>
                    <div class="contact-info-val">ONGC, Ankleshwar<br>Gujarat 393001, India</div>
                </div>
            </div>
            <div class="contact-info-item reveal">
                <div class="contact-icon">📞</div>
                <div>
                    <div class="contact-info-label">Phone</div>
                    <div class="contact-info-val">+91 1234567890</div>
                </div>
            </div>
            <div class="contact-info-item reveal">
                <div class="contact-icon">✉️</div>
                <div>
                    <div class="contact-info-label">Email</div>
                    <div class="contact-info-val">info@loggingservicesankleshwar.com</div>
                </div>
            </div>
            <div class="contact-info-item reveal">
                <div class="contact-icon">🕐</div>
                <div>
                    <div class="contact-info-label">Working Hours</div>
                    <div class="contact-info-val">Monday – Friday: 09:30 AM – 05:30 PM</div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="reveal">
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="name" class="form-input" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-input" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <textarea name="message" class="form-input" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="form-submit">Send Message</button>
            </form>
        </div>

    </div>
</section>

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand-name">Logging Services<br>Ankleshwar</div>
            <p class="footer-brand-desc">
                Logging Services, Ankleshwar based since its inception in 1968 has maintained high standards of work ethics and has kept this flag of success aloft in the field of oil and gas exploration and exploitation, serving the petroleum industry by providing best-quality products, amassing big data.
            </p>
        </div>
        <div>
            <div class="footer-col-title">Navigation</div>
            <ul class="footer-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Add New JCR</a></li>
                <li><a href="#">Checklists</a></li>
                <li><a href="#">Time Register</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-col-title">Services</div>
            <ul class="footer-links">
                <li><a href="#">Borehole Logging</a></li>
                <li><a href="#">Oil & Gas Logging</a></li>
                <li><a href="#">Data Interpretation</a></li>
                <li><a href="#">Equipment Rental</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-col-title">Contact</div>
            <ul class="footer-links">
                <li><a href="#">ONGC, Ankleshwar</a></li>
                <li><a href="#">+91 123 456 7890</a></li>
                <li><a href="#">+91 123 456 7890</a></li>
                <li><a href="#">info@lsankl.com</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-copy">© 2026 Logging Services, Ankleshwar. All rights reserved.</div>
        <div class="footer-credit">Developed & Designed by <span>Pratesh Mandal</span></div>
    </div>
</footer>

<script>
    // Intersection Observer for reveal animations
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    reveals.forEach(el => observer.observe(el));

    // Parallax hero depth columns
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        const cols = document.querySelectorAll('.depth-col');
        cols.forEach((col, i) => {
            col.style.transform = `translateY(${scrollY * (0.04 + i * 0.015)}px)`;
        });
    });
</script>
@endsection