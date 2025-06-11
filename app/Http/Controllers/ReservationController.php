<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        // Vérifier si l'utilisateur a le rôle restaurateur et bloquer l'accès
        if (Auth::user()->role && Auth::user()->role->name === 'restaurateur') {
            return redirect()->route('restaurants.index')
                ->with('error', 'Les restaurateurs ne peuvent pas faire de réservations. Veuillez utiliser un compte client pour cette action.');
        }

        $validated = $request->validate([
            'table_id' => ['required', 'exists:restaurant_tables,id'],
            'date_reservation' => ['required', 'date', 'after:now'],
            'heure_reservation' => ['required', 'date_format:H:i'],
        ]);

        $table = RestaurantTable::findOrFail($validated['table_id']);

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour réserver une table.');
        }

        // Concaténer date et heure pour le créneau complet
        $dateTime = $validated['date_reservation'] . ' ' . $validated['heure_reservation'];

        // Vérifier la disponibilité de la table pour ce créneau précis
        $exists = $table->reservations()
            ->where('date_reservation', $dateTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Cette table est déjà réservée pour ce créneau.');
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => $dateTime,
            'status' => 'pending',
        ]);

        // Création automatique d'une commande liée à la réservation
        $order = new \App\Models\FoodOrder([
            'client_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'status' => 'pending',
            'total_price' => 0
        ]);
        // Si tu ajoutes une colonne table_id à food_orders plus tard, tu pourras réassocier ici
        $order->save();

        // Envoi de l'email de confirmation
        \Mail::to(Auth::user()->email)->send(new \App\Mail\ReservationConfirmation($reservation));

        // Si l'utilisateur veut commander, rediriger vers la page de commande avec infos
        if ($request->has('commander')) {
            return redirect()->route('cart.index', [
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'date' => $validated['date_reservation'],
                'heure' => $validated['heure_reservation'],
            ]);
        }

        // Rediriger vers la page du restaurant avec le paramètre pour ouvrir la modale de commande
        return redirect()->route('restaurants.show', ['restaurant' => $restaurant->id, 'reservation' => 'success']);
    }

    /**
     * Afficher les réservations de l'utilisateur connecté.
     */
    public function index()
    {
        $reservations = Reservation::with(['restaurant', 'table'])
            ->where('user_id', Auth::id())
            ->orderBy('date_reservation', 'asc')
            ->get();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Annuler une réservation.
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }
        $reservation->status = 'cancelled';
        $reservation->save();
        return redirect()->back()->with('success', 'Réservation annulée.');
    }
}
