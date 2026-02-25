<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            "phone"         => ["required", "string", "regex:/^([0-9\s\-\+\(\)]*)$/" , "min:10", "max:14", "unique:users"],
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
            'first_name.required'   => 'First Name is required',
            'last_name.required'    => 'Last Name is required',
            'email.required'        => 'Email is required',
            'email.email'           => 'Email is invalid',
            'phone.required'        => 'Phone is required',
            'phone.regex'           => 'Phone is invalid',
            'phone.min'             => 'Phone must be at least 10 characters',
            'phone.max'             => 'Phone may not be greater than 14 characters',
        ];
    }

}
