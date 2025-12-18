<?php

namespace App\Http\Controllers;

use App\Models\ArmorItem;
use Illuminate\Http\Request;

class ArmorController extends Controller
{
    public function index(Request $request)
    {
        $material = $request->string('material')->toString();
        $slot = $request->string('slot')->toString();

        $material = in_array($material, ArmorItem::MATERIALS, true) ? $material : null;
        $slot = in_array($slot, ArmorItem::SLOTS, true) ? $slot : null;

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
        $itemsByMaterialSlot = $items->groupBy(['material', 'slot']);

        return view('armor.index', [
            'items' => $items,
            'itemsByMaterialSlot' => $itemsByMaterialSlot,
            'materialsToShow' => $materialsToShow,
            'slotsToShow' => $slotsToShow,
            'selectedMaterial' => $material,
            'selectedSlot' => $slot,
        ]);
    }

    public function show(string $slug)
    {
        $item = ArmorItem::where('slug', $slug)->firstOrFail();

        return view('armor.show', compact('item'));
    }
}
