<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'max:255',
            ],
            'description' => [
                'nullable',
                'max:500',
            ],
            'url' => 'url|nullable',
        ];
    }
}
