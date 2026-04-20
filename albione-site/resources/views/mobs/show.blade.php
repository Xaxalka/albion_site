@extends('layouts.app')

@php
    $formatValue = function ($value) {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_float($value) || (is_numeric($value) && str_contains((string) $value, '.'))) {
            return rtrim(rtrim(number_format((float) $value, 3, '.', ''), '0'), '.');
        }

        return (string) $value;
    };

    $generalStats = [
        'fame' => $mob->fame,
        'ability_power' => $mob->ability_power,
        'hitpoints_max' => $mob->hitpoints_max,
        'hitpoints_regeneration' => $mob->hitpoints_regeneration,
        'energy_max' => $mob->energy_max,
        'energy_regeneration' => $mob->energy_regeneration,
    ];

    $combatStats = [
        'attack_damage' => $mob->attack_damage,
        'attack_range' => $mob->attack_range,
        'physical_armor' => $mob->physical_armor,
        'magic_resistance' => $mob->magic_resistance,
        'crowd_control_resistance' => $mob->crowd_control_resistance,
        'move_speed' => $mob->move_speed,
        'attack_speed' => $mob->attack_speed,
        'attack_move_speed' => $mob->attack_move_speed,
        'melee_attack_damage_time' => $mob->melee_attack_damage_time,
        'aggro_radius' => $mob->aggro_radius,
        'pursuit_radius' => $mob->pursuit_radius,
        'alert_radius' => $mob->alert_radius,
        'collision_radius' => $mob->collision_radius,
        'attack_collision_radius' => $mob->attack_collision_radius,
    ];

    $phaseLabel = function (string $phase): string {
        return match ($phase) {
            'DeathSpells' => app()->getLocale() === 'ru' ? 'При смерти' : 'On death',
            default => app()->getLocale() === 'ru' ? 'Обычные умения' : 'Regular abilities',
        };
    };

    $labelValue = function (?string $value): ?string {
        if (! filled($value)) {
            return null;
        }

        $value = (string) $value;
        $locale = app()->getLocale();

        $map = [
            'MORGANA' => ['en' => 'Morgana', 'ru' => 'Моргана'],
            'HERETIC' => ['en' => 'Heretic', 'ru' => 'Еретики'],
            'UNDEAD' => ['en' => 'Undead', 'ru' => 'Нежить'],
            'KEEPER' => ['en' => 'Keeper', 'ru' => 'Хранители'],
            'DEMON' => ['en' => 'Demon', 'ru' => 'Демоны'],
            'AVALONIAN' => ['en' => 'Avalonian', 'ru' => 'Авалонцы'],
            'hostile' => ['en' => 'Hostile', 'ru' => 'Агрессивный'],
            'neutral' => ['en' => 'Neutral', 'ru' => 'Нейтральный'],
            'melee' => ['en' => 'Melee', 'ru' => 'Ближний бой'],
            'magic' => ['en' => 'Magic', 'ru' => 'Магия'],
            'ranged' => ['en' => 'Ranged', 'ru' => 'Дальний бой'],
            'normal' => ['en' => 'Normal', 'ru' => 'Обычный'],
            'standard' => ['en' => 'Standard', 'ru' => 'Стандартный'],
            'veteran' => ['en' => 'Veteran', 'ru' => 'Ветеран'],
            'elite' => ['en' => 'Elite', 'ru' => 'Элитный'],
            'boss' => ['en' => 'Boss', 'ru' => 'Босс'],
            'miniboss' => ['en' => 'Miniboss', 'ru' => 'Мини-босс'],
            'none' => ['en' => 'None', 'ru' => 'Нет'],
        ];

        if (isset($map[$value])) {
            return $map[$value][$locale] ?? $map[$value]['en'];
        }

        return \Illuminate\Support\Str::headline(str_replace('_', ' ', $value));
    };

    $humanizeTechnicalName = function (?string $value) use ($labelValue): ?string {
        if (! filled($value)) {
            return null;
        }

        $value = (string) $value;
        $clean = preg_replace('/^T\d+_/', '', $value) ?: $value;
        $clean = str_replace(
            ['MOB_', 'BOSS_', 'MINIBOSS_', 'ELITE_', 'EXP_'],
            '',
            $clean
        );

        return $labelValue($clean);
    };

    $mobDescription = (function () use ($mob, $labelValue) {
        $parts = [];

        if ($mob->tier) {
            $parts[] = app()->getLocale() === 'ru' ? "Тир {$mob->tier}" : "Tier {$mob->tier}";
        }

        if ($mob->faction) {
            $parts[] = (app()->getLocale() === 'ru' ? 'Фракция ' : 'Faction ').$labelValue($mob->faction);
        }

        if ($mob->attack_type) {
            $parts[] = (app()->getLocale() === 'ru' ? 'Тип атаки ' : 'Attack type ').$labelValue($mob->attack_type);
        }

        if ($mob->danger_state) {
            $parts[] = (app()->getLocale() === 'ru' ? 'Опасность ' : 'Danger ').$labelValue($mob->danger_state);
        }

        if ($parts === []) {
            return null;
        }

        return implode(', ', $parts).'.';
    })();

    $entryTypeLabel = function (string $type): string {
        return match ($type) {
            'Harvestable' => app()->getLocale() === 'ru' ? 'Ресурс / сбор' : 'Harvest',
            'LootListReference' => app()->getLocale() === 'ru' ? 'Таблица лута' : 'Loot table',
            'Item' => app()->getLocale() === 'ru' ? 'Предмет' : 'Item',
            default => \Illuminate\Support\Str::headline($type),
        };
    };

    $humanizeDropName = function (?string $value): string {
        if (! filled($value)) {
            return 'n/a';
        }

        $locale = app()->getLocale();
        $value = (string) $value;

        $exact = [
            'DEADRAT' => ['en' => 'Dead Rat', 'ru' => 'Тушка крысы'],
            'FIBER_CRITTER' => ['en' => 'Fiber', 'ru' => 'Волокно'],
            'FIBER_GUARDIAN_RED' => ['en' => 'Rare Fiber', 'ru' => 'Редкое волокно'],
            'HIDE_CRITTER' => ['en' => 'Hide', 'ru' => 'Шкура'],
            'HIDE_FOREST' => ['en' => 'Forest Hide', 'ru' => 'Лесная шкура'],
            'HIDE_GUARDIAN' => ['en' => 'Guardian Hide', 'ru' => 'Шкура хранителя'],
            'HIDE_HIGHLAND' => ['en' => 'Highland Hide', 'ru' => 'Шкура нагорья'],
            'HIDE_MOUNTAIN' => ['en' => 'Mountain Hide', 'ru' => 'Горная шкура'],
            'HIDE_STEPPE' => ['en' => 'Steppe Hide', 'ru' => 'Степная шкура'],
            'HIDE_SWAMP' => ['en' => 'Swamp Hide', 'ru' => 'Болотная шкура'],
            'ORE_CRITTER_DEAD' => ['en' => 'Ore', 'ru' => 'Руда'],
            'ORE_CRITTER_GREEN' => ['en' => 'Enchanted Ore', 'ru' => 'Зачарованная руда'],
            'ORE_CRITTER_RED' => ['en' => 'Rare Ore', 'ru' => 'Редкая руда'],
            'ORE_GUARDIAN_RED' => ['en' => 'Guardian Ore', 'ru' => 'Руда хранителя'],
            'ROCK_CRITTER_DEAD' => ['en' => 'Stone', 'ru' => 'Камень'],
            'ROCK_CRITTER_GREEN' => ['en' => 'Enchanted Stone', 'ru' => 'Зачарованный камень'],
            'ROCK_CRITTER_RED' => ['en' => 'Rare Stone', 'ru' => 'Редкий камень'],
            'ROCK_GUARDIAN_RED' => ['en' => 'Guardian Stone', 'ru' => 'Камень хранителя'],
            'WOOD_CRITTER_DEAD' => ['en' => 'Wood', 'ru' => 'Древесина'],
            'WOOD_CRITTER_GREEN' => ['en' => 'Enchanted Wood', 'ru' => 'Зачарованная древесина'],
            'WOOD_CRITTER_RED' => ['en' => 'Rare Wood', 'ru' => 'Редкая древесина'],
            'WOOD_GUARDIAN_RED' => ['en' => 'Guardian Wood', 'ru' => 'Древесина хранителя'],
            'SILVERCOINS_LOOT_STANDARD' => ['en' => 'Silver', 'ru' => 'Серебро'],
            'SILVERCOINS_LOOT_STANDARD_BOSS' => ['en' => 'Silver', 'ru' => 'Серебро'],
            'SILVERCOINS_LOOT_STANDARD_MINIBOSS' => ['en' => 'Silver', 'ru' => 'Серебро'],
            'SILVERCOINS_LOOT_STANDARD_TRASH' => ['en' => 'Silver', 'ru' => 'Серебро'],
            'SILVERCOINS_LOOT_VETERAN' => ['en' => 'Veteran Silver', 'ru' => 'Серебро ветерана'],
            'SILVERCOINS_LOOT_VETERAN_BOSS' => ['en' => 'Veteran Silver', 'ru' => 'Серебро ветерана'],
            'SILVERCOINS_LOOT_VETERAN_MINIBOSS' => ['en' => 'Veteran Silver', 'ru' => 'Серебро ветерана'],
            'SILVERCOINS_LOOT_VETERAN_TRASH' => ['en' => 'Veteran Silver', 'ru' => 'Серебро ветерана'],
            'SILVERCOINS_LOOT_ELITE' => ['en' => 'Elite Silver', 'ru' => 'Серебро элиты'],
            'SILVERCOINS_LOOT_ELITE_BOSS' => ['en' => 'Elite Silver', 'ru' => 'Серебро элиты'],
            'SILVERCOINS_LOOT_ELITE_MINIBOSS' => ['en' => 'Elite Silver', 'ru' => 'Серебро элиты'],
            'SILVERCOINS_LOOT_ELITE_TRASH' => ['en' => 'Elite Silver', 'ru' => 'Серебро элиты'],
            'CHEST_SILVERCOINS_LOOT_STANDARD' => ['en' => 'Chest Silver', 'ru' => 'Серебро из сундука'],
            'CHEST_EXP_SILVERCOINS_LOOT_STANDARD' => ['en' => 'Expedition Chest Silver', 'ru' => 'Серебро из сундука экспедиции'],
            'CHEST_EXP_SILVERCOINS_LOOT_VETERAN' => ['en' => 'Veteran Expedition Chest Silver', 'ru' => 'Серебро из сундука экспедиции ветерана'],
            'LOOT_MORGANA_FURNITURE' => ['en' => 'Morgana Furniture', 'ru' => 'Мебель Морганы'],
            'LOOT_KEEPER_FURNITURE' => ['en' => 'Keeper Furniture', 'ru' => 'Мебель хранителей'],
            'LOOT_HERETIC_FURNITURE' => ['en' => 'Heretic Furniture', 'ru' => 'Мебель еретиков'],
            'HELL_ESSENCE_LOOT' => ['en' => 'Hell Essence', 'ru' => 'Адская эссенция'],
            'HELL_FRAGMENT_LOOT' => ['en' => 'Hell Runes and Souls', 'ru' => 'Адские руны и души'],
            'T4_ESSENCE_LOOT' => ['en' => 'Essence', 'ru' => 'Эссенция'],
            'T5_ESSENCE_LOOT' => ['en' => 'Essence', 'ru' => 'Эссенция'],
            'T6_ESSENCE_LOOT' => ['en' => 'Essence', 'ru' => 'Эссенция'],
            'T7_ESSENCE_LOOT' => ['en' => 'Essence', 'ru' => 'Эссенция'],
            'T8_ESSENCE_LOOT' => ['en' => 'Essence', 'ru' => 'Эссенция'],
            'T4_FRAGMENT_LOOT' => ['en' => 'Runes and Souls', 'ru' => 'Руны и души'],
            'T5_FRAGMENT_LOOT' => ['en' => 'Runes and Souls', 'ru' => 'Руны и души'],
            'T6_FRAGMENT_LOOT' => ['en' => 'Runes and Souls', 'ru' => 'Руны и души'],
            'T7_FRAGMENT_LOOT' => ['en' => 'Runes and Souls', 'ru' => 'Руны и души'],
            'T8_FRAGMENT_LOOT' => ['en' => 'Runes and Souls', 'ru' => 'Руны и души'],
            'T4_GREEN_GEAR_LOOT' => ['en' => 'Green Gear', 'ru' => 'Зелёное снаряжение'],
            'T5_GREEN_GEAR_LOOT' => ['en' => 'Green Gear', 'ru' => 'Зелёное снаряжение'],
            'T6_GREEN_GEAR_LOOT' => ['en' => 'Green Gear', 'ru' => 'Зелёное снаряжение'],
            'T7_GREEN_GEAR_LOOT' => ['en' => 'Green Gear', 'ru' => 'Зелёное снаряжение'],
            'T8_GREEN_GEAR_LOOT' => ['en' => 'Green Gear', 'ru' => 'Зелёное снаряжение'],
            'T4_BLUE_GEAR_LOOT' => ['en' => 'Blue Gear', 'ru' => 'Синее снаряжение'],
            'T5_BLUE_GEAR_LOOT' => ['en' => 'Blue Gear', 'ru' => 'Синее снаряжение'],
            'T6_BLUE_GEAR_LOOT' => ['en' => 'Blue Gear', 'ru' => 'Синее снаряжение'],
            'T7_BLUE_GEAR_LOOT' => ['en' => 'Blue Gear', 'ru' => 'Синее снаряжение'],
            'T8_BLUE_GEAR_LOOT' => ['en' => 'Blue Gear', 'ru' => 'Синее снаряжение'],
        ];

        if (isset($exact[$value])) {
            return $exact[$value][$locale] ?? $exact[$value]['en'];
        }

        $clean = preg_replace('/^T\d+_/', '', $value) ?: $value;
        $clean = str_replace(
            ['UNIQUE_', 'FARM_', 'FURNITUREITEM_', 'MOB_', 'LOOT_', 'DIRECTLOOTDROP_', 'CHEST_', '_LOOT'],
            '',
            $clean
        );
        $clean = str_replace('_', ' ', \Illuminate\Support\Str::lower($clean));

        return \Illuminate\Support\Str::title(trim($clean));
    };

    $chanceLabel = function ($entry) {
        if ($entry->entry_type === 'Harvestable' && filled($entry->reference_name) && str_contains($entry->reference_name, 'SILVERCOINS')) {
            return '100%';
        }

        if ($entry->entry_type === 'Harvestable' && $entry->chance === null) {
            return '100%';
        }

        if ($entry->chance === null || $entry->chance === '') {
            return 'n/a';
        }

        $rawChance = (float) $entry->chance;
        $chance = $rawChance <= 1 ? $rawChance * 100 : $rawChance;
        $decimals = $chance >= 10 ? 0 : ($chance >= 1 ? 1 : 2);

        return rtrim(rtrim(number_format($chance, $decimals, '.', ''), '0'), '.').'%';
    };

    $lootSummary = function ($entry) use ($humanizeDropName): ?string {
        if ($entry->entry_type !== 'LootListReference') {
            return null;
        }

        $definition = data_get($entry->details, 'definition', []);

        if (! is_array($definition) || $definition === []) {
            return null;
        }

        $items = collect($definition)
            ->flatMap(function (array $node) {
                if (($node['tag'] ?? null) === 'Item') {
                    return [$node];
                }

                return collect($node['children'] ?? [])
                    ->filter(fn (array $child) => ($child['tag'] ?? null) === 'Item')
                    ->values();
            })
            ->take(3)
            ->map(function (array $item) use ($humanizeDropName) {
                $type = (string) data_get($item, 'attributes.type', '');
                $amount = data_get($item, 'attributes.amount');

                return trim($humanizeDropName($type).($amount ? ' x'.$amount : ''));
            })
            ->filter()
            ->values();

        if ($items->isEmpty()) {
            return null;
        }

        return $items->implode(', ');
    };
