<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'prix' => $this->faker->randomFloat(2, 5, 50),
            'description' => $this->faker->sentence(),
            'disponibility' => true,
            'restaurant_id' => Restaurant::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
