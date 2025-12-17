<?php

namespace App\Http\Controllers;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthController extends Controller
{

    public function create(): View
    {
        return view('auth.login', ['title' => 'Вход в аккаунт']);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $supportsUsername = Schema::hasColumn('users', 'username');
        $credentials = $request->validated();

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($loginField === 'username' && ! $supportsUsername) {
            $loginField = 'email';
        }

        $loginPayload = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (! Auth::attempt($loginPayload)) {
            return back()
                ->withErrors(['login' => 'Неверный логин или пароль'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        $redirectRoute = auth()->user()?->isAdmin()
            ? route('admin.dashboard')
            : route('wiki');

        return redirect()
            ->intended($redirectRoute)
            ->with('status', 'Добро пожаловать!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Вы вышли из системы.');
    }
}
