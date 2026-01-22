<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class BranchSkillPlaceholderSeeder extends Seeder
{
    public function run(): void
    {
        $slots = ['Q', 'W', 'E'];

        Branch::query()->each(function (Branch $branch) use ($slots) {
            foreach ($slots as $slot) {
                Skill::firstOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'slot' => $slot,
                        'name' => 'None',
                        'is_placeholder' => true,
                    ],
                    [
                        'sort' => 0,
                    ]
                );
            }
        });
    }
}
