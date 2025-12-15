<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $code)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Код подтверждения для Albion Codex')
            ->view('mail.verification-code')
            ->with(['code' => $this->code]);
    }
}
