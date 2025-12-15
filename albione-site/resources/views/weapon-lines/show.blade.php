@php($title = $line->name . ' — линия оружия')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm theme-muted">Weapon Line</p>
                <h1 class="text-3xl font-bold">{{ $line->name }}</h1>
                <p class="mt-2 theme-muted">{{ $line->description }}</p>
            </div>
            <a href="{{ route('weapon-lines.index') }}" class="text-sm theme-link">Back to lines</a>
        </div>

        <section class="rounded-lg border theme-panel p-5 space-y-4">
            <header class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">Shared Skills (Q / W / Passive)</h2>
                <span class="text-xs theme-muted">{{ $line->lineSkills->count() }} skills</span>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($line->lineSkills as $skill)
                    <article class="rounded border p-4 theme-card">
                        <div class="flex items-center justify-between text-sm theme-muted">
                            <span class="rounded px-2 py-0.5 text-xs font-semibold theme-tag">{{ $skill->slot }}</span>
                            @if($skill->author_notes)
                                <span class="text-xs theme-muted">Notes available</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-lg font-semibold">{{ $skill->name }}</h3>
                        <p class="text-sm theme-muted mt-1">{{ $skill->description }}</p>
                        @if($skill->author_notes)
                            <p class="mt-2 text-xs theme-muted">Author: {{ $skill->author_notes }}</p>
                        @endif
                    </article>
                @empty
                    <p class="text-sm theme-muted">No shared skills yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-lg border theme-panel p-5 space-y-4">
            <header class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">Weapons in this line</h2>
                <span class="text-xs theme-muted">{{ $line->weapons->count() }} weapons</span>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($line->weapons as $weapon)
                    <article class="rounded border p-4 theme-card">
                        @if($weapon->weaponSkill)
                            <div class="flex items-center gap-2 text-sm theme-muted">
                                <span class="rounded px-2 py-0.5 text-xs font-semibold theme-tag">E: {{ $weapon->weaponSkill->name }}</span>
                            </div>
                        @endif
                        <a href="{{ route('weapons.show', $weapon->slug) }}" class="block mt-2 text-lg font-semibold theme-link">
                            {{ $weapon->name }}
                        </a>
                        <p class="text-sm theme-muted mt-1">{{ $weapon->description }}</p>
                    </article>
                @empty
                    <p class="text-sm theme-muted">No weapons in this line yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
