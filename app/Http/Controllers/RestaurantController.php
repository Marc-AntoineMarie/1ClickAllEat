<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupérer toutes les catégories pour le filtre
        // Construction de la requête de base
        $query = Restaurant::query()->with('owner');

        // Filtre par nom
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Filtre note minimale
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->input('min_rating');
            $query->whereHas('ratings', function($q) use ($minRating) {
                $q->where('score', '>=', $minRating);
            });
        }

        // Filtre adresse/ville
        if ($request->filled('adresse')) {
            $query->where('adresse', 'LIKE', '%' . $request->input('adresse') . '%');
        }

        // Tri
        switch ($request->input('sort')) {
            case 'rating_desc':
                // Trier par moyenne de note décroissante
                $query->withAvg('ratings', 'score')->orderByDesc('ratings_avg_score');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'recent':
                $query->orderByDesc('created_at');
                break;
            default:
                $query->orderBy('name');
        }

        $restaurants = $query->get();

        return view('restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', [
    'restaurant' => $restaurant->load([
        'items',
        'tables',
        'ratings.user', // charge aussi l'utilisateur de chaque avis
    ])->loadAvg('ratings', 'score')
    ->loadCount('ratings')
]);
    }

    public function create()
    {
        if (! Gate::allows('create-restaurant')) {
            abort(403, 'Non autorisé à créer un restaurant.');
        }

        return view('restaurants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Gate::allows('create-restaurant')) {
            abort(403, 'Non autorisé à créer un restaurant.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'opening_hours' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        // Adapter les noms de champs pour correspondre à la base de données
        $restaurantData = [
            'name' => $validated['name'],
            'adresse' => $validated['address'],
            'description' => $validated['description'],
            'openHours' => $validated['opening_hours'],
            'capacity' => $validated['capacity'],
        ];

        $restaurant = new Restaurant($restaurantData);
        $restaurant->owner_id = Auth::id();
        $restaurant->save();

        return redirect()->route('restaurateur.dashboard.show', $restaurant)
            ->with('success', 'Restaurant créé avec succès!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à modifier ce restaurant.');
        }

        return view('restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        if (! Gate::allows('update', $restaurant)) {
            abort(403, 'Non autorisé à modifier ce restaurant.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'opening_hours' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        // Adapter les noms de champs pour correspondre à la base de données
        $restaurantData = [
            'name' => $validated['name'],
            'adresse' => $validated['address'],
            'description' => $validated['description'],
            'openHours' => $validated['opening_hours'],
            'capacity' => $validated['capacity'],
        ];

        $restaurant->update($restaurantData);

        return redirect()->route('restaurateur.dashboard.show', $restaurant)
            ->with('success', 'Restaurant mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        if (! Gate::allows('delete', $restaurant)) {
            abort(403, 'Non autorisé à supprimer ce restaurant.');
        }

        $restaurant->delete();

        return redirect()->route('restaurants.index')
            ->with('success', 'Restaurant supprimé avec succès!');
    }
}
