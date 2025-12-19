<?php

namespace App\Http\Controllers;

use App\Models\WeaponLine;
use Illuminate\Support\Facades\Cache;

class WeaponLineController extends Controller
{
    public function index()
    {
        $data = Cache::remember('weapon-lines.index', now()->addMinutes(10), function () {
            $lines = WeaponLine::whereIn('name', WeaponLine::ALLOWED_NAMES)
                ->with(['weapons' => function ($query) {
                    $query->orderBy('name');
                }])->orderBy('name')->get();

            $quickBranches = [
                'Warrior Weapons' => 'Арбалеты; Боевые перчатки; Молотки; Булавы; Топоры; Мечи',
                'Hunter Weapons' => 'Луки; Кинжалы; Копья; Шесты; Shapershifts; Друиды',
                'Mage Weapons' => 'Огненные посохи; Священные посохи; Мистические посохи; Морозные посохи; Проклятые посохи',
            ];

            return compact('lines', 'quickBranches');
        });

        return view('weapon-lines.index', $data);
    }

    public function show(string $slug)
    {
        $line = Cache::remember("weapon-lines.show:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return WeaponLine::whereIn('name', WeaponLine::ALLOWED_NAMES)->with([
                'lineSkills' => fn ($query) => $query->orderByRaw("FIELD(slot, 'Q','W','Passive')")->orderBy('name'),
                'weapons.weaponSkill',
            ])->where('slug', $slug)->firstOrFail();
        });

        return view('weapon-lines.show', compact('line'));
    }
}
