<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for validating event search and filter parameters
 */
class SearchEventsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Public endpoint, no authorization needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'integer', 'exists:event_categories,id'],
            'city' => ['nullable', 'integer', 'exists:cities,id'],
            'date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'price' => ['nullable', 'string', 'in:free,paid'],
            'page' => ['nullable', 'integer', 'min:1']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category.exists' => 'The selected category does not exist.',
            'city.exists' => 'The selected city does not exist.',
            'date.date_format' => 'The date must be in Y-m-d format.',
            'price.in' => 'Price filter must be either "free" or "paid".',
            'page.min' => 'Page number must be at least 1.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'search' => 'search term',
            'category' => 'category',
            'city' => 'city',
            'date' => 'event date',
            'price' => 'price filter',
            'page' => 'page number'
        ];
    }
}
