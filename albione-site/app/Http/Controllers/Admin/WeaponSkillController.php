<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Weapon;
use App\Models\WeaponSkill;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class WeaponSkillController extends Controller
{
    public function edit(int $weaponId)
    {
        $weapon = Weapon::with('weaponSkill.media')->findOrFail($weaponId);
        $skill = $weapon->weaponSkill ?: new WeaponSkill(['weapon_id' => $weapon->id]);

        return view('admin.weapon-skills.edit', compact('weapon', 'skill'));
    }

    public function update(Request $request, int $weaponId)
    {
        $weapon = Weapon::with('weaponSkill.media')->findOrFail($weaponId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
            'media_upload' => ['nullable', 'file', 'mimes:png,jpg,jpeg,gif', 'max:5120'],
            'media_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $skill = $weapon->weaponSkill
            ? tap($weapon->weaponSkill)->update($validated)
            : $weapon->weaponSkill()->create($validated);

        $this->storeMediaIfPresent($skill, $request);

        return redirect()->route('admin.weapons.edit', $weapon->id)->with('status', 'Unique E skill saved.');
    }

    /**
     * Persist uploaded file or external URL to the skill_media table.
     */
    protected function storeMediaIfPresent(WeaponSkill $skill, Request $request): void
    {
        /** @var UploadedFile|null $file */
        $file = $request->file('media_upload');

        if ($file) {
            $path = $file->store('skill-media', 'public');

            $skill->media()->create([
                'path' => $path,
                'disk' => 'public',
                'original_name' => $file->getClientOriginalName(),
                'is_private' => false,
            ]);
        }

        $url = $request->input('media_url');

        if ($url) {
            $skill->media()->create([
                'path' => $url,
                'disk' => 'url',
                'original_name' => null,
                'is_private' => false,
            ]);
        }
    }
}
