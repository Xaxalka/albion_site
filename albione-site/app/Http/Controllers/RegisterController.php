<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\VerificationCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private readonly VerificationCodeService $verificationCodes)
    {
    }

    public function create(): View
    {
        return view('auth.register', [
            'title' => 'Регистрация',
            'supportsUsername' => Schema::hasColumn('users', 'username'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $supportsUsername = Schema::hasColumn('users', 'username');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];

        if ($supportsUsername) {
            $rules['username'] = ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'];
        }

        $data = $request->validate($rules);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if ($supportsUsername) {
            $userData['username'] = $data['username'];
        }

        $user = User::create($userData);

        $this->verificationCodes->send($user);

        $request->session()->put('verification_email', $user->email);

        return redirect()->route('verification.notice')
            ->with('status', 'Мы отправили код подтверждения на вашу почту.');
    }
}
