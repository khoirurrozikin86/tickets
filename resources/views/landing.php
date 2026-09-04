<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket Dusun Semilir</title>

    <meta name="description"
        content="Pesan tiket Dusun Semilir dengan mudah. Pilih tiket, tentukan tanggal kunjungan, bayar dengan QRIS, dan dapatkan e-ticket.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --green-950: #022c22;
            --green-900: #064e3b;
            --green-800: #065f46;
            --green-700: #047857;
            --green-600: #059669;
            --green-500: #10b981;
            --green-400: #34d399;
            --green-100: #d1fae5;
            --green-50: #ecfdf5;

            --orange-500: #f97316;
            --orange-600: #ea580c;

            --dark: #0f172a;
            --muted: #64748b;

            --white: #ffffff;
            --background: #f7fbf9;

            --radius-xl: 32px;
            --radius-lg: 24px;
            --radius-md: 18px;

            --shadow-lg:
                0 25px 70px rgba(6, 78, 59, .10);

            --shadow-md:
                0 15px 40px rgba(6, 78, 59, .08);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            color: var(--dark);
            background: var(--background);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }

        /* =========================================
           PAGE BACKGROUND
        ========================================= */

        .page {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .bg-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(2px);
            z-index: 0;
        }

        .bg-orb.one {
            width: 420px;
            height: 420px;
            top: -180px;
            left: -160px;
            background: rgba(52, 211, 153, .12);
            animation: floatOrb 8s ease-in-out infinite;
        }

        .bg-orb.two {
            width: 520px;
            height: 520px;
            right: -220px;
            top: 350px;
            background: rgba(16, 185, 129, .08);
            animation: floatOrb 10s ease-in-out infinite reverse;
        }

        /* =========================================
           HEADER
        ========================================= */

        .header {
            position: relative;
            z-index: 20;

            width: min(1240px, calc(100% - 40px));
            margin: 0 auto;

            height: 78px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 15px;

            background: var(--green-100);
            color: var(--green-700);

            font-size: 23px;
        }

        .brand-text strong {
            display: block;
            font-size: 17px;
            font-weight: 900;
            color: var(--green-950);
        }

        .brand-text span {
            display: block;
            margin-top: 2px;
            color: #7b9088;
            font-size: 11px;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .nav a {
            padding: 10px 15px;
            border-radius: 12px;

            color: #52675f;
            font-size: 13px;
            font-weight: 600;

            transition: .25s ease;
        }

        .nav a:hover,
        .nav a.active {
            color: var(--green-700);
            background: var(--green-50);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .language {
            display: flex;
            align-items: center;
            gap: 7px;

            padding: 10px 13px;

            border: 1px solid #e2ebe7;
            border-radius: 12px;

            background: rgba(255, 255, 255, .8);

            font-size: 12px;
            font-weight: 700;
            color: var(--green-800);
        }

        .cart-button {
            position: relative;

            display: flex;
            align-items: center;
            gap: 8px;

            padding: 11px 15px;

            color: white;
            background: var(--green-700);

            border-radius: 13px;

            font-size: 13px;
            font-weight: 800;

            box-shadow: 0 10px 25px rgba(4, 120, 87, .18);

            transition: .25s ease;
        }

        .cart-button:hover {
            transform: translateY(-2px);
            background: var(--green-800);
        }

        .cart-count {
            min-width: 19px;
            height: 19px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            color: white;
            background: var(--orange-500);

            font-size: 10px;
        }

        /* =========================================
           HERO
        ========================================= */

        .hero {
            position: relative;
            z-index: 1;

            width: min(1240px, calc(100% - 40px));
            min-height: 570px;

            margin: 25px auto 0;

            display: grid;
            grid-template-columns: 1fr 1fr;

            align-items: center;

            padding: 60px;

            overflow: hidden;

            border-radius: var(--radius-xl);

            background:
                radial-gradient(circle at 70% 30%,
                    rgba(255, 255, 255, .9),
                    transparent 28%),
                linear-gradient(135deg,
                    #f0fdf7 0%,
                    #d1fae5 52%,
                    #a7f3d0 100%);

            border: 1px solid rgba(5, 150, 105, .10);

            box-shadow: var(--shadow-lg);
        }

        .hero::before {
            content: "";

            position: absolute;

            width: 430px;
            height: 430px;

            right: -170px;
            top: -210px;

            border-radius: 50%;

            background: rgba(255, 255, 255, .25);
        }

        .hero-content {
            position: relative;
            z-index: 3;

            max-width: 600px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 9px 15px;

            border-radius: 999px;

            background: rgba(255, 255, 255, .78);

            color: var(--green-700);

            font-size: 12px;
            font-weight: 800;

            box-shadow: 0 8px 25px rgba(6, 78, 59, .06);
        }

        .eyebrow-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: var(--green-500);

            animation: pulse 2s infinite;
        }

        .hero-title {
            margin: 24px 0 18px;

            font-size: clamp(44px, 5.5vw, 72px);
            line-height: .98;

            letter-spacing: -3px;

            font-weight: 900;

            color: var(--green-950);
        }

        .hero-title span {
            color: var(--green-600);
        }

        .hero-description {
            max-width: 570px;

            margin: 0 0 30px;

            color: #55766a;

            font-size: 17px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            padding: 14px 21px;

            border-radius: 14px;

            color: white;
            background: linear-gradient(135deg,
                    var(--green-700),
                    var(--green-500));

            font-size: 14px;
            font-weight: 800;

            box-shadow:
                0 15px 30px rgba(5, 150, 105, .22);

            transition: .25s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow:
                0 20px 38px rgba(5, 150, 105, .28);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            padding: 13px 20px;

            border-radius: 14px;

            color: var(--green-800);

            background: rgba(255, 255, 255, .75);

            border: 1px solid rgba(5, 150, 105, .14);

            font-size: 14px;
            font-weight: 700;

            transition: .25s ease;
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-2px);
        }

        /* =========================================
           HERO VISUAL
        ========================================= */

        .hero-visual {
            position: relative;

            min-height: 430px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visual-circle {
            position: absolute;

            width: 360px;
            height: 360px;

            border-radius: 50%;

            background:
                radial-gradient(circle,
                    rgba(255, 255, 255, .95),
                    rgba(255, 255, 255, .25));

            box-shadow:
                0 0 90px rgba(255, 255, 255, .75);
        }

        .visual-card {
            position: absolute;
            z-index: 4;

            padding: 17px 20px;

            border-radius: 18px;

            background: rgba(255, 255, 255, .94);

            border: 1px solid rgba(5, 150, 105, .10);

            box-shadow:
                0 20px 45px rgba(6, 78, 59, .13);

            backdrop-filter: blur(10px);

            animation: cardFloat 4s ease-in-out infinite;
        }

        .visual-card.top {
            top: 30px;
            right: 5px;
        }

        .visual-card.bottom {
            bottom: 25px;
            left: 0;
            animation-delay: -2s;
        }

        .visual-card strong {
            display: block;

            color: var(--green-800);

            font-size: 17px;
            font-weight: 900;
        }

        .visual-card span {
            display: block;

            margin-top: 3px;

            color: #71877e;

            font-size: 11px;
        }

        .ticket-stack {
            position: relative;
            z-index: 3;

            width: 285px;

            padding: 25px;

            border-radius: 25px;

            background: white;

            box-shadow:
                0 30px 70px rgba(6, 78, 59, .16);

            transform: rotate(-4deg);

            animation: ticketFloat 5s ease-in-out infinite;
        }

        .ticket-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 22px;
        }

        .ticket-brand {
            color: var(--green-700);
            font-size: 12px;
            font-weight: 900;
        }

        .ticket-status {
            padding: 5px 8px;

            border-radius: 999px;

            color: var(--green-700);
            background: var(--green-50);

            font-size: 9px;
            font-weight: 800;
        }

        .ticket-title {
            margin-bottom: 5px;

            color: var(--green-950);

            font-size: 24px;
            font-weight: 900;
        }

        .ticket-subtitle {
            color: #81958e;
            font-size: 11px;
        }

        .ticket-divider {
            margin: 20px 0;

            border-top: 1px dashed #dbe7e2;
        }

        .ticket-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .ticket-info small {
            display: block;

            margin-bottom: 4px;

            color: #91a29c;

            font-size: 9px;
        }

        .ticket-info strong {
            color: var(--dark);
            font-size: 12px;
        }

        .ticket-qr {
            width: 90px;
            height: 90px;

            margin: 22px auto 8px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background:
                linear-gradient(90deg,
                    var(--green-950) 10%,
                    transparent 10%,
                    transparent 20%,
                    var(--green-950) 20%,
                    var(--green-950) 30%,
                    transparent 30%);

            background-size: 18px 18px;

            opacity: .9;
        }

        .ticket-code {
            text-align: center;

            color: #7b9088;

            font-size: 9px;
            letter-spacing: 1px;
        }

        /* =========================================
           FEATURES
        ========================================= */

        .features {
            position: relative;
            z-index: 5;

            width: min(1120px, calc(100% - 60px));

            margin: -35px auto 0;

            display: grid;
            grid-template-columns: repeat(4, 1fr);

            background: white;

            border: 1px solid #e5eee9;

            border-radius: 22px;

            box-shadow:
                0 20px 50px rgba(6, 78, 59, .09);

            overflow: hidden;
        }

        .feature {
            padding: 24px 22px;

            display: flex;
            align-items: center;
            gap: 13px;

            border-right: 1px solid #edf3f0;
        }

        .feature:last-child {
            border-right: 0;
        }

        .feature-icon {
            width: 44px;
            height: 44px;

            flex: 0 0 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: var(--green-50);
            color: var(--green-700);
        }

        .feature strong {
            display: block;

            margin-bottom: 3px;

            color: var(--dark);

            font-size: 12px;
            font-weight: 800;
        }

        .feature span {
            color: #82958e;
            font-size: 10px;
            line-height: 1.4;
        }

        /* =========================================
           TICKET SECTION
        ========================================= */

        .section {
            position: relative;
            z-index: 2;

            width: min(1120px, calc(100% - 40px));

            margin: 95px auto 0;
        }

        .section-heading {
            text-align: center;

            margin-bottom: 35px;
        }

        .section-label {
            display: inline-block;

            margin-bottom: 8px;

            color: var(--green-600);

            font-size: 11px;
            font-weight: 900;

            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .section-heading h2 {
            margin: 0 0 10px;

            color: var(--green-950);

            font-size: clamp(30px, 4vw, 43px);
            font-weight: 900;

            letter-spacing: -1.5px;
        }

        .section-heading p {
            margin: 0 auto;

            max-width: 600px;

            color: #748a82;

            font-size: 14px;
            line-height: 1.6;
        }

        .ticket-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }

        .ticket-product {
            position: relative;

            overflow: hidden;

            background: white;

            border: 1px solid #e6eee9;

            border-radius: 24px;

            box-shadow: var(--shadow-md);

            transition: .3s ease;
        }

        .ticket-product:hover {
            transform: translateY(-7px);

            box-shadow:
                0 25px 55px rgba(6, 78, 59, .13);
        }

        .ticket-image {
            height: 190px;

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(135deg,
                    var(--green-700),
                    var(--green-400));
        }

        .ticket-image::after {
            content: "";

            position: absolute;
            inset: 0;

            background:
                linear-gradient(180deg,
                    transparent 40%,
                    rgba(0, 0, 0, .22));
        }

        .ticket-placeholder {
            position: absolute;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            color: rgba(255, 255, 255, .9);

            font-size: 42px;
        }

        .ticket-body {
            padding: 20px;
        }

        .ticket-category {
            display: inline-block;

            margin-bottom: 9px;

            padding: 6px 9px;

            border-radius: 8px;

            color: var(--green-700);
            background: var(--green-50);

            font-size: 10px;
            font-weight: 800;
        }

        .ticket-body h3 {
            margin: 0 0 7px;

            color: var(--dark);

            font-size: 18px;
            font-weight: 900;
        }

        .ticket-body p {
            min-height: 40px;

            margin: 0 0 18px;

            color: #80918b;

            font-size: 12px;
            line-height: 1.55;
        }

        .ticket-footer {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 10px;
        }

        .price small {
            display: block;

            color: #9aaa a4;

            font-size: 9px;
        }

        .price strong {
            display: block;

            margin-top: 2px;

            color: var(--green-700);

            font-size: 18px;
            font-weight: 900;
        }

        .buy-button {
            padding: 10px 14px;

            border-radius: 11px;

            color: white;
            background: var(--green-700);

            font-size: 11px;
            font-weight: 800;

            transition: .25s ease;
        }

        .buy-button:hover {
            background: var(--green-800);
        }

        /* =========================================
           BOOKING FLOW
        ========================================= */

        .flow-section {
            margin-top: 110px;
        }

        .flow-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }

        .flow-card {
            padding: 27px;

            border-radius: 22px;

            background: white;

            border: 1px solid #e6eee9;

            box-shadow: var(--shadow-md);
        }

        .flow-number {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 18px;

            border-radius: 13px;

            color: white;
            background: var(--green-700);

            font-size: 15px;
            font-weight: 900;
        }

        .flow-card h3 {
            margin: 0 0 8px;

            color: var(--dark);

            font-size: 17px;
            font-weight: 900;
        }

        .flow-card p {
            margin: 0;

            color: #81948d;

            font-size: 12px;
            line-height: 1.6;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .footer {
            margin-top: 110px;

            padding: 55px 0 25px;

            color: #d7eee5;

            background:
                linear-gradient(135deg,
                    var(--green-950),
                    var(--green-800));
        }

        .footer-inner {
            width: min(1120px, calc(100% - 40px));

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                1.4fr 1fr 1fr;

            gap: 50px;
        }

        .footer-brand strong {
            display: block;

            margin-bottom: 10px;

            color: white;

            font-size: 19px;
        }

        .footer-brand p {
            max-width: 330px;

            margin: 0;

            color: #a8c9bd;

            font-size: 12px;
            line-height: 1.7;
        }

        .footer-column h4 {
            margin: 0 0 15px;

            color: white;

            font-size: 13px;
        }

        .footer-column a,
        .footer-column span {
            display: block;

            margin-bottom: 9px;

            color: #a8c9bd;

            font-size: 11px;
        }

        .copyright {
            width: min(1120px, calc(100% - 40px));

            margin: 40px auto 0;
            padding-top: 20px;

            border-top: 1px solid rgba(255, 255, 255, .10);

            color: #88aaa0;

            font-size: 10px;

            text-align: center;
        }

        /* =========================================
           ANIMATION
        ========================================= */

        @keyframes floatOrb {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(25px);
            }
        }

        @keyframes ticketFloat {

            0%,
            100% {
                transform:
                    translateY(0) rotate(-4deg);
            }

            50% {
                transform:
                    translateY(-13px) rotate(-2deg);
            }
        }

        @keyframes cardFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow:
                    0 0 0 0 rgba(16, 185, 129, .25);
            }

            50% {
                box-shadow:
                    0 0 0 7px rgba(16, 185, 129, .05);
            }
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 900px) {

            .nav {
                display: none;
            }

            .hero {
                grid-template-columns: 1fr;

                padding: 45px 35px;
            }

            .hero-visual {
                min-height: 390px;
                margin-top: 20px;
            }

            .features {
                grid-template-columns: repeat(2, 1fr);
            }

            .feature:nth-child(2) {
                border-right: 0;
            }

            .feature:nth-child(-n+2) {
                border-bottom: 1px solid #edf3f0;
            }

            .ticket-grid,
            .flow-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {

            .header {
                width: min(100% - 28px, 1240px);
            }

            .brand-text span {
                display: none;
            }

            .language {
                display: none;
            }

            .hero {
                width: calc(100% - 28px);

                min-height: auto;

                padding: 38px 24px;

                border-radius: 25px;
            }

            .hero-title {
                font-size: 43px;
                letter-spacing: -2px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hero-visual {
                min-height: 330px;
            }

            .visual-circle {
                width: 280px;
                height: 280px;
            }

            .ticket-stack {
                width: 240px;
            }

            .visual-card.top {
                right: -5px;
            }

            .visual-card.bottom {
                left: -5px;
            }

            .features {
                width: calc(100% - 28px);

                margin-top: 18px;

                grid-template-columns: 1fr;

                border-radius: 18px;
            }

            .feature,
            .feature:nth-child(2) {
                border-right: 0;
                border-bottom: 1px solid #edf3f0;
            }

            .feature:last-child {
                border-bottom: 0;
            }

            .section {
                width: calc(100% - 28px);

                margin-top: 75px;
            }

            .footer-inner {
                grid-template-columns: 1fr;

                gap: 30px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <div class="page">

        <span class="bg-orb one"></span>
        <span class="bg-orb two"></span>

        {{-- =========================================
         HEADER
    ========================================== --}}

        <header class="header">

            <a href="/" class="brand">

                <div class="brand-logo">
                    🌿
                </div>

                <div class="brand-text">
                    <strong>Dusun Semilir</strong>
                    <span>Ticketing Online</span>
                </div>

            </a>

            <nav class="nav">

                <a href="/" class="active">
                    Beranda
                </a>

                <a href="#tiket">
                    Tiket
                </a>

                <a href="#cara-pesan">
                    Cara Pesan
                </a>

                <a href="#informasi">
                    Informasi
                </a>

            </nav>

            <div class="header-actions">

                <div class="language">
                    🌐 ID
                </div>

                <a href="#" class="cart-button">

                    🛒

                    Keranjang

                    <span class="cart-count">
                        0
                    </span>

                </a>

            </div>

        </header>


        {{-- =========================================
         HERO
    ========================================== --}}

        <main>

            <section class="hero">

                <div class="hero-content">

                    <div class="eyebrow">

                        <span class="eyebrow-dot"></span>

                        Tiket Resmi Dusun Semilir

                    </div>

                    <h1 class="hero-title">

                        Petualangan Seru
                        <br>

                        <span>Dimulai Dari Sini.</span>

                    </h1>

                    <p class="hero-description">

                        Pesan tiket Dusun Semilir dengan mudah.
                        Pilih wahana, tentukan tanggal kunjungan,
                        bayar dengan QRIS, lalu tiket langsung
                        dikirim ke email Anda.

                    </p>

                    <div class="hero-actions">

                        <a href="#tiket" class="btn-primary">

                            🎟️

                            Lihat Tiket

                            →

                        </a>

                        <a href="#cara-pesan" class="btn-secondary">

                            📅

                            Cara Pemesanan

                        </a>

                    </div>

                </div>


                {{-- VISUAL TICKET --}}

                <div class="hero-visual">

                    <div class="visual-circle"></div>

                    <div class="visual-card top">

                        <strong>⚡ Tiket Instan</strong>

                        <span>
                            Langsung dikirim setelah pembayaran
                        </span>

                    </div>

                    <div class="visual-card bottom">

                        <strong>✓ Pembayaran Aman</strong>

                        <span>
                            Mendukung pembayaran QRIS
                        </span>

                    </div>

                    <div class="ticket-stack">

                        <div class="ticket-header">

                            <div class="ticket-brand">
                                DUSUN SEMILIR
                            </div>

                            <div class="ticket-status">
                                E-TICKET
                            </div>

                        </div>

                        <div class="ticket-title">
                            Tiket Combo
                        </div>

                        <div class="ticket-subtitle">
                            Akses 12 wahana seru
                        </div>

                        <div class="ticket-divider"></div>

                        <div class="ticket-info">

                            <div>
                                <small>TANGGAL</small>
                                <strong>Pilih saat booking</strong>
                            </div>

                            <div>
                                <small>JUMLAH</small>
                                <strong>Per Pax</strong>
                            </div>

                        </div>

                        <div class="ticket-qr">
                            QR
                        </div>

                        <div class="ticket-code">
                            SCAN AT THE ENTRANCE
                        </div>

                    </div>

                </div>

            </section>


            {{-- =========================================
             FEATURES
        ========================================== --}}

            <section class="features">

                <div class="feature">

                    <div class="feature-icon">
                        🎟️
                    </div>

                    <div>
                        <strong>Tiket Instan</strong>

                        <span>
                            Tiket langsung dikirim
                            setelah pembayaran.
                        </span>
                    </div>

                </div>

                <div class="feature">

                    <div class="feature-icon">
                        💳
                    </div>

                    <div>
                        <strong>Pembayaran QRIS</strong>

                        <span>
                            Bayar praktis dengan
                            aplikasi favorit Anda.
                        </span>
                    </div>

                </div>

                <div class="feature">

                    <div class="feature-icon">
                        📅
                    </div>

                    <div>
                        <strong>Pilih Tanggal</strong>

                        <span>
                            Tentukan tanggal
                            kunjungan Anda.
                        </span>
                    </div>

                </div>

                <div class="feature">

                    <div class="feature-icon">
                        📱
                    </div>

                    <div>
                        <strong>QR Code</strong>

                        <span>
                            Tunjukkan QR Code
                            di pintu masuk.
                        </span>
                    </div>

                </div>

            </section>


            {{-- =========================================
             TICKET PRODUCTS
        ========================================== --}}

            <section id="tiket" class="section">

                <div class="section-heading">

                    <span class="section-label">
                        Pilihan Tiket
                    </span>

                    <h2>
                        Pilih Tiket Favoritmu
                    </h2>

                    <p>
                        Nikmati berbagai pilihan tiket
                        untuk pengalaman seru bersama
                        keluarga dan orang tercinta.
                    </p>

                </div>


                <div class="ticket-grid">

                    {{-- COMBO --}}

                    <article class="ticket-product">

                        <div class="ticket-image">

                            <div class="ticket-placeholder">
                                🎢
                            </div>

                        </div>

                        <div class="ticket-body">

                            <span class="ticket-category">
                                COMBO
                            </span>

                            <h3>
                                Tiket Combo
                            </h3>

                            <p>
                                Akses ke berbagai wahana
                                pilihan dalam satu tiket.
                            </p>

                            <div class="ticket-footer">

                                <div class="price">

                                    <small>
                                        Mulai dari
                                    </small>

                                    <strong>
                                        IDR 75.000
                                    </strong>

                                </div>

                                <a href="#" class="buy-button">
                                    Pilih Tiket
                                </a>

                            </div>

                        </div>

                    </article>


                    {{-- REGULER --}}

                    <article class="ticket-product">

                        <div class="ticket-image">

                            <div class="ticket-placeholder">
                                🌈
                            </div>

                        </div>

                        <div class="ticket-body">

                            <span class="ticket-category">
                                REGULER
                            </span>

                            <h3>
                                Tiket Reguler
                            </h3>

                            <p>
                                Pilihan tiket untuk menikmati
                                wahana reguler Dusun Semilir.
                            </p>

                            <div class="ticket-footer">

                                <div class="price">

                                    <small>
                                        Mulai dari
                                    </small>

                                    <strong>
                                        IDR 40.000
                                    </strong>

                                </div>

                                <a href="#" class="buy-button">
                                    Pilih Tiket
                                </a>

                            </div>

                        </div>

                    </article>


                    {{-- TERUSAN --}}

                    <article class="ticket-product">

                        <div class="ticket-image">

                            <div class="ticket-placeholder">
                                ❄️
                            </div>

                        </div>

                        <div class="ticket-body">

                            <span class="ticket-category">
                                TERUSAN
                            </span>

                            <h3>
                                Tiket Terusan
                            </h3>

                            <p>
                                Nikmati akses lebih banyak
                                wahana dalam satu kunjungan.
                            </p>

                            <div class="ticket-footer">

                                <div class="price">

                                    <small>
                                        Mulai dari
                                    </small>

                                    <strong>
                                        IDR 85.000
                                    </strong>

                                </div>

                                <a href="#" class="buy-button">
                                    Pilih Tiket
                                </a>

                            </div>

                        </div>

                    </article>

                </div>

            </section>


            {{-- =========================================
             BOOKING FLOW
        ========================================== --}}

            <section id="cara-pesan" class="section flow-section">

                <div class="section-heading">

                    <span class="section-label">
                        Mudah & Cepat
                    </span>

                    <h2>
                        Cara Pesan Tiket
                    </h2>

                    <p>
                        Hanya beberapa langkah untuk
                        mendapatkan tiket digital Anda.
                    </p>

                </div>


                <div class="flow-grid">

                    <div class="flow-card">

                        <div class="flow-number">
                            01
                        </div>

                        <h3>
                            Pilih Tiket
                        </h3>

                        <p>
                            Pilih kategori tiket yang
                            sesuai dengan kebutuhan
                            kunjungan Anda.
                        </p>

                    </div>


                    <div class="flow-card">

                        <div class="flow-number">
                            02
                        </div>

                        <h3>
                            Tentukan Tanggal
                        </h3>

                        <p>
                            Pilih tanggal kunjungan
                            dan jumlah tiket yang
                            ingin dibeli.
                        </p>

                    </div>


                    <div class="flow-card">

                        <div class="flow-number">
                            03
                        </div>

                        <h3>
                            Bayar & Dapatkan Tiket
                        </h3>

                        <p>
                            Selesaikan pembayaran QRIS.
                            E-ticket dan QR Code akan
                            dikirim ke email Anda.
                        </p>

                    </div>

                </div>

            </section>


            {{-- =========================================
             INFORMATION
        ========================================== --}}

            <section id="informasi" class="section">

                <div class="section-heading">

                    <span class="section-label">
                        Siapkan Liburanmu
                    </span>

                    <h2>
                        Datang, Scan, & Nikmati
                    </h2>

                    <p>
                        Saat tiba di Dusun Semilir,
                        cukup tunjukkan QR Code tiket
                        kepada petugas di pintu masuk.
                    </p>

                </div>

            </section>

        </main>


        {{-- =========================================
         FOOTER
    ========================================== --}}

        <footer class="footer">

            <div class="footer-inner">

                <div class="footer-brand">

                    <strong>
                        🌿 Dusun Semilir
                    </strong>

                    <p>
                        Wisata alam terbaik di Jawa Tengah
                        dengan berbagai wahana seru untuk
                        seluruh keluarga.
                    </p>

                </div>


                <div class="footer-column">

                    <h4>
                        Layanan Pelanggan
                    </h4>

                    <span>
                        WhatsApp
                    </span>

                    <span>
                        Email
                    </span>

                    <span>
                        Bantuan Tiket
                    </span>

                </div>


                <div class="footer-column">

                    <h4>
                        Informasi
                    </h4>

                    <a href="#">
                        Syarat & Ketentuan
                    </a>

                    <a href="#">
                        Kebijakan Privasi
                    </a>

                    <a href="#">
                        Cara Pemesanan
                    </a>

                </div>

            </div>


            <div class="copyright">

                © {{ date('Y') }} Dusun Semilir.
                Semua Hak Dilindungi.

            </div>

        </footer>

    </div>

</body>

</html>
