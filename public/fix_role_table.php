<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Role;

echo "<h1>Correction de la structure de la table roles</h1>";
echo "<pre>";

// Modifier la colonne permission pour la passer en VARCHAR au lieu de DOUBLE
try {
    echo "Modification de la structure de la table roles...\n";
    Schema::table('roles', function (Blueprint $table) {
        $table->string('permission')->change();
    });
    echo "✓ Table modifiée avec succès!\n\n";
} catch (\Exception $e) {
    echo "ERREUR lors de la modification de la table: " . $e->getMessage() . "\n";
    
    // Solution alternative: recréer la table
    try {
        echo "\nTentative de recréation de la table...\n";
        Schema::dropIfExists('roles');
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('permission');
            $table->timestamps();
        });
        echo "✓ Table recréée avec succès!\n\n";
    } catch (\Exception $e2) {
        echo "ERREUR lors de la recréation de la table: " . $e2->getMessage() . "\n";
        exit;
    }
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
        // Insertion directe via Query Builder avec guillemets pour les chaînes
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
echo "<p><a href='/1ClickAllEat/public/setup_roles.php'>Aller au script de configuration des rôles utilisateurs</a></p>";
