<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Skill;
use App\Models\WeaponLine;
use Illuminate\Http\Request;

class LineSkillController extends Controller
{
    public function create(int $weaponLineId)
    {
        $line = WeaponLine::findOrFail($weaponLineId);
        $branch = $line->branch;

        return view('admin.line-skills.create', compact('line', 'branch'));
    }

    public function store(Request $request, int $weaponLineId)
    {
        $line = WeaponLine::findOrFail($weaponLineId);
        $branch = $line->branch ?: Branch::create([
            'key' => $line->slug,
            'name' => $line->name,
            'description' => $line->description,
        ]);

        $validated = $request->validate([
            'slot' => ['required', 'in:Q,W,E'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'integer'],
        ]);

        $validated['sort'] = $validated['sort'] ?? 0;
        $branch->skills()->create($validated);

        return redirect()->route('admin.weapon-lines.edit', $line->id)->with('status', 'Skill added to line.');
    }

    public function edit(int $id)
    {
        $skill = Skill::with('branch')->findOrFail($id);
        $line = WeaponLine::where('slug', $skill->branch->key)->firstOrFail();

        return view('admin.line-skills.edit', compact('skill', 'line'));
    }

    public function update(Request $request, int $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'slot' => ['required', 'in:Q,W,E'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'integer'],
        ]);

        $validated['sort'] = $validated['sort'] ?? $skill->sort;
        $skill->update($validated);

        $line = WeaponLine::where('slug', $skill->branch->key)->first();

        return redirect()->route('admin.weapon-lines.edit', $line?->id)->with('status', 'Skill updated.');
    }
}
