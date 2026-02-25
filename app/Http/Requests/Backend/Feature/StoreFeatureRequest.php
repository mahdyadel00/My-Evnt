<?php

namespace App\Http\Requests\Backend\Feature;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
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
            'package_id'    => ['required', 'exists:packages,id'],
            'title'         => ['required', 'string', 'max:255'],
            'statue'        => ['sometimes', 'boolean'],
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
            'package_id.required'   => 'Feature is required',
            'package_id.exists'     => 'Feature is not exists',
            'title.required'        => 'Title is required',
            'title.string'          => 'Title must be a string',
            'title.max'             => 'Title must not be greater than 255 characters',
            'statue.boolean'        => 'Status must be a boolean',
        ];
    }
}
