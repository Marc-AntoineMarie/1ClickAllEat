<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider la table des rôles avant insertion
        DB::table('roles')->truncate();
        
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
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
