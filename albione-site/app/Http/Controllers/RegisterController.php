<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthenticationService;
use Illuminate\Http\RedirectResponse;
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

    public function store(RegisterRequest $request, AuthenticationService $authenticationService): RedirectResponse
    {
        $user = $authenticationService->register($request->validated());

        $request->session()->regenerate();

        return redirect()->intended(route('wiki'))
            ->with('status', 'Регистрация прошла успешно. Добро пожаловать!');
    }
}
