<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Role;

echo "<h1>Création des rôles pour 1ClickAllEat</h1>";
echo "<pre>";

// Vérifier d'abord la connexion à la base de données
try {
    $connection = DB::connection()->getPdo();
    $database = DB::connection()->getDatabaseName();
    
    echo "✓ Connexion à la base de données réussie: {$database}\n\n";
} catch (\Exception $e) {
    die("ERREUR DE CONNEXION: " . $e->getMessage());
}

// Voir si la table roles existe et ses colonnes
try {
    $columns = DB::getSchemaBuilder()->getColumnListing('roles');
    echo "✓ Table 'roles' trouvée avec les colonnes: " . implode(', ', $columns) . "\n\n";
} catch (\Exception $e) {
    die("ERREUR avec la table roles: " . $e->getMessage());
}

// Créer les rôles
$roles = [
    [
        'name' => 'client',
        'description' => 'Utilisateur standard pouvant passer des commandes',
        'permission' => 'client',
    ],
    [
        'name' => 'restaurateur',
        'description' => 'Propriétaire de restaurant',
        'permission' => 'restaurateur',
    ],
    [
        'name' => 'admin',
        'description' => 'Administrateur du site',
        'permission' => 'admin',
    ]
];

// Vider la table des rôles
try {
    DB::table('roles')->delete();
    echo "✓ Table 'roles' vidée avec succès\n\n";
} catch (\Exception $e) {
    echo "AVERTISSEMENT: Impossible de vider la table des rôles: " . $e->getMessage() . "\n\n";
}

// Créer les rôles un par un avec gestion d'erreur détaillée
echo "Création des rôles:\n";
foreach ($roles as $roleData) {
    try {
        // Insertion directe via Query Builder pour éviter les problèmes avec Eloquent
        $id = DB::table('roles')->insertGetId([
            'name' => $roleData['name'],
            'description' => $roleData['description'],
            'permission' => $roleData['permission'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "✓ Rôle '{$roleData['name']}' créé avec l'ID {$id}\n";
    } catch (\Exception $e) {
        echo "✗ ERREUR lors de la création du rôle '{$roleData['name']}': " . $e->getMessage() . "\n";
        
        // Afficher plus de détails sur l'erreur
        echo "   Détails: " . print_r($roleData, true) . "\n";
    }
}

// Vérifier que les rôles ont été créés
try {
    $rolesInDb = DB::table('roles')->get();
    echo "\nRôles actuellement dans la base de données:\n";
    
    if ($rolesInDb->isEmpty()) {
        echo "AUCUN RÔLE TROUVÉ!\n";
    } else {
        foreach ($rolesInDb as $role) {
            echo "ID: {$role->id}, Nom: {$role->name}, Description: {$role->description}\n";
        }
    }
} catch (\Exception $e) {
    echo "ERREUR lors de la récupération des rôles: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<p><a href='/1ClickAllEat/public'>Retour à l'accueil</a></p>";
