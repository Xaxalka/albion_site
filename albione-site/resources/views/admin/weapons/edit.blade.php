@extends('layouts.app')
@php
    $title = 'Edit Weapon';
@endphp

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Weapon</h1>
        <p class="text-sm text-slate-600">Update base attributes; extend with localized fields or media management later.</p>
    </div>

    <form action="{{ route('admin.weapons.update', $weapon->id) }}" method="POST" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Weapon Line</label>
                <select name="weapon_line_id" class="mt-1 w-full rounded border-slate-300" required>
                    @foreach($weaponLines as $line)
                        <option value="{{ $line->id }}" @selected(old('weapon_line_id', $weapon->weapon_line_id) == $line->id)>{{ $line->name }}</option>
                    @endforeach
                </select>
                @error('weapon_line_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Name</label>
                <input name="name" value="{{ old('name', $weapon->name) }}" class="mt-1 w-full rounded border-slate-300" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <input name="slug" value="{{ old('slug', $weapon->slug) }}" class="mt-1 w-full rounded border-slate-300">
                @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Image URL</label>
            <input name="image" value="{{ old('image', $weapon->image) }}" class="mt-1 w-full rounded border-slate-300">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if($weapon->image)
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ $weapon->image }}" alt="Preview {{ $weapon->name }}" class="max-h-48 rounded border border-slate-200">
                </div>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Icon URL (список/превью)</label>
            <input name="icon" value="{{ old('icon', $weapon->icon) }}" class="mt-1 w-full rounded border-slate-300">
            @error('icon')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if($weapon->icon)
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ $weapon->icon }}" alt="Icon preview {{ $weapon->name }}" class="max-h-24 rounded border border-slate-200">
                </div>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes', $weapon->author_notes) }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        @php
            $line = $weapon->weaponLine;
            $skills = ($weapon->branch?->skills ?? collect())->sortBy('sort');
        @endphp
        @if($line)
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-sm font-medium text-slate-700">Навыки ветки (Q/W/E)</div>
                        <p class="text-xs text-slate-500">Общие для ветки {{ $line->name }}.</p>
                    </div>
                    <a href="{{ route('admin.line-skills.create', $line->id) }}" class="text-sm text-indigo-700 hover:text-indigo-800">Добавить навык линии</a>
                </div>
                @if($skills->count() > 0)
                    <div class="space-y-2">
                        @foreach($skills as $skill)
                            <div class="flex items-center justify-between rounded border border-slate-200 px-3 py-2 text-sm text-slate-700">
                                <div class="flex items-center gap-2">
                                    <span class="rounded bg-indigo-50 px-2 py-0.5 text-indigo-700 font-semibold">{{ $skill->slot }}</span>
                                    <span class="font-medium text-slate-800">{{ $skill->name }}</span>
                                </div>
                                <a href="{{ route('admin.line-skills.edit', $skill->id) }}" class="text-sm text-indigo-700 hover:text-indigo-800">Редактировать</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Навыков пока нет. Добавьте Q/W/E для ветки.</p>
                @endif
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description', $weapon->description) }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Update</button>
            <a href="{{ route('admin.weapons.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
        </div>
    </form>
@endsection
