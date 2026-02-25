<?php

namespace App\Http\Requests\Backend\Packages;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
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
            'title'         => ['sometimes', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'price_monthly' => ['sometimes', 'numeric'],
            'price_yearly'  => ['sometimes', 'numeric'],
            'discount'      => ['sometimes', 'numeric'],
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
            'title.string'          => 'Title must be a string',
            'title.max'             => 'Title must not be greater than 255 characters',
            'description.string'    => 'Description must be a string',
            'price_monthly.numeric'  => 'Monthly price must be a number',
            'price_yearly.numeric'   => 'Yearly price must be a number',
            'discount.numeric'       => 'Discount must be a number',
        ];
    }
}
