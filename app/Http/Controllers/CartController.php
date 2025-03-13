<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with(['restaurant', 'table'])
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'guests_count' => 'required|integer|min:1',
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required',
            'table_id' => 'required|exists:tables,id',
            'selected_items' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $reservationTime = \Carbon\Carbon::parse($validated['reservation_date'] . ' ' . $validated['reservation_time']);

        $cartItem = CartItem::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'table_id' => $validated['table_id'],
            'guests_count' => $validated['guests_count'],
            'reservation_time' => $reservationTime,
            'selected_items' => $validated['selected_items'] ?? [],
            'notes' => $validated['notes'],
            'total_price' => $this->calculateTotalPrice($validated['selected_items'] ?? []),
        ]);

        return redirect()->route('cart.index')->with('success', 'Réservation ajoutée au panier');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Article supprimé du panier');
    }

    public function checkout()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with(['restaurant', 'table'])->get();
        $total = $cartItems->sum('total_price');

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::create([
            'amount' => $total * 100, // Stripe utilise les centimes
            'currency' => 'eur',
            'metadata' => [
                'user_id' => Auth::id(),
            ],
        ]);

        return view('cart.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'clientSecret' => $intent->client_secret,
        ]);
    }

    public function processPayment(Request $request)
    {
        try {
            DB::beginTransaction();

            $cartItems = CartItem::where('user_id', Auth::id())->with(['restaurant', 'table'])->get();

            foreach ($cartItems as $cartItem) {
                Reservation::create([
                    'user_id' => Auth::id(),
                    'restaurant_id' => $cartItem->restaurant_id,
                    'table_id' => $cartItem->table_id,
                    'guests_count' => $cartItem->guests_count,
                    'reservation_time' => $cartItem->reservation_time,
                    'notes' => $cartItem->notes,
                    'status' => 'confirmed',
                    'selected_items' => $cartItem->selected_items,
                    'total_price' => $cartItem->total_price,
                ]);
            }

            // Vider le panier
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Une erreur est survenue lors du traitement de votre paiement.'], 500);
        }
    }

    private function calculateTotalPrice($selectedItems)
    {
        $total = 0;
        foreach ($selectedItems as $item) {
            if (isset($item['quantity']) && isset($item['price'])) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }
}
