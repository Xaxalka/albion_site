<?php

namespace App\Services;

use App\Models\Mob;
use App\Models\MobLootEntry;
use App\Models\MobSpell;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;
use RuntimeException;
use SimpleXMLElement;

class AlbionMobImporter
{
    private const SOURCE = 'aodb';

    private const MOBS_URL = 'https://raw.githubusercontent.com/regner/aodb/master/inputs/mobs.txt';
    private const SPELLS_URL = 'https://raw.githubusercontent.com/regner/aodb/master/inputs/spells.txt';
    private const LOOT_URL = 'https://raw.githubusercontent.com/regner/aodb/master/inputs/loot.txt';
    private const LOCALIZATION_URL = 'https://raw.githubusercontent.com/regner/aodb/master/inputs/localization.txt';
    private const ITEM_LOCALIZATION_URL = 'https://raw.githubusercontent.com/ao-data/ao-bin-dumps/master/formatted/items.json';

    private const LOCAL_MOBS_PATH = '/tmp/aodb/inputs/mobs.txt';
    private const LOCAL_SPELLS_PATH = '/tmp/aodb/inputs/spells.txt';
    private const LOCAL_LOOT_PATH = '/tmp/aodb/inputs/loot.txt';
    private const LOCAL_LOCALIZATION_PATH = '/tmp/aodb/inputs/localization.txt';

    public function import(bool $dryRun = false, ?int $tierFilter = null): array
    {
        $mobsXml = $this->fetchTextAsset(self::LOCAL_MOBS_PATH, self::MOBS_URL);
        $spellsXml = $this->fetchTextAsset(self::LOCAL_SPELLS_PATH, self::SPELLS_URL);
        $lootXml = $this->fetchTextAsset(self::LOCAL_LOOT_PATH, self::LOOT_URL);
        $localizationXml = $this->fetchTextAsset(self::LOCAL_LOCALIZATION_PATH, self::LOCALIZATION_URL);

        $spellDefinitions = $this->parseSpellDefinitions($spellsXml);
        $lootDefinitions = $this->parseLootDefinitions($lootXml);
        $localizations = $this->parseLocalizations($localizationXml);
        $itemLocalizations = $this->fetchItemLocalizations();

        $summary = [
            'mobs' => 0,
            'spells' => 0,
            'loot_entries' => 0,
            'deleted_mobs' => 0,
            'skipped_mobs' => 0,
        ];

        $callback = function () use (
            $mobsXml,
            $spellDefinitions,
            $lootDefinitions,
            $localizations,
            $itemLocalizations,
            $tierFilter,
            &$summary
        ): void {
            $root = $this->loadXml($mobsXml);
            $keptUniqueNames = [];

            foreach ($root->Mob as $mobElement) {
                $attributes = $this->attributesToArray($mobElement);
                $uniqueName = (string) ($attributes['uniquename'] ?? '');

                if ($uniqueName === '' || $this->shouldSkipMob($attributes)) {
                    $summary['skipped_mobs']++;
                    continue;
                }

                $tier = $this->toInt($attributes['tier'] ?? null);

                if ($tierFilter !== null && $tier !== $tierFilter) {
                    continue;
                }

                $nameTranslations = $this->resolveMobTranslations($uniqueName, $localizations);
                $mob = Mob::updateOrCreate(
                    ['unique_name' => $uniqueName],
                    [
                        'slug' => Str::slug($uniqueName),
                        'name' => $nameTranslations['en'] ?? $this->humanizeIdentifier($uniqueName),
                        'name_ru' => $nameTranslations['ru'],
                        'description' => $this->buildMobDescription($attributes),
                        'description_ru' => $this->buildMobDescriptionRu($attributes),
                        'tier' => $tier,
                        'faction' => $attributes['faction'] ?? null,
                        'category' => $attributes['category'] ?? null,
                        'danger_state' => $attributes['dangerstate'] ?? null,
                        'npc_hostility' => $attributes['npchostility'] ?? null,
                        'attack_type' => $attributes['attacktype'] ?? null,
                        'ability_power' => $this->toFloat($attributes['abilitypower'] ?? null),
                        'fame' => $this->toInt($attributes['fame'] ?? null),
                        'attack_range' => $this->toFloat($attributes['attackrange'] ?? null),
                        'attack_damage' => $this->toFloat($attributes['attackdamage'] ?? null),
                        'hitpoints_max' => $this->toFloat($attributes['hitpointsmax'] ?? null),
                        'hitpoints_regeneration' => $this->toFloat($attributes['hitpointsregeneration'] ?? null),
                        'energy_max' => $this->toFloat($attributes['energymax'] ?? null),
                        'energy_regeneration' => $this->toFloat($attributes['energyregeneration'] ?? null),
                        'move_speed' => $this->toFloat($attributes['movespeed'] ?? null),
                        'attack_move_speed' => $this->toFloat($attributes['attackmovespeed'] ?? null),
                        'attack_speed' => $this->toFloat($attributes['attackspeed'] ?? null),
                        'melee_attack_damage_time' => $this->toFloat($attributes['meleeattackdamagetime'] ?? null),
                        'physical_armor' => $this->toFloat($attributes['physicalarmor'] ?? null),
                        'magic_resistance' => $this->toFloat($attributes['magicresistance'] ?? null),
                        'crowd_control_resistance' => $this->toFloat($attributes['crowdcontrolresistance'] ?? null),
                        'aggro_radius' => $this->toFloat($attributes['aggroradius'] ?? null),
                        'pursuit_radius' => $this->toFloat($attributes['pursuitradius'] ?? null),
                        'alert_radius' => $this->toFloat($attributes['alertradius'] ?? null),
                        'collision_radius' => $this->toFloat($attributes['collisionradius'] ?? null),
                        'attack_collision_radius' => $this->toFloat($attributes['attackcollisionradius'] ?? null),
                        'avatar' => $attributes['avatar'] ?? null,
                        'prefab' => $attributes['prefab'] ?? null,
                        'source' => self::SOURCE,
                        'raw_data' => [
                            'attributes' => $attributes,
                            'spells' => $this->childrenToArray($mobElement->Spells),
                            'death_spells' => $this->childrenToArray($mobElement->DeathSpells),
                            'loot' => $this->childrenToArray($mobElement->Loot),
                        ],
                    ]
                );

                $keptUniqueNames[] = $uniqueName;
                $summary['mobs']++;

                $mob->spells()->delete();
                $mob->lootEntries()->delete();

                $summary['spells'] += $this->storeMobSpells($mob, $mobElement, $spellDefinitions, $localizations);
                $summary['loot_entries'] += $this->storeMobLoot($mob, $mobElement, $lootDefinitions, $itemLocalizations);
            }

            $deleteQuery = Mob::query()->where('source', self::SOURCE);

            if ($tierFilter !== null) {
                $deleteQuery->where('tier', $tierFilter);
            }

            if ($keptUniqueNames !== []) {
                $deleteQuery->whereNotIn('unique_name', $keptUniqueNames);
            }

            $summary['deleted_mobs'] = $deleteQuery->delete();
            Cache::flush();
        };

        if ($dryRun) {
            DB::transaction(function () use ($callback): void {
                $callback();
                throw new RuntimeException('dry-run');
            });
        } else {
            DB::transaction($callback);
        }

        return $summary;
    }

