<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Menu;
use App\Models\Item;
use App\Models\Rating;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Database\Seeders\RestaurantSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@clickneat.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Créer quelques restaurateurs
        User::factory()->count(5)->create([
            'role' => 'restaurateur'
        ]);

        // Créer des clients
        User::factory()->count(20)->create([
            'role' => 'client'
        ]);

        $this->call([
            RestaurantSeeder::class,
        ]);
    }
}
