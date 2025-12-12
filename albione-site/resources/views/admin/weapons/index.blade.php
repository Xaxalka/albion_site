@php($title = 'Admin Weapons')
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manage Weapons</h1>
            <p class="text-sm text-slate-600">Basic CRUD shell ready for authentication later.</p>
        </div>
        <a href="{{ route('admin.weapons.create') }}" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Add Weapon
        </a>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr class="text-left text-sm font-semibold text-slate-700">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Tier</th>
                    <th class="px-4 py-3">Line</th>
                    <th class="px-4 py-3">Updated</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                @forelse($weapons as $weapon)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-indigo-700">
                            <a href="{{ route('weapons.show', $weapon->slug) }}" class="hover:underline">{{ $weapon->name }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $weapon->tier }}</td>
                        <td class="px-4 py-3">{{ $weapon->weaponLine?->name }}</td>
                        <td class="px-4 py-3">{{ $weapon->updated_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.weapons.edit', $weapon->id) }}" class="text-indigo-700 hover:text-indigo-800">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-slate-600">No weapons yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $weapons->links() }}
    </div>
@endsection
