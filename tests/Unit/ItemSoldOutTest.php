<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSoldOutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_item_can_be_marked_as_sold_out()
    {
        $restaurant = Restaurant::factory()->create();
        $item = Item::factory()->create(['restaurant_id' => $restaurant->id, 'disponibility' => true]);
        $this->assertFalse($item->isSoldOut());
        $item->disponibility = false;
        $item->save();
        $this->assertTrue($item->fresh()->isSoldOut());
    }

    /** @test */
    public function cannot_order_a_sold_out_item()
    {
        $restaurant = Restaurant::factory()->create();
        $item = Item::factory()->create(['restaurant_id' => $restaurant->id, 'disponibility' => false]);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('orders.store'), [
            'restaurant_id' => $restaurant->id,
            'items' => [
                ['id' => $item->id, 'quantity' => 1]
            ],
            'delivery_address' => '1 rue du test',
        ]);
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('food_orders', [
            'restaurant_id' => $restaurant->id,
            'client_id' => $user->id
        ]);
    }
}
