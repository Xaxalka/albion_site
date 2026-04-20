@extends('layouts.app')
@php
    $title = $item->display_name . ' — ' . __('ui.armor.title');
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">{{ __('ui.armor.card_title') }}</div>
    <h1>{{ $item->display_name }}</h1>
    <p>{{ $item->display_description }}</p>
    @if($item->icon)
        <div style="margin-top:12px; width:88px; height:88px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <img src="{{ $item->icon }}" alt="Иконка {{ $item->display_name }}" style="width:100%; height:100%; object-fit:contain;">
        </div>
    @endif
    @if($item->image)
        <div style="margin-top:12px;">
            <img src="{{ $item->image }}" alt="Изображение {{ $item->display_name }}" style="max-height:240px; border-radius:14px; border:1px solid var(--line); box-shadow:0 14px 30px var(--shadow);">
        </div>
    @endif
    <div class="tags">
        <span class="chip">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</span>
        <span class="chip">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</span>
        <a class="btn" href="{{ route('armor.index') }}">← {{ __('ui.actions.back_to_armor') }}</a>
    </div>
</div>

<section class="panel">
    <div class="subtle-title">{{ __('ui.armor.stats') }}</div>
    <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:16px;">
        <div class="card">
            <div class="meta">{{ __('ui.armor.category') }}</div>
            <h2 style="margin-top:6px;">{{ \App\Models\ArmorItem::materialLabel($item->material) }}</h2>
            <p class="muted">{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }} · {{ \App\Models\ArmorItem::slotLabel($item->slot) }}</p>
        </div>
    </div>
</section>

<section class="panel">
    <div class="subtle-title">{{ __('ui.armor.additional') }}</div>
    <table class="table">
        <tbody>
            <tr>
                <th>{{ __('ui.armor.material') }}</th>
                <td>{{ \App\Models\ArmorItem::materialLabel($item->material) }}</td>
            </tr>
            <tr>
                <th>{{ __('ui.armor.slot') }}</th>
                <td>{{ \App\Models\ArmorItem::slotLabel($item->slot) }}</td>
            </tr>
            <tr>
                <th>{{ __('ui.armor.type') }}</th>
                <td>{{ \App\Models\ArmorItem::pieceLabel($item->material, $item->slot) }}</td>
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
        <div class="subtle-title">{{ __('ui.armor.armor_skills') }}</div>
        <div class="content-area">
            <div class="card" x-show="activeSkill" x-transition>
                <div class="meta">{{ __('ui.armor.skill_picker') }}</div>
                <label class="muted" for="armorSkillSelect" style="display:block; margin-top:6px;">{{ __('ui.armor.skill_picker_help') }}</label>
                <select id="armorSkillSelect" x-model="activeSkillId" class="muted" style="margin-top:10px; width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--line); background: var(--panel); color: #e5e7eb;">
                    <template x-for="skill in skills" :key="skill.id">
                        <option :value="skill.id" x-text="skill.name"></option>
                    </template>
                </select>

                <div class="skill-card" style="margin-top:16px;">
                    <div class="skill-card__text">
                        <h3 style="margin:0;" x-text="activeSkill?.name"></h3>
                        <p class="muted" style="margin:0;" x-text="activeSkill?.description || '{{ __('ui.armor.description_missing') }}'"></p>
                        <template x-if="activeSkill?.author_notes">
                            <p class="muted" style="margin-top:8px;">{{ __('ui.armor.author_notes') }}: <span x-text="activeSkill?.author_notes"></span></p>
                        </template>
                    </div>
                    <div class="skill-card__media">
                        <template x-if="activeSkill?.preview">
                            <div>
                                <template x-if="activeSkill.preview.type === 'video' && isYoutube(activeSkill.preview.url)">
                                    <div class="skill-card__frame">
                                        <iframe :src="activeSkill.preview.url" title="YouTube video"
                                                class="skill-card__asset"
                                                width="500" height="500"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                    </div>
                                </template>
                                <template x-if="activeSkill.preview.type === 'video' && !isYoutube(activeSkill.preview.url)">
                                    <div class="skill-card__frame">
                                        <video :src="activeSkill.preview.url" controls class="skill-card__asset" width="500" height="500"></video>
                                    </div>
                                </template>
                                <template x-if="activeSkill.preview.type !== 'video'">
                                    <div class="skill-card__frame">
                                        <img :src="activeSkill.preview.url" :alt="activeSkill.preview.name" class="skill-card__asset" width="500" height="500">
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!activeSkill?.preview">
                            <p class="muted">{{ __('ui.armor.no_preview') }}</p>
                        </template>
                    </div>
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
