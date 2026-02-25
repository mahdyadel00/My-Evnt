<?php

namespace App\Http\Requests\Backend\Slider;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
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
        // dd($this->all());
        return [
            'title'         => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'url'           => ['nullable', 'string'],
            'image'         => [new Media],
            'small_image'   => [new Media],
            'event_id'      => ['required', 'exists:events,id'],
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
            'title.required'        => 'Title is required',
            'title.string'          => 'Title must be string',
            'title.max'             => 'Title must be less than 255 characters',

            'description.required'  => 'Description is required',
            'description.string'    => 'Description must be string',

            'url.string'            => 'URL must be string',
            'url.url'               => 'URL must be valid URL',

            'event_id.required'     => 'Event is required',
            'event_id.exists'       => 'Event not found',
        ];
    }
}