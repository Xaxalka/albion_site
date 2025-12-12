@php($title = 'Weapons')
@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <aside class="md:col-span-1 space-y-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <h2 class="text-lg font-semibold mb-3 text-slate-800">Filter</h2>
                <form method="GET" action="{{ route('weapons.index') }}" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tier</label>
                        <select name="tier" class="mt-1 w-full rounded border-slate-300">
                            <option value="">All tiers</option>
                            @foreach($tiers as $tier)
                                <option value="{{ $tier }}" @selected(request('tier') === $tier)>{{ $tier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Weapon Line</label>
                        <select name="weapon_line_id" class="mt-1 w-full rounded border-slate-300">
                            <option value="">All lines</option>
                            @foreach($weaponLines as $line)
                                <option value="{{ $line->id }}" @selected(request('weapon_line_id') == $line->id)>{{ $line->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="flex-1 rounded bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Apply</button>
                        <a href="{{ route('weapons.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Reset</a>
                    </div>
                </form>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-sm text-slate-600">
                <p class="font-semibold text-slate-800 mb-2">About</p>
                <p>Browse Albion-style weapons by tier and category. Content is ready for expansion into armor, skills, and more.</p>
            </div>
        </aside>
        <section class="md:col-span-3">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Weapons</h1>
                    <p class="text-sm text-slate-600">Listing {{ $weapons->count() }} items</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                @forelse($weapons as $weapon)
                    <article class="flex gap-4 rounded-lg border border-slate-200 bg-white p-4 hover:border-indigo-200">
                        <img class="h-20 w-20 rounded bg-slate-100 object-cover" src="{{ $weapon->image }}" alt="{{ $weapon->name }}">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 text-sm text-slate-600 flex-wrap">
                                <span class="rounded bg-slate-100 px-2 py-0.5 font-semibold text-slate-800">{{ $weapon->tier }}</span>
                                <span class="rounded bg-indigo-50 px-2 py-0.5 text-indigo-700">{{ $weapon->weaponLine?->name }}</span>
                                @if($weapon->weaponSkill)
                                    <span class="rounded bg-emerald-50 px-2 py-0.5 text-emerald-700">E: {{ $weapon->weaponSkill->name }}</span>
                                @endif
                            </div>
                            <a href="{{ route('weapons.show', $weapon->slug) }}" class="block text-lg font-semibold text-indigo-700 hover:text-indigo-800">
                                {{ $weapon->name }}
                            </a>
                            <p class="text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($weapon->description, 120) }}</p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg border border-dashed border-slate-200 bg-white p-6 text-center text-slate-600">
                        No weapons found. Adjust filters or seed the database.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
