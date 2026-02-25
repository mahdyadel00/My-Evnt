<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class Setup4EventRequest extends FormRequest
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
            'card_name'    => ['sometimes', 'string' , 'unique:payment_methods,card_name'],
            'card_number'  => ['sometimes', 'numeric' , 'unique:payment_methods,card_number' , 'min:16'],
            'card_expiry'  => ['sometimes', 'date_format:m/y' , 'unique:payment_methods,card_expiry' , 'after:today'],
            'card_cvc'     => ['sometimes', 'numeric' , 'min:3' , 'unique:payment_methods,card_cvc' , 'max:3'],
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
            'card_name.required'        => 'Card name is required',
            'card_name.string'          => 'Card name must be a string',
            'card_name.unique'          => 'Card name must be unique',

            'card_number.required'      => 'Card number is required',
            'card_number.numeric'       => 'Card number must be a number',
            'card_number.unique'        => 'Card number must be unique',
            'card_number.min'           => 'Card number must be 16 digits',
            'card_number.max'           => 'Card number must be 16 digits',

            'card_expiry.required'      => 'Card expiry is required',
            'card_expiry.date_format'   => 'Card expiry must be in the format m/y',
            'card_expiry.unique'        => 'Card expiry must be unique',
            'card_expiry.after'         => 'Card expiry must be after today',

            'card_cvc.required'         => 'Card cvc is required',
            'card_cvc.numeric'          => 'Card cvc must be a number',
            'card_cvc.min'              => 'Card cvc must be 3 digits',
            'card_cvc.unique'           => 'Card cvc must be unique',
            'card_cvc.max'              => 'Card cvc must be 3 digits',

        ];
    }
}
