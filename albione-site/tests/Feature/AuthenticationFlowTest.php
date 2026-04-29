<?php

use App\Mail\RegistrationVerificationCode;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('users can login with either username or email on MySQL schema', function () {
    $user = User::where('email', 'xaxalka@example.com')->firstOrFail();

    $response = $this->post(route('login.store'), [
        'login' => 'xaxalka',
        'password' => 'asddsa123321',
    ]);

    expect($response->headers->get('Location'))->toMatch('#/admin/[A-Za-z0-9]{40}$#');
    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'));

    $response = $this->post(route('login.store'), [
        'login' => 'xaxalka@example.com',
        'password' => 'asddsa123321',
    ]);

    expect($response->headers->get('Location'))->toMatch('#/admin/[A-Za-z0-9]{40}$#');
    $this->assertAuthenticatedAs($user);
});

test('registration stores lowercased email and hashed password', function () {
    Mail::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Новый Пользователь',
        'email' => 'NEWPLAYER@Example.COM',
        'password' => 'StrongPass123',
        'password_confirmation' => 'StrongPass123',
    ]);

    $response->assertRedirect(route('register.verify'));
    $this->assertGuest();
    expect(User::where('email', 'newplayer@example.com')->exists())->toBeFalse();

    Mail::assertSent(RegistrationVerificationCode::class, function (RegistrationVerificationCode $mail) {
        return $mail->hasTo('newplayer@example.com')
            && preg_match('/^\d{6}$/', $mail->code) === 1;
    });

    /** @var RegistrationVerificationCode $mail */
    $mail = Mail::sent(RegistrationVerificationCode::class)->first();

    $response = $this->post(route('register.verify.store'), [
        'code' => $mail->code,
    ]);

    $response->assertRedirect(route('wiki'));

    $user = User::where('email', 'newplayer@example.com')->first();

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
