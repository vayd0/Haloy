<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page introuvable</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Germania+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(["resources/css/normalize.css", 'resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background: #0e0d0d;
            color: #eeeeee;
            height: 100%;
        }
        main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0;
        }

        /* ── Glitch ─────────────────────────────────────────── */
        .error-404 {
            font-family: 'Germania One', serif;
            font-size: clamp(6rem, 18vw, 14rem);
            color: #ffffff;
            line-height: 1;
            margin: 0 0 0.05em 0;
            text-align: center;
            position: relative;
            animation: base-flicker 5s infinite;
        }
        .error-404::before,
        .error-404::after {
            content: attr(data-text);
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            font-family: 'Germania One', serif;
            opacity: 0;
        }
        /* Red channel — chromatic aberration */
        .error-404::before {
            color: #A7001E;
            animation: glitch-red 5s infinite;
        }
        /* Silver channel — chromatic aberration */
        .error-404::after {
            color: #BBC6C7;
            animation: glitch-silver 5s infinite;
        }

        @keyframes base-flicker {
            0%,   77%  { opacity: 1; }
            77.5%       { opacity: 0.75; }
            77.6%       { opacity: 1; }
            78%         { opacity: 0.5; }
            78.1%       { opacity: 1; }
            78.3%       { opacity: 0.85; }
            78.4%       { opacity: 1; }
            79%         { opacity: 0.6; }
            79.05%      { opacity: 1; }
            79.2%       { opacity: 0.4; }
            79.25%      { opacity: 1; }
            79.8%       { opacity: 0.9; }
            79.85%      { opacity: 1; }
            100%        { opacity: 1; }
        }

        @keyframes glitch-red {
            0%,   76%  { transform: none; clip-path: none; opacity: 0; }
            76.5%       { transform: translate(-8px, 0);    clip-path: polygon(0 10%, 100% 10%, 100% 28%, 0 28%); opacity: 1; }
            77%         { transform: translate(5px, -2px);  clip-path: polygon(0 48%, 100% 48%, 100% 63%, 0 63%); opacity: 1; }
            77.4%       { transform: translate(-12px, 1px); clip-path: polygon(0 72%, 100% 72%, 100% 88%, 0 88%); opacity: 1; }
            77.7%       { transform: translate(7px, 0);     clip-path: polygon(0 2%,  100% 2%,  100% 18%, 0 18%); opacity: 1; }
            77.9%       { transform: none; opacity: 0; }
            78.5%       { transform: translate(-15px, 2px); clip-path: polygon(0 35%, 100% 35%, 100% 58%, 0 58%); opacity: 1; }
            78.7%       { transform: translate(9px, -1px);  clip-path: polygon(0 0%,  100% 0%,  100% 15%, 0 15%); opacity: 1; }
            79%         { transform: translate(-6px, 3px);  clip-path: polygon(0 62%, 100% 62%, 100% 80%, 0 80%); opacity: 1; }
            79.3%       { transform: translate(11px, 0);    clip-path: polygon(0 20%, 100% 20%, 100% 42%, 0 42%); opacity: 1; }
            79.6%       { transform: none; opacity: 0; }
            100%        { transform: none; opacity: 0; }
        }

        @keyframes glitch-silver {
            0%,   76.3% { transform: none; clip-path: none; opacity: 0; }
            76.8%        { transform: translate(10px, 0);    clip-path: polygon(0 55%, 100% 55%, 100% 70%, 0 70%); opacity: 1; }
            77.2%        { transform: translate(-7px, 2px);  clip-path: polygon(0 5%,  100% 5%,  100% 22%, 0 22%); opacity: 1; }
            77.6%        { transform: translate(14px, -2px); clip-path: polygon(0 80%, 100% 80%, 100% 95%, 0 95%); opacity: 1; }
            77.9%        { transform: none; opacity: 0; }
            78.6%        { transform: translate(12px, 0);    clip-path: polygon(0 25%, 100% 25%, 100% 48%, 0 48%); opacity: 1; }
            78.8%        { transform: translate(-10px, 1px); clip-path: polygon(0 60%, 100% 60%, 100% 75%, 0 75%); opacity: 1; }
            79.1%        { transform: translate(16px, -3px); clip-path: polygon(0 88%, 100% 88%, 100% 100%,0 100%); opacity: 1; }
            79.4%        { transform: translate(-5px, 2px);  clip-path: polygon(0 12%, 100% 12%, 100% 30%, 0 30%); opacity: 1; }
            79.7%        { transform: none; opacity: 0; }
            100%         { transform: none; opacity: 0; }
        }

        /* ── Vinyl ───────────────────────────────────────────── */
        .vinyl-wrapper {
            width: clamp(220px, 44vw, 400px);
            animation: slowspin 18s linear infinite;
            transform-origin: center;
            margin-bottom: 2.5rem;
        }
        .vinyl-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }
        @keyframes slowspin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .vinyl-wrapper:hover { animation-play-state: paused; }

        /* ── Buttons — glass-morph style du site ─────────────── */
        .btn-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn-404 {
            font-family: 'Germania One', serif;
            font-size: 1rem;
            color: #eeeeee;
            text-decoration: none;
            /* Exactement glass-morph */
            background: rgba(255, 255, 255, 0.01);
            border-radius: 30px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.01));
            padding: 0.55rem 1.4rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: border-color .2s, background .2s, color .2s;
        }
        .btn-404:hover {
            border-color: rgba(167, 0, 30, 0.6);
            background: rgba(167, 0, 30, 0.08);
            color: #fff;
        }
        .btn-404.btn-primary {
            border-color: rgba(167, 0, 30, 0.5);
            background: rgba(167, 0, 30, 0.18);
            color: #fff;
        }
        .btn-404.btn-primary:hover {
            background: rgba(167, 0, 30, 0.35);
            border-color: rgba(167, 0, 30, 0.8);
        }
        .btn-404 i {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    @include('components.background')

    <main>
        <h1 class="error-404" data-text="404">404</h1>

        <div class="vinyl-wrapper">
            <img src="{{ asset('images/vinyl-broken.png') }}" alt="Vinyle brisé">
        </div>

        <div class="btn-group">
            <a href="{{ route('accueil') }}" class="btn-404 btn-primary">
                <i class="fa-solid fa-house"></i>Accueil
            </a>
            <a href="javascript:history.back()" class="btn-404">
                <i class="fa-solid fa-arrow-left"></i>Page précédente
            </a>
            <a href="{{ route('articles.all') }}" class="btn-404">
                <i class="fa-solid fa-music"></i>Articles
            </a>
        </div>
    </main>
</body>
</html>
