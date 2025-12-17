<?php

namespace App\Http\Controllers;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthenticationService;
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

    public function store(LoginRequest $request, AuthenticationService $authenticationService): RedirectResponse
    {
        $supportsUsername = Schema::hasColumn('users', 'username');
        $credentials = $request->validated();

        $user = $authenticationService->login(
            $credentials['login'],
            $credentials['password'],
        );

        if (! $user) {
            return back()
                ->withErrors(['login' => 'Неверный логин или пароль'])
                ->onlyInput('login');
        }

        if ($supportsUsername) {
            session(['login_field' => str_contains($credentials['login'], '@') ? 'email' : 'username']);
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('wiki'))
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
