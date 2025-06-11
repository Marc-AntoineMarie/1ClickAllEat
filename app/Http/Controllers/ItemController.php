<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ItemController extends Controller
{
    /**
     * Permet de basculer la disponibilité d'un item (sold out / disponible).
     */
    public function toggleDisponibility(Restaurant $restaurant, Item $item)
    {
        if (! \Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }
        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }
        $item->disponibility = !$item->disponibility;
        $item->save();
        return redirect()->back()->with('success', 'Disponibilité du plat modifiée.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Restaurant $restaurant)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }

        $items = $restaurant->items()->with('category')->get();
        return view('items.index', compact('restaurant', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Restaurant $restaurant)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }

        $categories = Category::all();
        return view('items.create', compact('restaurant', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $itemData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'prix' => $validated['price'],
            'category_id' => $validated['category_id'],
        ];

        $item = new Item($itemData);
        $item->restaurant_id = $restaurant->id;
        $item->save();

        return redirect()->route('restaurateur.dashboard.show', $restaurant)
            ->with('success', 'Plat ajouté avec succès!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant, Item $item)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }

        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        $categories = Category::all();
        return view('items.edit', compact('restaurant', 'item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant, Item $item)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }

        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $itemData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'prix' => $validated['price'],
            'category_id' => $validated['category_id'],
        ];

        $item->update($itemData);

        return redirect()->route('restaurateur.dashboard.show', $restaurant)
            ->with('success', 'Plat mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant, Item $item)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à gérer les plats de ce restaurant.');
        }

        if ($item->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        $item->delete();

        return redirect()->route('restaurateur.dashboard.show', $restaurant)
            ->with('success', 'Plat supprimé avec succès!');
    }
}
