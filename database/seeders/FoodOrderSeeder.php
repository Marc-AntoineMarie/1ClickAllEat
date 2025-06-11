<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodOrder;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Item;

class FoodOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Crée un client et un restaurant de test si besoin
        $client = User::where('role_id', 2)->first(); // suppose que 2 = client
        $restaurant = Restaurant::first();
        $items = Item::take(2)->get();

        if ($client && $restaurant && $items->count()) {
            $order = FoodOrder::create([
                'client_id' => $client->id,
                'restaurant_id' => $restaurant->id,
                'status' => 'pending',
                'total_price' => $items->sum('prix'),
            ]);

            foreach ($items as $item) {
                $order->orderItems()->create([
                    'item_id' => $item->id,
                    'quantity' => 1,
                    'price' => $item->prix,
                ]);
            }
        }
    }
}
