@php($title = 'Admin Armor')
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manage Armor</h1>
            <p class="text-sm text-slate-600">CRUD for armor items (cloth/leather/plate).</p>
        </div>
        <a href="{{ route('admin.armor.create') }}" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Add Armor
        </a>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr class="text-left text-sm font-semibold text-slate-700">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Material</th>
                    <th class="px-4 py-3">Slot</th>
                    <th class="px-4 py-3">Updated</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-indigo-700">
                            <a href="{{ route('armor.show', $item->slug) }}" class="hover:underline">{{ $item->name }}</a>
                        </td>
                        <td class="px-4 py-3">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</td>
                        <td class="px-4 py-3">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</td>
                        <td class="px-4 py-3">{{ $item->updated_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.armor.edit', $item->id) }}" class="text-indigo-700 hover:text-indigo-800">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-slate-600">No armor items yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection
