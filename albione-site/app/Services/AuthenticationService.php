<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthenticationService
{
    public function login(string $login, string $password): ?User
    {
        $supportsUsername = Schema::hasColumn('users', 'username');

        $loginField = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : ($supportsUsername ? 'username' : 'email');

        $credentials = [
            $loginField => $login,
            'password' => $password,
        ];

        $loggedIn = Auth::attempt($credentials);

        if (! $loggedIn && $supportsUsername && $loginField === 'username') {
            $loggedIn = Auth::attempt([
                'email' => $login,
                'password' => $password,
            ]);
        }

        if (! $loggedIn) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    public function register(array $attributes): User
    {
        $supportsUsername = Schema::hasColumn('users', 'username');

        return DB::transaction(function () use ($attributes, $supportsUsername) {
            $payload = [
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => $attributes['password'],
            ];

            if ($supportsUsername) {
                $payload['username'] = $attributes['username'];
            }

            /** @var User $user */
            $user = User::create($payload);
            $user->forceFill(['email_verified_at' => now()])->save();

            Auth::login($user);

            return $user;
        });
    }
}
