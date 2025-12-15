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
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body>
<div class="page-shell">
    <header class="site-header">
        <div class="crest">Albion Codex</div>
        <nav class="nav-links" aria-label="Главная навигация">
            <a href="{{ route('wiki') }}">Главная</a>
            <a href="{{ route('weapon-lines.index') }}">Линии оружия</a>
            <a href="{{ route('weapons.index') }}">Оружие</a>
            <a href="{{ route('admin.weapons.index') }}">Админка</a>
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
