<!DOCTYPE html>
<html lang="en" x-data="themeToggle()" x-init="init()" :data-theme="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Albion Codex' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (() => {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.data('themeToggle', () => ({
                theme: document.documentElement.getAttribute('data-theme') || 'light',
                init() {
                    this.apply(this.theme);
                },
                toggle() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    this.apply(this.theme);
                    localStorage.setItem('theme', this.theme);
                },
                apply(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                },
            }));
        });
    </script>
    <style>
        :root {
            --page-bg: #f8fafc;
            --panel-bg: #ffffff;
            --card-bg: #f1f5f9;
            --border-color: #e2e8f0;
            --text-color: #0f172a;
            --muted-color: #475569;
            --tag-bg: #eef2ff;
            --tag-text: #4338ca;
            --link: #4338ca;
            --link-hover: #312e81;
        }
        [data-theme="dark"] {
            color-scheme: dark;
            --page-bg: #0b1220;
            --panel-bg: #0f172a;
            --card-bg: #111827;
            --border-color: #1f2937;
            --text-color: #e2e8f0;
            --muted-color: #cbd5e1;
            --tag-bg: #1e1b4b;
            --tag-text: #c7d2fe;
            --link: #c084fc;
            --link-hover: #a855f7;
        }
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
            padding: 28px 20px 64px;
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
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(13,19,31,0.78);
            box-shadow: 0 10px 30px var(--shadow);
            position: sticky;
            top: 14px;
            z-index: 20;
            backdrop-filter: blur(12px);
        }
        .crest {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(13,19,31,0.7);
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
        .theme-panel {
            background-color: var(--panel-bg);
            border-color: var(--border-color);
        }
        .theme-card {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }
        .theme-muted {
            color: var(--muted-color);
        }
        .theme-tag {
            background-color: var(--tag-bg);
            color: var(--tag-text);
            border-color: var(--border-color);
        }
        .theme-link {
            color: var(--link);
        }
        .theme-link:hover {
            color: var(--link-hover);
        }
    </style>
</head>
<body>
<div class="page-shell">
    <header class="site-header">
        <div class="crest">Albion Codex</div>
        <nav class="nav-links" aria-label="Главная навигация">
            <a href="{{ route('wiki') }}">Главная</a>
            <a href="{{ route('weapon-lines.index') }}">Линии оружия</a>
            <a href="{{ route('weapons.index') }}">Оружие</a>
            @auth
                <a href="{{ route('admin.weapons.index') }}">Админка</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Выйти ({{ Auth::user()->name }})</button>
                </form>
            @else
                <a href="{{ route('register') }}">Регистрация</a>
                <a href="{{ route('login') }}">Войти</a>
            @endauth
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
