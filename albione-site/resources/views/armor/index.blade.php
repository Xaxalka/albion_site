@extends('layouts.app')
@php
    $title = 'Броня';
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">Каталог</div>
    <h1>Броня Albion</h1>
    <p>Разделение по типу брони: тканевые, кожаные и латные. Внутри — слоты тела, головы и ног (мантии/куртки/броня; колпаки/капюшоны/шлемы; сандали/сапоги/ботинки).</p>
</div>

<div class="content-area" style="grid-template-columns: 1fr; gap: 16px;">
    <aside class="panel">
        <div class="subtle-title">Фильтр</div>
        <form method="GET" action="{{ route('armor.index') }}">
            <div>
                <label>Материал</label>
                <select name="material">
                    <option value="">Все</option>
                    @foreach(\App\Models\ArmorItem::MATERIALS as $material)
                        <option value="{{ $material }}" @selected(($selectedMaterial ?? null) === $material)>{{ \App\Models\ArmorItem::materialLabel($material) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Слот</label>
                <select name="slot">
                    <option value="">Все</option>
                    @foreach(\App\Models\ArmorItem::SLOTS as $slot)
                        <option value="{{ $slot }}" @selected(($selectedSlot ?? null) === $slot)>{{ \App\Models\ArmorItem::slotLabel($slot) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:10px; flex-wrap: wrap;">
                <button type="submit">Применить</button>
                <a class="btn" href="{{ route('armor.index') }}">Сбросить</a>
            </div>
        </form>
        <div class="panel" style="margin-top:12px; padding:12px 14px;">
            <p class="subtle-title">Итог</p>
            <p class="muted" style="margin-top:6px;">Найдено {{ $items->count() }} предметов</p>
        </div>
    </aside>

    <section class="content-area" style="gap:14px;">
        @forelse($materialsToShow as $material)
            <div class="panel">
                <div class="subtle-title">Тип брони</div>
                <h2 style="margin-top:6px;">{{ \App\Models\ArmorItem::materialLabel($material) }}</h2>
            </div>

            <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px;">
                @foreach($slotsToShow as $slot)
                    @php
                        $slotItems = ($itemsByMaterialSlot[$material][$slot] ?? collect());
                    @endphp
                    <div class="card">
                        <div class="meta">{{ \App\Models\ArmorItem::slotLabel($slot) }}</div>
                        <h3 style="margin-top:6px;">{{ \App\Models\ArmorItem::pieceLabel($material, $slot) }}</h3>
                        <div class="tags" style="margin-top:10px;">
                            <span class="chip">{{ \App\Models\ArmorItem::materialLabel($material) }}</span>
                            <span class="chip">{{ \App\Models\ArmorItem::slotLabel($slot) }}</span>
                        </div>

                        <div style="margin-top:12px; display:grid; gap:10px;">
                            @forelse($slotItems as $item)
                                <div class="panel" style="padding:12px 14px;">
                                    <div style="display:flex; justify-content: space-between; gap:12px; align-items: baseline; flex-wrap: wrap;">
                                        <a href="{{ route('armor.show', $item->slug) }}" style="color:inherit; text-decoration:none; font-weight:700;">{{ $item->name }}</a>
                                        <div class="tags" style="margin-top:0;">
                                            <span class="chip">+{{ (int) $item->enchantment }}</span>
                                        </div>
                                    </div>
                                    @if($item->description)
                                        <p class="muted" style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($item->description, 140) }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="muted">Пока нет предметов в этой категории. Запустите сидер или добавьте данные.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="panel">
                <p class="muted">Броня не найдена. Запустите миграции и сиды, затем обновите страницу.</p>
            </div>
        @endforelse
    </section>
</div>
@endsection
