<?php

namespace App\Http\Requests\Api\v1\Auth;

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
            'phone'     => ['required', 'string', 'max:255' , 'exists:users,phone'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'code'      => ['required', 'string', 'min:4', 'max:4'],
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
            'phone.max'         => 'Phone must be less than 255 characters',
            'phone.exists'      => 'Phone not found',
            'password.required' => 'Password is required',
            'password.string'   => 'Password must be string',
            'password.min'      => 'Password must be at least 8 characters',
            'password.confirmed'=> 'Password confirmation does not match',
            'code.required'     => 'Code is required',
            'code.string'       => 'Code must be string',
            'code.min'          => 'Code must be at least 4 characters',
            'code.max'          => 'Code must be less than 4 characters',
        ];
    }
}
