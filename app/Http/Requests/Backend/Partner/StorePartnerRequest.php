<?php

namespace App\Http\Requests\Backend\Partner;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StorePartnerRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:191', 'unique:partners,name'],
            'description'   => ['nullable', 'string'],
            'is_active'     => ['nullable', 'boolean'],
            'sort_order'    => ['nullable', 'integer', 'min:0'],
            'image'         => [new Media()],
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
            'name.required'         => 'The name field is required.',
            'name.unique'           => 'The name has already been taken.',
            'name.max'              => 'The name may not be greater than 191 characters.',
            'description.string'    => 'The description must be a string.',
            'is_active.boolean'     => 'The is active must be a boolean.',
            'sort_order.integer'    => 'The sort order must be an integer.',
            'sort_order.min'        => 'The sort order must be at least 0.',
        ];
    }
}