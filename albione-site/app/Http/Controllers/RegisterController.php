<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\RegistrationVerificationCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class RegisterController extends Controller
{
    private const PENDING_REGISTRATION_KEY = 'auth.pending_registration';

    private const CODE_TTL_MINUTES = 10;

    public function create(): View
    {
        return view('auth.register', [
            'title' => __('ui.auth.register_title'),
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $code = (string) random_int(100000, 999999);

        $request->session()->put(self::PENDING_REGISTRATION_KEY, [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES)->toISOString(),
        ]);

        try {
            $this->sendVerificationCode($attributes['email'], $code);
        } catch (Throwable $exception) {
            $request->session()->forget(self::PENDING_REGISTRATION_KEY);

            Log::channel('auth')->error('Registration verification email failed.', [
                'email' => $attributes['email'],
                'ip' => $request->ip(),
                'error' => $exception->getMessage(),
            ]);

            return back()
                ->withInput($request->only(['name', 'email']))
                ->withErrors(['email' => __('ui.auth.verification_send_failed')]);
        }

        return redirect()->route('register.verify')
            ->with('status', __('ui.auth.verification_sent'));
    }

    public function verifyForm(): RedirectResponse|View
    {
        $pendingRegistration = session(self::PENDING_REGISTRATION_KEY);

        if (! $pendingRegistration) {
            return redirect()->route('register');
        }

        return view('auth.verify-registration', [
            'title' => __('ui.auth.verify_title'),
            'email' => $pendingRegistration['email'],
        ]);
    }

    public function verify(\Illuminate\Http\Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $pendingRegistration = $request->session()->get(self::PENDING_REGISTRATION_KEY);

        if (! $pendingRegistration) {
            return redirect()->route('register')
                ->withErrors(['code' => __('ui.auth.verification_missing')]);
        }

        if (now()->greaterThan(Date::parse($pendingRegistration['expires_at']))) {
            $request->session()->forget(self::PENDING_REGISTRATION_KEY);

            return redirect()->route('register')
                ->withErrors(['code' => __('ui.auth.verification_expired')]);
        }

        if (! Hash::check($validated['code'], $pendingRegistration['code_hash'])) {
            return back()
                ->withErrors(['code' => __('ui.auth.verification_invalid')]);
        }

        if (User::where('email', $pendingRegistration['email'])->exists()) {
            $request->session()->forget(self::PENDING_REGISTRATION_KEY);

            return redirect()->route('register')
                ->withErrors(['email' => __('validation.unique', ['attribute' => __('ui.auth.email')])]);
        }

        /** @var User $user */
        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'password' => $pendingRegistration['password'],
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        Auth::login($user);
        $this->writeSqlRecord($pendingRegistration, $user);

        Log::channel('auth')->info('User registered.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->forget(self::PENDING_REGISTRATION_KEY);
        $request->session()->regenerate();

        return redirect()->intended(route('wiki'))
            ->with('status', __('ui.auth.registered'));
    }

    private function sendVerificationCode(string $email, string $code): void
    {
        Mail::to($email)->send(new RegistrationVerificationCode($code, self::CODE_TTL_MINUTES));
    }

    private function writeSqlRecord(array $attributes, User $user): void
    {
        $sqlPath = database_path('user_registrations.sql');
        File::ensureDirectoryExists(dirname($sqlPath));

        if (! File::exists($sqlPath)) {
            File::put($sqlPath, '-- User registration records (hashed passwords)'.PHP_EOL.PHP_EOL);
        }

        $columns = ['name', 'email', 'password', 'created_at', 'updated_at'];
        $createdAt = $user->created_at ?: Date::now();
        $updatedAt = $user->updated_at ?: $createdAt;
        $values = [
            $this->quote($attributes['name']),
            $this->quote($attributes['email']),
            $this->quote($user->password),
            $this->quote($createdAt->toDateTimeString()),
            $this->quote($updatedAt->toDateTimeString()),
        ];

        $statement = sprintf(
            'INSERT INTO users (%s) VALUES (%s);%s',
            implode(', ', $columns),
            implode(', ', $values),
            PHP_EOL
        );

        File::append($sqlPath, $statement);
    }

    private function quote(?string $value): string
    {
        $sanitized = str_replace("'", "''", (string) $value);

        return "'{$sanitized}'";
    }
}
