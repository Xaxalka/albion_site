<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('seeded admin password is hashed correctly', function () {
    $admin = User::where('email', 'xaxalka@example.com')->first();

    expect($admin)->not->toBeNull();
    expect($admin->username)->toBe('xaxalka');
    expect($admin->is_admin)->toBeTrue();
    expect(Hash::check('asddsa123321', $admin->password))->toBeTrue();
});

test('seeded admin can login using the provided credentials', function () {
    $admin = User::where('email', 'xaxalka@example.com')->first();

    $response = $this->post(route('login.store'), [
        'login' => 'xaxalka',
        'password' => 'asddsa123321',
    ]);

    $response->assertRedirect(route('wiki'));
    $this->assertAuthenticatedAs($admin);
});
