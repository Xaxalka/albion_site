<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MobController extends Controller
{
    public function index(Request $request)
    {
        $tier = $request->filled('tier') ? $request->integer('tier') : null;
        $faction = $request->string('faction')->toString();
        $attackType = $request->string('attack_type')->toString();
        $dangerState = $request->string('danger_state')->toString();

        $filters = [
            'tier' => $tier ?: 'all',
            'faction' => $faction !== '' ? $faction : 'all',
            'attack_type' => $attackType !== '' ? $attackType : 'all',
            'danger_state' => $dangerState !== '' ? $dangerState : 'all',
        ];

        $cacheKey = 'mobs.index:'.http_build_query($filters);

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($tier, $faction, $attackType, $dangerState) {
            $query = Mob::query();

            if ($tier !== null) {
                $query->where('tier', $tier);
            }

            if ($faction !== '') {
                $query->where('faction', $faction);
            }

            if ($attackType !== '') {
                $query->where('attack_type', $attackType);
            }

            if ($dangerState !== '') {
                $query->where('danger_state', $dangerState);
            }

            return [
                'mobs' => $query
                    ->withCount(['spells', 'lootEntries'])
                    ->orderBy('tier')
                    ->orderBy('name')
                    ->get(),
                'tiers' => Mob::query()->whereNotNull('tier')->distinct()->orderBy('tier')->pluck('tier'),
                'factions' => Mob::query()->whereNotNull('faction')->where('faction', '!=', '')->distinct()->orderBy('faction')->pluck('faction'),
                'attackTypes' => Mob::query()->whereNotNull('attack_type')->where('attack_type', '!=', '')->distinct()->orderBy('attack_type')->pluck('attack_type'),
                'dangerStates' => Mob::query()->whereNotNull('danger_state')->where('danger_state', '!=', '')->distinct()->orderBy('danger_state')->pluck('danger_state'),
                'selectedTier' => $tier,
                'selectedFaction' => $faction,
                'selectedAttackType' => $attackType,
                'selectedDangerState' => $dangerState,
            ];
        });

        return view('mobs.index', $data);
    }

    public function show(string $slug)
    {
        $mob = Cache::remember("mobs.show:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return Mob::with(['spells', 'lootEntries'])->where('slug', $slug)->firstOrFail();
        });

        return view('mobs.show', compact('mob'));
    }
}
