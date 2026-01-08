<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArmorItem;
use App\Models\ArmorSkill;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ArmorSkillController extends Controller
{
    public function edit(int $armorId)
    {
        $armor = ArmorItem::with('armorSkills.media')->findOrFail($armorId);
        $skills = $armor->armorSkills;

        return view('admin.armor-skills.edit', compact('armor', 'skills'));
    }

    public function create(int $armorId)
    {
        $armor = ArmorItem::findOrFail($armorId);
        $skill = new ArmorSkill(['armor_item_id' => $armor->id]);

        return view('admin.armor-skills.create', compact('armor', 'skill'));
    }

    public function store(Request $request, int $armorId)
    {
        $armor = ArmorItem::findOrFail($armorId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
        ]);

        $skill = $armor->armorSkills()->create($validated);

        return redirect()->route('admin.armor-items.edit', $armor->id)
            ->with('status', 'Armor skill created successfully.');
    }

    public function update(Request $request, int $skillId)
    {
        $skill = ArmorSkill::findOrFail($skillId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
        ]);

        $skill->update($validated);

        return redirect()->route('admin.armor-items.edit', $skill->armor_item_id)
            ->with('status', 'Armor skill updated successfully.');
    }

    public function destroy(int $skillId)
    {
        $skill = ArmorSkill::findOrFail($skillId);
        $armorId = $skill->armor_item_id;

        $skill->delete();

        return redirect()->route('admin.armor-items.edit', $armorId)
            ->with('status', 'Armor skill deleted successfully.');
    }
}
