<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'adresse' => $this->faker->address(),
            
            'description' => $this->faker->sentence(),
            'openHours' => '08:00-22:00',
            'capacity' => $this->faker->numberBetween(10, 200),
            'owner_id' => \App\Models\User::factory(),
                    ];
    }
}
