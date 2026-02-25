<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class FomrServayRequest extends FormRequest
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
            'event_id'                          => ['required', 'exists:events,id'],
            'first_name'                        => ['required', 'string', 'max:255'],
            'last_name'                         => ['required', 'string', 'max:255'],
            'email'                             => ['required', 'email', 'max:255'],
            'phone'                             => ['required', 'string', 'max:20', 'regex:/^(\+2|002)?01[0125678][0-9]{8}$/'],
        ];
    }



    public function messages(): array
    {
        return [
            'event_id.required'                 => 'The event field is required.',
            'event_id.exists'                   => 'The selected event is invalid.',
            'first_name.required'               => 'The first name field is required.',
            'last_name.required'                => 'The last name field is required.',
            'email.required'                    => 'The email field is required.',
            'email.email'                       => 'The email must be a valid email address.',
            'phone.required'                    => 'The phone field is required.',
        ];
    }
}
