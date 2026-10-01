<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#e9f3ec">
    <title>@yield('title') | Dusun Semilir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            font-family: 'DM Sans', sans-serif;
            color: #183b2f;
            background: #eef5ef;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            background-color: #eef5ef;
            background-image: linear-gradient(135deg, rgba(255,255,255,.78), rgba(229,241,232,.72)), repeating-linear-gradient(0deg, transparent 0 39px, rgba(32,91,65,.035) 40px), repeating-linear-gradient(90deg, transparent 0 39px, rgba(32,91,65,.035) 40px);
        }

        .error-shell {
            display: grid;
            min-height: 100vh;
            grid-template-rows: auto 1fr auto;
            width: min(1120px, 100%);
            margin: 0 auto;
            padding: 28px clamp(22px, 5vw, 64px) 20px;
        }

        .error-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            width: fit-content;
            color: #214d3d;
            text-decoration: none;
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            font-weight: 800;
        }

        .error-brand img { width: 52px; height: 52px; object-fit: contain; }

        .error-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, .8fr);
            align-items: center;
            gap: clamp(24px, 5vw, 70px);
            padding: 30px 0;
        }

        .error-copy { max-width: 580px; }

        .error-code {
            margin: 0;
            color: #2e7653;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(76px, 13vw, 148px);
            font-weight: 800;
            line-height: .9;
        }

        .error-kicker {
            margin: 24px 0 10px;
            color: #59836c;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
        }

        .error-title {
            margin: 0;
            color: #183b2f;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800;
            line-height: 1.15;
        }

        .error-description {
            max-width: 470px;
            margin: 16px 0 0;
            color: #63776b;
            font-size: 16px;
            line-height: 1.75;
        }

        .error-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 28px; }

        .error-button {
            display: inline-flex;
            min-height: 46px;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border: 1px solid #236246;
            border-radius: 6px;
            background: #236246;
            color: white;
            font: 700 14px 'DM Sans', sans-serif;
            text-decoration: none;
            transition: background .18s ease, transform .18s ease;
        }

        .error-button:hover { transform: translateY(-1px); background: #194c35; }
        .error-button-secondary { border-color: #c8d9ce; background: rgba(255,255,255,.72); color: #275a41; }
        .error-button-secondary:hover { background: white; }

        .error-illustration { display: flex; align-items: center; justify-content: center; min-height: 340px; }
        .error-illustration img { display: block; width: min(100%, 410px); max-height: 420px; object-fit: contain; }

        .error-footer {
            padding-top: 16px;
            border-top: 1px solid rgba(35,98,70,.16);
            color: #819488;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            .error-shell { padding-top: 18px; }
            .error-main { grid-template-columns: 1fr; gap: 4px; padding: 35px 0 24px; }
            .error-copy { order: 1; }
            .error-illustration { order: 0; min-height: 180px; }
            .error-illustration img { width: min(70%, 250px); max-height: 220px; }
            .error-kicker { margin-top: 18px; }
            .error-description { font-size: 15px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <div class="error-shell">
        <a class="error-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/osil.png') }}" alt="">
            <span>DUSUN SEMILIR</span>
        </a>

        <main class="error-main">
            <section class="error-copy">
                @yield('content')
            </section>
            <div class="error-illustration">
                <img src="{{ asset('images/osil.png') }}" alt="Osil, maskot Dusun Semilir">
            </div>
        </main>

        <footer class="error-footer">Dusun Semilir · Wisata Keluarga, Cerita Tak Terlupa</footer>
    </div>
</body>
</html>
