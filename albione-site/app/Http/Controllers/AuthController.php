<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login', ['title' => 'Вход в админку']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $data['login'];
        $password = $data['password'];

        $credentialOptions = [
            ['email' => $login, 'password' => $password],
        ];

        if (Schema::hasColumn('users', 'username')) {
            $credentialOptions[] = ['username' => $login, 'password' => $password];
        }

        $credentialOptions[] = ['name' => $login, 'password' => $password];

        $attempted = false;

        foreach ($credentialOptions as $credentials) {
            $attempted = Auth::attempt($credentials);

            if ($attempted) {
                break;
            }
        }

        if (! $attempted) {
            return back()
                ->withErrors(['login' => 'Неверный логин или пароль'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('admin.weapons.index'))
            ->with('status', 'Добро пожаловать в панель управления.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('wiki')->with('status', 'Вы вышли из системы.');
    }
}
