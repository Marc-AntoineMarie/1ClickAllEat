<?php

namespace Database\Factories;

use App\Models\RestaurantTable;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantTableFactory extends Factory
{
    protected $model = RestaurantTable::class;

    public function definition()
    {
        return [
            'number' => $this->faker->unique()->numberBetween(1, 50),
            'capacity' => $this->faker->numberBetween(2, 10),
            'restaurant_id' => Restaurant::factory(),
        ];
    }
}
