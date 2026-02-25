<?php

namespace App\Http\Requests\Backend\Blog;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'company_id'   => ['sometimes', 'integer' , 'exists:companies,id'], // 'sometimes' means 'if the field is present in the request data, the field must pass the validation rules specified in the rule method. If the field is not present, the field is considered valid and will be removed from the request data returned by the validated method.
            'title'     => ['sometimes', 'string', 'max:255'],
            'content'   => ['sometimes', 'string'],
            'image'     => ['sometimes', new Media],
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
            'company_id.required'  => 'Company is required',
            'title.required'    => 'Title is required',
            'content.required'  => 'Content is required',
        ];
    }
}

