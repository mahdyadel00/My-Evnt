<?php

namespace App\Http\Requests\Frontend\Auth;

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
            'category_id'       => ['required', 'integer' , 'exists:event_categories,id'],
            'sub_category_id'   => ['nullable', 'integer' , 'exists:event_categories,id'],
            'email'             =>  ['required', 'email' , 'unique:users,email'],
            'password'          =>  ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'phone'             =>  ['nullable', 'numeric'],
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
            'category_id.required'  => 'Category is required',
            'category_id.exists'    => 'Category is not valid',

            'sub_category_id.exists'=> 'Sub Category is not valid',

            'email.unique'          => 'Email is already taken',
            'email.required'        => 'Email is required',
            'email.email'           => 'Email is not valid',

            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be at least 6 characters',
            'password.confirmed'    => 'Password confirmation does not match',

            'phone.numeric'         => 'Phone number must be numeric',
        ];
    }
}
