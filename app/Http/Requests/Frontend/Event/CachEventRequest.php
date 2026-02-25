<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class CachEventRequest extends FormRequest
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
            'address'    => ['required', 'string'],
            'email'      => ['required', 'email'],
            'phone'      => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address.required' => 'Address is required',

            'email.required'   => 'Email is required',
            'email.email'      => 'Email is invalid',

            'phone.required'   => 'Phone is required',
        ];
    }
}
