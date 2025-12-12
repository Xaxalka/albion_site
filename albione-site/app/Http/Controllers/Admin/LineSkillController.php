<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LineSkill;
use App\Models\WeaponLine;
use Illuminate\Http\Request;

class LineSkillController extends Controller
{
    public function create(int $weaponLineId)
    {
        $line = WeaponLine::findOrFail($weaponLineId);

        return view('admin.line-skills.create', compact('line'));
    }

    public function store(Request $request, int $weaponLineId)
    {
        $line = WeaponLine::findOrFail($weaponLineId);

        $validated = $request->validate([
            'slot' => ['required', 'in:Q,W,Passive'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
        ]);

        $line->lineSkills()->create($validated);

        return redirect()->route('admin.weapon-lines.edit', $line->id)->with('status', 'Skill added to line.');
    }

    public function edit(int $id)
    {
        $skill = LineSkill::with('weaponLine')->findOrFail($id);

        return view('admin.line-skills.edit', compact('skill'));
    }

    public function update(Request $request, int $id)
    {
        $skill = LineSkill::findOrFail($id);

        $validated = $request->validate([
            'slot' => ['required', 'in:Q,W,Passive'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
        ]);

        $skill->update($validated);

        return redirect()->route('admin.weapon-lines.edit', $skill->weapon_line_id)->with('status', 'Skill updated.');
    }
}
