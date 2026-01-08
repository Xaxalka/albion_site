<?php

namespace App\Http\Controllers;

use App\Models\ArmorItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArmorController extends Controller
{
    public function index(Request $request)
    {
        $material = $request->string('material')->toString();
        $slot = $request->string('slot')->toString();

        $material = in_array($material, ArmorItem::MATERIALS, true) ? $material : null;
        $slot = in_array($slot, ArmorItem::SLOTS, true) ? $slot : null;

        $materialKey = $material ?: 'all';
        $slotKey = $slot ?: 'all';
        $cacheKey = "armor.index:material={$materialKey}:slot={$slotKey}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($material, $slot) {
            $query = ArmorItem::query();

            if ($material) {
                $query->where('material', $material);
            }

            if ($slot) {
                $query->where('slot', $slot);
            }

            $items = $query
                ->orderByRaw("CASE material WHEN 'cloth' THEN 1 WHEN 'leather' THEN 2 WHEN 'plate' THEN 3 ELSE 99 END")
                ->orderByRaw("CASE slot WHEN 'chest' THEN 1 WHEN 'head' THEN 2 WHEN 'feet' THEN 3 ELSE 99 END")
                ->orderBy('name')
                ->get();

            $materialsToShow = $material ? [$material] : ArmorItem::MATERIALS;
            $slotsToShow = $slot ? [$slot] : ArmorItem::SLOTS;

            return [
                'items' => $items,
                'itemsByMaterialSlot' => $items->groupBy(['material', 'slot']),
                'materialsToShow' => $materialsToShow,
                'slotsToShow' => $slotsToShow,
                'selectedMaterial' => $material,
                'selectedSlot' => $slot,
            ];
        });

        return view('armor.index', $data);
    }

    public function show(string $slug)
    {
        $item = Cache::remember("armor.show:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return ArmorItem::with('armorSkills.media')->where('slug', $slug)->firstOrFail();
        });

        return view('armor.show', compact('item'));
    }
}
