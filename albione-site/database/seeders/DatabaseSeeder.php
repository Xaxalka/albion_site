<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ArmorSeeder;
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
        $testUser = [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ];

        $adminUser = [
            'name' => 'Admin',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ];

        $xaxalkaUser = [
            'name' => 'xaxalka',
            'password' => Hash::make('asddsa123321'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ];

        if ($supportsUsername) {
            $testUser['username'] = 'testuser';
            $adminUser['username'] = 'admin';
            $xaxalkaUser['username'] = 'xaxalka';
        }

        // Ensure baseline accounts keep their admin status even if they already exist
        User::updateOrCreate(['email' => 'test@example.com'], $testUser);
        User::updateOrCreate(['email' => 'admin@example.com'], $adminUser);
        User::updateOrCreate(['email' => 'xaxalka@example.com'], $xaxalkaUser);

        $this->call([
            WeaponSeeder::class,
            ArmorSeeder::class,
        ]);
    }
}
