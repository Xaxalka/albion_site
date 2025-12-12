@php($title = 'Edit Weapon')
@extends('layouts.app')

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
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Tier</label>
                <input name="tier" value="{{ old('tier', $weapon->tier) }}" class="mt-1 w-full rounded border-slate-300" required>
                @error('tier')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Image URL</label>
            <input name="image" value="{{ old('image', $weapon->image) }}" class="mt-1 w-full rounded border-slate-300">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes', $weapon->author_notes) }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description', $weapon->description) }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Update</button>
            <a href="{{ route('admin.weapons.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
            <a href="{{ route('admin.weapon-skills.edit', $weapon->id) }}" class="text-sm text-indigo-700 hover:text-indigo-800">Edit E Skill</a>
        </div>
    </form>
@endsection
