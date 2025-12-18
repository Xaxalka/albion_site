@extends('layouts.app')
@php
    $title = 'Edit Armor';
@endphp

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Armor Item</h1>
        <p class="text-sm text-slate-600">Update base attributes; media URLs are optional.</p>
    </div>

    <form action="{{ route('admin.armor.update', $item->id) }}" method="POST" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Name</label>
                <input name="name" value="{{ old('name', $item->name) }}" class="mt-1 w-full rounded border-slate-300" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <input name="slug" value="{{ old('slug', $item->slug) }}" class="mt-1 w-full rounded border-slate-300">
                @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Material</label>
                <select name="material" class="mt-1 w-full rounded border-slate-300" required>
                    @foreach(\App\Models\ArmorItem::MATERIALS as $material)
                        <option value="{{ $material }}" @selected(old('material', $item->material) === $material)>{{ \App\Models\ArmorItem::materialLabel($material) }}</option>
                    @endforeach
                </select>
                @error('material')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slot</label>
                <select name="slot" class="mt-1 w-full rounded border-slate-300" required>
                    @foreach(\App\Models\ArmorItem::SLOTS as $slot)
                        <option value="{{ $slot }}" @selected(old('slot', $item->slot) === $slot)>{{ \App\Models\ArmorItem::slotLabel($slot) }}</option>
                    @endforeach
                </select>
                @error('slot')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Enchantment (0-3)</label>
            <input type="number" min="0" max="3" name="enchantment" value="{{ old('enchantment', (int) $item->enchantment) }}" class="mt-1 w-full rounded border-slate-300">
            @error('enchantment')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Image URL</label>
            <input name="image" value="{{ old('image', $item->image) }}" class="mt-1 w-full rounded border-slate-300">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if($item->image)
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ $item->image }}" alt="Preview {{ $item->name }}" class="max-h-48 rounded border border-slate-200">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Icon URL (список/превью)</label>
            <input name="icon" value="{{ old('icon', $item->icon) }}" class="mt-1 w-full rounded border-slate-300">
            @error('icon')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if($item->icon)
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ $item->icon }}" alt="Icon preview {{ $item->name }}" class="max-h-24 rounded border border-slate-200">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes', $item->author_notes) }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description', $item->description) }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Update</button>
            <a href="{{ route('admin.armor.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
            <a href="{{ route('armor.show', $item->slug) }}" class="text-sm text-indigo-700 hover:text-indigo-800">View public</a>
        </div>
    </form>
@endsection
