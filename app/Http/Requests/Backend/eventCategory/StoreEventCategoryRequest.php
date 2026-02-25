<?php

namespace App\Http\Requests\Backend\eventCategory;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventCategoryRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:191', 'unique:event_categories,name'],
            'description'   => ['nullable', 'string'],
            'is_parent'     => ['nullable', 'boolean'],
            'parent_id'     => ['nullable', 'integer' , 'exists:event_categories,id'],
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
            'is_parent.boolean'     => 'The is parent must be a boolean.',

            'parent_id.integer'     => 'The parent id must be an integer.',
        ];
    }
}
