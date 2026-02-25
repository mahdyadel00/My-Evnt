<?php

namespace App\Http\Requests\Backend\User;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'image' => [new Media()],
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
            'city_id.required' => 'City is required',
            'city_id.integer' => 'City must be an integer',
            'city_id.exists' => 'City must exist in the database',

            'first_name.required' => 'First name is required',
            'first_name.string' => 'First name must be a string',
            'first_name.max' => 'First name must not be greater than 255 characters',

            'last_name.required' => 'Last name is required',
            'last_name.string' => 'Last name must be a string',
            'last_name.max' => 'Last name must not be greater than 255 characters',

            'middle_name.string' => 'Middle name must be a string',
            'middle_name.max' => 'Middle name must not be greater than 255 characters',

            'user_name.required' => 'User name is required',
            'user_name.string' => 'User name must be a string',
            'user_name.max' => 'User name must not be greater than 255 characters',
            'user_name.unique' => 'User name must be unique',

            'email.required' => 'Email is required',
            'email.string' => 'Email must be a string',
            'email.email' => 'Email must be a valid email',
            'email.max' => 'Email must not be greater than 255 characters',
            'email.unique' => 'Email must be unique',

            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password must be confirmed',

            'phone.required' => 'Phone is required',
            'phone.string' => 'Phone must be a string',
            'phone.max' => 'Phone must not be greater than 255 characters',

            'roles_name.array' => 'Roles name must be an array',
            'roles_name.*.string' => 'Roles name must be a string',
            'roles_name.*.exists' => 'Roles name must exist in the database',
            'roles_name.*.string' => 'Roles name must be a string',
            'roles_name.*.exists' => 'Roles name must exist in the database',

            'about.string' => 'About must be a string',
        ];
    }
}
