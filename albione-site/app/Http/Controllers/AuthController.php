<?php

namespace App\Http\Controllers;

use App\Services\VerificationCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly VerificationCodeService $verificationCodes)
    {
    }

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

        $user = Auth::user();

        if ($user && is_null($user->email_verified_at)) {
            Auth::logout();

            $this->verificationCodes->send($user);
            $request->session()->put('verification_email', $user->email);

            return redirect()->route('verification.notice')
                ->withErrors(['login' => 'Подтвердите email перед входом. Мы отправили новый код.']);
        }

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
