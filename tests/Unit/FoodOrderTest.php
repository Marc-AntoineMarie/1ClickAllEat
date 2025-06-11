<?php

namespace Tests\Unit;

use App\Models\FoodOrder;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\OrderItem;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_food_order_belongs_to_a_client_and_a_restaurant()
    {
        $client = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $order = FoodOrder::create([
            'client_id' => $client->id,
            'restaurant_id' => $restaurant->id,
            'status' => 'pending',
            'total_price' => 20.5,
        ]);

        $this->assertInstanceOf(User::class, $order->client);
        $this->assertEquals($client->id, $order->client->id);
        $this->assertInstanceOf(Restaurant::class, $order->restaurant);
        $this->assertEquals($restaurant->id, $order->restaurant->id);
    }

    /** @test */
    public function a_food_order_can_have_many_order_items()
    {
        $order = FoodOrder::factory()->create();
        $item = Item::factory()->create();
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'quantity' => 2,
            'price' => 12.5,
        ]);

        $this->assertCount(1, $order->orderItems);
        $this->assertEquals($orderItem->id, $order->orderItems->first()->id);
    }

    /** @test */
    public function a_food_order_can_have_many_items_through_pivot()
    {
        $order = FoodOrder::factory()->create();
        $item1 = Item::factory()->create(['prix' => 5.0]);
        $item2 = Item::factory()->create(['prix' => 8.0]);

        $order->items()->attach($item1->id, ['quantity' => 1, 'price' => 5.0]);
        $order->items()->attach($item2->id, ['quantity' => 2, 'price' => 8.0]);

        $this->assertCount(2, $order->items);
        $this->assertEquals(5.0, $order->items[0]->pivot->price);
        $this->assertEquals(8.0, $order->items[1]->pivot->price);
    }
}
