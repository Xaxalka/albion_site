@php($title = 'Edit Weapon Line')
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Weapon Line</h1>
        <p class="text-sm text-slate-600">Manage shared skills for this line.</p>
    </div>

    <form action="{{ route('admin.weapon-lines.update', $line->id) }}" method="POST" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Name</label>
                <input name="name" value="{{ old('name', $line->name) }}" class="mt-1 w-full rounded border border-slate-300" required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <input name="slug" value="{{ old('slug', $line->slug) }}" class="mt-1 w-full rounded border border-slate-300">
                @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border border-slate-300">{{ old('description', $line->description) }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Update</button>
            <a href="{{ route('admin.weapon-lines.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Cancel</a>
            <a href="{{ route('admin.line-skills.create', $line->id) }}" class="text-sm text-indigo-700 hover:text-indigo-800">Add Line Skill</a>
        </div>
    </form>

    <section class="mt-6 rounded-lg border border-slate-200 bg-white p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-slate-900">Line Skills</h2>
            <span class="text-xs text-slate-500">{{ $line->lineSkills->count() }} skills</span>
        </div>
        <div class="space-y-3">
            @forelse($line->lineSkills as $skill)
                <article class="rounded border border-slate-200 p-4 flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="rounded bg-indigo-50 px-2 py-0.5 text-indigo-700 font-semibold">{{ $skill->slot }}</span>
                            <span class="font-semibold text-slate-800">{{ $skill->name }}</span>
                        </div>
                        <p class="text-sm text-slate-700 mt-1">{{ $skill->description }}</p>
                    </div>
                    <a href="{{ route('admin.line-skills.edit', $skill->id) }}" class="text-sm text-indigo-700 hover:text-indigo-800">Edit</a>
                </article>
            @empty
                <p class="text-sm text-slate-600">No skills yet.</p>
            @endforelse
        </div>
    </section>
@endsection
