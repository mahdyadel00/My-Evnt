<?php

namespace App\Http\Requests\Backend\AboutWebinar;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class RequestAboutWebinar extends FormRequest
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
            'webinar_id'                => [$requiredOnCreateOrSometimes, 'exists:webinars,id'],
            'title'                     => [$requiredOnCreateOrSometimes, 'string', 'max:255'],
            'description'               => [$requiredOnCreateOrSometimes, 'string'],
            'image'                     => [new Media],
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
            'webinar_id'                    => 'Webinar',
            'title'                         => 'Title',
            'description'                   => 'Description',
            'image'                         => 'Image',
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
            'webinar_id.required'               => 'Webinar is required',
            'webinar_id.exists'                 => 'Webinar not found',
            'title.required'                    => 'Title is required',
            'title.string'                      => 'Title must be string',
            'title.max'                         => 'Title must be less than 255 characters',
            'description.required'              => 'Description is required',
            'description.string'                => 'Description must be string',
            'image.required'                    => 'Image is required',
            'image.image'                       => 'Image must be an image',
        ];
    }
}
