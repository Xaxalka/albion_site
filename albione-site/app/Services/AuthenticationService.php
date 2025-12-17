<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthenticationService
{
    private ?bool $supportsUsername = null;

    public function login(string $login, string $password): ?User
    {
        $supportsUsername = $this->supportsUsername();

        $user = User::query()
            ->where('email', $login)
            ->when($supportsUsername, fn ($query) => $query->orWhere('username', $login))
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        Auth::login($user);

        return $user;
    }

    public function register(array $attributes): User
    {
        $supportsUsername = $this->supportsUsername();

        return DB::transaction(function () use ($attributes, $supportsUsername) {
            $payload = [
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password']),
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

    private function supportsUsername(): bool
    {
        return $this->supportsUsername ??= Schema::hasColumn('users', 'username');
    }
}
