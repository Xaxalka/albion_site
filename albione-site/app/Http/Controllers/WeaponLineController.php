<?php

namespace App\Http\Controllers;

use App\Models\WeaponLine;
use Illuminate\Http\Request;

class WeaponLineController extends Controller
{
    public function index()
    {
        $lines = WeaponLine::with(['weapons' => function ($query) {
            $query->orderBy('tier');
        }])->orderBy('name')->get();

        return view('weapon-lines.index', compact('lines'));
    }

    public function show(string $slug)
    {
        $line = WeaponLine::with([
            'lineSkills' => fn ($query) => $query->orderByRaw("FIELD(slot, 'Q','W','Passive')")->orderBy('name'),
            'weapons.weaponSkill',
        ])->where('slug', $slug)->firstOrFail();

        return view('weapon-lines.show', compact('line'));
    }
}
