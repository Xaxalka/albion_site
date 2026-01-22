<?php

namespace App\Services;

use App\Models\Skill;
use App\Models\Weapon;

class WeaponSkillService
{
    public function getBranchSkillsForWeapon(int $weaponId): array
    {
        $weapon = Weapon::with('branch.skills')->findOrFail($weaponId);
        $skills = $weapon->branch?->skills ?? collect();

        return [
            'qSkills' => $this->sortedSkills($skills, 'Q'),
            'wSkills' => $this->sortedSkills($skills, 'W'),
            'eSkills' => $this->sortedSkills($skills, 'E'),
        ];
    }

    protected function sortedSkills($skills, string $slot)
    {
        return $skills
            ->where('slot', $slot)
            ->sortBy('sort')
            ->values();
    }
}
