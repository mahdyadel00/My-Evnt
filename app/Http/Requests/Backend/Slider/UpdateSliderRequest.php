<?php

namespace App\Http\Requests\Backend\Slider;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
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
            'title'         => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'url'           => ['nullable', 'string', 'url'],
            'image'         => ['nullable', new Media],
            'small_image'   => ['nullable', new Media],
            'event_id'      => ['nullable', 'exists:events,id'],
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
            'title.string'          => 'Title must be string',
            'title.max'             => 'Title must be less than 255 characters',

            'description.string'    => 'Description must be string',

            'url.string'            => 'URL must be string',

            'event_id.exists'       => 'Event not found',
        ];
    }
}
