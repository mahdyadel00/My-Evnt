<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class BankEventRequest extends FormRequest
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
            'country_id'        => ['required', 'integer' , 'exists:countries,id'],
            'city_id'           => ['required', 'integer' , 'exists:cities,id'],
            'bank_name'         => ['required', 'string'],
            'email'             => ['required', 'email'],
            'phone'             => ['required', 'string'],
            'branch'            => ['required', 'string'],
            'postal_code'       => ['required', 'string'],
            'iban'              => ['required', 'string'],
            'account_name'      => ['required', 'string'],
            'account_number'    => ['required', 'string'],
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
            'country_id.required'       => 'Country is required',
            'country_id.integer'        => 'Country must be an integer',
            'country_id.exists'         => 'Country does not exist',
            'city_id.required'          => 'City is required',
            'city_id.integer'           => 'City must be an integer',
            'city_id.exists'            => 'City does not exist',
            'bank_name.required'        => 'Bank name is required',
            'bank_name.string'          => 'Bank name must be a string',
            'email.required'            => 'Email is required',
            'email.email'               => 'Email must be a valid email',
            'phone.required'            => 'Phone is required',
            'phone.string'              => 'Phone must be a string',
            'branch.required'           => 'Branch is required',
            'branch.string'             => 'Branch must be a string',
            'postal_code.required'      => 'Postal code is required',
            'postal_code.string'        => 'Postal code must be a string',
            'iban.required'             => 'IBAN is required',
            'iban.string'               => 'IBAN must be a string',
            'account_name.required'     => 'Account name is required',
            'account_name.string'       => 'Account name must be a string',
            'account_number.required'   => 'Account number is required',
            'account_number.string'     => 'Account number must be a string',
        ];
    }
}
