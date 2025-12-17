<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'title' => 'Регистрация',
            'supportsUsername' => Schema::hasColumn('users', 'username'),
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $supportsUsername = Schema::hasColumn('users', 'username');

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

        $request->session()->regenerate();

        return redirect()->intended(route('wiki'))
            ->with('status', 'Регистрация прошла успешно. Добро пожаловать!');
    }

    private function writeSqlRecord(array $attributes, bool $supportsUsername, User $user): void
    {
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
        $values[] = $this->quote($attributes['password']);

        $columns[] = 'created_at';
        $columns[] = 'updated_at';
        $values[] = $this->quote(optional($user->created_at)->toDateTimeString());
        $values[] = $this->quote(optional($user->updated_at)->toDateTimeString());

        $statement = sprintf(
            "INSERT INTO users (%s) VALUES (%s);%s",
            implode(', ', $columns),
            implode(', ', $values),
            PHP_EOL
        );

        $sqlPath = database_path('user_registrations.sql');
        File::append($sqlPath, $statement);
    }

    private function quote(?string $value): string
    {
        $sanitized = str_replace("'", "''", (string) $value);

        return "'{$sanitized}'";
    }
}
