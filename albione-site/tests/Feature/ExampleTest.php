<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated users can view the homepage', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
});

test('guests are redirected to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
