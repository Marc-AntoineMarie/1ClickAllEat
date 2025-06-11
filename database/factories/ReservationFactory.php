<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $restaurant = Restaurant::factory()->create();
        $table = RestaurantTable::factory()->create([
            'restaurant_id' => $restaurant->id,
        ]);
        return [
            'user_id' => User::factory(),
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => $this->faker->dateTimeBetween('+1 days', '+2 weeks'),
            'status' => $this->faker->randomElement(['pending', 'confirmed']),
        ];
    }
}
