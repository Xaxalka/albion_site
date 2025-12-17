@extends('layouts.app')
@php
    $title = 'Edit E Skill';
@endphp

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit E Skill for {{ $weapon->name }}</h1>
        <p class="text-sm text-slate-600">Unique per-weapon ability.</p>
    </div>

    @php
        $latestMedia = $skill->media->sortByDesc('created_at')->first();
        $previewUrl = null;

        if ($latestMedia) {
            $previewUrl = $latestMedia->disk === 'url'
                ? $latestMedia->path
                : \Illuminate\Support\Facades\URL::temporarySignedRoute('media.show', now()->addMinutes(30), ['media' => $latestMedia]);
        }
    @endphp

    <form action="{{ route('admin.weapon-skills.update', $weapon->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700">Name</label>
            <input name="name" value="{{ old('name', $skill->name) }}" class="mt-1 w-full rounded border-slate-300" required>
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded border-slate-300">{{ old('description', $skill->description) }}</textarea>
            @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Author Notes</label>
            <textarea name="author_notes" rows="3" class="mt-1 w-full rounded border-slate-300">{{ old('author_notes', $skill->author_notes) }}</textarea>
            @error('author_notes')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid md:grid-cols-3 gap-4 items-start">
            <div class="md:col-span-2 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Upload media (png/jpg/gif)</label>
                    <input type="file" name="media_upload" accept=".png,.jpg,.jpeg,.gif" class="mt-1 w-full rounded border-slate-300 bg-white">
                    @error('media_upload')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">YouTube или внешний URL</label>
                    <input name="media_url" value="{{ old('media_url') }}" placeholder="https://youtu.be/..." class="mt-1 w-full rounded border-slate-300">
                    @error('media_url')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <div class="block text-sm font-medium text-slate-700 mb-1">Предпросмотр иконки</div>
                <div id="mediaPreview" class="mt-1 flex h-28 w-full items-center justify-center rounded border border-dashed border-slate-300 bg-slate-50 overflow-hidden">
                    @if($previewUrl)
                        <img id="mediaPreviewImg" src="{{ $previewUrl }}" alt="Media preview" class="h-full w-full object-contain" data-initial="{{ $previewUrl }}">
                    @else
                        <span id="mediaPreviewPlaceholder" class="text-xs text-slate-500 text-center px-2">Выберите файл или вставьте ссылку, чтобы увидеть иконку</span>
                        <img id="mediaPreviewImg" src="" alt="" class="hidden h-full w-full object-contain" data-initial="">
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-2">Картинка автоматически впишется в квадрат без искажения.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700" type="submit">Save</button>
            <a href="{{ route('admin.weapons.edit', $weapon->id) }}" class="text-sm text-slate-600 hover:text-slate-800">Back</a>
        </div>
    </form>

    <script>
        const fileInput = document.querySelector('input[name="media_upload"]');
        const urlInput = document.querySelector('input[name="media_url"]');
        const previewImg = document.getElementById('mediaPreviewImg');
        const placeholder = document.getElementById('mediaPreviewPlaceholder');

        const setPreview = (src) => {
            if (!previewImg) return;
            if (src) {
                previewImg.src = src;
                previewImg.classList.remove('hidden');
                placeholder?.classList.add('hidden');
                return;
            }

            previewImg.src = '';
            previewImg.classList.add('hidden');
            placeholder?.classList.remove('hidden');
        };

        fileInput?.addEventListener('change', (event) => {
            const [file] = event.target.files || [];
            if (!file) {
                setPreview(urlInput?.value || previewImg?.dataset.initial || '');
                return;
            }
            const objectUrl = URL.createObjectURL(file);
            setPreview(objectUrl);
        });

        urlInput?.addEventListener('input', (event) => {
            const value = event.target.value.trim();
            if (value === '') {
                setPreview(previewImg?.dataset.initial || '');
                return;
            }
            setPreview(value);
        });

        setPreview(urlInput?.value?.trim() || previewImg?.dataset.initial || '');
    </script>
@endsection
