<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSetup2EventRequest extends FormRequest
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
            'country_id'    => ['sometimes', 'integer' , 'exists:countries,id'],
            'city_id'       => ['sometimes', 'integer' , 'exists:cities,id'],
            'location'      => ['sometimes', 'string'],
            'address'       => ['sometimes', 'string'],
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
            'country_id.required'   => 'Country is required',
            'country_id.integer'    => 'Country must be an integer',
            'country_id.exists'     => 'Country must exist in the database',
            'city_id.required'      => 'City is required',
            'city_id.integer'       => 'City must be an integer',
            'city_id.exists'        => 'City must exist in the database',
            'location.required'     => 'Location is required',
            'location.string'       => 'Location must be a string',
            'address.required'      => 'Address is required',
            'address.string'        => 'Address must be a string',
        ];
    }
}