@endphp

@section('content')
    <section class="section-header">
        <span class="eyebrow">{{ $mob->tier ? 'T'.$mob->tier : __('ui.mobs.title') }}</span>
        <h1>{{ $mob->display_name }}</h1>
        <p>{{ $mobDescription ?: $mob->display_description ?: __('ui.mobs.description_missing') }}</p>
        <div class="tags">
            <a class="btn" href="{{ route('mobs.index') }}">{{ __('ui.actions.back_to_mobs') }}</a>
            @if($mob->faction)
                <span class="chip">{{ __('ui.mobs.faction') }}: {{ $labelValue($mob->faction) }}</span>
            @endif
            @if($mob->attack_type)
                <span class="chip">{{ __('ui.mobs.attack_type') }}: {{ $labelValue($mob->attack_type) }}</span>
            @endif
            @if($mob->danger_state)
                <span class="chip">{{ __('ui.mobs.danger_state') }}: {{ $labelValue($mob->danger_state) }}</span>
            @endif
        </div>
    </section>

    <section class="card-grid">
        <article class="panel">
            <div class="section-header">
                <span class="eyebrow">{{ __('ui.mobs.general') }}</span>
                <h2>{{ __('ui.mobs.overview') }}</h2>
            </div>
            <div class="mob-overview-grid">
                <div class="overview-item">
                    <span class="subtle-title">ID</span>
                    <strong>{{ $mob->unique_name }}</strong>
                </div>
                @if($mob->npc_hostility)
                    <div class="overview-item">
                        <span class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Поведение' : 'Hostility' }}</span>
                        <strong>{{ $labelValue($mob->npc_hostility) }}</strong>
                    </div>
                @endif
                @if($mob->category)
                    <div class="overview-item">
                        <span class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Категория' : 'Category' }}</span>
                        <strong>{{ $labelValue($mob->category) }}</strong>
                    </div>
                @endif
                @if($mob->prefab)
                    <div class="overview-item">
                        <span class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Модель' : 'Model' }}</span>
                        <strong>{{ $humanizeTechnicalName($mob->prefab) }}</strong>
                    </div>
                @endif
                @if($mob->avatar)
                    <div class="overview-item">
                        <span class="subtle-title">{{ app()->getLocale() === 'ru' ? 'Архетип' : 'Archetype' }}</span>
                        <strong>{{ $humanizeTechnicalName($mob->avatar) }}</strong>
                    </div>
                @endif
            </div>
        </article>

        <article class="panel">
            <div class="section-header">
                <span class="eyebrow">{{ __('ui.mobs.stats') }}</span>
                <h2>{{ __('ui.mobs.general') }}</h2>
            </div>
            <div class="stats-grid">
                @foreach($generalStats as $key => $value)
                    @continue($value === null)
                    <div class="stat-tile">
                        <span class="subtle-title">{{ __('ui.mobs.stats_labels.'.$key) }}</span>
                        <strong>{{ $formatValue($value) }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="panel">
            <div class="section-header">
                <span class="eyebrow">{{ __('ui.mobs.combat') }}</span>
                <h2>{{ __('ui.mobs.combat') }}</h2>
            </div>
            <div class="stats-grid">
                @foreach($combatStats as $key => $value)
                    @continue($value === null)
                    <div class="stat-tile">
                        <span class="subtle-title">{{ __('ui.mobs.stats_labels.'.$key) }}</span>
                        <strong>{{ $formatValue($value) }}</strong>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <section class="panel">
        <div class="section-header">
            <span class="eyebrow">{{ __('ui.mobs.spells') }}</span>
            <h2>{{ __('ui.mobs.spells') }}</h2>
        </div>

        @if($mob->spells->isEmpty())
            <p>{{ __('ui.mobs.no_spells') }}</p>
        @else
            <div class="card-grid">
                @foreach($mob->spells as $spell)
                    <article class="card">
                        <div class="section-header" style="position:relative;z-index:1;">
                            <span class="eyebrow">{{ $phaseLabel($spell->phase) }}</span>
                            <h3>{{ $spell->display_name }}</h3>
                            <p>{{ __('ui.mobs.target') }}: {{ $spell->target ?: 'n/a' }}</p>
                        </div>

                        @if($spell->display_saytext)
                            <div class="alert" style="margin-top:12px;position:relative;z-index:1;">
                                <strong>{{ __('ui.mobs.saytext') }}:</strong> {{ $spell->display_saytext }}
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="panel">
        <div class="section-header">
            <span class="eyebrow">{{ __('ui.mobs.loot') }}</span>
            <h2>{{ __('ui.mobs.loot') }}</h2>
        </div>

        @if($mob->lootEntries->isEmpty())
            <p>{{ __('ui.mobs.no_loot') }}</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>{{ __('ui.mobs.entry_type') }}</th>
                    <th>{{ __('ui.mobs.reference') }}</th>
                    <th>{{ __('ui.mobs.chance') }}</th>
                    <th>{{ __('ui.mobs.amount') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mob->lootEntries as $entry)
                    <tr>
                        <td>{{ $entryTypeLabel($entry->entry_type) }}</td>
                        <td>
                            <div>{{ $humanizeDropName($entry->reference_name) }}</div>
                            @if($lootSummary($entry))
                                <div class="meta">{{ $lootSummary($entry) }}</div>
                            @endif
                        </td>
                        <td>{{ $chanceLabel($entry) }}</td>
                        <td>
                            @if($entry->amount)
                                {{ $entry->amount }}
                            @elseif($entry->tier)
                                T{{ $entry->tier }}
                            @else
                                n/a
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection

@push('styles')
    <style>
        .mob-overview-grid,
        .stats-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .overview-item,
        .stat-tile {
            display: grid;
            gap: 8px;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.03);
        }

        .overview-item strong,
        .stat-tile strong {
            color: #fff;
            font-size: 16px;
            line-height: 1.4;
        }
    </style>
@endpush
