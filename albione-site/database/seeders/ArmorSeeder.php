<?php

namespace Database\Seeders;

use App\Models\ArmorItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArmorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Cloth
            [
                'name' => 'Мантия ученого',
                'material' => 'cloth',
                'slot' => 'chest',
                'description' => 'Тканевая броня для магов: мантия (слот тела).',
                'icon' => 'https://via.placeholder.com/96?text=Cloth+Robe',
            ],
            [
                'name' => 'Колпак ученого',
                'material' => 'cloth',
                'slot' => 'head',
                'description' => 'Тканевая броня для магов: колпак (слот головы).',
                'icon' => 'https://via.placeholder.com/96?text=Cloth+Cowl',
            ],
            [
                'name' => 'Сандали ученого',
                'material' => 'cloth',
                'slot' => 'feet',
                'description' => 'Тканевая броня для магов: сандали (слот ног).',
                'icon' => 'https://via.placeholder.com/96?text=Cloth+Sandals',
            ],

            // Leather
            [
                'name' => 'Куртка охотника',
                'material' => 'leather',
                'slot' => 'chest',
                'description' => 'Кожаная броня для мобильности: куртка (слот тела).',
                'icon' => 'https://via.placeholder.com/96?text=Leather+Jacket',
            ],
            [
                'name' => 'Капюшон охотника',
                'material' => 'leather',
                'slot' => 'head',
                'description' => 'Кожаная броня для мобильности: капюшон (слот головы).',
                'icon' => 'https://via.placeholder.com/96?text=Leather+Hood',
            ],
            [
                'name' => 'Сапоги охотника',
                'material' => 'leather',
                'slot' => 'feet',
                'description' => 'Кожаная броня для мобильности: сапоги (слот ног).',
                'icon' => 'https://via.placeholder.com/96?text=Leather+Boots',
            ],

            // Plate
            [
                'name' => 'Броня солдата',
                'material' => 'plate',
                'slot' => 'chest',
                'description' => 'Латная броня для фронтлайна: броня (слот тела).',
                'icon' => 'https://via.placeholder.com/96?text=Plate+Armor',
            ],
            [
                'name' => 'Шлем солдата',
                'material' => 'plate',
                'slot' => 'head',
                'description' => 'Латная броня для фронтлайна: шлем (слот головы).',
                'icon' => 'https://via.placeholder.com/96?text=Plate+Helmet',
            ],
            [
                'name' => 'Ботинки солдата',
                'material' => 'plate',
                'slot' => 'feet',
                'description' => 'Латная броня для фронтлайна: ботинки (слот ног).',
                'icon' => 'https://via.placeholder.com/96?text=Plate+Shoes',
            ],
        ];

        foreach ($items as $data) {
            ArmorItem::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'enchantment' => $data['enchantment'] ?? 0,
                    'material' => $data['material'],
                    'slot' => $data['slot'],
                    'description' => $data['description'] ?? null,
                    'author_notes' => $data['author_notes'] ?? null,
                    'icon' => $data['icon'] ?? null,
                    'image' => $data['image'] ?? null,
                ]
            );
        }
    }
}
