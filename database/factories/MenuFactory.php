<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    public function definition(): array
    {
        $menuTypes = [
            'Menu du Midi', 'Menu du Soir', 'Menu Dégustation',
            'Menu Week-end', 'Menu Groupe', 'Carte des Vins'
        ];

        return [
            'name' => fake()->randomElement($menuTypes),
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
