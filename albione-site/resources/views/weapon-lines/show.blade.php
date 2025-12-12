@php($title = $line->name . ' - Weapon Line')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm text-slate-600">Weapon Line</p>
                <h1 class="text-3xl font-bold text-slate-900">{{ $line->name }}</h1>
                <p class="mt-2 text-slate-700">{{ $line->description }}</p>
            </div>
            <a href="{{ route('weapon-lines.index') }}" class="text-sm text-indigo-700 hover:text-indigo-800">Back to lines</a>
        </div>

        <section class="rounded-lg border border-slate-200 bg-white p-5 space-y-4">
            <header class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Shared Skills (Q / W / Passive)</h2>
                <span class="text-xs text-slate-500">{{ $line->lineSkills->count() }} skills</span>
            </header>
            <div class="grid md:grid-cols-2 gap-4">
                @forelse($line->lineSkills as $skill)
                    <article class="rounded border border-slate-200 p-4 bg-slate-50">
                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span class="rounded bg-indigo-50 px-2 py-0.5 text-indigo-700 font-semibold">{{ $skill->slot }}</span>
                            @if($skill->author_notes)
                                <span class="text-xs text-slate-500">Notes available</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $skill->name }}</h3>
                        <p class="text-sm text-slate-700 mt-1">{{ $skill->description }}</p>
                        @if($skill->author_notes)
                            <p class="mt-2 text-xs text-slate-500">Author: {{ $skill->author_notes }}</p>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-slate-600">No shared skills yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-5 space-y-4">
            <header class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Weapons in this line</h2>
                <span class="text-xs text-slate-500">{{ $line->weapons->count() }} weapons</span>
            </header>
            <div class="grid md:grid-cols-2 gap-4">
                @forelse($line->weapons as $weapon)
                    <article class="rounded border border-slate-200 p-4">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="rounded bg-slate-100 px-2 py-0.5 font-semibold text-slate-800">{{ $weapon->tier }}</span>
                            @if($weapon->weaponSkill)
                                <span class="rounded bg-emerald-50 px-2 py-0.5 text-emerald-700">E: {{ $weapon->weaponSkill->name }}</span>
                            @endif
                        </div>
                        <a href="{{ route('weapons.show', $weapon->slug) }}" class="block mt-2 text-lg font-semibold text-indigo-700 hover:text-indigo-800">
                            {{ $weapon->name }}
                        </a>
                        <p class="text-sm text-slate-700 mt-1">{{ $weapon->description }}</p>
                    </article>
                @empty
                    <p class="text-sm text-slate-600">No weapons in this line yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
