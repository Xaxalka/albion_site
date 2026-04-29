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

    expect($response->headers->get('Location'))->toMatch('#/admin/[A-Za-z0-9]{40}$#');
    $this->assertAuthenticatedAs($admin);
});

test('seeded admin login trims accidental whitespace', function () {
    $admin = User::where('email', 'xaxalka@example.com')->first();

    $response = $this->post(route('login.store'), [
        'login' => '  xaxalka  ',
        'password' => '  asddsa123321  ',
    ]);

    expect($response->headers->get('Location'))->toMatch('#/admin/[A-Za-z0-9]{40}$#');
    $this->assertAuthenticatedAs($admin);
});

test('admin panel rejects direct fixed admin url', function () {
    $admin = User::where('email', 'xaxalka@example.com')->first();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertNotFound();
});

test('admin panel requires current session access key', function () {
    $admin = User::where('email', 'xaxalka@example.com')->first();

    $response = $this->post(route('login.store'), [
        'login' => 'xaxalka',
        'password' => 'asddsa123321',
    ]);

    $adminUrl = $response->headers->get('Location');

    $dashboardResponse = $this->get($adminUrl);

    $dashboardResponse->assertRedirect();
    expect($dashboardResponse->headers->get('Location'))->toMatch('#/admin/[A-Za-z0-9]{40}/weapon-lines$#');
    $this->get($dashboardResponse->headers->get('Location'))->assertOk();
    $this->get('/admin/'.str_repeat('a', 40))->assertNotFound();
});
