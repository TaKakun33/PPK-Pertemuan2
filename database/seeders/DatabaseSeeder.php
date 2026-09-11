<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin JARA',
            'email'    => 'admin@jara.test',
            'password' => 'password123',
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'User Biasa',
            'email'    => 'user@jara.test',
            'password' => 'password123',
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Kolaborator',
            'email'    => 'colab@jara.test',
            'password' => 'password123',
            'role'     => 'collaborator',
        ]);
    }
}
