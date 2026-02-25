<?php

namespace App\Http\Requests\Frontend\AuthOrganization;

use App\Rules\Media;
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
            'first_name'                            => ['nullable', 'string', 'max:255'],
            'last_name'                             => ['nullable', 'string', 'max:255'],
            'user_name'                             => ['nullable', 'string', 'max:255'],
            'company_name'                          => ['nullable', 'string', 'max:255'],
            'email'                                 => ['nullable', 'string', 'email', 'max:255'],
            'phone'                                 => ['nullable', 'string', 'max:255'],
            'website'                               => ['nullable', 'string', 'max:255', 'url', 'active_url'],
            'description'                           => ['nullable', 'string'],
            'address'                               => ['nullable', 'string'],
            'logo'                                  => ['nullable', new Media()],
            'identity_image'                        => ['nullable', new Media()],
            'commercial_register_image'             => ['nullable', new Media()],
            'tax_card'                              => ['nullable', new Media()],
            'commercial_record'                     => ['nullable', new Media()],
            'type'                                  => ['nullable', 'in:user,company'],
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
            'first_name.string' => 'First name must be a string',
            'first_name.max' => 'First name must not be greater than 255 characters',

            'last_name.string' => 'Last name must be a string',
            'last_name.max' => 'Last name must not be greater than 255 characters',

            'user_name.string' => 'User name must be a string',
            'user_name.max' => 'User name must not be greater than 255 characters',

            'company_name.string' => 'Company name must be a string',
            'company_name.max' => 'Company name must not be greater than 255 characters',

            'email.string' => 'Email must be a string',
            'email.email' => 'Email must be a valid email',

            'phone.string' => 'Phone must be a string',
            'phone.max' => 'Phone must not be greater than 255 characters',

            'website.string' => 'Website must be a string',
            'website.max' => 'Website must not be greater than 255 characters',
        ];
    }
}
