<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RestaurantTableController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $restaurant->load(['tables' => function ($query) {
            $query->withCount(['orders as is_available' => function ($query) {
                $query->whereIn('status', ['pending', 'confirmed'])
                    ->where('dateReservation', '>=', now());
            }])
            ->with(['currentOrder' => function ($query) {
                $query->whereIn('status', ['pending', 'confirmed'])
                    ->where('dateReservation', '>=', now());
            }]);
        }]);

        return view('restaurateur.tables.index', compact('restaurant'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('restaurant_tables', 'number')
                    ->where('restaurant_id', $restaurant->id)
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:12']
        ]);

        $restaurant->tables()->create($validated);

        return redirect()->back()->with('success', 'Table ajoutée avec succès.');
    }

    public function update(Request $request, Restaurant $restaurant, RestaurantTable $table)
    {
        if ($table->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        if ($restaurant->owner_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('restaurant_tables', 'number')
                    ->where('restaurant_id', $restaurant->id)
                    ->ignore($table->id)
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:12']
        ]);

        $table->update($validated);

        return redirect()->back()->with('success', 'Table mise à jour avec succès.');
    }

    public function destroy(Restaurant $restaurant, RestaurantTable $table)
    {
        if ($table->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        if ($restaurant->owner_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier si la table a des réservations en cours
        if ($table->currentOrder) {
            return redirect()->back()->with('error', 'Impossible de supprimer une table qui a des réservations en cours.');
        }

        $table->delete();

        return redirect()->back()->with('success', 'Table supprimée avec succès.');
    }
}
