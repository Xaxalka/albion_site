<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Weapon;
use App\Models\WeaponLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $line = WeaponLine::findOrFail($validated['weapon_line_id']);
        $branch = $line->branch ?: Branch::create([
            'key' => $line->slug,
            'name' => $line->name,
            'description' => $line->description,
        ]);

        $weapon = Weapon::create(array_merge($validated, ['branch_id' => $branch->id]));

        Cache::forget('weapons.index:all');
        Cache::forget("weapons.index:line={$weapon->weapon_line_id}");

        return redirect()->route('admin.weapons.index')->with('status', 'Weapon created.');
    }

    public function edit(int $id)
    {
        $weapon = Weapon::with(['weaponLine', 'branch.skills'])->findOrFail($id);
        $weaponLines = WeaponLine::orderBy('name')->get();

        return view('admin.weapons.edit', compact('weapon', 'weaponLines'));
    }

    public function update(Request $request, int $id)
    {
        $weapon = Weapon::findOrFail($id);
        $oldLineId = $weapon->weapon_line_id;
        $oldSlug = $weapon->slug;

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

        $line = WeaponLine::findOrFail($validated['weapon_line_id']);
        $branch = $line->branch ?: Branch::create([
            'key' => $line->slug,
            'name' => $line->name,
            'description' => $line->description,
        ]);

        $weapon->update(array_merge($validated, ['branch_id' => $branch->id]));

        Cache::forget('weapons.index:all');
        Cache::forget("weapons.index:line={$oldLineId}");
        Cache::forget("weapons.index:line={$weapon->weapon_line_id}");
        Cache::forget("weapons.show:{$oldSlug}");
        Cache::forget("weapons.show:{$weapon->slug}");

        return redirect()->route('admin.weapons.index')->with('status', 'Weapon updated.');
    }

    public function destroy(int $id)
    {
        $weapon = Weapon::findOrFail($id);
        $lineId = $weapon->weapon_line_id;
        $slug = $weapon->slug;
        $weapon->delete();

        Cache::forget('weapons.index:all');
        Cache::forget("weapons.index:line={$lineId}");
        Cache::forget("weapons.show:{$slug}");

        return redirect()->route('admin.weapons.index')->with('status', 'Weapon deleted.');
    }
}
