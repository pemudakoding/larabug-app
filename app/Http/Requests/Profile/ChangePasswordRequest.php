<?php

namespace App\Http\Requests\Profile;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current' => 'password',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->uncompromised()
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
