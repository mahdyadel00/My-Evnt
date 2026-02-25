<?php

namespace App\Http\Requests\Frontend\AuthOrganization;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
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
            'current_password'  => ['required', 'string'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
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
            'current_password.required' => 'Current password is required',
            'current_password.string'   => 'Current password must be a string',

            'password.required'         => 'Password is required',
            'password.string'           => 'Password must be a string',
            'password.min'              => 'Password must be at least 8 characters',
            'password.confirmed'        => 'Password confirmation does not match',
        ];
    }
}
