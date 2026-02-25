<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Profile extends FormRequest
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
            'first_name'        => ['sometimes', 'string', 'max:255'],
            'last_name'         => ['sometimes', 'string', 'max:255'],
            'email'             => ['sometimes', 'string', 'email', 'max:255'],
            'phone'             => ['sometimes', 'string', 'max:255'],
            'address'           => ['sometimes', 'string', 'max:255'],
            'birth_date'        => ['sometimes', 'date'],
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
            'first_name.string'         => 'First name must be a string',
            'first_name.max'            => 'First name must not be greater than 255 characters',

            'last_name.string'          => 'Last name must be a string',
            'last_name.max'             => 'Last name must not be greater than 255 characters',

            'email.string'              => 'Email must be a string',
            'email.email'               => 'Email must be a valid email address',
            'email.max'                 => 'Email must not be greater than 255 characters',

            'phone.string'              => 'Phone must be a string',
            'phone.max'                 => 'Phone must not be greater than 255 characters',

            'address.string'            => 'Address must be a string',
            'address.max'               => 'Address must not be greater than 255 characters',

            'birth_date.date'           => 'Birth date must be a date',

//            'company_name.string'       => 'Company name must be a string',
//            'company_name.max'          => 'Company name must not be greater than 255 characters',
//
//            'company_email.string'      => 'Company email must be a string',
//            'company_email.email'       => 'Company email must be a valid email address',
//            'company_email.max'         => 'Company email must not be greater than 255 characters',
//
//            'company_address.string'    => 'Company address must be a string',
//            'company_address.max'       => 'Company address must not be greater than 255 characters',
//
//            'company_website.string'    => 'Company website must be a string',
//            'company_website.max'       => 'Company website must not be greater than 255 characters',
        ];
    }
}
