@php
    $isEdit = isset($item);
    $title = $isEdit ? 'Edit Armor' : 'Create Armor';
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('content')
    <div class="section-header">
        <div class="eyebrow">Админка брони</div>
        <h1>{{ $isEdit ? 'Редактировать броню' : 'Добавить броню' }}</h1>
        <p>{{ $isEdit ? 'Обновите атрибуты предмета.' : 'Добавьте предмет брони: выберите материал и слот; медиа — опционально.' }}</p>
    </div>

    <form action="{{ $isEdit ? route('admin.armor-items.update', $item->id) : route('admin.armor-items.store') }}" method="POST" class="panel" style="margin-top: 18px;">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="subtle-title">Базовые данные</div>
        <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-top: 12px;">
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Название</span>
                <input name="name" value="{{ old('name', $item->name ?? '') }}" required
                       style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text);">
                @error('name')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
            </label>
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Slug</span>
                <input name="slug" value="{{ old('slug', $item->slug ?? '') }}" placeholder="auto-generated if empty"
                       style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text);">
                @error('slug')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
            </label>
        </div>

        <div class="subtle-title" style="margin-top: 16px;">Категория</div>
        <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-top: 12px;">
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Материал</span>
                <select name="material" required
                        style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text);">
                    @foreach(\App\Models\ArmorItem::MATERIALS as $material)
                        <option value="{{ $material }}" @selected(old('material', $item->material ?? null) === $material)>{{ \App\Models\ArmorItem::materialLabel($material) }}</option>
                    @endforeach
                </select>
                @error('material')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
            </label>
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Слот</span>
                <select name="slot" required
                        style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text);">
                    @foreach(\App\Models\ArmorItem::SLOTS as $slot)
                        <option value="{{ $slot }}" @selected(old('slot', $item->slot ?? null) === $slot)>{{ \App\Models\ArmorItem::slotLabel($slot) }}</option>
                    @endforeach
                </select>
                @error('slot')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
            </label>
        </div>

        <div class="subtle-title" style="margin-top: 16px;">Изображения</div>
        <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-top: 12px;">
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Image URL</span>
                <input name="image" value="{{ old('image', $item->image ?? '') }}"
                       style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text);">
                @error('image')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
                @if(old('image') || (!empty($item->image)))
                    <div>
                        <div class="meta">Preview</div>
                        <img src="{{ old('image', $item->image ?? '') }}" alt="Preview" style="max-height: 180px; border-radius: 12px; border: 1px solid var(--line); box-shadow: 0 10px 26px var(--shadow);">
                    </div>
                @endif
            </label>
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Icon URL (список/превью)</span>
                <input name="icon" value="{{ old('icon', $item->icon ?? '') }}"
                       style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text);">
                @error('icon')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
                @if(!empty($item->icon))
                    <div>
                        <div class="meta">Preview</div>
                        <img src="{{ $item->icon }}" alt="Icon preview" style="max-height: 96px; border-radius: 12px; border: 1px solid var(--line); box-shadow: 0 10px 26px var(--shadow);">
                    </div>
                @endif
            </label>
        </div>

        <div class="subtle-title" style="margin-top: 16px;">Описание</div>
        <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-top: 12px;">
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Author Notes</span>
                <textarea name="author_notes" rows="3"
                          style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text); resize: vertical;">{{ old('author_notes', $item->author_notes ?? '') }}</textarea>
                @error('author_notes')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
            </label>
            <label class="card" style="display: grid; gap: 8px;">
                <span class="meta">Description</span>
                <textarea name="description" rows="4"
                          style="padding: 10px 12px; border-radius: 10px; border: 1px solid var(--line); background: rgba(255,255,255,0.03); color: var(--text); resize: vertical;">{{ old('description', $item->description ?? '') }}</textarea>
                @error('description')<span class="muted" style="color: #fca5a5;">{{ $message }}</span>@enderror
            </label>
        </div>

        <div class="tags" style="margin-top: 16px;">
            <button type="submit">{{ $isEdit ? 'Update' : 'Save' }}</button>
            <a class="btn" href="{{ route('admin.armor-items.index') }}">Cancel</a>
            @if($isEdit)
                <a class="btn" href="{{ route('armor.show', $item->slug) }}">View public</a>
            @endif
        </div>
    </form>

    @if($isEdit)
        <section class="panel" style="margin-top: 24px;">
            <div class="subtle-title">Скиллы брони</div>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 6px;">
                <h2 style="margin: 0;">Умения и эффекты</h2>
                <a class="btn" style="padding: 10px 14px; font-size: 13px;" href="{{ route('admin.armor-skills.create', $item->id) }}">+ Добавить скилл</a>
            </div>
            <div class="card-grid" style="margin-top: 12px;">
                @forelse($item->armorSkills as $skill)
                    <article class="card">
                        <div class="meta">Навык</div>
                        <h3 style="margin-top: 6px;">{{ $skill->name }}</h3>
                        <p class="muted" style="margin-top: 6px;">
                            {{ $skill->description ? Str::limit($skill->description, 100) : 'Описание пока не заполнено.' }}
                        </p>
                        <div class="tags" style="margin-top: 12px;">
                            <a class="btn" style="padding: 8px 12px; font-size: 12px;" href="{{ route('admin.armor-skills.edit', $skill->id) }}">Редактировать</a>
                            <form action="{{ route('admin.armor-skills.destroy', $skill->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this skill?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 8px 12px; font-size: 12px; border-color: rgba(248,113,113,0.5); color: #fecaca; background: linear-gradient(140deg, rgba(248,113,113,0.16), rgba(16,23,35,0.9));">Удалить</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="muted">Навыков пока нет. Добавьте первый.</p>
                @endforelse
            </div>
        </section>
    @endif
@endsection
