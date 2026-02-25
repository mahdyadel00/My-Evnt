<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Login extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "phone"         => ["required", "string", "min:10", "max:14"],
        ];

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required'    => 'Phone is required',
            'phone.string'      => 'Phone must be string',
            'phone.max'         => 'Phone must be less than 14 characters',
            'phone.min'         => 'Phone must be at least 10 characters',
            'phone.exists'      => 'Phone must be a valid phone number',
            'phone.regex'       => 'Phone must be a valid phone number',
        ];
    }
}
