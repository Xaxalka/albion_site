<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Albion Codex' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0c0f17;
            --bg-2: #0f1624;
            --gold: #d7b676;
            --gold-strong: #f2c87c;
            --text: #e5e7eb;
            --muted: #b6c2cf;
            --panel: #0d131f;
            --shadow: rgba(0, 0, 0, 0.45);
            --line: rgba(215, 182, 118, 0.35);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: radial-gradient(circle at 18% 22%, rgba(215,182,118,0.08), transparent 35%),
                        radial-gradient(circle at 82% 12%, rgba(255,189,89,0.08), transparent 32%),
                        linear-gradient(145deg, var(--bg) 0%, var(--bg-2) 100%);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }
        .page-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 64px;
            position: relative;
        }
        .page-shell::before {
            content: "";
            position: absolute;
            inset: 18% auto 10% 5%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(242,200,124,0.16), transparent 70%);
            filter: blur(8px);
            pointer-events: none;
        }
        .site-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 24px 16px 14px;
            border: 1px solid var(--line);
            border-radius: 0 0 18px 18px;
            background: rgba(13,19,31,0.78);
            box-shadow: 0 10px 30px var(--shadow);
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(12px);
            margin: 0 -20px;
            width: calc(100% + 40px);
        }
        .crest {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 14px 10px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: rgba(13,19,31,0.7);
            text-align: center;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--gold-strong);
            font-size: 13px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .nav-links form {
            margin: 0;
        }
        .nav-links__admin {
            background: linear-gradient(140deg, rgba(242,200,124,0.18), rgba(16,23,35,0.95));
            border-color: var(--gold-strong);
            color: #fff;
        }
        .nav-links a {
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--line);
            color: var(--text);
            text-decoration: none;
            background: linear-gradient(140deg, rgba(242,200,124,0.08), rgba(16,23,35,0.85));
            font-weight: 700;
            letter-spacing: 0.02em;
            transition: 160ms ease;
        }
        .nav-links a:hover,
        .nav-links a:focus-visible {
            border-color: var(--gold-strong);
            color: #fff;
            outline: none;
            box-shadow: 0 10px 26px rgba(242,200,124,0.18);
        }
        .content-area {
            margin-top: 26px;
            display: grid;
            gap: 20px;
        }
        .section-header {
            display: grid;
            gap: 8px;
        }
        .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: var(--gold-strong);
            font-size: 12px;
        }
        h1, h2, h3 { font-family: 'Cinzel', 'Inter', serif; margin: 0; color: #fff; }
        h1 { font-size: clamp(26px, 4vw, 36px); letter-spacing: 0.02em; }
        h2 { font-size: 22px; }
        h3 { font-size: 18px; }
        p { margin: 0; color: var(--muted); line-height: 1.6; }
        .panel {
            position: relative;
            border-radius: 18px;
            padding: 18px;
            background: linear-gradient(160deg, rgba(13,19,31,0.95), rgba(11,16,25,0.92));
            border: 1px solid var(--line);
            box-shadow: 0 14px 34px var(--shadow);
            overflow: hidden;
        }
        .panel::before {
            content: "";
            position: absolute;
            inset: 14px;
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 14px;
            pointer-events: none;
        }
        .card-grid { display: grid; gap: 14px; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .card { position: relative; padding: 16px; border-radius: 16px; border: 1px solid var(--line); background: linear-gradient(150deg, rgba(16,23,35,0.95), rgba(13,19,31,0.82)); box-shadow: 0 14px 30px var(--shadow); overflow: hidden; }
        .card::after { content: ""; position: absolute; inset: 0; background: radial-gradient(circle at 20% 20%, rgba(242,200,124,0.12), transparent 45%); opacity: 0.8; pointer-events: none; }
        .meta { color: var(--muted); font-size: 13px; letter-spacing: 0.02em; }
        .chip { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text); font-size: 14px; letter-spacing: 0.02em; }
        .tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .muted { color: var(--muted); }
        .subtle-title { color: var(--gold-strong); font-weight: 700; letter-spacing: 0.06em; font-size: 12px; text-transform: uppercase; }
        .stat-box { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 12px; border: 1px solid var(--line); background: rgba(242,200,124,0.08); color: #fff; font-weight: 700; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; overflow: hidden; border-radius: 12px; border: 1px solid var(--line); }
        .table th, .table td { padding: 12px; text-align: left; }
        .table tr:nth-child(odd) { background: rgba(255,255,255,0.02); }
        .table th { color: var(--gold-strong); letter-spacing: 0.03em; font-weight: 700; }
        form { display: grid; gap: 10px; }
        label { font-weight: 600; color: var(--text); font-size: 14px; }
        select, input, textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: linear-gradient(140deg, rgba(242,200,124,0.06), rgba(16,23,35,0.9));
            color: var(--text);
        }
        select:focus, input:focus, textarea:focus {
            outline: none;
            border-color: var(--gold-strong);
            box-shadow: 0 8px 20px rgba(242,200,124,0.16);
        }
        button, .btn { display: inline-flex; justify-content: center; align-items: center; gap: 8px; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--line); background: linear-gradient(140deg, rgba(242,200,124,0.12), rgba(16,23,35,0.9)); color: #fff; font-weight: 700; letter-spacing: 0.02em; cursor: pointer; text-decoration: none; transition: 160ms ease; }
        button:hover, .btn:hover { border-color: var(--gold-strong); box-shadow: 0 10px 24px rgba(242,200,124,0.2); transform: translateY(-1px); }
        .alert { border: 1px solid var(--line); border-radius: 14px; padding: 12px 14px; background: rgba(242,200,124,0.08); color: #fff; }
        .footer { margin-top: 40px; border-top: 1px solid var(--line); padding-top: 16px; color: var(--muted); font-size: 14px; text-align: center; }
        @media (max-width: 720px) {
            .site-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
    @if(request()->routeIs('admin.*'))
        <link rel="stylesheet" href="{{ asset('css/admin-tables.css') }}">
    @endif
</head>
<body>
<div class="page-shell">
    <header class="site-header">
        <div class="crest">Albion Codex</div>
        @php
            $currentUser = $currentUser ?? auth()->user();
            $isAdmin = $isAdmin ?? (bool) $currentUser?->isAdmin();
        @endphp
        <nav class="nav-links" aria-label="Главная навигация">
            @if($isAdmin)
                <a class="nav-links__admin" href="{{ route('admin.dashboard') }}">Админ-панель</a>
            @endif
            <a href="{{ route('wiki') }}">Главная</a>
            <a href="{{ route('weapon-lines.index') }}">Ветки оружий</a>
            <a href="{{ route('weapons.index') }}">Оружие</a>
            <a href="{{ route('armor.index') }}">Броня</a>
            @if($currentUser)
                @if($isAdmin)
                    <a href="{{ route('admin.weapon-lines.index') }}">Ветки (админ)</a>
                    <a href="{{ route('admin.weapons.index') }}">Оружие (админ)</a>
                    <a href="{{ route('admin.armor.index') }}">Броня (админ)</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Выйти ({{ $currentUser->name }})</button>
                </form>
            @else
                <a href="{{ route('register') }}">Регистрация</a>
                <a href="{{ route('login') }}">Войти</a>
            @endif
        </nav>
    </header>

    <main class="content-area">
        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">Albion Codex — фанатская база. Готово к расширению.</footer>
</div>
</body>
</html>
