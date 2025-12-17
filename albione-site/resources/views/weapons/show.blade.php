@extends('layouts.app')
@php
    $title = $weapon->name . ' — оружие Albion';
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">Карточка оружия</div>
    <h1>{{ $weapon->name }}</h1>
    <p>{{ $weapon->description }}</p>
    @php
        $latestMedia = optional($weapon->weaponSkill?->media)->sortByDesc('created_at')->first();
        $iconUrl = null;

        if ($latestMedia) {
            $iconUrl = $latestMedia->disk === 'url'
                ? $latestMedia->path
                : \Illuminate\Support\Facades\URL::temporarySignedRoute('media.show', now()->addMinutes(30), ['media' => $latestMedia]);
        } elseif ($weapon->icon) {
            $iconUrl = $weapon->icon;
        }
    @endphp
    @if($iconUrl)
        <div style="margin-top:12px; width:88px; height:88px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <img src="{{ $iconUrl }}" alt="Иконка {{ $weapon->weaponSkill?->name ?? $weapon->name }}" style="width:100%; height:100%; object-fit:contain;">
        </div>
    @endif
    @if($weapon->image)
        <div style="margin-top:12px;">
            <img src="{{ $weapon->image }}" alt="Изображение {{ $weapon->name }}" style="max-height:240px; border-radius:14px; border:1px solid var(--line); box-shadow:0 14px 30px var(--shadow);">
        </div>
    @endif
    <div class="tags">
        <span class="chip">{{ $weapon->weaponLine?->name ?? 'Без линии' }}</span>
        <span class="chip">Тип: {{ $weapon->type }}</span>
        <a class="btn" href="{{ route('weapons.index') }}">← К списку оружия</a>
    </div>
</div>

<section class="panel" x-data="{ enchantments: [0,1,2,3], selectedEnchant: @json((int) $weapon->enchantment) }">
    <div class="subtle-title">Характеристики</div>
    <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:16px;">
        <div class="card">
            <div class="meta">Item Power</div>
            <h2 style="margin-top:6px;">{{ $weapon->item_power }}</h2>
            <p class="muted">Базовая мощь предмета, меняется от зачарования.</p>
        </div>
        <div class="card">
            <div class="meta">Зачарование</div>
            <h2 style="margin-top:6px;">+<span x-text="selectedEnchant"></span></h2>
            <p class="muted">Выберите уровень, чтобы отметить редкость.</p>
        </div>
    </div>
    <div class="panel" style="margin-top:14px; padding:14px;">
        <p class="subtle-title">Варианты зачарования</p>
        <div class="tags" style="margin-top:8px;">
            <template x-for="level in enchantments" :key="level">
                <button type="button" @click="selectedEnchant = level" :aria-pressed="selectedEnchant === level" :class="selectedEnchant === level ? 'btn' : 'chip'" style="border: none; background: none; padding: 0;">
                    <span class="chip" :style="selectedEnchant === level ? 'border-color: var(--gold-strong); box-shadow: 0 8px 18px rgba(242,200,124,0.18);' : ''">+<span x-text="level"></span></span>
                </button>
            </template>
        </div>
    </div>
</section>

<section class="panel">
    <div class="subtle-title">Дополнительно</div>
    <table class="table">
        <tbody>
            <tr>
                <th>Линия</th>
                <td>{{ $weapon->weaponLine?->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Тип</th>
                <td class="capitalize">{{ $weapon->type }}</td>
            </tr>
            <tr>
                <th>Зачарование</th>
                <td>+<span x-text="selectedEnchant"></span></td>
            </tr>
        </tbody>
    </table>
</section>
@endsection
