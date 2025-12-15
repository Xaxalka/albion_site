<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\WeaponSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $supportsUsername = Schema::hasColumn('users', 'username');

        // Avoid duplicate test user on repeated seeding
        $testData = [
            'name' => 'Test User',
            'password' => 'password',
            'email_verified_at' => now(),
        ];

        $adminData = [
            'name' => 'Admin',
            'password' => 'admin',
            'email_verified_at' => now(),
        ];

        if ($supportsUsername) {
            $testData['username'] = 'testuser';
            $adminData['username'] = 'admin';
        }

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            $testData
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            $adminData
        );

        $this->call([
            WeaponSeeder::class,
        ]);
    }
}
