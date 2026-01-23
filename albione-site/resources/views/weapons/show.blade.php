@extends('layouts.app')
@php
    $title = $weapon->name . ' — оружие Albion';
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">Карточка оружия</div>
    <h1>{{ $weapon->name }}</h1>
    <p>{{ $weapon->description }}</p>
    @php
        $eSkill = $skillGroups['eSkills']->first(fn ($skill) => ! $skill->is_placeholder);
        $iconUrl = null;

        if ($eSkill?->icon) {
            $iconUrl = $eSkill->icon;
        } elseif ($weapon->icon) {
            $iconUrl = $weapon->icon;
        }
    @endphp
    @if($iconUrl)
        <div style="margin-top:12px; width:88px; height:88px; border-radius:18px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <img src="{{ $iconUrl }}" alt="Иконка {{ $eSkill?->name ?? $weapon->name }}" style="width:100%; height:100%; object-fit:contain;">
        </div>
    @endif
    @if($weapon->image)
        <div style="margin-top:12px;">
            <img src="{{ $weapon->image }}" alt="Изображение {{ $weapon->name }}" style="max-height:240px; border-radius:14px; border:1px solid var(--line); box-shadow:0 14px 30px var(--shadow);">
        </div>
    @endif
    <div class="tags">
        <span class="chip">{{ $weapon->weaponLine?->name ?? 'Без линии' }}</span>
        <span class="chip">Тип: {{ $weapon->type }}</span>
        <a class="btn" href="{{ route('weapons.index') }}">← К списку оружия</a>
    </div>
</div>

<section class="panel" x-data="{ enchantments: [0,1,2,3], selectedEnchant: @json((int) $weapon->enchantment) }">
    <div class="subtle-title">Характеристики</div>
    <div class="content-area" style="grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:16px;">
        <div class="card">
            <div class="meta">Зачарование</div>
            <h2 style="margin-top:6px;">+<span x-text="selectedEnchant"></span></h2>
            <p class="muted">Выберите уровень, чтобы отметить редкость.</p>
        </div>
    </div>
    <div class="panel" style="margin-top:14px; padding:14px;">
        <p class="subtle-title">Варианты зачарования</p>
        <div class="tags" style="margin-top:8px;">
            <template x-for="level in enchantments" :key="level">
                <button type="button" @click="selectedEnchant = level" :aria-pressed="selectedEnchant === level" :class="selectedEnchant === level ? 'btn' : 'chip'" style="border: none; background: none; padding: 0;">
                    <span class="chip" :style="selectedEnchant === level ? 'border-color: var(--gold-strong); box-shadow: 0 8px 18px rgba(242,200,124,0.18);' : ''">+<span x-text="level"></span></span>
                </button>
            </template>
        </div>
    </div>
</section>

