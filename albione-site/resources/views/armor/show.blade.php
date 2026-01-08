@extends('layouts.app')
@php
    $title = $item->name . ' — броня Albion';
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">Карточка брони</div>
    <h1>{{ $item->name }}</h1>
    <p>{{ $item->description }}</p>
    @if($item->icon)
        <div style="margin-top:12px; width:88px; height:88px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <img src="{{ $item->icon }}" alt="Иконка {{ $item->name }}" style="width:100%; height:100%; object-fit:contain;">
        </div>
    @endif
    @if($item->image)
        <div style="margin-top:12px;">
            <img src="{{ $item->image }}" alt="Изображение {{ $item->name }}" style="max-height:240px; border-radius:14px; border:1px solid var(--line); box-shadow:0 14px 30px var(--shadow);">
        </div>
    @endif
    <div class="tags">
        <span class="chip">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</span>
        <span class="chip">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</span>
        <span class="chip">+{{ (int) $item->enchantment }}</span>
        <a class="btn" href="{{ route('armor.index') }}">← К списку брони</a>
    </div>
</div>

<section class="panel">
    <div class="subtle-title">Характеристики</div>
    <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:16px;">
        <div class="card">
            <div class="meta">Категория</div>
            <h2 style="margin-top:6px;">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</h2>
            <p class="muted">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }} · {{ \App\Models\ArmorItem::slotLabel($item->slot) }}</p>
        </div>
        <div class="card">
            <div class="meta">Зачарование</div>
            <h2 style="margin-top:6px;">+{{ (int) $item->enchantment }}</h2>
            <p class="muted">Отметка уровня зачарования (визуальная метка).</p>
        </div>
    </div>
</section>

<section class="panel">
    <div class="subtle-title">Дополнительно</div>
    <table class="table">
        <tbody>
            <tr>
                <th>Материал</th>
                <td>{{ \App\Models\ArmorItem::materialLabel($item->material) }}</td>
            </tr>
            <tr>
                <th>Слот</th>
                <td>{{ \App\Models\ArmorItem::slotLabel($item->slot) }}</td>
            </tr>
            <tr>
                <th>Тип</th>
                <td>{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</td>
            </tr>
            <tr>
                <th>Зачарование</th>
                <td>+{{ (int) $item->enchantment }}</td>
            </tr>
        </tbody>
    </table>
</section>

@if($item->armorSkills && $item->armorSkills->count() > 0)
    <section class="panel">
        <div class="subtle-title">Скиллы брони</div>
        <div class="content-area" style="display: flex; flex-wrap: wrap; gap: 12px;">
            @foreach($item->armorSkills as $skill)
                <x-skill-hover-popup :skill="$skill" />
            @endforeach
        </div>
    </section>
@endif
@endsection
