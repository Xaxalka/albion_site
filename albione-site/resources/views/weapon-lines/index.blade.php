@php($title = 'Линии оружия')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Арсенал</div>
    <h1>Линии оружия</h1>
    <p>Albion-ветки с общими умениями Q/W и пассивками. Атмосфера тёмного кодекса перенесена на весь сайт.</p>
</div>

<section class="panel">
    <div class="subtle-title">Быстрый обзор</div>
    <p class="muted">Под сетками добавлены подсказки по основным веткам, чтобы проще ориентироваться между вкладками.</p>
    <div class="card-grid spaced-top">
        <div class="card">
            <div class="subtle-title">Вкладка воина</div>
            <h3>Арбалеты, Молоты, Топоры</h3>
            <p class="muted">Боевые перчатки, Булавы, Мечи.</p>
        </div>
        <div class="card">
            <div class="subtle-title">Вкладка охотников</div>
            <h3>Луки, Кинжалы, Копья</h3>
            <p class="muted">Шесты, Shapeshifts, Друиды.</p>
        </div>
        <div class="card">
            <div class="subtle-title">Вкладка магов</div>
            <h3>Огненные, Священные, Мистические</h3>
            <p class="muted">Морозные посохи, Проклятые посохи.</p>
        </div>
    </div>
</section>

<section class="card-grid">
    @foreach($lines as $line)
        <article class="card">
            <div class="subtle-title">{{ $line->weapons->count() }} оружия</div>
            <h2><a class="link-plain" href="{{ route('weapon-lines.show', $line->slug) }}">{{ $line->name }}</a></h2>
            <p>{{ \Illuminate\Support\Str::limit($line->description, 160) }}</p>
            <div class="tags">
                @foreach($line->weapons as $weapon)
                    <span class="chip">{{ $weapon->name }}</span>
                @endforeach
            </div>
            @if(isset($quickBranches[$line->name]))
                <div class="panel panel-compact">
                    <div class="subtle-title">Основные ветки</div>
                    <p class="muted mt-6">{{ $quickBranches[$line->name] }}</p>
                </div>
            @endif
        </article>
    @endforeach
</section>
@endsection
