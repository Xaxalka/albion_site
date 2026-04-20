<?php

use App\Services\AlbionMobImporter;
use App\Services\OpenAlbionGearImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('albion:import-gear {--tier= : Import only the specified item tier, for example 4.0} {--dry-run : Validate import without committing changes}', function (OpenAlbionGearImporter $importer) {
    try {
        $summary = $importer->import(
            (bool) $this->option('dry-run'),
            $this->option('tier') ?: null,
        );
    } catch (\RuntimeException $exception) {
        if ($exception->getMessage() !== 'dry-run') {
            throw $exception;
        }

        $this->info('Dry run completed. Transaction rolled back.');

        return;
    }

    $this->table(['Metric', 'Count'], [
        ['weapon_lines created', $summary['weapon_lines']],
        ['branches created', $summary['branches']],
        ['weapons upserted', $summary['weapons']],
        ['armor items upserted', $summary['armor_items']],
        ['weapons skipped', $summary['skipped_weapons']],
        ['armors skipped', $summary['skipped_armors']],
        ['weapons deleted', $summary['deleted_weapons']],
        ['armors deleted', $summary['deleted_armors']],
        ['weapon lines deleted', $summary['deleted_weapon_lines']],
        ['branches deleted', $summary['deleted_branches']],
    ]);
})->purpose('Import Albion gear data from OpenAlbion into local tables');

Artisan::command('albion:import-mobs {--tier= : Import only the specified mob tier, for example 6} {--dry-run : Validate import without committing changes}', function (AlbionMobImporter $importer) {
    try {
        $summary = $importer->import(
            (bool) $this->option('dry-run'),
            $this->option('tier') !== null ? (int) $this->option('tier') : null,
        );
    } catch (\RuntimeException $exception) {
        if ($exception->getMessage() !== 'dry-run') {
            throw $exception;
        }

        $this->info('Dry run completed. Transaction rolled back.');

        return;
    }

    $this->table(['Metric', 'Count'], [
        ['mobs upserted', $summary['mobs']],
        ['mob spells imported', $summary['spells']],
        ['mob loot entries imported', $summary['loot_entries']],
        ['mobs skipped', $summary['skipped_mobs']],
        ['mobs deleted', $summary['deleted_mobs']],
    ]);
})->purpose('Import Albion mobs, spells, and loot from Albion data dumps');
