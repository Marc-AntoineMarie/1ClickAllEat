<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $restaurants = $user->restaurants;
        $menus = Menu::whereIn('restaurant_id', $restaurants->pluck('id'))->get();
        return view('restaurateur.menus.index', compact('menus'));
    }

    public function create()
    {
        $user = Auth::user();
        $restaurants = $user->restaurants;
        return view('restaurateur.menus.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'restaurant_id' => 'required|exists:restaurants,id',
            'is_active' => 'boolean'
        ]);

        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        Menu::create($validated);

        return redirect()->route('menus.index')
            ->with('success', 'Menu créé avec succès.');
    }

    public function edit(Menu $menu)
    {
        if ($menu->restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $restaurants = Auth::user()->restaurants;
        return view('restaurateur.menus.edit', compact('menu', 'restaurants'));
    }

    public function update(Request $request, Menu $menu)
    {
        if ($menu->restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'restaurant_id' => 'required|exists:restaurants,id',
            'is_active' => 'boolean'
        ]);

        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $menu->update($validated);

        return redirect()->route('menus.index')
            ->with('success', 'Menu mis à jour avec succès.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $menu->delete();

        return redirect()->route('menus.index')
            ->with('success', 'Menu supprimé avec succès.');
    }
}
