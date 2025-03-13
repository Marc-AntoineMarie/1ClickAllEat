<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(rand(2, 4), true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 8, 45),
            'available' => fake()->boolean(80),
            'preparation_time' => fake()->randomElement([15, 20, 25, 30, 35, 40]),
        ];
    }
}
