<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Trouver le rôle employé ou le créer s'il n'existe pas
        $employeeRole = Role::firstOrCreate(['name' => 'employee'], [
            'name' => 'employee',
            'description' => 'Employé de restaurant'
        ]);

        // Créer un utilisateur avec le rôle d'employé
        User::create([
            'name' => 'Employee Test',
            'email' => 'employee@test.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
        ]);
    }
}
