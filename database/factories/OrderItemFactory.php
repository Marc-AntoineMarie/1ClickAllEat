<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\FoodOrder;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'order_id' => function () {
                return FoodOrder::factory()->create()->id;
            },
            'item_id' => Item::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
