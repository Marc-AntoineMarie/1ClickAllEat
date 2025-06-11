<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Afficher le panier
     */
    public function index()
    {
        $cart = Session::get('cart', [
            'restaurant_id' => null,
            'items' => [],
            'total' => 0
        ]);

        $restaurant = null;
        if ($cart['restaurant_id']) {
            $restaurant = Restaurant::find($cart['restaurant_id']);
        }

        return view('cart.index', compact('cart', 'restaurant'));
    }

    /**
     * Ajouter un item au panier
     */
    public function add(Request $request, Restaurant $restaurant, Item $item)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10']
        ]);

        $cart = Session::get('cart', [
            'restaurant_id' => null,
            'items' => [],
            'total' => 0
        ]);

        // Vérifier si le panier est vide ou si l'item est du même restaurant
        if (!$cart['restaurant_id'] || $cart['restaurant_id'] === $restaurant->id) {
            $cart['restaurant_id'] = $restaurant->id;
            
            // Ajouter ou mettre à jour la quantité de l'item
            if (isset($cart['items'][$item->id])) {
                $cart['items'][$item->id]['quantity'] += $validated['quantity'];
                if ($cart['items'][$item->id]['quantity'] > 10) {
                    $cart['items'][$item->id]['quantity'] = 10;
                }
            } else {
                $cart['items'][$item->id] = [
                    'name' => $item->name,
                    'price' => $item->prix,
                    'quantity' => $validated['quantity']
                ];
            }

            // Recalculer le total
            $cart['total'] = 0;
            foreach ($cart['items'] as $cartItem) {
                $cart['total'] += $cartItem['price'] * $cartItem['quantity'];
            }

            Session::put('cart', $cart);

            $message = $validated['quantity'] > 1 
                ? $validated['quantity'] . ' × ' . $item->name . ' ajoutés au panier !'
                : $item->name . ' ajouté au panier !';

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Vous ne pouvez commander que d\'un seul restaurant à la fois.');
    }

    /**
     * Mettre à jour la quantité d'un item
     */
    public function update(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:10']
        ]);

        $cart = Session::get('cart');

        if ($validated['quantity'] === 0) {
            unset($cart['items'][$itemId]);
        } else {
            $cart['items'][$itemId]['quantity'] = $validated['quantity'];
        }

        // Si le panier est vide, le réinitialiser complètement
        if (empty($cart['items'])) {
            Session::forget('cart');
            return redirect()->route('cart.index')
                ->with('success', 'Panier vidé !');
        }

        // Recalculer le total
        $cart['total'] = 0;
        foreach ($cart['items'] as $item) {
            $cart['total'] += $item['price'] * $item['quantity'];
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Panier mis à jour !');
    }

    /**
     * Supprimer un article du panier
     */
    public function remove($itemId)
    {
        $cart = Session::get('cart');

        if (isset($cart['items'][$itemId])) {
            $itemName = $cart['items'][$itemId]['name'];
            unset($cart['items'][$itemId]);

            // Si le panier est vide, le réinitialiser complètement
            if (empty($cart['items'])) {
                Session::forget('cart');
                return redirect()->route('cart.index')
                    ->with('success', 'Article supprimé. Le panier est maintenant vide.');
            }

            // Recalculer le total
            $cart['total'] = 0;
            foreach ($cart['items'] as $item) {
                $cart['total'] += $item['price'] * $item['quantity'];
            }

            Session::put('cart', $cart);

            return redirect()->route('cart.index')
                ->with('success', $itemName . ' a été supprimé du panier.');
        }

        return redirect()->route('cart.index')
            ->with('error', 'Article introuvable dans le panier.');
    }

    /**
     * Vider le panier
     */
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')
            ->with('success', 'Panier vidé !');
    }

    /**
     * Afficher le formulaire de réservation
     */
    public function showCheckout()
    {
        $cart = Session::get('cart');
        
        if (!$cart || empty($cart['items'])) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $restaurant = Restaurant::findOrFail($cart['restaurant_id']);
        return view('cart.checkout', compact('cart', 'restaurant'));
    }

    /**
     * Procéder à la réservation
     */
    public function checkout(Request $request)
    {
        $cart = Session::get('cart');
        
        if (!$cart || empty($cart['items'])) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $restaurant = Restaurant::findOrFail($cart['restaurant_id']);

        // Valider les données de réservation
        $validated = $request->validate([
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'number_of_guests' => ['required', 'integer', 'min:1', 'max:12'],
            'table_id' => ['required', 'exists:restaurant_tables,id'],
            'special_requests' => ['nullable', 'string', 'max:500']
        ]);

        // Combiner la date et l'heure
        $dateReservation = $validated['reservation_date'] . ' ' . $validated['reservation_time'];

        // Vérifier que la table existe et est disponible
        $table = $restaurant->tables()->findOrFail($validated['table_id']);

        if (!$table->is_available) {
            return redirect()->route('cart.showCheckout')
                ->with('error', 'Désolé, cette table n\'est plus disponible.');
        }

        // Vérifier que la table a une capacité suffisante
        if ($table->capacity < $validated['number_of_guests']) {
            return redirect()->route('cart.showCheckout')
                ->with('error', 'Cette table n\'a pas une capacité suffisante pour votre groupe.');
        }

        // Créer la commande
        $order = $restaurant->orders()->create([
            'dateReservation' => $dateReservation,
            'montantTotal' => $cart['total'],
            'client_id' => Auth::id(),
            'table_id' => $table->id,
            'number_of_guests' => $validated['number_of_guests'],
            'special_requests' => $validated['special_requests'],
            'status' => 'pending'
        ]);

        // Ajouter les items à la commande
        foreach ($cart['items'] as $id => $item) {
            $order->items()->attach($id, [
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Marquer la table comme occupée
        $table->update(['is_available' => false]);

        // Vider le panier
        Session::forget('cart');

        return redirect()->route('orders.index')
            ->with('success', 'Votre réservation a été enregistrée avec succès !');
    }
}
