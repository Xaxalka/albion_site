<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\WeaponSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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

        // Create baseline users without overwriting manually managed roles
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'email_verified_at' => now(),
                'username' => $supportsUsername ? 'testuser' : null,
                'is_admin' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'admin',
                'email_verified_at' => now(),
                'username' => $supportsUsername ? 'admin' : null,
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'xaxalka@example.com'],
            [
                'name' => 'xaxalka',
                'password' => 'asddsa123321',
                'email_verified_at' => now(),
                'username' => $supportsUsername ? 'xaxalka' : null,
                'is_admin' => true,
            ]
        );

        $this->call([
            WeaponSeeder::class,
        ]);
    }
}
