<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supportsUsername = Schema::hasColumn('users', 'username');

        $loginRules = $supportsUsername
            ? ['required', 'string']
            : ['required', 'string', 'email'];

        return [
            'login' => $loginRules,
            'password' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'login' => trim((string) $this->input('login')), 
            'password' => trim((string) $this->input('password')),
        ]);
    }
}
