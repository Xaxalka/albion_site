<?php

namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;

class VerificationCodeService
{
    public function send(User $user): EmailVerificationCode
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $verificationCode = EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => CarbonImmutable::now()->addMinutes(30),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return $verificationCode;
    }
}
