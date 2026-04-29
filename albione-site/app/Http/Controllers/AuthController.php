<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureAdmin;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        if (auth()->user()?->isAdmin()) {
            return redirect()
                ->to($this->adminDashboardRoute($request))
                ->with('status', __('ui.auth.welcome_back'));
        }

        return redirect()
            ->intended(route('wiki'))
            ->with('status', __('ui.auth.welcome_back'));
    }

    private function adminDashboardRoute(Request $request): string
    {
        $accessKey = Str::random(40);

        $request->session()->put(EnsureAdmin::ACCESS_KEY_SESSION, $accessKey);

        return route('admin.dashboard', [
            EnsureAdmin::ROUTE_PARAMETER => $accessKey,
        ]);
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
