<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArmorItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArmorController extends Controller
{
    public function index()
    {
        $q = request('q');

        $items = ArmorItem::query()
            ->when($q, function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends(['q' => $q]);

        return view('admin.armor-items.armor-list', compact('items'));
    }

    public function create()
    {
        return view('admin.armor-items.armor-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:armor_items,slug'],
            'material' => ['required', 'in:cloth,leather,plate'],
            'slot' => ['required', 'in:chest,head,feet'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        ArmorItem::create($validated);

        return redirect()->route('admin.armor-items.index')->with('status', 'Armor item created.');
    }

    public function edit(int $id)
    {
        $item = ArmorItem::with('armorSkills.media')->findOrFail($id);

        return view('admin.armor-items.armor-form', compact('item'));
    }

    public function update(Request $request, int $id)
    {
        $item = ArmorItem::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:armor_items,slug,' . $item->id],
            'material' => ['required', 'in:cloth,leather,plate'],
            'slot' => ['required', 'in:chest,head,feet'],
            'description' => ['nullable', 'string'],
            'author_notes' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $item->update($validated);

        return redirect()->route('admin.armor-items.index')->with('status', 'Armor item updated.');
    }

    public function destroy(int $id)
    {
        $item = ArmorItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.armor-items.index')->with('status', 'Armor item deleted.');
    }
}
