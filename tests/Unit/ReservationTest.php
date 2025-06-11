<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_reserve_a_table()
    {
        Mail::fake();
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);
        $this->actingAs($user);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => now()->addDays(2),
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'table_id' => $table->id,
        ]);
    }

    /** @test */
    public function cannot_reserve_same_table_same_datetime()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);
        $date = now()->addDays(3);
        Reservation::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => $date,
            'status' => 'pending',
        ]);
        $this->expectException(\Illuminate\Database\QueryException::class);
        Reservation::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => $date,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function a_user_can_cancel_a_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => now()->addDays(4),
            'status' => 'pending',
        ]);
        $reservation->status = 'cancelled';
        $reservation->save();
        $this->assertEquals('cancelled', $reservation->fresh()->status);
    }

    /** @test */
    public function confirmation_email_is_sent_on_reservation()
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'test@example.com']);
        $restaurant = Restaurant::factory()->create();
        $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);
        $this->actingAs($user);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'date_reservation' => now()->addDays(5),
            'status' => 'pending',
        ]);
        Mail::to($user->email)->send(new ReservationConfirmation($reservation));
        Mail::assertSent(ReservationConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
