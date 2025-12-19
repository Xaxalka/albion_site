<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'title' => 'Регистрация',
            'supportsUsername' => User::supportsUsername(),
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $supportsUsername = User::supportsUsername();

        $payload = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ];

        if ($supportsUsername && ! empty($attributes['username'])) {
            $payload['username'] = $attributes['username'];
        }

        /** @var User $user */
        $user = User::create($payload);
        $user->forceFill(['email_verified_at' => now()])->save();

        Auth::login($user);
        $this->writeSqlRecord($attributes, $supportsUsername, $user);

        Log::channel('auth')->info('User registered.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->regenerate();

        return redirect()->intended(route('wiki'))
            ->with('status', 'Регистрация прошла успешно. Добро пожаловать!');
    }

    private function writeSqlRecord(array $attributes, bool $supportsUsername, User $user): void
    {
        $sqlPath = database_path('user_registrations.sql');
        File::ensureDirectoryExists(dirname($sqlPath));

        if (! File::exists($sqlPath)) {
            File::put($sqlPath, "-- User registration records (hashed passwords)" . PHP_EOL . PHP_EOL);
        }

        $columns = ['name', 'email'];
        $values = [
            $this->quote($attributes['name']),
            $this->quote($attributes['email']),
        ];

        if ($supportsUsername && ! empty($attributes['username'])) {
            $columns[] = 'username';
            $values[] = $this->quote($attributes['username']);
        }

        $columns[] = 'password';
        $values[] = $this->quote($user->password);

        $columns[] = 'created_at';
        $columns[] = 'updated_at';
        $createdAt = $user->created_at ?: Date::now();
        $updatedAt = $user->updated_at ?: $createdAt;
        $values[] = $this->quote($createdAt->toDateTimeString());
        $values[] = $this->quote($updatedAt->toDateTimeString());

        $statement = sprintf(
            "INSERT INTO users (%s) VALUES (%s);%s",
            implode(', ', $columns),
            implode(', ', $values),
            PHP_EOL
        );

        File::append($sqlPath, $statement);
    }

    private function quote(?string $value): string
    {
        $sanitized = str_replace("'", "''", (string) $value);

        return "'{$sanitized}'";
    }
}
