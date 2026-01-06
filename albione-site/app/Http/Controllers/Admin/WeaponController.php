<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Weapon;
use App\Models\WeaponLine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeaponController extends Controller
{
    public function index()
    {
        $q = request('q');

        $weapons = Weapon::with('weaponLine')
            ->when($q, function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends(['q' => $q]);

        return view('admin.weapons.index', compact('weapons'));
    }

    public function create()
    {
        $weaponLines = WeaponLine::orderBy('name')->get();

        return view('admin.weapons.create', compact('weaponLines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'weapon_line_id' => ['required', 'exists:weapon_lines,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:weapons,slug'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        Weapon::create($validated);

        return redirect()->route('admin.weapons.index')->with('status', 'Weapon created.');
    }

    public function edit(int $id)
    {
        $weapon = Weapon::with('weaponSkill.media')->findOrFail($id);
        $weaponLines = WeaponLine::orderBy('name')->get();

        return view('admin.weapons.edit', compact('weapon', 'weaponLines'));
    }

    public function update(Request $request, int $id)
    {
        $weapon = Weapon::findOrFail($id);

        $validated = $request->validate([
            'weapon_line_id' => ['required', 'exists:weapon_lines,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:weapons,slug,' . $weapon->id],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $weapon->update($validated);

        return redirect()->route('admin.weapons.index')->with('status', 'Weapon updated.');
    }

    public function destroy(int $id)
    {
        $weapon = Weapon::findOrFail($id);
        $weapon->delete();

        return redirect()->route('admin.weapons.index')->with('status', 'Weapon deleted.');
    }
}
