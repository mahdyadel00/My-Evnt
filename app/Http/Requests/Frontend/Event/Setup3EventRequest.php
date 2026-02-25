<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class Setup3EventRequest extends FormRequest
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
            'ticket_type'       => ['array', 'required'],
            'ticket_type.*'     => ['string', 'max:255'],
            'price'             => ['array', 'required'],
            'price.*'           => ['numeric'],
            'quantity'          => ['array', 'required'],
            'quantity.*'        => ['numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_type.required'  => 'Event ticket_type is required',
            'ticket_type.string'    => 'Event ticket_type must be a string',
            'ticket_type.max'       => 'Event ticket_type must not be more than 255 characters',

            'price.required'        => 'Event price is required',
            'price.numeric'         => 'Event price must be a number',

            'quantity.required'     => 'Event quantity is required',
            'quantity.numeric'      => 'Event quantity must be a number',
        ];
    }
}
