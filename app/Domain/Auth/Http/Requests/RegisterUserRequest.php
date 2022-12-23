<?php

namespace App\Domain\Auth\Http\Requests;

use App\Infrastructure\Rules\AlphaNumDashSpaceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'min:2', 'max:255', new AlphaNumDashSpaceRule()],
            'last_name' => ['required', 'min:2', 'max:255', new AlphaNumDashSpaceRule()],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => [Password::defaults(), 'confirmed'],
        ];
    }
}
