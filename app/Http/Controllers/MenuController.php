<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $menus = $restaurant->menus()->with('items')->get();
        return view('menus.index', compact('restaurant', 'menus'));
    }

    public function create(Restaurant $restaurant)
    {
        $items = $restaurant->items()->get();
        return view('menus.create', compact('restaurant', 'items'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'is_daily' => 'boolean',
            'promotion' => 'nullable|numeric|min:0|max:100',
            'items' => 'nullable|array',
            'items.*' => 'exists:items,id',
        ]);
        $menu = $restaurant->menus()->create([
            'name' => $validated['name'],
            'date' => $validated['date'] ?? null,
            'is_daily' => $validated['is_daily'] ?? false,
            'promotion' => $validated['promotion'] ?? null,
        ]);
        if (!empty($validated['items'])) {
            $menu->items()->sync($validated['items']);
        }
        return redirect()->route('restaurants.menus.index', $restaurant)->with('success', 'Menu créé.');
    }

    public function edit(Restaurant $restaurant, Menu $menu)
    {
        $items = $restaurant->items()->get();
        $menu->load('items');
        return view('menus.edit', compact('restaurant', 'menu', 'items'));
    }

    public function update(Request $request, Restaurant $restaurant, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'is_daily' => 'boolean',
            'promotion' => 'nullable|numeric|min:0|max:100',
            'items' => 'nullable|array',
            'items.*' => 'exists:items,id',
        ]);
        $menu->update([
            'name' => $validated['name'],
            'date' => $validated['date'] ?? null,
            'is_daily' => $validated['is_daily'] ?? false,
            'promotion' => $validated['promotion'] ?? null,
        ]);
        $menu->items()->sync($validated['items'] ?? []);
        return redirect()->route('restaurants.menus.index', $restaurant)->with('success', 'Menu mis à jour.');
    }

    public function destroy(Restaurant $restaurant, Menu $menu)
    {
        $menu->delete();
        return redirect()->route('restaurants.menus.index', $restaurant)->with('success', 'Menu supprimé.');
    }
}
