@extends('layouts.app')
@php
    $title = $weapon->display_name . ' — ' . __('ui.weapons.title');
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">{{ __('ui.weapons.card_title') }}</div>
    <h1>{{ $weapon->display_name }}</h1>
    <p>{{ $weapon->display_description }}</p>
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
            <img src="{{ $iconUrl }}" alt="Иконка {{ $eSkill?->name ?? $weapon->display_name }}" style="width:100%; height:100%; object-fit:contain;">
        </div>
    @endif
    @if($weapon->image)
        <div style="margin-top:12px;">
            <img src="{{ $weapon->image }}" alt="Изображение {{ $weapon->display_name }}" style="max-height:240px; border-radius:14px; border:1px solid var(--line); box-shadow:0 14px 30px var(--shadow);">
        </div>
    @endif
    <div class="tags">
        <span class="chip">{{ $weapon->weaponLine?->name ?? __('ui.weapons.no_line') }}</span>
        <span class="chip">{{ __('ui.weapons.type') }}: {{ __('ui.weapons.types.'.$weapon->type) }}</span>
        <a class="btn" href="{{ route('weapons.index') }}">← {{ __('ui.actions.back_to_weapons') }}</a>
    </div>
</div>

<section class="panel">
    <div class="subtle-title">{{ __('ui.weapons.additional') }}</div>
    <table class="table">
        <tbody>
            <tr>
                <th>{{ __('ui.weapons.line') }}</th>
                <td>{{ $weapon->weaponLine?->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>{{ __('ui.weapons.type') }}</th>
                <td>{{ __('ui.weapons.types.'.$weapon->type) }}</td>
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
    <div class="subtle-title">{{ __('ui.weapons.skills') }}</div>
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
                <p class="muted">{{ __('ui.weapons.no_q') }}</p>
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
                <p class="muted">{{ __('ui.weapons.no_w') }}</p>
            @endforelse
        </div>
        <div class="card" x-show="activeTab === 'e'" x-transition>
            <div class="meta">E</div>
            @if($weaponSkill)
                <div class="skill-card">
                    <div class="skill-card__text">
                        <h3 style="margin:0;">{{ $weaponSkill->name }}</h3>
                        <p class="muted" style="margin:0;">{{ $weaponSkill->description ?? __('ui.weapons.description_missing') }}</p>
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
                            <p class="muted">{{ __('ui.weapons.no_preview') }}</p>
                        @endif
                    </div>
                </div>
                @if($weaponSkill->author_notes)
                    <p class="muted" style="margin-top:8px;">{{ __('ui.weapons.author_notes') }}: {{ $weaponSkill->author_notes }}</p>
                @endif
            @else
                <p class="muted">{{ __('ui.weapons.no_e') }}</p>
            @endif
        </div>
    </div>
</section>
@endsection
