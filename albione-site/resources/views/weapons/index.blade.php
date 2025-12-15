@php($title = 'Оружие')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Каталог</div>
    <h1>Оружие Albion</h1>
    <p>Подборка оружия со встроенными фильтрами. Тёмный дизайн кодекса сохраняет настроение фанатской базы.</p>
</div>

<div class="content-area content-two-column">
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
            <div class="flex-tags">
                <button type="submit" class="btn">Применить</button>
                <a class="btn" href="{{ route('weapons.index') }}">Сбросить</a>
            </div>
        </form>
        <div class="panel panel-compact">
            <p class="subtle-title">Справка</p>
            <p class="muted mt-6">База готова к расширению на броню, навыки и контент. Используйте фильтр, чтобы сузить выбор.</p>
        </div>
    </aside>

    <section class="content-area content-gap-14">
        <div class="panel">
            <div class="subtle-title">Итог</div>
            <h2 class="mt-6">Найдено {{ $weapons->count() }} предметов</h2>
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
                    <h2 class="mt-10">
                        <a class="link-plain" href="{{ route('weapons.show', $weapon->slug) }}">{{ $weapon->name }}</a>
                    </h2>
                    <p class="mt-6">{{ \Illuminate\Support\Str::limit($weapon->description, 140) }}</p>
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
