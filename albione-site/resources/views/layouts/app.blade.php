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
            background-color: var(--page-bg);
            color: var(--text-color);
            transition: background-color 150ms ease, color 150ms ease;
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
<body class="min-h-screen">
    <div class="border-b theme-panel shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('weapons.index') }}" class="text-xl font-semibold theme-link">
                    Albion Armory
                </a>
                <span class="text-sm theme-muted">Wiki Base</span>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <button @click="toggle" type="button" class="rounded border px-3 py-1.5 text-sm font-semibold theme-panel theme-link">
                    <span x-text="theme === 'dark' ? 'Light mode' : 'Dark mode'"></span>
                </button>
                <a class="theme-link" href="{{ route('weapon-lines.index') }}">Weapon Lines</a>
                <a class="theme-link" href="{{ route('weapons.index') }}">Weapons</a>
                <a class="theme-link" href="{{ route('admin.weapons.index') }}">Admin</a>
            </div>
        </div>
    </div>

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
