@php($title = $weapon->name . ' - Albion Armory')
@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <aside class="md:col-span-1 space-y-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <h2 class="text-lg font-semibold text-slate-800 mb-2">Weapon Info</h2>
                <dl class="space-y-2 text-sm text-slate-700">
                    <div class="flex justify-between">
                        <dt class="font-medium text-slate-600">Tier</dt>
                        <dd class="font-semibold">{{ $weapon->tier }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-slate-600">Line</dt>
                        <dd class="font-semibold">{{ $weapon->weaponLine?->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-600">Slug</dt>
                        <dd class="break-words">{{ $weapon->slug }}</dd>
                    </div>
                </dl>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm text-slate-600">
                <p class="font-semibold text-slate-800 mb-2">Navigate</p>
                <a href="{{ route('weapons.index') }}" class="text-indigo-700 hover:text-indigo-800">Back to list</a>
            </div>
        </aside>
        <article class="md:col-span-3 space-y-6">
            <header class="flex flex-col md:flex-row md:items-center md:gap-6">
                <img class="h-40 w-40 rounded bg-slate-100 object-cover border border-slate-200" src="{{ $weapon->image }}" alt="{{ $weapon->name }}">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="rounded bg-slate-100 px-2 py-0.5 font-semibold text-slate-800">{{ $weapon->tier }}</span>
                        <span class="rounded bg-indigo-50 px-2 py-0.5 text-indigo-700">{{ $weapon->category }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $weapon->name }}</h1>
                </div>
            </header>

            <section class="rounded-lg border border-slate-200 bg-white p-6 space-y-4">
                <h2 class="text-xl font-semibold text-slate-900">Description</h2>
                <p class="text-slate-700 leading-relaxed">{{ $weapon->description }}</p>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900">Unique E Skill</h2>
                    @if($weapon->weaponSkill)
                        <span class="text-xs text-slate-500">Custom to this weapon</span>
                    @endif
                </div>
                @if($weapon->weaponSkill)
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-indigo-800">{{ $weapon->weaponSkill->name }}</p>
                        <p class="text-slate-700">{{ $weapon->weaponSkill->description }}</p>
                        @if($weapon->weaponSkill->author_notes)
                            <p class="text-xs text-slate-500">Author: {{ $weapon->weaponSkill->author_notes }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-slate-600">No E skill defined yet.</p>
                @endif
            </section>

            <section class="rounded-lg border border-dashed border-slate-200 bg-white p-6 space-y-3">
                <h2 class="text-xl font-semibold text-slate-900">Line Skills (Q/W/Passive)</h2>
                <div class="grid md:grid-cols-2 gap-3">
                    @forelse($weapon->weaponLine?->lineSkills ?? [] as $skill)
                        <article class="rounded border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="rounded bg-indigo-50 px-2 py-0.5 text-indigo-700 font-semibold">{{ $skill->slot }}</span>
                                <span class="text-slate-700 font-semibold">{{ $skill->name }}</span>
                            </div>
                            <p class="text-sm text-slate-700 mt-1">{{ $skill->description }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-slate-600">No line skills defined for this weapon line.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                <p>This page is ready for additional sections such as abilities, spells, crafting, and lore.</p>
            </section>
        </article>
    </div>
@endsection
