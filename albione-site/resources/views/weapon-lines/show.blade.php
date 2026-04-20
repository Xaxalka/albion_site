@php($title = $line->display_name . ' — ' . __('ui.weapons.line'))
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">{{ __('ui.weapons.line') }}</div>
    <h1>{{ $line->display_name }}</h1>
    <p>{{ $line->display_description }}</p>
    <div class="tags">
        <a class="btn" href="{{ route('weapon-lines.index') }}">← {{ app()->getLocale() === 'ru' ? 'Вернуться к линиям' : 'Back to lines' }}</a>
    </div>
</div>

<section class="panel">
    <div class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Общие навыки' : 'Shared skills' }}</div>
    <h2 style="margin-top:6px;">Q / W / E</h2>
    <div class="card-grid" style="margin-top:12px;">
        @foreach(['Q','W','E'] as $slot)
            <article class="card">
                <div class="meta">{{ $slot }}</div>
                @forelse($skillGroups[$slot] as $skill)
                    <div style="margin-top:8px;">
                        <h3>{{ $skill->name }}</h3>
                        <p style="margin-top:6px;">{{ $skill->description }}</p>
                    </div>
                @empty
                    <p class="muted">{{ app()->getLocale() === 'ru' ? 'Нет навыков.' : 'No skills.' }}</p>
                @endforelse
            </article>
        @endforeach
    </div>
</section>

<section class="panel">
    <div class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Оружие в линии' : 'Weapons in this line' }}</div>
    <h2 style="margin-top:6px;">{{ $line->weapons->count() }} {{ app()->getLocale() === 'ru' ? 'предметов' : 'items' }}</h2>
    <div class="card-grid" style="margin-top:12px;">
        @forelse($line->weapons as $weapon)
            <article class="card">
                <h3 style="margin-top:10px;">
                    <a href="{{ route('weapons.show', $weapon->slug) }}" style="color:inherit; text-decoration:none;">{{ $weapon->display_name }}</a>
                </h3>
                <p style="margin-top:6px;">{{ $weapon->display_description }}</p>
            </article>
        @empty
            <p class="muted">{{ app()->getLocale() === 'ru' ? 'В этой линии пока нет оружия.' : 'No weapons in this line yet.' }}</p>
        @endforelse
    </div>
</section>
@endsection
