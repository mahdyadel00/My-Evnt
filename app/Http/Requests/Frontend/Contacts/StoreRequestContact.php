<?php

namespace App\Http\Requests\Frontend\Contacts;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestContact extends FormRequest
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
            'email'     => ['required', 'email'],
            'phone'     => ['required', 'string'],
            'subject'   => ['required', 'string'], // 'required|string|max:255
            'message'   => ['required', 'string'],
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
            'email.required'    => 'Email is required',
            'email.email'       => 'Email is invalid',
            'phone.required'    => 'Phone is required',
            'phone.string'      => 'Phone is invalid',
            'subject.required'  => 'Subject is required',
            'subject.string'    => 'Subject is invalid',
            'message.required'  => 'Message is required',
            'message.string'    => 'Message is invalid',
        ];
    }
}
