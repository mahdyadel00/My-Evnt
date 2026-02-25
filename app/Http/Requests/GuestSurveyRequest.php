<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestSurveyRequest extends FormRequest
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
            'event_id'                  => 'required|exists:events,id',
            'first_name'                => 'required|string|max:255',
            'last_name'                 => 'required|string|max:255',
            'email'                     => 'required|email|max:255',
            'phone'                     => 'required|string|max:20',
            'notes'                     => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_id.required'         => 'Event ID is required.',
            'event_id.exists'           => 'Invalid event selected.',
            'first_name.required'       => 'First name is required.',
            'first_name.max'            => 'First name must not exceed 255 characters.',
            'last_name.required'        => 'Last name is required.',
            'last_name.max'             => 'Last name must not exceed 255 characters.',
            'email.required'            => 'Email address is required.',
            'email.email'               => 'Please enter a valid email address.',
            'email.max'                 => 'Email must not exceed 255 characters.',
            'phone.required'            => 'Phone number is required.',
            'phone.max'                 => 'Phone number must not exceed 20 characters.',
            'notes.max'                 => 'Notes must not exceed 1000 characters.',
        ];
    }
}
