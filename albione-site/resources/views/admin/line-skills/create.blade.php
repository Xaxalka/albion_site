@php($title = 'Add Line Skill')
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Add Skill to {{ $line->name }}</h1>
        <p class="text-sm text-slate-600">Shared Q/W/Passive skill.</p>
    </div>

    <form action="{{ route('admin.line-skills.store', $line->id) }}" method="POST" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Slot</label>
                <select name="slot" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Choose slot</option>
                    @foreach(['Q','W','Passive'] as $slot)
                        <option value="{{ $slot }}" @selected(old('slot') === $slot)>{{ $slot }}</option>
                    @endforeach
                </select>
                @error('slot')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Name</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-slate-300" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes') }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Save</button>
            <a href="{{ route('admin.weapon-lines.edit', $line->id) }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
        </div>
    </form>
@endsection
