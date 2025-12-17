<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('users can login with either username or email on MySQL schema', function () {
    $user = User::where('email', 'xaxalka@example.com')->firstOrFail();

    $response = $this->post(route('login.store'), [
        'login' => 'xaxalka',
        'password' => 'asddsa123321',
    ]);

    $response->assertRedirect(route('wiki'));
    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'));

    $response = $this->post(route('login.store'), [
        'login' => 'xaxalka@example.com',
        'password' => 'asddsa123321',
    ]);

    $response->assertRedirect(route('wiki'));
    $this->assertAuthenticatedAs($user);
});

test('registration stores lowercased email and hashed password', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Новый Пользователь',
        'username' => 'newplayer',
        'email' => 'NEWPLAYER@Example.COM',
        'password' => 'StrongPass123',
        'password_confirmation' => 'StrongPass123',
    ]);

    $response->assertRedirect(route('wiki'));

    $user = User::where('username', 'newplayer')->first();

    expect($user)->not->toBeNull();
    expect($user->email)->toBe('newplayer@example.com');
    expect($user->email_verified_at)->not->toBeNull();
    expect(Hash::check('StrongPass123', $user->password))->toBeTrue();

    $this->assertAuthenticatedAs($user);
});

test('invalid credentials are rejected without authentication', function () {
    $response = $this->from(route('login'))
        ->post(route('login.store'), [
            'login' => 'admin',
            'password' => 'wrong-password',
        ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors(['login']);
    $this->assertGuest();
});
