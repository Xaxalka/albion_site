@php($title = $weapon->name . ' - Albion Armory')
@extends('layouts.app')

@section('content')
    <style>[x-cloak]{display:none;}</style>
    <div
        class="max-w-5xl mx-auto space-y-8"
        x-data="{
            tiers: ['T4','T5','T6','T7','T8'],
            enchantments: [0,1,2,3],
            selectedTier: @json($weapon->tier),
            selectedEnchant: @json((int) $weapon->enchantment),
        }"
    >
        <header class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center">
                <div class="md:w-48 bg-slate-50 border-b md:border-b-0 md:border-r border-slate-200 flex justify-center p-6">
                    <img
                        src="{{ $weapon->image ?: 'https://via.placeholder.com/256x256?text=Weapon' }}"
                        alt="{{ $weapon->name }}"
                        class="h-32 w-32 object-contain drop-shadow"
                    >
                </div>
                <div class="flex-1 p-6 space-y-3">
                    <div class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
                        <span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-700" x-text="selectedTier" x-cloak></span>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">Enchantment +<span x-text="selectedEnchant"></span></span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 capitalize">{{ $weapon->type }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $weapon->name }}</h1>
                    <p class="text-slate-600">Item Power: <span class="font-semibold text-slate-900">{{ $weapon->item_power }}</span></p>
                    <p class="text-slate-700 leading-relaxed">{{ $weapon->description }}</p>
                </div>
            </div>
        </header>

        <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
            <h2 class="text-xl font-semibold text-slate-900">Characteristics</h2>
            <div class="overflow-hidden rounded-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <tbody class="divide-y divide-slate-200">
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Tier</th>
                            <td class="px-4 py-3 text-slate-900 font-semibold">
                                <span x-text="selectedTier"></span>
                            </td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Enchantment</th>
                            <td class="px-4 py-3 text-slate-900 font-semibold">+<span x-text="selectedEnchant"></span></td>
                        </tr>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Item Power</th>
                            <td class="px-4 py-3 text-slate-900 font-semibold">{{ $weapon->item_power }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Type</th>
                            <td class="px-4 py-3 text-slate-900 capitalize">{{ $weapon->type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900">Variants</h2>
                <p class="text-sm text-slate-600" x-text="`Selected: ${selectedTier} · Enchantment +${selectedEnchant}`"></p>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Tier</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="tier in tiers" :key="tier">
                            <button
                                type="button"
                                @click="selectedTier = tier"
                                :aria-pressed="selectedTier === tier"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                                :class="selectedTier === tier ? 'bg-indigo-600 text-white border-indigo-600 shadow' : 'bg-white text-slate-700 border-slate-200 hover:border-indigo-300 hover:text-indigo-700'"
                            >
                                <span x-text="tier"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Enchantment</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="level in enchantments" :key="level">
                            <button
                                type="button"
                                @click="selectedEnchant = level"
                                :aria-pressed="selectedEnchant === level"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                                :class="selectedEnchant === level ? 'bg-amber-500 text-white border-amber-500 shadow' : 'bg-white text-slate-700 border-slate-200 hover:border-amber-200 hover:text-amber-700'"
                            >
                                +<span x-text="level"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
