<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
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
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255'],
            'phone'         => ['required', 'min:10' , 'max:12' , 'regex:/[0-9]{11}/'],
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
            'first_name.required'       => 'First Name is required',
            'first_name.string'         => 'First Name must be a string',
            'first_name.max'            => 'First Name must not be greater than 255 characters',

            'last_name.required'        => 'Last Name is required',
            'last_name.string'          => 'Last Name must be a string',
            'last_name.max'             => 'Last Name must not be greater than 255 characters',

            'email.required'            => 'Email is required',
            'email.string'              => 'Email must be a string',
            'email.email'               => 'Email must be a valid email',
            'email.max'                 => 'Email must not be greater than 255 characters',

            'phone.required'            => 'Phone is required',
            'phone.numeric'             => 'Phone must be a number',
            'phone.unique'              => 'Phone must be unique',
            'phone.min'                 => 'Phone must be 10 digits',
            'phone.max'                 => 'Phone must be 10 digits',
            'phone.regex'               => 'Phone must be a valid 10 digit number',
        ];
    }
}
