<?php

namespace App\Services;

use App\Models\ArmorItem;
use App\Models\Branch;
use App\Models\Weapon;
use App\Models\WeaponLine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class OpenAlbionGearImporter
{
    private const WEAPONS_URL = 'https://api.openalbion.com/api/v3/weapons';
    private const ARMORS_URL = 'https://api.openalbion.com/api/v3/armors';
    private const LOCALIZATION_URL = 'https://raw.githubusercontent.com/ao-data/ao-bin-dumps/master/formatted/items.json';
    private const IMPORT_NOTE_PREFIX = 'OpenAlbion identifier:';

    public function import(bool $dryRun = false, ?string $tierFilter = null): array
    {
        $weapons = $this->fetchDataset(self::WEAPONS_URL, $tierFilter);
        $armors = $this->fetchDataset(self::ARMORS_URL, $tierFilter);
        $localizations = $this->fetchLocalizations();

        $summary = [
            'weapon_lines' => 0,
            'branches' => 0,
            'weapons' => 0,
            'armor_items' => 0,
            'skipped_weapons' => 0,
            'skipped_armors' => 0,
            'deleted_weapons' => 0,
            'deleted_armors' => 0,
            'deleted_weapon_lines' => 0,
            'deleted_branches' => 0,
        ];

        $callback = function () use ($weapons, $armors, $localizations, &$summary): void {
            $summary['weapons'] = $this->importWeapons($weapons, $localizations, $summary);
            $summary['armor_items'] = $this->importArmors($armors, $localizations, $summary);
            $summary['deleted_weapons'] = $this->pruneImportedWeapons($weapons);
            $summary['deleted_armors'] = $this->pruneImportedArmors($armors);
            $summary['deleted_weapon_lines'] = $this->pruneEmptyWeaponLines();
            $summary['deleted_branches'] = $this->pruneEmptyBranches();
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

    private function fetchLocalizations(): Collection
    {
        $response = Http::timeout(180)
            ->acceptJson()
            ->get(self::LOCALIZATION_URL)
            ->throw();

        return collect($response->json())->keyBy('UniqueName');
    }

    private function fetchDataset(string $url, ?string $tierFilter = null): Collection
    {
        $response = Http::timeout(60)
            ->acceptJson()
            ->get($url)
            ->throw();

        $items = collect($response->json('data', []));

        if ($tierFilter === null) {
            return $items;
        }

        return $items->filter(fn (array $item): bool => (string) data_get($item, 'tier') === $tierFilter)->values();
    }

    private function importWeapons(Collection $items, Collection $localizations, array &$summary): int
    {
        $imported = 0;

        foreach ($items as $item) {
            $identifier = (string) data_get($item, 'identifier', '');
            $lineName = trim((string) data_get($item, 'subcategory.name', ''));
            $categoryName = trim((string) data_get($item, 'category.name', ''));
            $localized = $localizations->get($identifier, []);

            if (! $this->isSupportedWeaponIdentifier($identifier) || $lineName === '' || $categoryName === '') {
                $summary['skipped_weapons']++;
                continue;
            }

            $line = WeaponLine::firstOrNew(['slug' => Str::slug($lineName)]);
            $line->fill([
                'name' => $lineName,
                'name_ru' => $this->translateWeaponLineName($lineName),
                'description' => "Imported from OpenAlbion: {$categoryName} / {$lineName}.",
                'description_ru' => "Импортировано из OpenAlbion: {$this->translateCategory($categoryName)} / {$this->translateWeaponLineName($lineName)}.",
            ]);
            $wasNewLine = ! $line->exists;
            $line->save();
            $summary['weapon_lines'] += $wasNewLine ? 1 : 0;

            $branch = Branch::firstOrNew(['key' => $line->slug]);
            $branch->fill([
                'name' => $lineName,
                'description' => $branch->description ?: "Imported from OpenAlbion: {$categoryName} / {$lineName}.",
            ]);
            $wasNewBranch = ! $branch->exists;
            $branch->save();
            $summary['branches'] += $wasNewBranch ? 1 : 0;

            $weapon = Weapon::firstOrNew(['slug' => Str::slug((string) data_get($item, 'name', $identifier))]);
            $weapon->fill([
                'branch_id' => $branch->id,
                'weapon_line_id' => $line->id,
                'name' => (string) data_get($item, 'name', $identifier),
                'name_ru' => $this->normalizeRussianName($this->localizedValue($localized, 'LocalizedNames.RU-RU')),
                'enchantment' => 0,
                'type' => $this->resolveWeaponType($categoryName, $lineName),
                'icon' => (string) data_get($item, 'icon'),
                'image' => (string) data_get($item, 'icon'),
                'description' => $this->localizedValue($localized, 'LocalizedDescriptions.EN-US') ?: $this->buildWeaponDescription($item),
                'description_ru' => $this->localizedValue($localized, 'LocalizedDescriptions.RU-RU') ?: $this->buildWeaponDescriptionRu($item),
                'author_notes' => $this->buildImportNote($identifier),
            ]);
            $weapon->save();
            $imported++;
        }

        return $imported;
    }

    private function importArmors(Collection $items, Collection $localizations, array &$summary): int
    {
        $imported = 0;

        foreach ($items as $item) {
            $material = $this->resolveArmorMaterial((string) data_get($item, 'category.name', ''));
            $slot = $this->resolveArmorSlot((string) data_get($item, 'subcategory.name', ''));
            $identifier = (string) data_get($item, 'identifier', '');
            $localized = $localizations->get($identifier, []);

            if ($material === null || $slot === null || $identifier === '') {
                $summary['skipped_armors']++;
                continue;
            }

            $name = (string) data_get($item, 'name', $identifier);

            $armor = ArmorItem::firstOrNew(['slug' => Str::slug($name)]);
            $armor->fill([
                'name' => $name,
                'name_ru' => $this->normalizeRussianName($this->localizedValue($localized, 'LocalizedNames.RU-RU')),
                'enchantment' => 0,
                'material' => $material,
                'slot' => $slot,
                'icon' => (string) data_get($item, 'icon'),
                'image' => (string) data_get($item, 'icon'),
                'description' => $this->localizedValue($localized, 'LocalizedDescriptions.EN-US') ?: $this->buildArmorDescription($item),
                'description_ru' => $this->localizedValue($localized, 'LocalizedDescriptions.RU-RU') ?: $this->buildArmorDescriptionRu($item),
                'author_notes' => $this->buildImportNote($identifier),
            ]);
            $armor->save();
            $imported++;
        }

        return $imported;
    }

    private function resolveWeaponType(string $categoryName, string $lineName): string
    {
        if (Str::contains($categoryName, 'Mage')) {
            return 'magic';
        }

        if (in_array($lineName, ['Bow', 'Crossbow'], true)) {
            return 'ranged';
        }

        return 'melee';
    }

    private function resolveArmorMaterial(string $categoryName): ?string
    {
        return match ($categoryName) {
            'Cloth Armor' => 'cloth',
            'Leather Armor' => 'leather',
            'Plate Armor' => 'plate',
            default => null,
        };
    }

    private function resolveArmorSlot(string $subcategoryName): ?string
    {
        if (Str::contains($subcategoryName, ['Robe', 'Jacket', 'Armor'])) {
            return 'chest';
        }

        if (Str::contains($subcategoryName, ['Cowl', 'Hood', 'Helmet'])) {
            return 'head';
        }

        if (Str::contains($subcategoryName, ['Sandal', 'Shoe', 'Boot'])) {
            return 'feet';
        }

        return null;
    }

    private function buildWeaponDescription(array $item): string
    {
        $tier = (string) data_get($item, 'tier', 'n/a');
        $category = (string) data_get($item, 'category.name', 'Weapon');
        $line = (string) data_get($item, 'subcategory.name', 'Unknown line');

        return "Imported from OpenAlbion. {$category} / {$line}, tier {$tier}.";
    }

    private function buildWeaponDescriptionRu(array $item): string
    {
        $tier = (string) data_get($item, 'tier', 'n/a');
        $category = $this->translateCategory((string) data_get($item, 'category.name', 'Weapon'));
        $line = $this->translateWeaponLineName((string) data_get($item, 'subcategory.name', 'Unknown line'));

        return "Импортировано из OpenAlbion. {$category} / {$line}, tier {$tier}.";
    }

    private function isSupportedWeaponIdentifier(string $identifier): bool
    {
        if ($identifier === '' || (! Str::startsWith($identifier, 'T') && ! Str::startsWith($identifier, 'UNIQUE_'))) {
            return false;
        }

        return ! Str::contains($identifier, '_OFF_');
    }

    private function buildArmorDescription(array $item): string
    {
        $tier = (string) data_get($item, 'tier', 'n/a');
        $category = (string) data_get($item, 'category.name', 'Armor');
        $line = (string) data_get($item, 'subcategory.name', 'Unknown slot');

        return "Imported from OpenAlbion. {$category} / {$line}, tier {$tier}.";
    }

    private function buildArmorDescriptionRu(array $item): string
    {
        $tier = (string) data_get($item, 'tier', 'n/a');
        $category = $this->translateCategory((string) data_get($item, 'category.name', 'Armor'));
        $line = $this->translateArmorSubcategory((string) data_get($item, 'subcategory.name', 'Unknown slot'));

        return "Импортировано из OpenAlbion. {$category} / {$line}, tier {$tier}.";
    }

    private function localizedValue(array $localized, string $key): ?string
    {
        $value = data_get($localized, $key);

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function normalizeRussianName(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return preg_replace('/\s*\([^)]*\)\s*$/u', '', trim($value)) ?: $value;
    }

    private function translateCategory(string $category): string
    {
        return match ($category) {
            'Warrior Weapons' => 'Оружие воина',
            'Hunter Weapons' => 'Оружие охотника',
            'Mage Weapons' => 'Оружие мага',
            'Cloth Armor' => 'Тканевая броня',
            'Leather Armor' => 'Кожаная броня',
            'Plate Armor' => 'Латная броня',
            default => $category,
        };
    }

    private function translateWeaponLineName(string $lineName): string
    {
        return match ($lineName) {
            'Axe' => 'Топоры',
            'Sword' => 'Мечи',
            'Mace' => 'Булавы',
            'Hammer' => 'Молоты',
            'Crossbow' => 'Арбалеты',
            'War Gloves' => 'Боевые перчатки',
            'Bow' => 'Луки',
            'Dagger' => 'Кинжалы',
            'Quarterstaff' => 'Посохи бойца',
            'Spear' => 'Копья',
            'Arcane Staff' => 'Мистические посохи',
            'Cursed Staff' => 'Проклятые посохи',
            'Fire Staff' => 'Огненные посохи',
            'Frost Staff' => 'Морозные посохи',
            'Holy Staff' => 'Священные посохи',
            'Nature Staff' => 'Природные посохи',
            'Shapeshifter Staff' => 'Посохи оборотня',
            default => $lineName,
        };
    }

    private function translateArmorSubcategory(string $subcategory): string
    {
        return match ($subcategory) {
            'Robes' => 'Мантии',
            'Jackets' => 'Куртки',
            'Armors' => 'Броня',
            'Cowl' => 'Колпаки',
            'Hoods' => 'Капюшоны',
            'Helmets' => 'Шлемы',
            'Sandals' => 'Сандали',
            'Shoes' => 'Сапоги',
            'Boots' => 'Ботинки',
            default => $subcategory,
        };
    }

    private function pruneImportedWeapons(Collection $items): int
    {
        $slugs = $items
            ->filter(fn (array $item): bool => $this->isSupportedWeaponIdentifier((string) data_get($item, 'identifier', '')))
            ->map(fn (array $item): string => Str::slug((string) data_get($item, 'name', data_get($item, 'identifier', ''))))
            ->filter()
            ->unique()
            ->values();

        $query = Weapon::query()->where('author_notes', 'like', self::IMPORT_NOTE_PREFIX.'%');

        if ($slugs->isNotEmpty()) {
            $query->whereNotIn('slug', $slugs->all());
        }

        return $query->delete();
    }

    private function pruneImportedArmors(Collection $items): int
    {
        $slugs = $items
            ->map(fn (array $item): string => Str::slug((string) data_get($item, 'name', data_get($item, 'identifier', ''))))
            ->filter()
            ->unique()
            ->values();

        $query = ArmorItem::query()->where('author_notes', 'like', self::IMPORT_NOTE_PREFIX.'%');

        if ($slugs->isNotEmpty()) {
            $query->whereNotIn('slug', $slugs->all());
        }

        return $query->delete();
    }

    private function pruneEmptyWeaponLines(): int
    {
        return WeaponLine::query()
            ->doesntHave('weapons')
            ->doesntHave('lineSkills')
            ->delete();
    }

    private function pruneEmptyBranches(): int
    {
        return Branch::query()
            ->doesntHave('weapons')
            ->doesntHave('skills')
            ->delete();
    }

    private function buildImportNote(string $identifier): string
    {
        return self::IMPORT_NOTE_PREFIX.' '.$identifier;
    }
}
