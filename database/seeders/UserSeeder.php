<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les IDs des rôles
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $restaurateurRoleId = Role::where('name', 'restaurateur')->first()->id;
        $clientRoleId = Role::where('name', 'client')->first()->id;

        // Créer un administrateur
        User::updateOrCreate(
            ['email' => 'admin@1clickalleat.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
            ]
        );

        // Créer des restaurateurs
        $restaurateurs = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean@restaurant.com',
                'password' => Hash::make('password'),
                'role_id' => $restaurateurRoleId,
            ],
            [
                'name' => 'Marie Martin',
                'email' => 'marie@restaurant.com',
                'password' => Hash::make('password'),
                'role_id' => $restaurateurRoleId,
            ],
            [
                'name' => 'Pierre Dubois',
                'email' => 'pierre@restaurant.com',
                'password' => Hash::make('password'),
                'role_id' => $restaurateurRoleId,
            ],
            [
                'name' => 'Sophie Lefebvre',
                'email' => 'sophie@restaurant.com',
                'password' => Hash::make('password'),
                'role_id' => $restaurateurRoleId,
            ],
        ];

        foreach ($restaurateurs as $restaurateur) {
            User::updateOrCreate(
                ['email' => $restaurateur['email']],
                [
                    'name' => $restaurateur['name'],
                    'password' => $restaurateur['password'],
                    'role_id' => $restaurateur['role_id'],
                ]
            );
        }

        // Créer des clients
        $clients = [
            [
                'name' => 'Client Test',
                'email' => 'client@test.com',
                'password' => Hash::make('password'),
                'role_id' => $clientRoleId,
            ],
            [
                'name' => 'Thomas Bernard',
                'email' => 'thomas@example.com',
                'password' => Hash::make('password'),
                'role_id' => $clientRoleId,
            ],
            [
                'name' => 'Julie Petit',
                'email' => 'julie@example.com',
                'password' => Hash::make('password'),
                'role_id' => $clientRoleId,
            ],
            [
                'name' => 'Nicolas Moreau',
                'email' => 'nicolas@example.com',
                'password' => Hash::make('password'),
                'role_id' => $clientRoleId,
            ],
            [
                'name' => 'Camille Roux',
                'email' => 'camille@example.com',
                'password' => Hash::make('password'),
                'role_id' => $clientRoleId,
            ],
        ];

        foreach ($clients as $client) {
            User::updateOrCreate(
                ['email' => $client['email']],
                [
                    'name' => $client['name'],
                    'password' => $client['password'],
                    'role_id' => $client['role_id'],
                ]
            );
        }
    }
}
