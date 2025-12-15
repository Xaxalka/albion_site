@php($title = 'Оружие')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Каталог</div>
    <h1>Оружие Albion</h1>
    <p>Подборка оружия со встроенными фильтрами. Тёмный дизайн кодекса сохраняет настроение фанатской базы.</p>
</div>

<div class="content-area" style="grid-template-columns: 320px 1fr; gap: 20px; align-items: start;">
    <aside class="panel">
        <div class="subtle-title">Фильтр</div>
        <form method="GET" action="{{ route('weapons.index') }}">
            <div>
                <label>Линия оружия</label>
                <select name="weapon_line_id">
                    <option value="">Все линии</option>
                    @foreach($weaponLines as $line)
                        <option value="{{ $line->id }}" @selected(request('weapon_line_id') == $line->id)>{{ $line->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:10px; flex-wrap: wrap;">
                <button type="submit">Применить</button>
                <a class="btn" href="{{ route('weapons.index') }}">Сбросить</a>
            </div>
        </form>
        <div class="panel" style="margin-top:12px; padding:12px 14px;">
            <p class="subtle-title">Справка</p>
            <p class="muted" style="margin-top:6px;">База готова к расширению на броню, навыки и контент. Используйте фильтр, чтобы сузить выбор.</p>
        </div>
    </aside>

    <section class="content-area" style="gap:14px;">
        <div class="panel">
            <div class="subtle-title">Итог</div>
            <h2 style="margin-top:6px;">Найдено {{ $weapons->count() }} предметов</h2>
        </div>
        <div class="card-grid">
            @forelse($weapons as $weapon)
                <article class="card">
                    <div class="tags">
                        <span class="chip">{{ $weapon->weaponLine?->name ?? 'Без линии' }}</span>
                        @if($weapon->weaponSkill)
                            <span class="chip">E: {{ $weapon->weaponSkill->name }}</span>
                        @endif
                    </div>
                    <h2 style="margin-top:10px;">
                        <a href="{{ route('weapons.show', $weapon->slug) }}" style="color:inherit; text-decoration:none;">{{ $weapon->name }}</a>
                    </h2>
                    <p style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($weapon->description, 140) }}</p>
                </article>
            @empty
                <div class="panel">
                    <p class="muted">Оружие не найдено. Сбросьте фильтры или загрузите данные.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