    private function storeMobSpells(Mob $mob, SimpleXMLElement $mobElement, Collection $spellDefinitions, Collection $localizations): int
    {
        $count = 0;

        foreach (['Spells', 'DeathSpells'] as $phase) {
            if (! isset($mobElement->{$phase})) {
                continue;
            }

            $sortOrder = 1;

            foreach ($mobElement->{$phase}->children() as $spellElement) {
                $spellAttributes = $this->attributesToArray($spellElement);
                $spellUniqueName = (string) ($spellAttributes['name'] ?? '');
                $definition = $spellDefinitions->get($spellUniqueName, []);
                $saytextKey = $spellAttributes['saytext'] ?? null;
                $saytextTranslations = $saytextKey ? $localizations->get($saytextKey, []) : [];

                MobSpell::create([
                    'mob_id' => $mob->id,
                    'phase' => $phase,
                    'unique_name' => $spellUniqueName,
                    'name' => $this->humanizeIdentifier($spellUniqueName),
                    'name_ru' => $this->humanizeIdentifierRu($spellUniqueName),
                    'target' => $spellAttributes['target'] ?? null,
                    'saytext_key' => $saytextKey,
                    'saytext' => $saytextTranslations['EN-US'] ?? null,
                    'saytext_ru' => $saytextTranslations['RU-RU'] ?? null,
                    'sort_order' => $sortOrder++,
                    'conditions' => $this->childrenToArray($spellElement),
                    'details' => $definition,
                ]);

                $count++;
            }
        }

        return $count;
    }

