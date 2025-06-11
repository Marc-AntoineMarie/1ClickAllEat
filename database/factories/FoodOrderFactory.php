<?php

namespace Database\Factories;

use App\Models\FoodOrder;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodOrderFactory extends Factory
{
    protected $model = FoodOrder::class;

    public function definition()
    {
        return [
            'client_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'status' => 'pending',
            'total_price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
