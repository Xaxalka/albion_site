<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeaponLine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class WeaponLineController extends Controller
{
    public function index()
    {
        $q = request('q');

        $lines = WeaponLine::withCount(['weapons', 'lineSkills'])
            ->when($q, function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(9)
            ->appends(['q' => $q]);

        return view('admin.weapon-lines.index', compact('lines'));
    }

    public function create()
    {
        return view('admin.weapon-lines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('weapon_lines', 'name'),
            ],
            'slug' => ['nullable', 'string', 'max:255', 'unique:weapon_lines,slug'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        WeaponLine::create($validated);

        return redirect()->route('admin.weapon-lines.index')->with('status', 'Weapon line created.');
    }

    public function edit(int $id)
    {
        $line = WeaponLine::with('lineSkills')->findOrFail($id);

        return view('admin.weapon-lines.edit', compact('line'));
    }

    public function update(Request $request, int $id)
    {
        $line = WeaponLine::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('weapon_lines', 'name')->ignore($line->id),
            ],
            'slug' => ['nullable', 'string', 'max:255', 'unique:weapon_lines,slug,' . $line->id],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $line->update($validated);

        return redirect()->route('admin.weapon-lines.index')->with('status', 'Weapon line updated.');
    }

    public function destroy(int $id)
    {
        $line = WeaponLine::findOrFail($id);
        $line->delete();

        return redirect()->route('admin.weapon-lines.index')->with('status', 'Weapon line deleted.');
    }
}
