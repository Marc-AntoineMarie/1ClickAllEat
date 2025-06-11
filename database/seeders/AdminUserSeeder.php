<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifie si un administrateur existe déjà
        $adminExists = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->exists();
        
        if (!$adminExists) {
            // Récupère le rôle admin
            $adminRole = Role::where('name', 'admin')->first();
            
            if ($adminRole) {
                // Crée un utilisateur administrateur par défaut
                User::create([
                    'name' => 'Admin',
                    'email' => 'admin@1clickalleat.com',
                    'password' => Hash::make('admin123'),
                    'role_id' => $adminRole->id
                ]);
                
                $this->command->info('Utilisateur administrateur créé avec succès!');
                $this->command->info('Email: admin@1clickalleat.com');
                $this->command->info('Mot de passe: admin123');
            } else {
                $this->command->error('Le rôle "admin" n\'existe pas. Veuillez exécuter d\'abord le RoleSeeder.');
            }
        } else {
            $this->command->info('Un utilisateur administrateur existe déjà.');
        }
    }
}
