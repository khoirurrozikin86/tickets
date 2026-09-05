@extends('layouts.admin')
@section('title', 'Dashboard')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Overview</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
@endsection

@section('content')
    {{-- Hero Welcome --}}

    {{-- ========================================================= --}}
    {{-- HERO DASHBOARD --}}
    {{-- ========================================================= --}}

    <div class="dashboard-hero mb-4">

        {{-- Decorative background --}}
        <div class="hero-circle hero-circle-1"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-wave"></div>

        <div class="row align-items-center position-relative">

            {{-- LEFT --}}
            <div class="col-lg-7">

                <div class="hero-content">

                    <div class="hero-badge mb-3">
                        <span>Welcome back</span>

                        {{-- <i data-feather="home"></i> --}}
                    </div>


                    <h1 class="hero-title">
                        Halo,
                        <span>
                            {{ auth()->user()->name ?? 'Developer' }}
                        </span>
                        👋
                    </h1>


                    <p class="hero-description">
                        Selamat datang di panel admin.
                        Kelola scan tiket, monitor aktivitas,
                        dan lihat laporan dengan mudah.
                    </p>


                    {{-- MINI STAT --}}
                    <div class="row g-3 mt-4">

                        {{-- UPTIME --}}
                        <div class="col-sm-4">

                            <div class="hero-stat">

                                <div class="hero-stat-icon green">
                                    <i data-feather="map-pin"></i>
                                </div>

                                <div>

                                    <div class="hero-stat-label">
                                        Total Outlet
                                    </div>

                                    <div class="hero-stat-value">

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- SCAN --}}
                        <div class="col-sm-4">

                            <div class="hero-stat">

                                <div class="hero-stat-icon emerald">
                                    <i data-feather="activity"></i>
                                </div>

                                <div>

                                    <div class="hero-stat-label">
                                        Scan Hari Ini
                                    </div>

                                    <div class="hero-stat-value">

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- USER --}}
                        <div class="col-sm-4">

                            <div class="hero-stat">

                                <div class="hero-stat-icon teal">
                                    <i data-feather="users"></i>
                                </div>

                                <div>

                                    <div class="hero-stat-label">
                                        User
                                    </div>

                                    <div class="hero-stat-value">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT / OSIL --}}
            <div class="col-lg-5">

                <div class="osil-wrapper">

                    <div class="osil-glow"></div>

                    <img src="{{ asset('images/osil.png') }}" alt="OSIL" class="osil-image">

                    <div class="osil-message">

                        <div class="osil-message-small">
                            Bersama OSIL
                        </div>

                        <div class="osil-message-title">
                            Semua Lebih
                            <strong>Mudah!</strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>






@endsection

