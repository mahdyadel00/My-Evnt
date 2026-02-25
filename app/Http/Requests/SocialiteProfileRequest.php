<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialiteProfileRequest extends FormRequest
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
            'provider'                      => 'required|string|in:google,facebook',
            'access_token'                  => 'required|string|min:10',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'provider.required'             => 'Social provider is required.',
            'provider.in'                   => 'Provider must be either google or facebook.',
            'access_token.required'         => 'Access token is required.',
            'access_token.min'              => 'Access token must be at least 10 characters.',
        ];
    }
}
