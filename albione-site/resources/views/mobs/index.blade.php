@extends('layouts.app')

@section('content')
    <section class="section-header">
        <span class="eyebrow">{{ __('ui.catalog.label') }}</span>
        <h1>{{ __('ui.mobs.title') }}</h1>
        <p>{{ __('ui.mobs.intro') }}</p>
    </section>

    <section class="panel">
        <form method="GET" action="{{ route('mobs.index') }}" class="card-grid">
            <div>
                <label for="tier">{{ __('ui.mobs.tier') }}</label>
                <select id="tier" name="tier">
                    <option value="">{{ __('ui.mobs.all') }}</option>
                    @foreach($tiers as $tier)
                        <option value="{{ $tier }}" @selected($selectedTier === (int) $tier)>T{{ $tier }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="faction">{{ __('ui.mobs.faction') }}</label>
                <select id="faction" name="faction">
                    <option value="">{{ __('ui.mobs.all') }}</option>
                    @foreach($factions as $faction)
                        <option value="{{ $faction }}" @selected($selectedFaction === $faction)>{{ \Illuminate\Support\Str::headline($faction) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="attack_type">{{ __('ui.mobs.attack_type') }}</label>
                <select id="attack_type" name="attack_type">
                    <option value="">{{ __('ui.mobs.all') }}</option>
                    @foreach($attackTypes as $attackType)
                        <option value="{{ $attackType }}" @selected($selectedAttackType === $attackType)>{{ \Illuminate\Support\Str::headline($attackType) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="danger_state">{{ __('ui.mobs.danger_state') }}</label>
                <select id="danger_state" name="danger_state">
                    <option value="">{{ __('ui.mobs.all') }}</option>
                    @foreach($dangerStates as $dangerState)
                        <option value="{{ $dangerState }}" @selected($selectedDangerState === $dangerState)>{{ \Illuminate\Support\Str::headline($dangerState) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:10px;align-items:end;">
                <button type="submit">{{ __('ui.actions.apply') }}</button>
                <a class="btn" href="{{ route('mobs.index') }}">{{ __('ui.actions.reset') }}</a>
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="section-header">
            <span class="eyebrow">{{ __('ui.catalog.summary') }}</span>
            <h2>{{ __('ui.mobs.found', ['count' => $mobs->count()]) }}</h2>
        </div>

        @if($mobs->isEmpty())
            <p>{{ __('ui.mobs.empty') }}</p>
        @else
            <div class="card-grid">
                @foreach($mobs as $mob)
                    <article class="card">
                        <div class="section-header" style="position:relative;z-index:1;">
                            <span class="eyebrow">{{ $mob->tier ? 'T'.$mob->tier : __('ui.mobs.overview') }}</span>
                            <h3>{{ $mob->display_name }}</h3>
                            <p>{{ $mob->display_description ?: __('ui.mobs.description_missing') }}</p>
                        </div>

                        <div class="tags" style="position:relative;z-index:1;">
                            @if($mob->faction)
                                <span class="chip">{{ __('ui.mobs.faction') }}: {{ \Illuminate\Support\Str::headline($mob->faction) }}</span>
                            @endif
                            @if($mob->attack_type)
                                <span class="chip">{{ __('ui.mobs.attack_type') }}: {{ \Illuminate\Support\Str::headline($mob->attack_type) }}</span>
                            @endif
                            @if($mob->danger_state)
                                <span class="chip">{{ __('ui.mobs.danger_state') }}: {{ \Illuminate\Support\Str::headline($mob->danger_state) }}</span>
                            @endif
                        </div>

                        <div class="tags" style="position:relative;z-index:1;">
                            <span class="stat-box">{{ __('ui.mobs.spells') }}: {{ $mob->spells_count }}</span>
                            <span class="stat-box">{{ __('ui.mobs.loot') }}: {{ $mob->loot_entries_count }}</span>
                            @if($mob->fame !== null)
                                <span class="stat-box">{{ __('ui.mobs.stats_labels.fame') }}: {{ $mob->fame }}</span>
                            @endif
                        </div>

                        <div style="margin-top:14px;position:relative;z-index:1;">
                            <a class="btn" href="{{ route('mobs.show', $mob->slug) }}">{{ __('ui.mobs.details') }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
