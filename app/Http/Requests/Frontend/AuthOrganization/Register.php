<?php

namespace App\Http\Requests\Frontend\AuthOrganization;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'email'      =>  ['required', 'email' , 'unique:companies,email'],
            'password'   =>  ['required', 'min:8' , 'confirmed' , 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
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
            'email.required'        => 'Email is required',
            'email.email'           => 'Email is not valid',

            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be at least 6 characters',
            'password.confirmed'    => 'Password confirmation does not match',
        ];
    }
}
