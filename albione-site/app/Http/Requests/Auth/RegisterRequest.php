<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supportsUsername = User::supportsUsername();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];

        if ($supportsUsername) {
            $rules['username'] = ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'username' => trim((string) $this->input('username')),
            'email' => strtolower(trim((string) $this->input('email'))),
            'password' => trim((string) $this->input('password')),
            'password_confirmation' => trim((string) $this->input('password_confirmation')),
        ]);
    }
}
