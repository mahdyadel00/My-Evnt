<?php

namespace App\Http\Requests\Backend\eventCategory;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventCategoryRequest extends FormRequest
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
            'name'          => ['sometimes', 'string', 'max:191'],
            'description'   => ['nullable', 'string'],
            'is_parent'     => ['sometimes', 'boolean'],
            'parent_id'     => ['nullable', 'integer' , 'exists:event_categories,id'],
            'image'         => ['sometimes' , new Media()],
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
            'name.string'           => 'The name must be a string.',
            'name.max'              => 'The name may not be greater than 191 characters.',
            'description.string'    => 'The description must be a string.',
            'is_parent.boolean'     => 'The is parent must be a boolean.',
            'parent_id.integer'     => 'The parent id must be an integer.',
        ];
    }
}
