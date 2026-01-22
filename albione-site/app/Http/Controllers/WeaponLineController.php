<?php

namespace App\Http\Controllers;

use App\Models\WeaponLine;
use App\Models\Skill;
use Illuminate\Support\Facades\Cache;

class WeaponLineController extends Controller
{
    public function index()
    {
        $data = Cache::remember('weapon-lines.index', now()->addMinutes(10), function () {
            $lines = WeaponLine::with(['weapons' => function ($query) {
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
            return WeaponLine::with(['weapons' => fn ($query) => $query->orderBy('name')])
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $branch = $line->branch;
        $skills = $branch
            ? Skill::where('branch_id', $branch->id)->orderBy('sort')->get()->groupBy('slot')
            : collect();

        $skillGroups = [
            'Q' => $skills->get('Q', collect()),
            'W' => $skills->get('W', collect()),
            'E' => $skills->get('E', collect()),
        ];

        return view('weapon-lines.show', compact('line', 'skillGroups'));
    }
}
