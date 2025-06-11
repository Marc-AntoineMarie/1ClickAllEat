<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer l'ID du rôle restaurateur
        $restaurateurRoleId = Role::where('name', 'restaurateur')->first()->id;
        
        // Récupérer les utilisateurs restaurateurs
        $restaurateurs = User::where('role_id', $restaurateurRoleId)->get();
        
        $restaurants = [
            [
                'name' => 'La Bella Italia',
                'description' => 'Authentique cuisine italienne avec des pâtes fraîches et des pizzas cuites au feu de bois.',
                'adresse' => '15 Rue des Italiens, 75002 Paris',
                'globalNote' => 4.5,
                'openHours' => 'Lun-Dim: 11h30-14h30, 18h30-22h30',
                'capacity' => 80,
            ],
            [
                'name' => 'Sushi Master',
                'description' => 'Le meilleur de la cuisine japonaise avec des sushis préparés par nos chefs experts.',
                'adresse' => '8 Avenue de Tokyo, 75008 Paris',
                'globalNote' => 4.7,
                'openHours' => 'Lun-Sam: 11h30-14h30, 18h30-22h30',
                'capacity' => 60,
            ],
            [
                'name' => 'Le Bistrot Français',
                'description' => 'Cuisine française traditionnelle dans un cadre chaleureux et authentique.',
                'adresse' => '22 Rue Saint-Michel, 75005 Paris',
                'globalNote' => 4.3,
                'openHours' => 'Mar-Dim: 12h00-14h30, 19h00-22h30',
                'capacity' => 100,
            ],
            [
                'name' => 'Spice of India',
                'description' => 'Découvrez les saveurs épicées et parfumées de l\'Inde avec nos plats authentiques.',
                'adresse' => '5 Rue des Épices, 75010 Paris',
                'globalNote' => 4.6,
                'openHours' => 'Lun-Dim: 12h00-14h30, 18h30-23h00',
                'capacity' => 70,
            ],
        ];

        // Associer chaque restaurant à un restaurateur
        foreach ($restaurants as $index => $restaurantData) {
            $restaurateur = $restaurateurs[$index % count($restaurateurs)];
            
            $restaurant = new Restaurant($restaurantData);
            $restaurant->owner_id = $restaurateur->id;
            $restaurant->save();
        }
    }
}
