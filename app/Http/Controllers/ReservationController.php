<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function create(Restaurant $restaurant)
    {
        return view('reservations.create', compact('restaurant'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'guests_count' => 'required|integer|min:1|max:20',
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500'
        ]);

        // Vérifier la disponibilité des tables
        $availableTables = $restaurant->getAvailableTablesForDateTime(
            $validated['reservation_date'],
            $validated['reservation_time'],
            $validated['guests_count']
        );

        if ($availableTables->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['message' => 'Désolé, aucune table n\'est disponible pour cette date et cette heure avec ce nombre de convives.']);
        }

        // Sélectionner la plus petite table disponible qui peut accueillir le groupe
        $selectedTable = $availableTables->first();

        // Créer la réservation
        $reservation = new Reservation([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'table_id' => $selectedTable->id,
            'guests_count' => $validated['guests_count'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'notes' => $validated['notes'],
            'status' => 'pending'
        ]);

        $reservation->save();

        return redirect()
            ->route('restaurants.show', $restaurant)
            ->with('success', 'Votre réservation a été créée avec succès ! Vous recevrez une confirmation par email.');
    }

    public function index()
    {
        $reservations = Auth::user()->reservations()
            ->with('restaurant')
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled'
        ]);

        $reservation->update($validated);

        return back()->with('success', 'La réservation a été mise à jour.');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        
        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'La réservation a été annulée.');
    }
}
