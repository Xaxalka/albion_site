@php($title = 'Albion Codex — вход')
@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="eyebrow">Быстрый старт</div>
    <h1>Albion Arsenal</h1>
    <p>Перейдите сразу к списку линий или всего оружия. Все страницы оформлены в едином тёмном стиле фанатской wiki.</p>
</div>

<section class="panel">
    <div class="card-grid">
        <article class="card">
            <div class="subtle-title">Ветки оружий</div>
            <h2>Общие ветки</h2>
            <p class="muted" style="margin-top:6px;">Список веток с общими умениями и быстрыми подсказками.</p>
            <div class="tags" style="margin-top:10px;">
                <a class="btn" href="{{ route('weapon-lines.index') }}">Открыть линии</a>
            </div>
        </article>
        <article class="card">
            <div class="subtle-title">Полный каталог</div>
            <h2>Оружие</h2>
            <p class="muted" style="margin-top:6px;">Фильтры, карточки и характеристики в одном месте.</p>
            <div class="tags" style="margin-top:10px;">
                <a class="btn" href="{{ route('weapons.index') }}">Смотреть оружие</a>
            </div>
        </article>
    </div>
</section>
@endsection
