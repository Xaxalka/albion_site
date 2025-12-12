<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Weapon;
use App\Models\WeaponSkill;
use Illuminate\Http\Request;

class WeaponSkillController extends Controller
{
    public function edit(int $weaponId)
    {
        $weapon = Weapon::with('weaponSkill')->findOrFail($weaponId);
        $skill = $weapon->weaponSkill ?: new WeaponSkill(['weapon_id' => $weapon->id]);

        return view('admin.weapon-skills.edit', compact('weapon', 'skill'));
    }

    public function update(Request $request, int $weaponId)
    {
        $weapon = Weapon::with('weaponSkill')->findOrFail($weaponId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
        ]);

        if ($weapon->weaponSkill) {
            $weapon->weaponSkill->update($validated);
        } else {
            $weapon->weaponSkill()->create($validated);
        }

        return redirect()->route('admin.weapons.edit', $weapon->id)->with('status', 'Unique E skill saved.');
    }
}
