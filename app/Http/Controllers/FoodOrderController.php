<?php

namespace App\Http\Controllers;

use App\Policies\FoodOrderPolicy;
use App\Models\FoodOrder;
use App\Models\Restaurant;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FoodOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role && Auth::user()->role->name === 'client') {
            $orders = Auth::user()->clientFoodOrders()->with(['restaurant', 'items', 'reservation', 'reservation.table'])->latest()->get();
            $reservations = \App\Models\Reservation::with(['restaurant', 'table'])
                ->where('user_id', Auth::id())
                ->orderBy('date_reservation', 'asc')
                ->get();
            return view('orders.index', compact('orders', 'reservations'));
        } elseif (Auth::user()->role && Auth::user()->role->name === 'restaurateur') {
            $orders = Auth::user()->restaurantFoodOrders()->with(['client', 'items', 'reservation', 'reservation.table'])->latest()->get();
            return view('orders.index', compact('orders'));
        } else {
            // Si l'utilisateur n'a pas de rôle assigné ou a un rôle inconnu
            $orders = collect(); // Collection vide
            $reservations = collect();
            return view('orders.index', compact('orders', 'reservations'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur a le rôle restaurateur et bloquer l'accès
        if (Auth::user()->role && Auth::user()->role->name === 'restaurateur') {
            return redirect()->route('restaurants.index')
                ->with('error', 'Les restaurateurs ne peuvent pas passer de commandes. Veuillez utiliser un compte client pour cette action.');
        }
        
        $validated = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'items' => ['required', 'array'],
            'table_id' => ['required', 'exists:restaurant_tables,id'],
            'date_reservation' => ['required', 'date', 'after:now'],
            'heure_reservation' => ['required', 'date_format:H:i'],
            'nb_personnes' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        // Adapter la structure des items (clé = id, valeur = [quantity])
        $itemIds = array_keys($validated['items']);
        $items = Item::findMany($itemIds);
        $selectedItems = [];
        foreach ($items as $item) {
            $qty = intval($validated['items'][$item->id]['quantity'] ?? 0);
            if ($qty > 0) {
                $selectedItems[] = [
                    'id' => $item->id,
                    'quantity' => $qty,
                    'price' => $item->effective_price,
                ];
            }
        }
        if (count($selectedItems) === 0) {
            return back()->with('error', 'Veuillez sélectionner au moins un plat.');
        }
        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        $table = \App\Models\RestaurantTable::findOrFail($validated['table_id']);

        // Vérifier la disponibilité de la table pour ce créneau précis
        $dateTime = $validated['date_reservation'] . ' ' . $validated['heure_reservation'];
        $exists = $table->reservations()
            ->where('date_reservation', $dateTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
        if ($exists) {
            return back()->with('error', 'Cette table est déjà réservée pour ce créneau.');
        }

        // Transaction pour garantir que tout est créé ou rien
        \Illuminate\Support\Facades\DB::beginTransaction();
        
        try {
            // Création de la commande
            $order = new FoodOrder([
                'client_id' => Auth::id(),
                'restaurant_id' => $restaurant->id,
                'status' => 'pending',
                'total_price' => array_sum(array_map(function($i) { return $i['quantity'] * $i['price']; }, $selectedItems)),
            ]);
            $order->save();
            
            // Attacher les items avec leur quantité
            foreach ($selectedItems as $itemData) {
                $order->items()->attach($itemData['id'], [
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);
            }
            
            // Création de la réservation de table associée
            $reservation = \App\Models\Reservation::create([
                'user_id' => Auth::id(),
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'date_reservation' => $dateTime,
                'nb_personnes' => $validated['nb_personnes'],
                'status' => 'pending',
                'food_order_id' => $order->id, // Lien vers la commande
            ]);
            
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('orders.index')->with('success', 'Commande et réservation créées avec succès!');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodOrder $order)
    {
        if (! Gate::allows('view', $order)) {
            abort(403);
        }

        $order->load(['restaurant', 'client', 'items', 'reservation', 'reservation.table']);
        return view('orders.show', compact('order'));
    }

    /**
     * Remove the specified order from storage (if not validated).
     */
    public function destroy(FoodOrder $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Impossible de supprimer une commande déjà validée.');
        }
        if (!Gate::allows('delete-order', $order)) {
            abort(403);
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodOrder $order)
    {
        if (! Gate::allows('update', $order)) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,accepted,preparing,ready,delivering,delivered,cancelled'],
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Statut de la commande mis à jour!');
    }

    /**
     * Cancel the order.
     */
    public function cancel(FoodOrder $order)
    {
        if (! Gate::allows('cancel', $order)) {
            abort(403);
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Commande annulée!');
    }
}
