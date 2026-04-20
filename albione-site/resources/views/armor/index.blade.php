@extends('layouts.app')
@php
    $title = __('ui.armor.title');
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">{{ __('ui.catalog.label') }}</div>
    <h1>{{ __('ui.armor.page_title') }}</h1>
    <p>{{ __('ui.armor.intro') }}</p>
</div>

<div class="content-area" style="grid-template-columns: 1fr; gap: 16px;">
    <aside class="panel">
        <div class="subtle-title">{{ __('ui.catalog.filter') }}</div>
        <form method="GET" action="{{ route('armor.index') }}">
            <div>
                <label>{{ __('ui.armor.material') }}</label>
                <select name="material">
                    <option value="">{{ __('ui.armor.all') }}</option>
                    @foreach(\App\Models\ArmorItem::MATERIALS as $material)
                        <option value="{{ $material }}" @selected(($selectedMaterial ?? null) === $material)>{{ \App\Models\ArmorItem::materialLabel($material) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>{{ __('ui.armor.slot') }}</label>
                <select name="slot">
                    <option value="">{{ __('ui.armor.all') }}</option>
                    @foreach(\App\Models\ArmorItem::SLOTS as $slot)
                        <option value="{{ $slot }}" @selected(($selectedSlot ?? null) === $slot)>{{ \App\Models\ArmorItem::slotLabel($slot) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:10px; flex-wrap: wrap;">
                <button type="submit">{{ __('ui.actions.apply') }}</button>
                <a class="btn" href="{{ route('armor.index') }}">{{ __('ui.actions.reset') }}</a>
            </div>
        </form>
        <div class="panel" style="margin-top:12px; padding:12px 14px;">
            <p class="subtle-title">{{ __('ui.catalog.summary') }}</p>
            <p class="muted" style="margin-top:6px;">{{ __('ui.armor.found', ['count' => $items->count()]) }}</p>
        </div>
    </aside>

    <section class="content-area" style="gap:14px;">
        @forelse($materialsToShow as $material)
            <div class="panel">
                <div class="subtle-title">{{ __('ui.armor.armor_type') }}</div>
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
                                        <a href="{{ route('armor.show', $item->slug) }}" style="color:inherit; text-decoration:none; font-weight:700;">{{ $item->display_name }}</a>
                                    </div>
                                    @if($item->description)
                                        <p class="muted" style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($item->display_description, 140) }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="muted">{{ __('ui.armor.empty_category') }}</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="panel">
                <p class="muted">{{ __('ui.armor.empty_all') }}</p>
            </div>
        @endforelse
    </section>
</div>
@endsection
