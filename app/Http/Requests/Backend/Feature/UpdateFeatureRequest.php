<?php

namespace App\Http\Requests\Backend\Feature;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends FormRequest
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
            'package_id'    => ['sometimes', 'exists:packages,id'],
            'title'         => ['sometimes', 'string', 'max:255'],
            'status'        => ['sometimes', 'boolean'],
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
            'package_id.exists'     => 'Feature is not exists',
            'title.string'          => 'Title must be a string',
            'title.max'             => 'Title must not be greater than 255 characters',
            'status.boolean'        => 'Status must be a boolean',
        ];
    }
}


