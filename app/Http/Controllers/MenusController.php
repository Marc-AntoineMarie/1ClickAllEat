<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenusController extends Controller
{
    public function index(Request $request)
    {
        // Si un restaurant_id est fourni, filtrer les menus de ce restaurant
        if ($request->has('restaurant_id')) {
            $restaurant = Restaurant::findOrFail($request->restaurant_id);
            $menus = $restaurant->menus;
            return view('menus.index', compact('menus', 'restaurant'));
        }
        
        // Sinon, afficher tous les menus (pour l'admin)
        $menus = Menu::with('restaurant')->get();
        return view('menus.index', compact('menus'));
    }

    public function create(Request $request)
    {
        $restaurants = Restaurant::all();
        $selectedRestaurant = null;
        
        if ($request->has('restaurant_id')) {
            $selectedRestaurant = Restaurant::findOrFail($request->restaurant_id);
        }
        
        return view('menus.create', compact('restaurants', 'selectedRestaurant'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        
        Menu::create($validated);
        
        return redirect()->route('menus.index', ['restaurant_id' => $request->restaurant_id])
            ->with('success', 'Menu créé avec succès !');
    }

    public function show(Menu $menu)
    {
        $menu->load(['restaurant', 'categories.items']);
        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $restaurants = Restaurant::all();
        return view('menus.edit', compact('menu', 'restaurants'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        
        $menu->update($validated);
        
        return redirect()->route('menus.index', ['restaurant_id' => $menu->restaurant_id])
            ->with('success', 'Menu mis à jour avec succès !');
    }

    public function destroy(Menu $menu)
    {
        $restaurantId = $menu->restaurant_id;
        $menu->delete();
        
        return redirect()->route('menus.index', ['restaurant_id' => $restaurantId])
            ->with('success', 'Menu supprimé avec succès !');
    }
}