@push('styles')
    <style>
        /* Hero gradient + animation */
        /* =========================================================
                                                                                           DASHBOARD HERO
                                                                                           ========================================================= */

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            min-height: 310px;
            border-radius: 22px;
            padding: 38px 42px;
            background:
                radial-gradient(circle at 85% 20%,
                    rgba(255, 255, 255, .55),
                    transparent 28%),
                linear-gradient(135deg,
                    #ecfdf5 0%,
                    #d1fae5 48%,
                    #bbf7d0 100%);
            border: 1px solid #d9f2e3;
        }


        /* Decorative circles */

        .hero-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-circle-1 {
            width: 260px;
            height: 260px;
            right: -90px;
            top: -100px;
            background: rgba(34, 197, 94, .10);
        }

        .hero-circle-2 {
            width: 180px;
            height: 180px;
            right: 220px;
            bottom: -100px;
            background: rgba(16, 185, 129, .08);
        }


        /* =========================================================
                                                                                           HERO CONTENT
                                                                                           ========================================================= */

        .hero-content {
            position: relative;
            z-index: 5;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 13px;
            border-radius: 30px;
            background: rgba(255, 255, 255, .75);
            color: #15803d;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, .8);
            backdrop-filter: blur(10px);
        }

        .hero-badge svg {
            width: 15px;
            height: 15px;
        }

        .hero-title {
            margin: 0;
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 800;
            color: #102a1b;
            letter-spacing: -1px;
        }

        .hero-title span {
            color: #15803d;
        }

        .hero-description {
            max-width: 600px;
            margin-top: 10px;
            margin-bottom: 0;
            color: #527064;
            font-size: 14px;
            line-height: 1.7;
        }


        /* =========================================================
                                                                                           HERO STAT
                                                                                           ========================================================= */

        .hero-stat {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px;
            min-height: 72px;
            background: rgba(255, 255, 255, .78);
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 13px;
            box-shadow: 0 8px 25px rgba(21, 128, 61, .06);
            backdrop-filter: blur(10px);
        }

        .hero-stat-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .hero-stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .hero-stat-icon.green {
            background: #dcfce7;
            color: #16a34a;
        }

        .hero-stat-icon.emerald {
            background: #d1fae5;
            color: #059669;
        }

        .hero-stat-icon.teal {
            background: #ccfbf1;
            color: #0f766e;
        }

        .hero-stat-label {
            font-size: 10px;
            color: #718579;
            margin-bottom: 2px;
        }

        .hero-stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #173325;
        }


        /* =========================================================
                                                                                           OSIL
                                                                                           ========================================================= */

        .osil-wrapper {
            position: relative;
            min-height: 270px;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            z-index: 5;
        }

        .osil-glow {
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .65);
            filter: blur(5px);
            bottom: 5px;
        }

        .osil-image {
            position: relative;
            z-index: 2;
            width: 240px;
            max-width: 90%;
            object-fit: contain;
            animation: osilFloat 4s ease-in-out infinite;
            filter: drop-shadow(0 18px 15px rgba(0, 0, 0, .12));
        }

        @keyframes osilFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-9px) rotate(1deg);
            }

        }


        /* =========================================================
                                                                                           OSIL MESSAGE
                                                                                           ========================================================= */

        .osil-message {
            position: absolute;
            right: 0;
            top: 25px;
            z-index: 4;
            padding: 13px 18px;
            min-width: 145px;
            background: #15803d;
            color: white;
            border-radius: 15px 15px 15px 4px;
            box-shadow: 0 10px 25px rgba(21, 128, 61, .20);
            animation: messageFloat 4s ease-in-out infinite;
        }

        .osil-message::after {
            content: "";
            position: absolute;
            left: -10px;
            bottom: 0;
            border-style: solid;
            border-width: 10px 10px 0 0;
            border-color: #15803d transparent transparent transparent;
        }

        .osil-message-small {
            font-size: 10px;
            opacity: .8;
            margin-bottom: 2px;
        }

        .osil-message-title {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.25;
        }

        .osil-message-title strong {
            color: #fef08a;
        }

        @keyframes messageFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }


        /* =========================================================
                                                                                           QUICK ACCESS CARD
                                                                                           ========================================================= */

        .quick-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 18px;
            min-height: 150px;
            padding: 22px;
            overflow: hidden;
            text-decoration: none !important;
            border-radius: 17px;
            border: 1px solid #e8edf0;
            background: white;
            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
        }

        .quick-card::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            right: -55px;
            bottom: -80px;
            border-radius: 50%;
            opacity: .7;
        }

        .quick-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, .08);
        }

        .quick-icon {
            width: 64px;
            height: 64px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
        }

        .quick-icon svg {
            width: 31px;
            height: 31px;
            stroke-width: 2;
        }


        /* GREEN */

        .quick-card-green .quick-icon {
            background: #dcfce7;
            color: #15803d;
        }

        .quick-card-green::after {
            background: #ecfdf5;
        }

        .quick-card-green h5,
        .quick-card-green .quick-action {
            color: #15803d;
        }


        /* BLUE */

        .quick-card-blue .quick-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .quick-card-blue::after {
            background: #eff6ff;
        }

        .quick-card-blue h5,
        .quick-card-blue .quick-action {
            color: #2563eb;
        }


        /* GOLD */

        .quick-card-gold .quick-icon {
            background: #fef3c7;
            color: #ca8a04;
        }

        .quick-card-gold::after {
            background: #fffbeb;
        }

        .quick-card-gold h5,
        .quick-card-gold .quick-action {
            color: #b7791f;
        }


        /* =========================================================
                                                                                           QUICK CONTENT
                                                                                           ========================================================= */

        .quick-content {
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .quick-content h5 {
            margin: 0 0 6px;
            font-size: 16px;
            font-weight: 800;
        }

        .quick-content p {
            margin: 0 0 13px;
            color: #718096;
            font-size: 12px;
            line-height: 1.5;
            max-width: 240px;
        }

        .quick-action {
            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================================
                                                                                           ARROW
                                                                                           ========================================================= */

        .quick-arrow {
            position: relative;
            z-index: 2;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
            transition: transform .2s ease;
        }

        .quick-arrow svg {
            width: 19px;
            height: 19px;
        }

        .quick-card:hover .quick-arrow {
            transform: translateX(4px);
        }


        /* =========================================================
                                                                                           RESPONSIVE
                                                                                           ========================================================= */

        @media (max-width: 991px) {

            .dashboard-hero {
                padding: 30px;
            }

            .osil-wrapper {
                min-height: 230px;
                margin-top: 20px;
            }

            .osil-image {
                width: 190px;
            }

            .osil-message {
                right: 10%;
            }

        }

        @media (max-width: 575px) {

            .dashboard-hero {
                padding: 25px 20px;
                border-radius: 17px;
            }

            .hero-title {
                font-size: 27px;
            }

            .hero-stat {
                min-height: 65px;
            }

            .osil-wrapper {
                min-height: 200px;
            }

            .osil-message {
                right: 0;
                top: 10px;
                transform: scale(.9);
            }

            .quick-card {
                min-height: 130px;
                padding: 17px;
            }

            .quick-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
            }

            .quick-icon svg {
                width: 25px;
                height: 25px;
            }

        }
    </style>
@endpush
