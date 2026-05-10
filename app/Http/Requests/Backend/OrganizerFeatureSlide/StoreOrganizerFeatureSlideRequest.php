<?php

declare(strict_types=1);

namespace App\Http\Requests\Backend\OrganizerFeatureSlide;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizerFeatureSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:50000'],
            'hero_image_remote' => ['nullable', 'string', 'max:2048'],
            'hero_image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
