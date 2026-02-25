<?php

namespace App\Http\Requests\Api\v1\Company;

use Illuminate\Foundation\Http\FormRequest;

class CheckQrCode extends FormRequest
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
            'qr_code'       => ['required', 'string'],
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
            'qr_code.required'      => 'QR code is required!',
            'qr_code.string'        => 'QR code must be a string!',

        ];
    }
}
