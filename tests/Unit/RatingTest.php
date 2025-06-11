<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_leave_a_rating_for_a_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $rating = Rating::factory()->create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'score' => 4,
            'comment' => 'Super expérience !',
        ]);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'score' => 4,
        ]);
        $this->assertEquals($user->id, $rating->user->id);
        $this->assertEquals($restaurant->id, $rating->restaurant->id);
    }

    /** @test */
    public function a_user_cannot_leave_multiple_ratings_for_the_same_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        Rating::factory()->create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'score' => 3,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        // Tentative de doublon
        Rating::factory()->create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'score' => 5,
        ]);
    }

    /** @test */
    public function it_calculates_the_average_rating_for_a_restaurant()
    {
        $restaurant = Restaurant::factory()->create();
        $users = User::factory()->count(3)->create();
        Rating::factory()->create([
            'user_id' => $users[0]->id,
            'restaurant_id' => $restaurant->id,
            'score' => 4,
        ]);
        Rating::factory()->create([
            'user_id' => $users[1]->id,
            'restaurant_id' => $restaurant->id,
            'score' => 5,
        ]);
        Rating::factory()->create([
            'user_id' => $users[2]->id,
            'restaurant_id' => $restaurant->id,
            'score' => 3,
        ]);

        $average = Rating::where('restaurant_id', $restaurant->id)->avg('score');
        $this->assertEquals(4.0, $average);
    }
}
