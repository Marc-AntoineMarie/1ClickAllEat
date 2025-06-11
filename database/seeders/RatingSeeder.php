<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = Restaurant::all();
        $clientRoleId = \App\Models\Role::where('name', 'client')->first()->id;
        $clients = User::where('role_id', $clientRoleId)->get();

        // Générer des évaluations aléatoires pour chaque restaurant
        foreach ($restaurants as $restaurant) {
            // Limiter le nombre d'évaluations au nombre de clients disponibles
            $maxRatings = min(count($clients), 5); // Maximum 5 évaluations par restaurant
            
            // Sélectionner des clients aléatoires sans répétition
            $selectedClients = $clients->random($maxRatings);
            
            foreach ($selectedClients as $client) {
                // Générer une note entre 3 et 5 (plus de bonnes notes pour avoir des restaurants bien notés)
                $score = rand(3, 5);
                
                // Générer un commentaire en fonction de la note
                $comment = $this->generateComment($score);
                
                // Créer l'évaluation
                Rating::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'user_id' => $client->id,
                    ],
                    [
                        'score' => $score,
                        'comment' => $comment,
                        'created_at' => now()->subDays(rand(1, 60)),
                    ]
                );
            }
        }
    }

    /**
     * Générer un commentaire en fonction de la note
     */
    private function generateComment($score)
    {
        $comments = [
            5 => [
                "Excellent service et nourriture délicieuse ! Je recommande vivement.",
                "Une expérience culinaire exceptionnelle. Le personnel est très attentionné.",
                "Le meilleur restaurant de ce type que j'ai essayé. À ne pas manquer !",
                "Plats savoureux et service impeccable. J'y retournerai sans hésiter.",
                "Ambiance chaleureuse et cuisine de qualité. Parfait pour un dîner en famille ou entre amis.",
            ],
            4 => [
                "Très bon restaurant, service rapide et plats savoureux.",
                "Bonne expérience globale, quelques petits détails à améliorer mais je recommande.",
                "Cuisine savoureuse et personnel agréable. Prix raisonnables.",
                "Bon rapport qualité-prix. Les plats sont bien présentés et délicieux.",
                "Service efficace et nourriture de qualité. L'ambiance pourrait être améliorée.",
            ],
            3 => [
                "Restaurant correct mais sans plus. Les plats manquent un peu de saveur.",
                "Service un peu lent mais la nourriture est correcte.",
                "Qualité moyenne. Certains plats étaient bons, d'autres décevants.",
                "Expérience mitigée. Le cadre est agréable mais la cuisine est moyenne.",
                "Acceptable pour un repas rapide mais rien d'exceptionnel.",
            ],
        ];
        
        return $comments[$score][array_rand($comments[$score])];
    }
}