    private function storeMobLoot(Mob $mob, SimpleXMLElement $mobElement, Collection $lootDefinitions, Collection $itemLocalizations): int
    {
        if (! isset($mobElement->Loot)) {
            return 0;
        }

        $count = 0;
        $sortOrder = 1;

        foreach ($mobElement->Loot->children() as $lootElement) {
            $attributes = $this->attributesToArray($lootElement);
            $entryType = $lootElement->getName();
            $referenceName = (string) ($attributes['name'] ?? $attributes['type'] ?? $entryType);
            $details = $this->childrenToArray($lootElement);
            $labels = $this->resolveLootLabels($referenceName, $itemLocalizations);

            if ($entryType === 'LootListReference') {
                $details = [
                    'definition' => $lootDefinitions->get($referenceName, []),
                ];
            }

            MobLootEntry::create([
                'mob_id' => $mob->id,
                'entry_type' => $entryType,
                'reference_name' => $referenceName,
                'reference_label' => $labels['en'],
                'reference_label_ru' => $labels['ru'],
                'chance' => $this->toFloat($attributes['chance'] ?? null),
                'amount' => $attributes['amount'] ?? null,
                'tier' => $attributes['tier'] ?? null,
                'enchantment_level' => $this->toInt($attributes['enchantmentlevel'] ?? null),
                'sort_order' => $sortOrder++,
                'details' => $details,
            ]);

            $count++;
        }

        return $count;
    }

    private function parseSpellDefinitions(string $xml): Collection
    {
        $root = $this->loadXml($xml);
        $definitions = [];

        foreach ($root->children() as $spellElement) {
            $uniqueName = (string) ($spellElement['uniquename'] ?? '');

            if ($uniqueName === '') {
                continue;
            }

            $definitions[$uniqueName] = [
                'attributes' => $this->attributesToArray($spellElement),
                'effects' => $this->childrenToArray($spellElement),
            ];
        }

        return collect($definitions);
    }

