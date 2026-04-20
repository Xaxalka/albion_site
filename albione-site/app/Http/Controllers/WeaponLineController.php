<?php

namespace App\Http\Controllers;

use App\Models\WeaponLine;
use App\Models\Skill;
use Illuminate\Support\Facades\Cache;

class WeaponLineController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $data = Cache::remember("weapon-lines.index:{$locale}", now()->addMinutes(10), function () use ($locale) {
            $lines = WeaponLine::with(['weapons' => function ($query) {
                    $query->orderBy('name');
                }])->orderBy('name')->get();

            $quickBranches = [
                'axe' => $locale === 'ru' ? 'Боевые топоры и артефактные варианты.' : 'Battleaxes and artifact variants.',
                'sword' => $locale === 'ru' ? 'Мечи для мобильности и фронтлайна.' : 'Swords for mobility and frontline fights.',
                'mace' => $locale === 'ru' ? 'Булавы с контролем и инициированием.' : 'Maces focused on control and engage.',
                'hammer' => $locale === 'ru' ? 'Молоты для оглушений и танкования.' : 'Hammers built for stuns and tanking.',
                'crossbow' => $locale === 'ru' ? 'Арбалеты с давлением и бурстом.' : 'Crossbows with pressure and burst.',
                'war-gloves' => $locale === 'ru' ? 'Боевые перчатки для комбо и мобильности.' : 'War gloves for combos and mobility.',
                'bow' => $locale === 'ru' ? 'Луки для кайта и дистанционного давления.' : 'Bows for kiting and ranged pressure.',
                'dagger' => $locale === 'ru' ? 'Кинжалы для соло-пиков и burst.' : 'Daggers for solo picks and burst.',
                'quarterstaff' => $locale === 'ru' ? 'Посохи бойца для контроля и дизейблов.' : 'Quarterstaves for control and disruption.',
                'spear' => $locale === 'ru' ? 'Копья для гибридного давления.' : 'Spears for flexible hybrid pressure.',
                'arcane-staff' => $locale === 'ru' ? 'Мистические посохи для саппорта и utility.' : 'Arcane staves for support and utility.',
                'cursed-staff' => $locale === 'ru' ? 'Проклятые посохи для DoT и антихила.' : 'Cursed staves for DoT and anti-heal.',
                'fire-staff' => $locale === 'ru' ? 'Огненные посохи для сильного AoE-урона.' : 'Fire staves for heavy AoE damage.',
                'frost-staff' => $locale === 'ru' ? 'Морозные посохи для кайта и контроля.' : 'Frost staves for kiting and control.',
                'holy-staff' => $locale === 'ru' ? 'Священные посохи для лечения группы.' : 'Holy staves for group healing.',
                'nature-staff' => $locale === 'ru' ? 'Природные посохи для sustain-хила.' : 'Nature staves for sustain healing.',
                'shapeshifter-staff' => $locale === 'ru' ? 'Посохи оборотня для гибридных форм.' : 'Shapeshifter staves for hybrid forms.',
            ];

            return compact('lines', 'quickBranches');
        });

        return view('weapon-lines.index', $data);
    }

    public function show(string $slug)
    {
        $locale = app()->getLocale();

        $line = Cache::remember("weapon-lines.show:{$slug}:{$locale}", now()->addMinutes(10), function () use ($slug) {
            return WeaponLine::with(['weapons' => fn ($query) => $query->orderBy('name')])
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $branch = $line->branch;
        $skills = $branch
            ? Skill::where('branch_id', $branch->id)->orderBy('sort')->get()->groupBy('slot')
            : collect();

        $skillGroups = [
            'Q' => $skills->get('Q', collect()),
            'W' => $skills->get('W', collect()),
            'E' => $skills->get('E', collect()),
        ];

        return view('weapon-lines.show', compact('line', 'skillGroups'));
    }
}
