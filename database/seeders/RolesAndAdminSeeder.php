<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des rôles s'ils n'existent pas déjà
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrateur avec accès complet']
        );
        
        $restaurateurRole = Role::firstOrCreate(
            ['name' => 'restaurateur'],
            ['description' => 'Propriétaire de restaurant']
        );
        
        $clientRole = Role::firstOrCreate(
            ['name' => 'client'],
            ['description' => 'Client standard']
        );
        
        // Création d'un administrateur par défaut si aucun n'existe
        $adminExists = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@1clickalleat.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id
            ]);
            $this->command->info('Utilisateur administrateur créé avec succès!');
        }
    }
}
