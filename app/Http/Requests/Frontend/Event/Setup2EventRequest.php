<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class Setup2EventRequest extends FormRequest
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
            'country_id'    => ['required', 'integer' , 'exists:countries,id'],
            'city_id'       => ['required', 'integer' , 'exists:cities,id'],
            'location'      => ['required', 'string'],
            'address'       => ['required', 'string'],
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
            'country_id.exists'     => 'Country does not exist',
            'city_id.required'      => 'City is required',
            'city_id.integer'       => 'City must be an integer',
            'city_id.exists'        => 'City does not exist',
            'location.required'     => 'Location is required',
            'location.string'       => 'Location must be a string',
            'address.required'      => 'Address is required',
            'address.string'        => 'Address must be a string',
        ];
    }
}
