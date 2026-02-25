<?php

namespace App\Http\Requests\Api\v1\Auth;

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
            'old_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed' , 'different:old_password' , 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
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
            'old_password.required'         => 'Old password is required',
            'old_password.string'           => 'Old password must be a string',
            'old_password.min'              => 'Old password must be at least 8 characters',
            'new_password.required'         => 'New password is required',
            'new_password.string'           => 'New password must be a string',
            'new_password.min'              => 'New password must be at least 8 characters',
            'new_password.confirmed'        => 'New password confirmation does not match',
        ];
    }
}
