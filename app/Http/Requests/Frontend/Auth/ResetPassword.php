<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPassword extends FormRequest
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
            'code'  =>  ['required', 'string', 'min:4'],
            'password'  =>  ['required', 'string', 'min:8', 'confirmed'],
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
            'code.required'         => 'The code field is required.',
            'code.min'              => 'The code must be at least 4 characters.',

            'password.required'     => 'The password field is required.',
            'password.min'          => 'The password must be at least 8 characters.',
            'password.confirmed'    => 'The password confirmation does not match.',
        ];
    }
}
