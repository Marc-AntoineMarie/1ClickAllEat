<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Entrées',
                'description' => 'Pour bien commencer le repas',
                'type' => 'plat',
            ],
            [
                'name' => 'Plats principaux',
                'description' => 'Les spécialités de la maison',
                'type' => 'plat',
            ],
            [
                'name' => 'Desserts',
                'description' => 'Pour finir en douceur',
                'type' => 'plat',
            ],
            [
                'name' => 'Boissons',
                'description' => 'Pour accompagner votre repas',
                'type' => 'boisson',
            ],
            [
                'name' => 'Menus',
                'description' => 'Nos formules complètes',
                'type' => 'menu',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
