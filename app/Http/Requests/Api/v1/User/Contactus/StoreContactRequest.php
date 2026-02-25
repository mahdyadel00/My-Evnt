<?php

namespace App\Http\Requests\Api\v1\User\Contactus;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'email'   => ['required', 'string', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
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
            'email.required'   => 'Email is required',
            'email.email'      => 'Email is not valid',
            'phone.string'     => 'Phone is not valid',
            'subject.required' => 'Subject is required',
            'message.required' => 'Message is required',
        ];
    }
}
