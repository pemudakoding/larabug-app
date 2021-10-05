<?php

namespace App\Http\Requests\Profile;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('users')->ignore(auth()->user()->id)
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
