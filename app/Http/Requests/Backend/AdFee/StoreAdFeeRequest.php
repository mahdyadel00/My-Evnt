<?php

namespace App\Http\Requests\Backend\AdFee;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdFeeRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:255'],
            'price'         => ['required', 'numeric'],
            'duration'      => ['required', 'numeric'],
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
            'name.string'           => 'Name must be a string',
            'name.max'              => 'Name must not be greater than 255 characters',
            'description.string'    => 'Description must be a string',
            'description.max'       => 'Description must not be greater than 255 characters',
            'price.required'        => 'Price is required',
            'price.numeric'         => 'Price must be a number',
            'duration.required'     => 'Duration is required',
            'duration.numeric'      => 'Duration must be a number',
        ];
    }
}
