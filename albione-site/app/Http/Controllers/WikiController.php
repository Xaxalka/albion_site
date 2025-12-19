<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WikiController extends Controller
{
    public function index(): View
    {
        $data = Cache::remember('wiki.index', now()->addMinutes(30), function () {
            $mobs = [
                [
                    'name' => 'Morgana Knight',
                    'tier_range' => 'T6 – T8',
                    'skills' => ['Shield Bash', 'War Banner', 'Fiery Charge'],
                    'image' => 'https://render.albiononline.com/v1/item/Morgana_Knight.png',
                    'description' => 'Элитный рыцарь Моргана, владеющий контролем толпы и усилениями группы.',
                ],
                [
                    'name' => 'Undead Archer',
                    'tier_range' => 'T4 – T7',
                    'skills' => ['Piercing Shot', 'Deathly Arrows', 'Shadow Slip'],
                    'image' => 'https://render.albiononline.com/v1/item/Undead_Sniper.png',
                    'description' => 'Дальний боец, накладывающий кровотечение и дебаффы брони.',
                ],
                [
                    'name' => 'Avalonian Construct',
                    'tier_range' => 'T7 – T8',
                    'skills' => ['Crushing Leap', 'Light Beam', 'Sanctuary'],
                    'image' => 'https://render.albiononline.com/v1/item/Avalonian_Winged_Guardian.png',
                    'description' => 'Массивный голем с сильными АоЕ атаками и защитным куполом.',
                ],
                [
                    'name' => 'Keeper Bear',
                    'tier_range' => 'T4 – T8',
                    'skills' => ['Ground Slam', 'Feral Roar', 'Thick Hide'],
                    'image' => 'https://render.albiononline.com/v1/item/Keepers_Bear.png',
                    'description' => 'Танк-существо Хранителей, отлично выдерживает урон и оглушает цели.',
                ],
            ];

            $weaponBranches = [
                [
                    'name' => 'Warrior Weapons',
                    'icon' => '🗡️',
                    'summary' => 'Мечи, топоры и булавы, нацеленные на ближний бой и контроль.',
                    'q_skills' => [
                        ['name' => 'Heroic Strike', 'description' => 'Ускоряет и усиливает последующие атаки.', 'video' => 'https://www.youtube.com/embed/hbb-wYHAmX4'],
                        ['name' => 'Rending Swing', 'description' => 'Наносит кровотечение всем задетым врагам.', 'video' => 'https://www.youtube.com/embed/SqyK2BrqD5Q'],
                    ],
                    'w_skills' => [
                        ['name' => 'Interrupt', 'description' => 'Прерывает каст и оглушает цель.', 'video' => 'https://www.youtube.com/embed/uhdI_2cRPx0'],
                        ['name' => 'Parry Strike', 'description' => 'Блокирует урон и наносит встречный удар.', 'video' => 'https://www.youtube.com/embed/RHeBzVvsIrc'],
                    ],
                    'passive' => ['name' => 'Deep Cuts', 'description' => 'Каждый третий удар накладывает длительное кровотечение.', 'video' => 'https://www.youtube.com/embed/f4ttEoTyWb8'],
                    'variants' => [
                        ['name' => 'Claymore', 'role' => 'Burst / Engage', 'image' => 'https://render.albiononline.com/v1/item/T4_MAIN_SWORD.png', 'video' => 'https://www.youtube.com/embed/7a2Rxrtvk0c'],
                        ['name' => 'Greataxe', 'role' => 'AoE Damage', 'image' => 'https://render.albiononline.com/v1/item/T6_2H_AXE2.png', 'video' => 'https://www.youtube.com/embed/8kzVnvYTRpk'],
                        ['name' => 'One Handed Mace', 'role' => 'CC / Tank', 'image' => 'https://render.albiononline.com/v1/item/T5_MAIN_MACE.png', 'video' => 'https://www.youtube.com/embed/9uHZkCEJvDE'],
                    ],
                ],
                [
                    'name' => 'Hunter Weapons',
                    'icon' => '🏹',
                    'summary' => 'Луки и копья для мобильности и урона с дистанции.',
                    'q_skills' => [
                        ['name' => 'Deadly Shot', 'description' => 'Повышает урон с дистанции и пробивает броню.', 'video' => 'https://www.youtube.com/embed/97Zz-GcnfIk'],
                        ['name' => 'Forest of Spears', 'description' => 'Линия копий, наносящая урон и замедление.', 'video' => 'https://www.youtube.com/embed/8mlM6UfwGsU'],
                    ],
                    'w_skills' => [
                        ['name' => 'Frost Shot', 'description' => 'Рывок назад с одновременным замедлением цели.', 'video' => 'https://www.youtube.com/embed/QWFhIuwZBxs'],
                        ['name' => 'Impale', 'description' => 'Прыжок с мощным ударом по земле.', 'video' => 'https://www.youtube.com/embed/HFjf67T2oGs'],
                    ],
                    'passive' => ['name' => 'Attack Speed', 'description' => 'Постепенно увеличивает скорость атаки при попаданиях.', 'video' => 'https://www.youtube.com/embed/OF3B5XPm1RA'],
                    'variants' => [
                        ['name' => 'Warbow', 'role' => 'Kiting / Burst', 'image' => 'https://render.albiononline.com/v1/item/T4_2H_WARBOW.png', 'video' => 'https://www.youtube.com/embed/N_mK1sufJmE'],
                        ['name' => 'Spirithunter', 'role' => 'Debuff / Anti-heal', 'image' => 'https://render.albiononline.com/v1/item/T7_2H_HARPOON.png', 'video' => 'https://www.youtube.com/embed/lmWk5ULU5Xw'],
                        ['name' => 'Trinity Spear', 'role' => 'Burst / Engage', 'image' => 'https://render.albiononline.com/v1/item/T8_2H_TRIDENT_UNDEAD.png', 'video' => 'https://www.youtube.com/embed/gn1U9UsTxTE'],
                    ],
                ],
                [
                    'name' => 'Mage Weapons',
                    'icon' => '🔮',
                    'summary' => 'Посохи и магия дальнего боя с сильным контролем.',
                    'q_skills' => [
                        ['name' => 'Fire Bolt', 'description' => 'Стреляет огненным зарядом, накладывающим горение.', 'video' => 'https://www.youtube.com/embed/Fe9QAMdJHPs'],
                        ['name' => 'Frost Bolt', 'description' => 'Замедляет врагов и наносит ледяной урон.', 'video' => 'https://www.youtube.com/embed/EjjXesqvGZ8'],
                    ],
                    'w_skills' => [
                        ['name' => 'Flame Tornado', 'description' => 'Движущийся торнадо с массовым уроном.', 'video' => 'https://www.youtube.com/embed/BiOwzvt2zSo'],
                        ['name' => 'Time Freeze', 'description' => 'Останавливает время в области и обездвиживает врагов.', 'video' => 'https://www.youtube.com/embed/nhQjhokWJzY'],
                    ],
                    'passive' => ['name' => 'Energetic', 'description' => 'Восстанавливает энергию при попадании заклинаниями.', 'video' => 'https://www.youtube.com/embed/4gNaJ_mNc1E'],
                    'variants' => [
                        ['name' => 'Brimstone Staff', 'role' => 'Big AoE', 'image' => 'https://render.albiononline.com/v1/item/T8_2H_FIRE_3.png', 'video' => 'https://www.youtube.com/embed/ydxJYkCIJis'],
                        ['name' => 'Holy Staff', 'role' => 'Group Heal', 'image' => 'https://render.albiononline.com/v1/item/T6_MAIN_HOLYSTAFF.png', 'video' => 'https://www.youtube.com/embed/UCk4yV5JNEE'],
                        ['name' => 'Cursed Skull', 'role' => 'DoT / Control', 'image' => 'https://render.albiononline.com/v1/item/T7_2H_CURSEDSTAFF@1.png', 'video' => 'https://www.youtube.com/embed/H1n5F8pT7xY'],
                    ],
                ],
            ];

            $armors = [
                ['name' => 'Guardian Armor', 'type' => 'Plate', 'description' => 'Широкий щит для всей группы и высокий физический резист.', 'video' => 'https://www.youtube.com/embed/qm5C7T0jOXU'],
                ['name' => 'Mage Robe', 'type' => 'Cloth', 'description' => 'Усиление урона и временная неуязвимость к контролю.', 'video' => 'https://www.youtube.com/embed/vfY3uPyK4Hs'],
                ['name' => 'Mercenary Jacket', 'type' => 'Leather', 'description' => 'Самолечение от нанесённого урона, подходит для соло.', 'video' => 'https://www.youtube.com/embed/B5_4bMGkfq0'],
                ['name' => 'Hellion Hood', 'type' => 'Leather', 'description' => 'Вампиризм по площади, полезно в PvP.', 'video' => 'https://www.youtube.com/embed/Lq9kJ3u1kMo'],
                ['name' => 'Cleric Robe', 'type' => 'Cloth', 'description' => 'Дает неуязвимость и увеличенный урон после окончания.', 'video' => 'https://www.youtube.com/embed/0tGeRmgoQWg'],
            ];

            $contents = [
                [
                    'name' => 'Stalker Corrupted Dungeon',
                    'icon' => 'https://render.albiononline.com/v1/item/T4_Cape%20ITEM.png',
                    'description' => 'Соло PvP/PvE активность с вторжениями игроков и сокровищами.',
                    'gallery' => [
                        'https://assets.albiononline.com/uploads/media/default/media/638cc8a50d338716219900.jpg',
                        'https://assets.albiononline.com/uploads/media/default/media/611fc4c656b0a164431214.jpg',
                    ],
                ],
                [
                    'name' => 'Avalonian Roads',
                    'icon' => 'https://render.albiononline.com/v1/item/T8_Bag.png',
                    'description' => 'Извилистые дорожки для групп, где можно найти хейты, сундуки и данжи.',
                    'gallery' => [
                        'https://assets.albiononline.com/uploads/media/default/media/5f1a0bc63f7a4421696362.jpg',
                        'https://assets.albiononline.com/uploads/media/default/media/5f1a0bc90d0d4421696364.jpg',
                    ],
                ],
                [
                    'name' => 'Hellgates 2v2',
                    'icon' => 'https://render.albiononline.com/v1/item/T6_TOKEN_SPECIAL_FAME.png',
                    'description' => 'Интенсивные дуэли пар в огненных аренах, сочетающие PvP и PvE.',
                    'gallery' => [
                        'https://assets.albiononline.com/uploads/media/default/media/5fbdcb936e6de125306799.jpg',
                        'https://assets.albiononline.com/uploads/media/default/media/5fbdcb946e6de125306800.jpg',
                    ],
                ],
            ];

            $builds = [
                [
                    'name' => 'Claymore Solo PvP',
                    'description' => 'Мобильный дуэльный билд с мощным инициационным контролем.',
                    'tags' => ['PvP', 'Solo', 'Burst'],
                ],
                [
                    'name' => 'Warbow Kiting',
                    'description' => 'Стрельба с дистанции, замедления и безопасный урон.',
                    'tags' => ['PvP', 'Solo', 'Kiting'],
                ],
                [
                    'name' => 'Holy Staff Group Heal',
                    'description' => 'Поддержка группы с сильным исцелением и зачисткой дебаффов.',
                    'tags' => ['PvE', 'Group', 'Support'],
                ],
                [
                    'name' => 'Greataxe Fame Farm',
                    'description' => 'Быстрый зачистной билд для сплит-пулов и фарма серебра.',
                    'tags' => ['PvE', 'Group', 'Farm'],
                ],
                [
                    'name' => 'Cursed Skull ZvZ',
                    'description' => 'Массовый урон по площади и проклятия для больших боев.',
                    'tags' => ['PvP', 'Group', 'ZvZ'],
                ],
            ];

            return compact('mobs', 'weaponBranches', 'armors', 'contents', 'builds');
        });

        return view('wiki', $data);
    }
}
