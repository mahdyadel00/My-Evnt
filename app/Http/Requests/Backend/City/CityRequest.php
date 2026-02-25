<?php

namespace App\Http\Requests\Backend\City;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);
        $requiredOnCreateOrSometimes = $isUpdate ? 'sometimes' : 'required';
        return [
            'country_id'    =>  [$requiredOnCreateOrSometimes, 'exists:countries,id'],
            'name'          => array_filter([$requiredOnCreateOrSometimes,'string','max:255',!$isUpdate ? 'unique:cities,name' : null,]),
            'is_available'  => [$requiredOnCreateOrSometimes, 'boolean']
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
            'country_id.exists'     => 'Country is invalid',
            'name.required'         => 'City name is required',
            'name.string'           => 'City name must be string',
            'name.max'              => 'City name must not be greater than 255 characters',
            'name.unique'           => 'City name must be unique',
            'is_available.required' => 'Is available is required',
            'is_available.boolean'  => 'Is available must be boolean'
        ];
    }
}
