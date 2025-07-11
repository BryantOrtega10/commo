<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Bryant Ortega',
            'email' => 'bryant@mdccolombia.com',
            'password' => Hash::make("1900"),
            'role' => 'superadmin'
        ]);

        User::create([
            'name' => 'Jorge',
            'email' => 'jorge@siempreseguro.com',
            'password' => Hash::make("J0rg3*"),
            'role' => 'superadmin'
        ]);

        User::create([
            'name' => 'Noel',
            'email' => 'Noel@siempreseguro.com',
            'password' => Hash::make("No3l*"),
            'role' => 'superadmin'
        ]);

        User::create([
            'name' => 'Mauricio',
            'email' => 'mauricio@siempreseguro.com',
            'password' => Hash::make("Maur1c10*"),
            'role' => 'superadmin'
        ]);

    }
}
