@php
    $isEdit = isset($item);
    $title = $isEdit ? 'Edit Armor' : 'Create Armor';
@endphp
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">{{ $isEdit ? 'Редактировать броню' : 'Добавить броню' }}</h1>
        <p class="text-sm text-slate-600">{{ $isEdit ? 'Обновите атрибуты предмета.' : 'Добавьте предмет брони: выберите материал и слот; медиа — опционально.' }}</p>
    </div>

    <form action="{{ $isEdit ? route('admin.armor-items.update', $item->id) : route('admin.armor-items.store') }}" method="POST" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Name</label>
                <input name="name" value="{{ old('name', $item->name ?? '') }}" class="mt-1 w-full rounded border-slate-300" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <input name="slug" value="{{ old('slug', $item->slug ?? '') }}" placeholder="auto-generated if empty" class="mt-1 w-full rounded border-slate-300">
                @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Material</label>
                <select name="material" class="mt-1 w-full rounded border-slate-300" required>
                    @foreach(\App\Models\ArmorItem::MATERIALS as $material)
                        <option value="{{ $material }}" @selected(old('material', $item->material ?? null) === $material)>{{ \App\Models\ArmorItem::materialLabel($material) }}</option>
                    @endforeach
                </select>
                @error('material')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slot</label>
                <select name="slot" class="mt-1 w-full rounded border-slate-300" required>
                    @foreach(\App\Models\ArmorItem::SLOTS as $slot)
                        <option value="{{ $slot }}" @selected(old('slot', $item->slot ?? null) === $slot)>{{ \App\Models\ArmorItem::slotLabel($slot) }}</option>
                    @endforeach
                </select>
                @error('slot')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Enchantment (0-3)</label>
            <input type="number" min="0" max="3" name="enchantment" value="{{ old('enchantment', isset($item) ? (int)$item->enchantment : 0) }}" class="mt-1 w-full rounded border-slate-300">
            @error('enchantment')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Image URL</label>
            <input name="image" value="{{ old('image', $item->image ?? '') }}" class="mt-1 w-full rounded border-slate-300">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if(old('image') || (!empty($item->image)))
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ old('image', $item->image ?? '') }}" alt="Preview" class="max-h-48 rounded border border-slate-200">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Icon URL (список/превью)</label>
            <input name="icon" value="{{ old('icon', $item->icon ?? '') }}" class="mt-1 w-full rounded border-slate-300">
            @error('icon')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if(!empty($item->icon))
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ $item->icon }}" alt="Icon preview" class="max-h-24 rounded border border-slate-200">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes', $item->author_notes ?? '') }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">{{ $isEdit ? 'Update' : 'Save' }}</button>
            <a href="{{ route('admin.armor-items.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
            @if($isEdit)
                <a href="{{ route('armor.show', $item->slug) }}" class="text-sm text-indigo-700 hover:text-indigo-800">View public</a>
            @endif
        </div>
    </form>
@endsection
