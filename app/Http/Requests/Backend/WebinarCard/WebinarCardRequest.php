<?php

namespace App\Http\Requests\Backend\WebinarCard;

use Illuminate\Foundation\Http\FormRequest;

class WebinarCardRequest extends FormRequest
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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);
        $requiredOnCreateOrSometimes = $isUpdate ? 'sometimes' : 'required';
        return [
            'webinar_id'            => [$requiredOnCreateOrSometimes, 'exists:webinars,id'],
            'title'                 => [$requiredOnCreateOrSometimes, 'string', 'max:255'],
            'description'           => [$requiredOnCreateOrSometimes, 'string'],
            'link'                  => ['nullable', 'string', 'max:255'],
            'status'                => [$requiredOnCreateOrSometimes, 'boolean'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    
    {
        return [
            'webinar_id'            => 'Webinar',
            'title'                 => 'Title',
            'description'           => 'Description',
            'link'                  => 'Link',
            'status'                => 'Status',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    
    {
        return [
            'webinar_id.exists'            => 'Webinar is invalid',
            'title.string'                 => 'Title must be a string',
            'title.max'                    => 'Title must not be greater than 255 characters',
            'description.string'           => 'Description must be a string',
            'link.string'                  => 'Link must be a string',
            'link.max'                     => 'Link must not be greater than 255 characters',
            'status.boolean'               => 'Status must be a boolean',
        ];
    }
}
