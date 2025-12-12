<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Albion Armory Wiki' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <div class="border-b border-slate-200 bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('weapons.index') }}" class="text-xl font-semibold text-indigo-700 hover:text-indigo-800">
                    Albion Armory
                </a>
                <span class="text-sm text-slate-500">Wiki Base</span>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <a class="text-slate-700 hover:text-indigo-700" href="{{ route('weapon-lines.index') }}">Weapon Lines</a>
                <a class="text-slate-700 hover:text-indigo-700" href="{{ route('weapons.index') }}">Weapons</a>
                <a class="text-slate-700 hover:text-indigo-700" href="{{ route('admin.weapons.index') }}">Admin</a>
            </div>
        </div>
    </div>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 py-4 text-sm text-slate-500">
            Albion Armory Wiki base &middot; Ready for expansion
        </div>
    </footer>
</body>
</html>
