<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthController extends Controller
{

    public function create(): View
    {
        return view('auth.login', ['title' => __('ui.auth.login_title')]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $supportsUsername = User::supportsUsername();
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
            Log::channel('auth')->warning('Login failed.', [
                'login' => $credentials['login'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()
                ->withErrors(['login' => __('ui.auth.invalid_credentials')])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        Log::channel('auth')->info('Login succeeded.', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $redirectRoute = auth()->user()?->isAdmin()
            ? route('admin.dashboard')
            : route('wiki');

        return redirect()
            ->intended($redirectRoute)
            ->with('status', __('ui.auth.welcome_back'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Log::channel('auth')->info('Logout succeeded.', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', __('ui.auth.logged_out'));
    }
}
