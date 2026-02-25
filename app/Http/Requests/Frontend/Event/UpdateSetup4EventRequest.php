<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSetup4EventRequest extends FormRequest
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
            'card_name'    => ['sometimes', 'string'],
            'card_number'  => ['sometimes', 'string'],
            'card_expiry'  => ['sometimes', 'string'],
            'card_cvc'     => ['sometimes', 'string'],
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
            'card_name.required'    => 'Card name is required',
            'card_number.required'  => 'Card number is required',
            'card_expiry.required'  => 'Card expiry is required',
            'card_cvv.required'     => 'Card cvv is required',
        ];
    }
}
