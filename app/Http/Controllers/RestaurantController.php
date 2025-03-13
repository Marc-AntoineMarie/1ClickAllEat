<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
     * Display a listing of all restaurants.
     */
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('restaurants.index', compact('restaurants'));
    }

    /**
     * Display a listing of the restaurants for the authenticated restaurateur.
     */
    public function indexForRestaurateur()
    {
        $restaurants = Auth::user()->restaurants;
        return view('restaurateur.restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new restaurant.
     */
    public function create()
    {
        return view('restaurateur.restaurants.create');
    }

    /**
     * Store a newly created restaurant in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $restaurant = new Restaurant($validated);
        $restaurant->owner_id = Auth::id();
        $restaurant->save();

        return redirect()->route('restaurateur.restaurants.index')
            ->with('success', 'Restaurant créé avec succès.');
    }

    /**
     * Display the specified restaurant.
     */
    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified restaurant.
     */
    public function edit(Restaurant $restaurant)
    {
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        return view('restaurateur.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified restaurant in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $restaurant->update($validated);

        return redirect()->route('restaurateur.restaurants.index')
            ->with('success', 'Restaurant mis à jour avec succès.');
    }

    /**
     * Remove the specified restaurant from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $restaurant->delete();

        return redirect()->route('restaurateur.restaurants.index')
            ->with('success', 'Restaurant supprimé avec succès.');
    }
}