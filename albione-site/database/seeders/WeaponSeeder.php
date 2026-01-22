<?php

namespace Database\Seeders;

use App\Models\Weapon;
use App\Models\WeaponLine;
use App\Models\Branch;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WeaponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lines = [
            [
                'name' => 'Warrior Weapons',
                'description' => 'Frontline arsenal: swords, maces, axes - built for engage and durability.',
                'skills' => [
                    ['slot' => 'Q', 'name' => 'Heroic Strike', 'description' => 'Single-target strike building heroism.'],
                    ['slot' => 'W', 'name' => 'Splitting Slash', 'description' => 'Arc slash that slows enemies.'],
                ],
                'weapons' => [
                    [
                        'name' => 'Broadsword',
                        'description' => 'Reliable sword for frontline skirmishes.',
                        'author_notes' => 'Starter weapon; good baseline.',
                        'image' => 'https://via.placeholder.com/200?text=Broadsword',
                        'skill' => ['name' => 'Mighty Blow', 'description' => 'Leap to target and strike.'],
                    ],
                    [
                        'name' => 'Claymore',
                        'description' => 'Heavy two-handed sword with burst initiation.',
                        'author_notes' => 'Rewards aggressive play.',
                        'image' => 'https://via.placeholder.com/200?text=Claymore',
                        'skill' => ['name' => 'Charge', 'description' => 'Charge forward, stunning the first hit.'],
                    ],
                ],
            ],
            [
                'name' => 'Hunter Weapons',
                'description' => 'Ranged pressure: bows, crossbows, spears with kite and control.',
                'skills' => [
                    ['slot' => 'Q', 'name' => 'Deadly Shot', 'description' => 'Piercing arrow stacking damage.'],
                    ['slot' => 'W', 'name' => 'Frost Shot', 'description' => 'Jump back and slow enemies.'],
                ],
                'weapons' => [
                    [
                        'name' => 'Warbow',
                        'description' => 'High burst from range with long stun shot.',
                        'author_notes' => 'Great for picks.',
                        'image' => 'https://via.placeholder.com/200?text=Warbow',
                        'skill' => ['name' => 'Ray of Light', 'description' => 'Explosive shot that roots.'],
                    ],
                ],
            ],
            [
                'name' => 'Mage Weapons',
                'description' => 'Magical staves: AoE, damage over time, distance control.',
                'skills' => [
                    ['slot' => 'Q', 'name' => 'Fire Bolt', 'description' => 'Ranged burning projectile.'],
                    ['slot' => 'W', 'name' => 'Ignite', 'description' => 'Apply burning stacks.'],
                ],
                'weapons' => [
                    [
                        'name' => 'Fire Staff',
                        'description' => 'Core fire weapon with strong AoE zoning.',
                        'author_notes' => 'Staple for group fights.',
                        'image' => 'https://via.placeholder.com/200?text=Fire+Staff',
                        'skill' => ['name' => 'Meteor', 'description' => 'Channel a meteor strike after delay.'],
                    ],
                ],
            ],
        ];

        foreach ($lines as $lineData) {
            $line = WeaponLine::updateOrCreate(
                ['slug' => Str::slug($lineData['name'])],
                [
                    'name' => $lineData['name'],
                    'description' => $lineData['description'],
                    'slug' => Str::slug($lineData['name']),
                ]
            );

            $branch = Branch::updateOrCreate(
                ['key' => $line->slug],
                [
                    'name' => $line->name,
                    'description' => $line->description,
                ]
            );

            foreach ($lineData['skills'] as $skill) {
                Skill::updateOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'slot' => $skill['slot'],
                        'name' => $skill['name'],
                    ],
                    [
                        'description' => $skill['description'],
                        'sort' => 0,
                    ]
                );
            }

            foreach ($lineData['weapons'] as $weaponData) {
                $weapon = Weapon::updateOrCreate(
                    ['slug' => Str::slug($weaponData['name'])],
                    [
                        'branch_id' => $branch->id,
                        'weapon_line_id' => $line->id,
                        'name' => $weaponData['name'],
                        'slug' => Str::slug($weaponData['name']),
                        'description' => $weaponData['description'] ?? null,
                        'author_notes' => $weaponData['author_notes'] ?? null,
                        'image' => $weaponData['image'] ?? null,
                    ]
                );

                if (! empty($weaponData['skill'])) {
                    Skill::updateOrCreate(
                        [
                            'branch_id' => $branch->id,
                            'slot' => 'E',
                            'name' => $weaponData['skill']['name'],
                        ],
                        [
                            'description' => $weaponData['skill']['description'] ?? null,
                            'sort' => $weapon->id,
                        ]
                    );
                }
            }
        }
    }
}
