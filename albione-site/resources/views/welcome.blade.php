<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Albion Helper') }}</title>
    <style>
        :root {
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            color-scheme: dark;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 20%, #1f2937, #0b1220 55%);
            color: #e5e7eb;
        }

        .card {
            background: rgba(17, 24, 39, 0.9);
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: 28px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        h1 {
            margin: 0 0 12px;
            font-size: 26px;
            letter-spacing: 0.02em;
        }

        p {
            margin: 0 0 18px;
            color: #cbd5e1;
            line-height: 1.5;
        }

        .links {
            display: grid;
            gap: 10px;
            margin-top: 12px;
        }

        a {
            display: inline-block;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #111827;
            color: #e5e7eb;
            text-decoration: none;
            text-align: center;
            transition: border-color 120ms ease, transform 120ms ease, background 120ms ease;
        }

        a:hover {
            border-color: #fbbf24;
            background: #0f172a;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
<div class="card">
    <h1>Albion Arsenal</h1>
    <p>Быстрый старт: переходи к списку линий оружия или сразу к самим предметам. Данные берутся из вашего бэкенда.</p>
    <p>Доступные ветки: Warrior Weapons, Hunter Weapons, Mage Weapons.</p>
    <div class="links">
        <a href="{{ route('weapon-lines.index') }}">Линии оружия</a>
        <a href="{{ route('weapons.index') }}">Все оружие</a>
    </div>
</div>
</body>
</html>
