<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Services\VerificationCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(private readonly VerificationCodeService $verificationCodes)
    {
    }

    public function show(Request $request): View
    {
        $email = $request->session()->get('verification_email', old('email'));

        return view('auth.verify', [
            'title' => 'Подтверждение почты',
            'email' => $email,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Пользователь не найден.'])->withInput();
        }

        $verification = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $data['code'])
            ->where('expires_at', '>', Date::now())
            ->latest()
            ->first();

        if (! $verification) {
            return back()->withErrors(['code' => 'Неверный или просроченный код.'])->withInput();
        }

        $user->forceFill(['email_verified_at' => Date::now()])->save();

        EmailVerificationCode::where('user_id', $user->id)->delete();

        Auth::login($user);

        return redirect()->intended(route('wiki'))->with('status', 'Email успешно подтвержден.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Пользователь не найден.'])->withInput();
        }

        if ($user->email_verified_at) {
            return back()->with('status', 'Email уже подтвержден.')->withInput();
        }

        $this->verificationCodes->send($user);
        $request->session()->put('verification_email', $user->email);

        return back()->with('status', 'Новый код отправлен на вашу почту.');
    }
}