    private function parseLootDefinitions(string $xml): Collection
    {
        $root = $this->loadXml($xml);
        $definitions = [];

        foreach ($root->Lootlist as $lootList) {
            $name = (string) ($lootList['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $definitions[$name] = $this->childrenToArray($lootList);
        }

        return collect($definitions);
    }

    private function parseLocalizations(string $xml): Collection
    {
        $root = $this->loadXml($xml);
        $localizations = [];

        foreach ($root->body->tu as $tu) {
            $key = (string) ($tu['tuid'] ?? '');

            if ($key === '') {
                continue;
            }

            foreach ($tu->tuv as $tuv) {
                $lang = (string) $tuv->attributes('xml', true)->lang;
                $localizations[$key][$lang] = trim((string) $tuv->seg);
            }
        }

        return collect($localizations);
    }

    private function fetchItemLocalizations(): Collection
    {
        try {
            $response = Http::timeout(20)->acceptJson()->get(self::ITEM_LOCALIZATION_URL)->throw();

            return collect($response->json())
                ->mapWithKeys(function (array $item): array {
                    $uniqueName = (string) ($item['UniqueName'] ?? '');

                    if ($uniqueName === '') {
                        return [];
                    }

                    return [
                        $uniqueName => [
                            'en' => data_get($item, 'LocalizedNames.EN-US'),
                            'ru' => data_get($item, 'LocalizedNames.RU-RU'),
                        ],
                    ];
                });
        } catch (Throwable) {
            return collect();
        }
    }

    private function resolveMobTranslations(string $uniqueName, Collection $localizations): array
    {
        foreach ($this->candidateMobLocalizationKeys($uniqueName) as $key) {
            $translations = $localizations->get($key);

            if (is_array($translations)) {
                return [
                    'en' => $translations['EN-US'] ?? null,
                    'ru' => $translations['RU-RU'] ?? null,
                ];
            }
        }

        return ['en' => null, 'ru' => null];
    }

    private function candidateMobLocalizationKeys(string $uniqueName): array
    {
        $normalized = preg_replace('/_EXP_/', '_', $uniqueName) ?: $uniqueName;

        return array_values(array_unique([
            '@MOB_'.$uniqueName,
            '@MOB_'.$normalized,
        ]));
    }

    private function resolveLootLabels(string $referenceName, Collection $itemLocalizations): array
    {
        $localized = $itemLocalizations->get($referenceName);

        if (is_array($localized)) {
            return [
                'en' => $localized['en'] ?: $this->humanizeIdentifier($referenceName),
                'ru' => $localized['ru'] ?: null,
            ];
        }

        return [
            'en' => $this->humanizeIdentifier($referenceName),
            'ru' => null,
        ];
    }

    private function buildMobDescription(array $attributes): string
    {
        $tier = $attributes['tier'] ?? '?';
        $faction = $this->humanizeIdentifier((string) ($attributes['faction'] ?? 'Unknown'));
        $attackType = $this->humanizeIdentifier((string) ($attributes['attacktype'] ?? 'unknown'));
        $dangerState = $this->humanizeIdentifier((string) ($attributes['dangerstate'] ?? 'normal'));

        return "Imported from Albion game data. Tier {$tier}, faction {$faction}, {$attackType} attacker, {$dangerState} danger state.";
    }

    private function buildMobDescriptionRu(array $attributes): string
    {
        $tier = $attributes['tier'] ?? '?';
        $faction = $this->humanizeIdentifierRu((string) ($attributes['faction'] ?? 'Unknown'));
        $attackType = $this->humanizeIdentifierRu((string) ($attributes['attacktype'] ?? 'unknown'));
        $dangerState = $this->humanizeIdentifierRu((string) ($attributes['dangerstate'] ?? 'normal'));

        return "Импортировано из игровых данных Albion. Тир {$tier}, фракция {$faction}, тип атаки {$attackType}, состояние опасности {$dangerState}.";
    }

    private function shouldSkipMob(array $attributes): bool
    {
        $uniqueName = (string) ($attributes['uniquename'] ?? '');
        $faction = Str::lower((string) ($attributes['faction'] ?? ''));
        $prefab = Str::lower((string) ($attributes['prefab'] ?? ''));

        return Str::contains($uniqueName, ['DUMMY', 'TEST'])
            || $faction === 'dummy'
            || Str::contains($prefab, ['dummy']);
    }

    private function humanizeIdentifier(string $value): string
    {
        $value = preg_replace('/^T\d+_/', '', $value) ?: $value;
        $value = str_replace(['MOB_', 'UNIQUE_'], '', $value);
        $value = str_replace('_', ' ', Str::lower($value));

        return Str::title(trim($value));
    }

    private function humanizeIdentifierRu(string $value): string
    {
        return $this->humanizeIdentifier($value);
    }

    private function fetchTextAsset(string $localPath, string $remoteUrl): string
    {
        if (is_file($localPath)) {
            $payload = file_get_contents($localPath);

            if (is_string($payload) && $payload !== '') {
                return $this->extractXmlFromTextAsset($payload);
            }
        }

        $response = Http::timeout(180)->get($remoteUrl)->throw();

        return $this->extractXmlFromTextAsset($response->body());
    }

    private function extractXmlFromTextAsset(string $payload): string
    {
        if (! preg_match('/m_Script = "(.*)"\n\s*1 string m_PathName/s', $payload, $matches)) {
            throw new RuntimeException('Failed to parse Albion text asset.');
        }

        return stripcslashes($matches[1]);
    }

    private function loadXml(string $xml): SimpleXMLElement
    {
        $element = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NOCDATA | LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);

        if (! $element instanceof SimpleXMLElement) {
            throw new RuntimeException('Failed to parse Albion XML payload.');
        }

        return $element;
    }

    private function attributesToArray(?SimpleXMLElement $element): array
    {
        if (! $element instanceof SimpleXMLElement) {
            return [];
        }

        $attributes = [];

        foreach ($element->attributes() as $key => $value) {
            $attributes[(string) $key] = trim((string) $value);
        }

        return $attributes;
    }

    private function childrenToArray(?SimpleXMLElement $element): array
    {
        if (! $element instanceof SimpleXMLElement) {
            return [];
        }

        $result = [];
        $children = $element->children();

        if (! $children) {
            return [];
        }

        foreach ($children as $child) {
            $entry = [
                'tag' => $child->getName(),
                'attributes' => $this->attributesToArray($child),
            ];

            $children = $this->childrenToArray($child);

            if ($children !== []) {
                $entry['children'] = $children;
            }

            $text = trim((string) $child);

            if ($text !== '') {
                $entry['text'] = $text;
            }

            $result[] = $entry;
        }

        return $result;
    }

    private function toFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : null;
    }

    private function toInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }
}
