<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un utilisateur restaurateur
        $owner = User::create([
            'name' => 'Restaurant Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'role' => 'restaurateur'
        ]);

        // Créer un client test
        $client = User::create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client'
        ]);

        // Créer quelques restaurants
        $restaurants = [
            [
                'name' => 'La Belle Époque',
                'description' => 'Restaurant gastronomique français',
                'address' => '15 rue de la Paix, Paris',
                'theme' => 'classic',
                'tables' => [
                    ['number' => '1', 'capacity' => 2, 'location' => 'intérieur'],
                    ['number' => '2', 'capacity' => 2, 'location' => 'intérieur'],
                    ['number' => '3', 'capacity' => 4, 'location' => 'intérieur'],
                    ['number' => '4', 'capacity' => 4, 'location' => 'terrasse'],
                    ['number' => '5', 'capacity' => 6, 'location' => 'terrasse'],
                ],
                'categories' => [
                    [
                        'name' => 'Entrées',
                        'items' => [
                            ['name' => 'Foie Gras', 'description' => 'Foie gras maison et sa confiture de figues', 'price' => 18.50],
                            ['name' => 'Soupe à l\'oignon', 'description' => 'Soupe à l\'oignon gratinée', 'price' => 12.00],
                        ]
                    ],
                    [
                        'name' => 'Plats',
                        'items' => [
                            ['name' => 'Magret de Canard', 'description' => 'Magret de canard et ses légumes de saison', 'price' => 26.00],
                            ['name' => 'Filet de Bœuf', 'description' => 'Filet de bœuf sauce au poivre', 'price' => 32.00],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Sushi Master',
                'description' => 'Les meilleurs sushis de la ville',
                'address' => '8 avenue des Champs-Élysées, Paris',
                'theme' => 'modern',
                'tables' => [
                    ['number' => '1', 'capacity' => 2, 'location' => 'bar'],
                    ['number' => '2', 'capacity' => 2, 'location' => 'bar'],
                    ['number' => '3', 'capacity' => 4, 'location' => 'salle'],
                    ['number' => '4', 'capacity' => 4, 'location' => 'salle'],
                    ['number' => '5', 'capacity' => 6, 'location' => 'salle'],
                ],
                'categories' => [
                    [
                        'name' => 'Sushis',
                        'items' => [
                            ['name' => 'Sushi Mix', 'description' => 'Assortiment de 12 sushis', 'price' => 22.00],
                            ['name' => 'Sashimi Mix', 'description' => 'Assortiment de sashimis', 'price' => 24.00],
                        ]
                    ],
                    [
                        'name' => 'Makis',
                        'items' => [
                            ['name' => 'California Roll', 'description' => '6 pièces', 'price' => 12.00],
                            ['name' => 'Dragon Roll', 'description' => '8 pièces', 'price' => 16.00],
                        ]
                    ]
                ]
            ]
        ];

        foreach ($restaurants as $restaurantData) {
            $tables = $restaurantData['tables'];
            $categories = $restaurantData['categories'];
            unset($restaurantData['tables'], $restaurantData['categories']);

            $restaurant = Restaurant::create(array_merge($restaurantData, [
                'owner_id' => $owner->id,
            ]));

            // Créer les tables
            foreach ($tables as $tableData) {
                Table::create(array_merge($tableData, [
                    'restaurant_id' => $restaurant->id,
                ]));
            }

            // Créer les catégories et les plats
            foreach ($categories as $categoryData) {
                $items = $categoryData['items'];
                unset($categoryData['items']);

                $category = Category::create(array_merge($categoryData, [
                    'restaurant_id' => $restaurant->id,
                ]));

                foreach ($items as $itemData) {
                    Item::create(array_merge($itemData, [
                        'category_id' => $category->id,
                    ]));
                }
            }
        }
    }
}
