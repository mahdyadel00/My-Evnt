<?php

namespace App\Http\Requests\Backend\Blog;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
            'company_id'    => ['required', 'integer' , 'exists:companies,id'],
            'title'         => ['required', 'string', 'max:255'],
            'content'       => ['required', 'string'],
            'image'         => [new Media],
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
