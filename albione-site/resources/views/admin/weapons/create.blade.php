@php($title = 'Create Weapon')
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Create Weapon</h1>
        <p class="text-sm text-slate-600">Lightweight form; extend with validation messages or media uploads later.</p>
    </div>

    <form action="{{ route('admin.weapons.store') }}" method="POST" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Weapon Line</label>
                <select name="weapon_line_id" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Select line</option>
                    @foreach($weaponLines as $line)
                        <option value="{{ $line->id }}" @selected(old('weapon_line_id') == $line->id)>{{ $line->name }}</option>
                    @endforeach
                </select>
                @error('weapon_line_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Name</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-slate-300" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <input name="slug" value="{{ old('slug') }}" placeholder="auto-generated if empty" class="mt-1 w-full rounded border-slate-300">
                @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Tier</label>
                <input name="tier" value="{{ old('tier') }}" placeholder="T4, T5" class="mt-1 w-full rounded border-slate-300" required>
                @error('tier')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Image URL</label>
            <input name="image" value="{{ old('image') }}" placeholder="https://example.com/placeholder.png" class="mt-1 w-full rounded border-slate-300">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            @if(old('image'))
                <div class="mt-3">
                    <div class="text-xs text-slate-600 mb-1">Preview</div>
                    <img src="{{ old('image') }}" alt="Preview" class="max-h-48 rounded border border-slate-200">
                </div>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes') }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Save</button>
            <a href="{{ route('admin.weapons.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
        </div>
    </form>
@endsection
