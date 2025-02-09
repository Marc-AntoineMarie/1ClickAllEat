<?php

namespace Database\Factories;

use App\Models\Restaurants;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantsFactory extends Factory
{
    protected $model = Restaurants::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->name,
            'description' => $this->faker->text,
            'place_max' => $this->faker->numberBetween(1, 100),
            'place_occuper' => $this->faker->numberBetween(1, 100),
            'place_disponible' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl,
        ];
    }
}