<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\WeaponLine;
use Illuminate\Http\Request;

class WeaponController extends Controller
{
    public function index(Request $request)
    {
        $query = Weapon::with(['weaponLine', 'weaponSkill']);

        if ($request->filled('tier')) {
            $query->where('tier', $request->string('tier'));
        }

        if ($request->filled('weapon_line_id')) {
            $query->where('weapon_line_id', $request->integer('weapon_line_id'));
        }

        $weapons = $query->orderBy('tier')->orderBy('name')->get();

        $tiers = Weapon::select('tier')->distinct()->orderBy('tier')->pluck('tier');
        $weaponLines = WeaponLine::orderBy('name')->get();

        return view('weapons.index', compact('weapons', 'tiers', 'weaponLines'));
    }

    public function show(string $slug)
    {
        $weapon = Weapon::where('slug', $slug)->firstOrFail();

        return view('weapons.show', compact('weapon'));
    }
}
