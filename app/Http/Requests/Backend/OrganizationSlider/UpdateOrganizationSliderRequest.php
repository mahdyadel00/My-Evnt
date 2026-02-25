<?php

namespace App\Http\Requests\Backend\OrganizationSlider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationSliderRequest extends FormRequest
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
            'title'         => ['sometimes', 'string'],
            'description'   => ['sometimes', 'string'],
            'video'         => ['nullable','mimes:mp4,webm,ogg,flv,avi,wmv,mov,qt,mpg,mpeg,3gp,3g2'],
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
            'description.string'    => 'Description must be a string',
            'video.mimes'           => 'Video must be a file of type: mp4,webm,ogg,flv,avi,wmv,mov,qt,mpg,mpeg,3gp,3g2',
        ];
    }
}
