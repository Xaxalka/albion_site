<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\WeaponSeeder;
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
        // Avoid duplicate test user on repeated seeding
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'username' => 'testuser', 'password' => 'password']
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'username' => 'admin', 'password' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'admin', 'password' => bcrypt('admin')]
        );

        $this->call([
            WeaponSeeder::class,
        ]);
    }
}