<section class="panel">
    <div class="subtle-title">Дополнительно</div>
    <table class="table">
        <tbody>
            <tr>
                <th>Линия</th>
                <td>{{ $weapon->weaponLine?->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Тип</th>
                <td class="capitalize">{{ $weapon->type }}</td>
            </tr>
            <tr>
                <th>Зачарование</th>
                <td>+<span x-text="selectedEnchant"></span></td>
            </tr>
        </tbody>
    </table>
</section>

@php
    $defaultTab = $weapon->weaponSkill ? 'e' : 'q';
    $weaponSkill = $weapon->weaponSkill;
    $preferredSkillMedia = $weaponSkill?->media->firstWhere('type', 'gif')
        ?? $weaponSkill?->media->firstWhere('type', 'image')
        ?? $weaponSkill?->media->firstWhere('type', 'video')
        ?? $weaponSkill?->media->first();
    $weaponSkillPreviewUrl = null;

    if ($preferredSkillMedia) {
        $weaponSkillPreviewUrl = $preferredSkillMedia->disk === 'url'
            ? $preferredSkillMedia->path
            : \Illuminate\Support\Facades\URL::temporarySignedRoute('media.show', now()->addMinutes(30), ['media' => $preferredSkillMedia]);
    }
@endphp
<section class="panel" x-data="{ activeTab: '{{ $defaultTab }}' }" style="--skill-media-size: 500px;">
    <div class="subtle-title">Навыки</div>
    <div class="tags" style="margin-top:8px;">
        <button type="button" class="chip" :style="activeTab === 'q' ? 'border-color: var(--gold-strong); box-shadow: 0 8px 18px rgba(242,200,124,0.18);' : ''" @click="activeTab = 'q'">Q</button>
        <button type="button" class="chip" :style="activeTab === 'w' ? 'border-color: var(--gold-strong); box-shadow: 0 8px 18px rgba(242,200,124,0.18);' : ''" @click="activeTab = 'w'">W</button>
        <button type="button" class="chip" :style="activeTab === 'e' ? 'border-color: var(--gold-strong); box-shadow: 0 8px 18px rgba(242,200,124,0.18);' : ''" @click="activeTab = 'e'">E</button>
    </div>

    <div class="content-area" style="margin-top:14px; gap:25px;">
        <div class="card" x-show="activeTab === 'q'" x-transition>
            <div class="meta">Q</div>
            @forelse($skillGroups['qSkills'] as $skill)
                <div class="skill-card">
                    <div class="skill-card__text">
                        <h3 style="margin:0;">{{ $skill->name }}</h3>
                        <p class="muted" style="margin:0;">{{ $skill->description }}</p>
                    </div>
                    @if($skill->icon)
                        <div class="skill-card__media" style="width:350px; height:250px; max-width:350px; max-height:250px; min-width:350px; min-height:250px; margin-left:100px;">
                            <div class="skill-card__frame" style="width:350px !important; height:250px !important; max-width:350px; max-height:250px; min-width:350px; min-height:250px; overflow:hidden;">
                                <img src="{{ $skill->icon }}" alt="{{ $skill->name }}" class="skill-card__asset" width="350" height="250" style="width:100% !important; height:100% !important; object-fit:contain;">
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="muted">Нет Q навыков.</p>
            @endforelse
        </div>
        <div class="card" x-show="activeTab === 'w'" x-transition>
            <div class="meta">W</div>
            @forelse($skillGroups['wSkills'] as $skill)
                <div class="skill-card">
                    <div class="skill-card__text">
                        <h3 style="margin:0;">{{ $skill->name }}</h3>
                        <p class="muted" style="margin:0;">{{ $skill->description }}</p>
                    </div>
                    @if($skill->icon)
                        <div class="skill-card__media" style="width:350px; height:250px; max-width:350px; max-height:250px; min-width:350px; min-height:250px; margin-left:100px;">
                            <div class="skill-card__frame" style="width:350px !important; height:250px !important; max-width:350px; max-height:250px; min-width:350px; min-height:250px; overflow:hidden;">
                                <img src="{{ $skill->icon }}" alt="{{ $skill->name }}" class="skill-card__asset" width="350" height="250" style="width:100% !important; height:100% !important; object-fit:contain;">
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="muted">Нет W навыков.</p>
            @endforelse
        </div>
        <div class="card" x-show="activeTab === 'e'" x-transition>
            <div class="meta">E</div>
            @if($weaponSkill)
                <div class="skill-card">
                    <div class="skill-card__text">
                        <h3 style="margin:0;">{{ $weaponSkill->name }}</h3>
                        <p class="muted" style="margin:0;">{{ $weaponSkill->description ?? 'Описание пока не заполнено.' }}</p>
                    </div>
                    <div class="skill-card__media" style="width:350px; height:250px; max-width:350px; max-height:250px; min-width:350px; min-height:250px; margin-left:100px;">
                        @if($weaponSkillPreviewUrl)
                            @php
                                $isYoutube = \Illuminate\Support\Str::contains($weaponSkillPreviewUrl, ['youtube.com', 'youtu.be']);
                            @endphp
                            @if($preferredSkillMedia?->type === 'video' && $isYoutube)
                                <div class="skill-card__frame" style="width:350px !important; height:250px !important; max-width:350px; max-height:250px; min-width:350px; min-height:250px; overflow:hidden;">
                                    <iframe src="{{ $weaponSkillPreviewUrl }}" title="YouTube video"
                                            class="skill-card__asset"
                                            width="350" height="250"
                                            style="width:100% !important; height:100% !important;"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                </div>
                            @elseif($preferredSkillMedia?->type === 'video')
                                <div class="skill-card__frame" style="width:350px !important; height:250px !important; max-width:350px; max-height:250px; min-width:350px; min-height:250px; overflow:hidden;">
                                    <video src="{{ $weaponSkillPreviewUrl }}" controls
                                           class="skill-card__asset" width="350" height="250" style="width:100% !important; height:100% !important; object-fit:contain;"></video>
                                </div>
                            @else
                                <div class="skill-card__frame" style="width:350px !important; height:250px !important; max-width:350px; max-height:250px; min-width:350px; min-height:250px; overflow:hidden;">
                                    <img src="{{ $weaponSkillPreviewUrl }}" alt="{{ $weaponSkill->name }}" class="skill-card__asset" width="350" height="250" style="width:100% !important; height:100% !important; object-fit:contain;">
                                </div>
                            @endif
                        @else
                            <p class="muted">Для навыка нет GIF/картинки.</p>
                        @endif
                    </div>
                </div>
                @if($weaponSkill->author_notes)
                    <p class="muted" style="margin-top:8px;">Заметки автора: {{ $weaponSkill->author_notes }}</p>
                @endif
            @else
                <p class="muted">Нет E навыка.</p>
            @endif
        </div>
    </div>
</section>
@endsection
