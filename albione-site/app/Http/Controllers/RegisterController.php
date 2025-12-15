<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\VerificationCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private readonly VerificationCodeService $verificationCodes)
    {
    }

    public function create(): View
    {
        return view('auth.register', ['title' => 'Регистрация']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $this->verificationCodes->send($user);

        $request->session()->put('verification_email', $user->email);

        return redirect()->route('verification.notice')
            ->with('status', 'Мы отправили код подтверждения на вашу почту.');
    }
}
