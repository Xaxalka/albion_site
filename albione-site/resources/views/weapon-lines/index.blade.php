@php($title = 'Weapon Lines')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <header>
            <h1 class="text-3xl font-bold text-slate-900">Weapon Lines</h1>
            <p class="text-sm text-slate-600">Albion-style lines with shared Q/W/Passive skills.</p>
        </header>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($lines as $line)
                <article class="rounded-lg border border-slate-200 bg-white p-5 hover:border-indigo-200">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <a href="{{ route('weapon-lines.show', $line->slug) }}" class="text-xl font-semibold text-indigo-700 hover:text-indigo-800">
                                {{ $line->name }}
                            </a>
                            <p class="text-sm text-slate-600 mt-1">{{ \Illuminate\Support\Str::limit($line->description, 120) }}</p>
                        </div>
                        <span class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">{{ $line->weapons->count() }} weapons</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                        @foreach($line->weapons as $weapon)
                            <span class="rounded bg-indigo-50 px-2 py-1 text-indigo-700">{{ $weapon->name }}</span>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>

        <section class="rounded-lg border border-slate-200 bg-white p-5 space-y-3">
            <h2 class="text-xl font-semibold text-slate-900">Сетки оружия</h2>
            <p class="text-sm text-slate-700">Под сетками добавлены быстрые списки основных веток, чтобы было проще ориентироваться между вкладками.</p>
            <div class="grid md:grid-cols-3 gap-4 text-sm text-slate-700">
                <div class="space-y-1">
                    <h3 class="font-semibold text-slate-900">Вкладка воина</h3>
                    <p class="italic">Арбалеты; Боевые перчатки; Молотки; Булавы; Топоры; Мечи</p>
                </div>
                <div class="space-y-1">
                    <h3 class="font-semibold text-slate-900">Вкладка охотников</h3>
                    <p class="italic">Луки; Кинжалы; Копья; Шесты; Shapershifts; Друиды</p>
                </div>
                <div class="space-y-1">
                    <h3 class="font-semibold text-slate-900">Вкладка магов</h3>
                    <p class="italic">Огненные посохи; Священные посохи; Мистические посохи; Морозные посохи; Проклятые посохи</p>
                </div>
            </div>
        </section>
    </div>
@endsection
