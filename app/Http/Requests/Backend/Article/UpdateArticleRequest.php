<?php

namespace App\Http\Requests\Backend\Article;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'blog_id'       => ['sometimes', 'integer', 'exists:blogs,id'],
            'title'         => ['sometimes', 'string', 'max:255'],
            'description'   => ['sometimes', 'string'],
            'image'         => ['sometimes', new Media],
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
            'blog_id.integer'       => 'The blog field must be an integer.',
            'blog_id.exists'        => 'The selected blog is invalid.',
            'title.string'          => 'The title field must be a string.',
            'title.max'             => 'The title field must not exceed 255 characters.',
            'description.string'    => 'The description field must be a string.',
            'image'                 => 'The image field is invalid.',
        ];
    }
}
