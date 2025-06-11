<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Vérifier si l'utilisateur a déjà noté ce restaurant
        $existingRating = $restaurant->ratings()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRating) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà noté ce restaurant.');
        }

        $rating = new Rating([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'score' => $validated['score'],
            'comment' => $validated['comment'] ?? null,
        ]);

        $rating->save();

        return redirect()->back()
            ->with('success', 'Merci pour votre avis !');
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(Request $request, Restaurant $restaurant, Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $rating->update([
            'score' => $validated['score'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Votre avis a été mis à jour !');
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy(Restaurant $restaurant, Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            abort(403);
        }

        $rating->delete();

        return redirect()->back()
            ->with('success', 'Votre avis a été supprimé !');
    }
}
