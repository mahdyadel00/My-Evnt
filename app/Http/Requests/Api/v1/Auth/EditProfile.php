<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class EditProfile extends FormRequest
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
            "first_name"        => ["sometimes", "string", "max:255"],
            "middle_name"       => ["sometimes", "string", "max:255"],
            "last_name"         => ["sometimes", "string", "max:255"],
            "user_name"         => ["sometimes", "string", "max:255"],
            "email"             => ["sometimes", "string", "email" , "unique:users,email," . $this->user()->id],
            "country_id"        => ["sometimes", "integer", "exists:countries,id"],
            "phone"             => ["sometimes", "string", "max:11", "regex:/^01[0-2]{1}[0-9]{8}/", "unique:users,phone"],
            "about"             => ["sometimes", "string", "max:255"],
            'image'             => ['sometimes', new Media()],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'first_name.required'               => 'First name is required',
            'first_name.string'                 => 'First name must be string',
            'first_name.max'                    => 'First name must be less than 255 characters',

            'middle_name.required'              => 'Middle name is required',
            'middle_name.string'                => 'Middle name must be string',
            'middle_name.max'                   => 'Middle name must be less than 255 characters',

            'last_name.required'                => 'Last name is required',
            'last_name.string'                  => 'Last name must be string',
            'last_name.max'                     => 'Last name must be less than 255 characters',

            'user_name.required'                => 'User name is required',
            'user_name.string'                  => 'User name must be string',
            'user_name.max'                     => 'User name must be less than 255 characters',

            'email.required'                    => 'Email is required',
            'email.string'                      => 'Email must be string',
            'email.email'                       => 'Email must be a valid email',
            'email.unique'                      => 'The email address is already used by another account.',

            'old_password.required'             => 'Old password is required',
            'old_password.string'               => 'Old password must be string',
            'old_password.min'                  => 'Old password must be at least 8 characters',

            'new_password.required'             => 'New password is required',
            'new_password.string'               => 'New password must be string',
            'new_password.min'                  => 'New password must be at least 8 characters',
            'new_password.confirmed'            => 'New password must be confirmed',

            'country_id.required'               => 'Country is required',
            'country_id.integer'                => 'Country must be integer',
            'country_id.exists'                 => 'Country must be a valid country',

            'phone.required'                    => 'Phone is required',
            'phone.string'                      => 'Phone must be string',
            'phone.max'                         => 'Phone must be less than 11 characters',
            'phone.regex'                       => 'Phone must be a valid phone number',

            'about.required'                    => 'About is required',
            'about.string'                      => 'About must be string',
            'about.max'                         => 'About must be less than 255 characters',
        ];
    }
}
