@php($title = $weapon->name . ' — оружие Albion')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Карточка оружия</div>
    <h1>{{ $weapon->name }}</h1>
    <p>{{ $weapon->description }}</p>
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
