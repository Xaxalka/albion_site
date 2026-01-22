@extends('layouts.app')
@php
    $title = $item->name . ' — броня Albion';
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">Карточка брони</div>
    <h1>{{ $item->name }}</h1>
    <p>{{ $item->description }}</p>
    @if($item->icon)
        <div style="margin-top:12px; width:88px; height:88px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <img src="{{ $item->icon }}" alt="Иконка {{ $item->name }}" style="width:100%; height:100%; object-fit:contain;">
        </div>
    @endif
    @if($item->image)
        <div style="margin-top:12px;">
            <img src="{{ $item->image }}" alt="Изображение {{ $item->name }}" style="max-height:240px; border-radius:14px; border:1px solid var(--line); box-shadow:0 14px 30px var(--shadow);">
        </div>
    @endif
    <div class="tags">
        <span class="chip">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</span>
        <span class="chip">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</span>
        <span class="chip">+{{ (int) $item->enchantment }}</span>
        <a class="btn" href="{{ route('armor.index') }}">← К списку брони</a>
    </div>
</div>

<section class="panel">
    <div class="subtle-title">Характеристики</div>
    <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:16px;">
        <div class="card">
            <div class="meta">Категория</div>
            <h2 style="margin-top:6px;">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</h2>
            <p class="muted">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }} · {{ \App\Models\ArmorItem::slotLabel($item->slot) }}</p>
        </div>
        <div class="card">
            <div class="meta">Зачарование</div>
            <h2 style="margin-top:6px;">+{{ (int) $item->enchantment }}</h2>
            <p class="muted">Отметка уровня зачарования (визуальная метка).</p>
        </div>
    </div>
</section>

<section class="panel">
    <div class="subtle-title">Дополнительно</div>
    <table class="table">
        <tbody>
            <tr>
                <th>Материал</th>
                <td>{{ \App\Models\ArmorItem::materialLabel($item->material) }}</td>
            </tr>
            <tr>
                <th>Слот</th>
                <td>{{ \App\Models\ArmorItem::slotLabel($item->slot) }}</td>
            </tr>
            <tr>
                <th>Тип</th>
                <td>{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</td>
            </tr>
            <tr>
                <th>Зачарование</th>
                <td>+{{ (int) $item->enchantment }}</td>
            </tr>
        </tbody>
    </table>
</section>

@if($item->armorSkills && $item->armorSkills->count() > 0)
    @php
        $skillPayload = $item->armorSkills
            ->sortBy('created_at')
            ->map(function ($skill) {
                $preferred = $skill->media->firstWhere('type', 'gif')
                    ?? $skill->media->firstWhere('type', 'image')
                    ?? $skill->media->firstWhere('type', 'video');
                $preview = null;

                if ($preferred) {
                    $previewUrl = $preferred->disk === 'url'
                        ? $preferred->path
                        : \Illuminate\Support\Facades\URL::temporarySignedRoute('media.show', now()->addMinutes(30), ['media' => $preferred]);
                    $preview = [
                        'type' => $preferred->type,
                        'url' => $previewUrl,
                        'name' => $preferred->original_name,
                    ];
                }

                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'description' => $skill->description,
                    'author_notes' => $skill->author_notes,
                    'preview' => $preview,
                ];
            })
            ->values();
    @endphp

    <section class="panel" x-data="armorSkillPicker(@json($skillPayload))">
        <div class="subtle-title">Скиллы брони</div>
        <div class="content-area" style="gap: 16px;">
            <div class="card" style="flex: 1 1 260px;">
                <div class="meta">Выбор навыка</div>
                <label class="muted" for="armorSkillSelect" style="display:block; margin-top:6px;">Выберите 1 из доступных навыков</label>
                <select id="armorSkillSelect" x-model="activeSkillId" class="muted" style="margin-top:10px; width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--line); background: var(--panel); color: #e5e7eb;">
                    <template x-for="skill in skills" :key="skill.id">
                        <option :value="skill.id" x-text="skill.name"></option>
                    </template>
                </select>
            </div>

            <div class="card" style="flex: 2 1 360px;" x-show="activeSkill" x-transition>
                <div class="meta">Описание навыка</div>
                <h3 style="margin-top:6px;" x-text="activeSkill?.name"></h3>
                <p class="muted" style="margin-top:6px;" x-text="activeSkill?.description || 'Описание пока не заполнено.'"></p>
                <template x-if="activeSkill?.author_notes">
                    <p class="muted" style="margin-top:8px;">Заметки автора: <span x-text="activeSkill?.author_notes"></span></p>
                </template>
                <div style="margin-top:12px;">
                    <template x-if="activeSkill?.preview">
                        <div>
                            <template x-if="activeSkill.preview.type === 'video' && isYoutube(activeSkill.preview.url)">
                                <iframe :src="activeSkill.preview.url" title="YouTube video"
                                        style="width:100%; height:280px; border-radius:12px; border:1px solid var(--line); box-shadow:0 10px 26px var(--shadow);"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </template>
                            <template x-if="activeSkill.preview.type === 'video' && !isYoutube(activeSkill.preview.url)">
                                <video :src="activeSkill.preview.url" controls style="width:100%; max-height:280px; border-radius:12px; border:1px solid var(--line); box-shadow:0 10px 26px var(--shadow);"></video>
                            </template>
                            <template x-if="activeSkill.preview.type !== 'video'">
                                <img :src="activeSkill.preview.url" :alt="activeSkill.preview.name" style="width:100%; max-height:280px; object-fit:contain; border-radius:12px; border:1px solid var(--line); box-shadow:0 10px 26px var(--shadow);">
                            </template>
                        </div>
                    </template>
                    <template x-if="!activeSkill?.preview">
                        <p class="muted">Для навыка нет GIF/картинки.</p>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('armorSkillPicker', (skills) => ({
                skills,
                activeSkillId: skills[0]?.id ?? null,
                get activeSkill() {
                    return this.skills.find((skill) => skill.id === this.activeSkillId) || null;
                },
                isYoutube(url) {
                    return /youtube\.com|youtu\.be/.test(url || '');
                },
            }));
        });
    </script>
@endif
@endsection
