@extends('layouts.app')
@php
    $title = 'Оружие';
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">Каталог</div>
    <h1>Оружие Albion</h1>
    <p>Подборка оружия со встроенными фильтрами. Тёмный дизайн кодекса сохраняет настроение фанатской базы.</p>
</div>

<div class="content-area" style="grid-template-columns: 1fr; gap: 16px;">
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
        <div class="card-grid" style="grid-template-columns: 1fr; gap:18px;">
            @forelse($weapons as $weapon)
                @php
                    $latestMedia = null;
                    if ($weapon->weaponSkill && $weapon->weaponSkill->media->count() > 0) {
                        $latestMedia = $weapon->weaponSkill->media->sortByDesc('created_at')->first();
                    }
                    $iconUrl = null;

                    if ($latestMedia) {
                        $iconUrl = $latestMedia->disk === 'url'
                            ? $latestMedia->path
                            : \Illuminate\Support\Facades\URL::temporarySignedRoute('media.show', now()->addMinutes(30), ['media' => $latestMedia]);
                    } elseif ($weapon->icon) {
                        $iconUrl = $weapon->icon;
                    }
                @endphp
                <article class="card" style="width:100%;">
                    <div style="display:flex; gap:16px; align-items:center;">
                        <div style="min-width:110px; width:110px; height:110px; border-radius:16px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            @if($iconUrl)
                                <img src="{{ $iconUrl }}" alt="Иконка {{ $weapon->weaponSkill?->name ?? $weapon->name }}" style="width:100%; height:100%; object-fit:contain;">
                            @else
                                <span class="muted" style="font-size:12px;">Нет иконки</span>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div class="tags" style="margin-top:0; gap:10px;">
                                <span class="chip">{{ $weapon->weaponLine?->name ?? 'Без линии' }}</span>
                                @if($weapon->weaponSkill)
                                    <span class="chip">E: {{ $weapon->weaponSkill->name }}</span>
                                @endif
                            </div>
                            <h2 style="margin-top:10px;">
                                <a href="{{ route('weapons.show', $weapon->slug) }}" style="color:inherit; text-decoration:none;">{{ $weapon->name }}</a>
                            </h2>
                            <p style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($weapon->description, 180) }}</p>
                        </div>
                    </div>
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
