@php($title = $line->name . ' — линия оружия')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Линия оружия</div>
    <h1>{{ $line->name }}</h1>
    <p>{{ $line->description }}</p>
    <div class="tags">
        <a class="btn" href="{{ route('weapon-lines.index') }}">← Вернуться к линиям</a>
    </div>
</div>

<section class="panel">
    <div class="subtle-title">Общие навыки</div>
    <h2 style="margin-top:6px;">Q / W / Passive</h2>
    <div class="card-grid" style="margin-top:12px;">
        @forelse($line->lineSkills as $skill)
            <article class="card">
                <div class="meta">{{ $skill->slot }}</div>
                <h3>{{ $skill->name }}</h3>
                <p style="margin-top:6px;">{{ $skill->description }}</p>
                @if($skill->author_notes)
                    <p class="muted" style="margin-top:8px;">Заметки автора: {{ $skill->author_notes }}</p>
                @endif
            </article>
        @empty
            <p class="muted">Пока нет общих навыков для этой линии.</p>
        @endforelse
    </div>
</section>

<section class="panel">
    <div class="subtle-title">Оружие в линии</div>
    <h2 style="margin-top:6px;">{{ $line->weapons->count() }} предметов</h2>
    <div class="card-grid" style="margin-top:12px;">
        @forelse($line->weapons as $weapon)
            <article class="card">
                @if($weapon->weaponSkill)
                    <span class="chip">E: {{ $weapon->weaponSkill->name }}</span>
                @endif
                <h3 style="margin-top:10px;">
                    <a href="{{ route('weapons.show', $weapon->slug) }}" style="color:inherit; text-decoration:none;">{{ $weapon->name }}</a>
                </h3>
                <p style="margin-top:6px;">{{ $weapon->description }}</p>
            </article>
        @empty
            <p class="muted">В этой линии пока нет оружия.</p>
        @endforelse
    </div>
</section>
@endsection
