<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\WeaponLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeaponController extends Controller
{
    public function index(Request $request)
    {
        $weaponLineId = $request->filled('weapon_line_id')
            ? $request->integer('weapon_line_id')
            : null;

        $cacheKey = $weaponLineId
            ? "weapons.index:line={$weaponLineId}"
            : 'weapons.index:all';

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($weaponLineId) {
            $query = Weapon::with(['weaponLine', 'weaponSkill.media']);

            if ($weaponLineId) {
                $query->where('weapon_line_id', $weaponLineId);
            }

            return [
                'weapons' => $query->orderBy('name')->get(),
                'weaponLines' => WeaponLine::orderBy('name')->get(),
            ];
        });

        return view('weapons.index', $data);
    }

    public function show(string $slug)
    {
        $weapon = Cache::remember("weapons.show:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return Weapon::with('weaponSkill.media')
                ->where('slug', $slug)
                ->firstOrFail();
        });

        return view('weapons.show', compact('weapon'));
    }
}
