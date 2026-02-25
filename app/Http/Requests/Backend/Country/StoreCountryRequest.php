<?php

namespace App\Http\Requests\Backend\Country;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
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
            'name'              =>  ['required', 'string', 'max:255'],
            'code'              =>  ['required', 'string', 'max:255'],
            'is_available'      =>  ['required', 'boolean'],
            'logo'              =>  [new Media],
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
            'name.required'         => 'Name is required',
            'name.string'           => 'Name must be string',
            'name.max'              => 'Name must be less than 255 characters',
            'code.required'         => 'Code is required',
            'code.string'           => 'Code must be string',
            'code.max'              => 'Code must be less than 255 characters',
            'is_available.required' => 'Is Available is required',
            'is_available.boolean'  => 'Is Available must be boolean',
        ];
    }
}
