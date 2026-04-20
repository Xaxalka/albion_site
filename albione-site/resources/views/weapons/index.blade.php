@extends('layouts.app')
@php
    $title = __('ui.weapons.title');
@endphp

@section('content')
<div class="section-header">
    <div class="eyebrow">{{ __('ui.catalog.label') }}</div>
    <h1>{{ __('ui.weapons.page_title') }}</h1>
    <p>{{ __('ui.weapons.intro') }}</p>
</div>

<div class="content-area" style="grid-template-columns: 1fr; gap: 16px;">
    <aside class="panel">
        <div class="subtle-title">{{ __('ui.catalog.filter') }}</div>
        <form method="GET" action="{{ route('weapons.index') }}">
            <div>
                <label>{{ __('ui.weapons.weapon_line') }}</label>
                <select name="weapon_line_id">
                    <option value="">{{ __('ui.weapons.all_lines') }}</option>
                    @foreach($weaponLines as $line)
                        <option value="{{ $line->id }}" @selected(request('weapon_line_id') == $line->id)>{{ $line->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:10px; flex-wrap: wrap;">
                <button type="submit">{{ __('ui.actions.apply') }}</button>
                <a class="btn" href="{{ route('weapons.index') }}">{{ __('ui.actions.reset') }}</a>
            </div>
        </form>
        <div class="panel" style="margin-top:12px; padding:12px 14px;">
            <p class="subtle-title">{{ __('ui.catalog.help') }}</p>
            <p class="muted" style="margin-top:6px;">{{ __('ui.weapons.help_text') }}</p>
        </div>
    </aside>

    <section class="content-area" style="gap:14px;">
        <div class="panel">
            <div class="subtle-title">{{ __('ui.catalog.summary') }}</div>
            <h2 style="margin-top:6px;">{{ __('ui.weapons.found', ['count' => $weapons->count()]) }}</h2>
        </div>
        <div class="card-grid" style="grid-template-columns: 1fr; gap:18px;">
            @forelse($weapons as $weapon)
                @php
                    $eSkill = ($weapon->branch?->skills ?? collect())
                        ->where('slot', 'E')
                        ->sortBy('sort')
                        ->first(fn ($skill) => ! $skill->is_placeholder);
                    $iconUrl = null;

                    if ($eSkill?->icon) {
                        $iconUrl = $eSkill->icon;
                    } elseif ($weapon->icon) {
                        $iconUrl = $weapon->icon;
                    }
                @endphp
                <article class="card" style="width:100%;">
                    <div style="display:flex; gap:16px; align-items:center;">
                        <div style="min-width:110px; width:110px; height:110px; border-radius:16px; border:1px solid var(--line); background: rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            @if($iconUrl)
                                <img src="{{ $iconUrl }}" alt="Иконка {{ $eSkill?->name ?? $weapon->display_name }}" style="width:100%; height:100%; object-fit:contain;">
                            @else
                                <span class="muted" style="font-size:12px;">{{ __('ui.weapons.no_icon') }}</span>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div class="tags" style="margin-top:0; gap:10px;">
                                <span class="chip">{{ $weapon->weaponLine?->name ?? __('ui.weapons.no_line') }}</span>
                                @if($eSkill)
                                    <span class="chip">E: {{ $eSkill->name }}</span>
                                @endif
                            </div>
                            <h2 style="margin-top:10px;">
                                <a href="{{ route('weapons.show', $weapon->slug) }}" style="color:inherit; text-decoration:none;">{{ $weapon->display_name }}</a>
                            </h2>
                            <p style="margin-top:6px;">{{ \Illuminate\Support\Str::limit($weapon->display_description, 180) }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel">
                    <p class="muted">{{ __('ui.weapons.empty') }}</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
