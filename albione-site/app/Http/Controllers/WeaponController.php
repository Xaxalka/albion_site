<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\WeaponLine;
use Illuminate\Http\Request;

class WeaponController extends Controller
{
    public function index(Request $request)
    {
        $query = Weapon::with(['weaponLine', 'weaponSkill'])
            ->whereHas('weaponLine', fn ($q) => $q->whereIn('name', WeaponLine::ALLOWED_NAMES));

        if ($request->filled('weapon_line_id')) {
            $query->where('weapon_line_id', $request->integer('weapon_line_id'));
        }

        $weapons = $query->orderBy('name')->get();
        $weaponLines = WeaponLine::whereIn('name', WeaponLine::ALLOWED_NAMES)->orderBy('name')->get();

        return view('weapons.index', compact('weapons', 'weaponLines'));
    }

    public function show(string $slug)
    {
        $weapon = Weapon::where('slug', $slug)
            ->whereHas('weaponLine', fn ($q) => $q->whereIn('name', WeaponLine::ALLOWED_NAMES))
            ->firstOrFail();

        return view('weapons.show', compact('weapon'));
    }
}
