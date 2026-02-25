<?php

namespace App\Http\Requests\Backend\Event;

use App\Enums\FacilityType;
use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Required Fields
            'category_id'                                       => ['required', 'integer', 'exists:event_categories,id'],
            'city_id'                                           => ['nullable', 'integer', 'exists:cities,id'],
            'currency_id'                                       => ['required', 'integer', 'exists:currencies,id'],
            'upload_by'                                         => ['required', 'string', 'max:255'],
            'name'                                              => ['required' , 'string', 'max:255'],
            'description'                                       => ['required', 'string'],
            'format'                                            => ['required', 'boolean'],
            'location'                                          => ['nullable', 'string', 'max:255'],
            'poster'                                            => ['required', new Media()],
            'banner'                                            => ['required', new Media()],
            'exclusive_image'                                   => ['nullable', new Media()],
            'cancellation_policy'                               => ['nullable', 'string'],
            'is_exclusive'                                      => ['required', 'boolean'],
            'summary'                                           => ['nullable', 'string'],
            'area'                                              => ['nullable', 'string'],
            // Optional Fields (Foreign Keys)
            'sub_category_id'                                   => ['nullable', 'integer', 'exists:event_categories,id'],
            'company_id'                                        => ['nullable', 'integer', 'exists:companies,id'],

            // Optional Fields (Text and Links)
            'organized_by'                                      => ['nullable', 'string', 'max:255'],
            'external_link'                                     => ['nullable', 'url'],
            // 'link_meeting'                                      => [Rule::requiredIf($this->format == 1), 'nullable', 'url'],
            'link_meeting'                                      => ['nullable', 'url'],
            'longitude'                                         => ['nullable', 'numeric', 'between:-180,180'],
            'latitude'                                          => ['nullable', 'numeric', 'between:-90,90'],

            // Facilities (Array with Enum Validation)
            'facility'                                          => ['nullable', 'array', Rule::in(FacilityType::values())],

            // Event Dates (Array Fields) - All nullable to allow optional dates
            'start_date'                    => ['nullable', 'array'],
            'start_date.*'                  => ['nullable', 'date'],
            'end_date'                      => ['nullable', 'array'],
            'end_date.*'                    => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!$value) return; // Skip validation if null
                    $index = explode('.', $attribute)[1];
                    $startDate = $this->start_date[$index] ?? null;
                    if ($startDate && $value < $startDate) {
                        $fail("The end date at position " . ($index + 1) . " must be on or after the start date.");
                    }
                },
            ],
            'start_time'                    => ['nullable', 'array'],
            'start_time.*'                  => ['nullable', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'end_time'                      => ['nullable', 'array'],
            'end_time.*'                    => [
                'nullable',
                'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/',
                function ($attribute, $value, $fail) {
                    if (!$value) return; // Skip validation if null
                    $index = explode('.', $attribute)[1];
                    $startDate = $this->start_date[$index] ?? null;
                    $endDate = $this->end_date[$index] ?? null;
                    $startTime = $this->start_time[$index] ?? null;
                    
                    // Normalize times to HH:MM format for comparison
                    $normalizedStartTime = $startTime ? substr($startTime, 0, 5) : null;
                    $normalizedEndTime = substr($value, 0, 5);
                    
                    if ($startDate && $endDate && $normalizedStartTime && $normalizedEndTime && $startDate === $endDate && $normalizedEndTime <= $normalizedStartTime) {
                        $fail("The end time at position " . ($index + 1) . " must be after the start time when the dates are the same.");
                    }
                },
            ],

            // Tickets (Array Fields)
            'ticket_type' => ['nullable', 'array'],
            'ticket_type.*' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'array'],
            'price.*' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'array'],
            'quantity.*' => ['nullable', 'integer', 'min:0'],
            
            // SEO Fields
            'meta_title'                    => ['nullable', 'string'],
            'meta_description'              => ['nullable', 'string'],
            'meta_keywords'                 => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Required Fields
            'category_id.required'                              => 'The event category field is required.',
            'category_id.exists'                                => 'The selected event category does not exist.',
            'city_id.nullable'                                  => 'The city field is nullable.',
            'city_id.exists'                                    => 'The selected city does not exist.',
            'currency_id.required'                              => 'The currency field is required.',
            'currency_id.exists'                                => 'The selected currency does not exist.',
            'upload_by.required'                                => 'The upload by field is required.',
            'upload_by.max'                                     => 'The upload by field must not exceed 255 characters.',
            'name.required'                                     => 'The event name field is required.',
            'name.unique'                                       => 'The event name has already been taken.',
            'name.max'                                          => 'The event name must not exceed 255 characters.',
            'description.required'                              => 'The description field is required.',
            'format.required'                                   => 'The format field is required.',
            'format.boolean'                                    => 'The format must be either online or offline.',
            'location.nullable'                                 => 'The event location field is nullable.',
            'location.max'                                      => 'The location must not exceed 255 characters.',
            'poster.required'                                   => 'The event poster is required.',
            'banner.required'                                   => 'The event banner is required.',
            'cancellation_policy.required'                      => 'The cancellation policy field is required.',
            'summary.string'                                    => 'The summary must be a string.',
            'area.string'                                       => 'The area must be a string.',
            'exclusive_image.required'                          => 'The exclusive image is required.',
            // Optional Fields
            'sub_category_id.exists'                            => 'The selected sub category does not exist.',
            'company_id.exists'                                 => 'The selected company does not exist.',
            'organized_by.max'                                  => 'The organized by field must not exceed 255 characters.',
            'external_link.url'                                 => 'The external link must be a valid URL.',
            'link_meeting.url'                                  => 'The meeting link must be a valid URL.',
            'link_meeting.required_if'                          => 'The meeting link is required for online events.',
            'longitude.between'                                 => 'The longitude must be between -180 and 180 degrees.',
            'latitude.between'                                  => 'The latitude must be between -90 and 90 degrees.',

            // Facilities
            'facility.array'                                    => 'The facilities must be an array.',
            'facility.in'                                       => 'The selected facilities are invalid. Choose from: ' . implode(', ', FacilityType::values()) . '.',

            // Event Dates
            'start_date.required'                               => 'At least one start date is required.',
            'start_date.*.required'                             => 'The start date field is required.',
            'start_date.*.date'                                 => 'The start date must be a valid date.',
            'start_date.*.after_or_equal'                       => 'The start date must be today or a future date.',
            'end_date.required'                                 => 'At least one end date is required.',
            'end_date.*.required'                               => 'The end date field is required.',
            'end_date.*.date'                                   => 'The end date must be a valid date.',
            'start_time.required'                               => 'At least one start time is required.',
            'start_time.*.required'                             => 'The start time field is required.',
            'start_time.*.regex'                                => 'The start time must be in the format HH:MM (e.g., 14:30).',
            'end_time.required'                                 => 'At least one end time is required.',
            'end_time.*.required'                               => 'The end time field is required.',
            'end_time.*.regex'                                  => 'The end time must be in the format HH:MM (e.g., 14:30).',

            // Tickets
            'ticket_type.*.max'                                 => 'The ticket type must not exceed 255 characters.',
            'price.*.numeric'                                   => 'The ticket price must be a number.',
            'price.*.min'                                       => 'The ticket price cannot be negative.',
            'quantity.*.integer'                                => 'The ticket quantity must be an integer.',
            'quantity.*.min'                                    => 'The ticket quantity cannot be negative.',
        ];
    }

    /**
     * Get custom attribute names for the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id'                                       => 'event category',
            'sub_category_id'                                   => 'sub category',
            'city_id'                                           => 'city',
            'currency_id'                                       => 'currency',
            'company_id'                                        => 'company',
            'upload_by'                                         => 'uploaded by',
            'organized_by'                                      => 'organized by',
            'summary'                                           => 'summary',
            'area'                                              => 'area',
            'name'                                              => 'event name',
            'location'                                          => 'event location',
            'longitude'                                         => 'longitude',
            'latitude'                                          => 'latitude',
            'external_link'                                     => 'external link',
            'description'                                       => 'description',
            'format'                                            => 'format',
            'start_date.*'                                      => 'start date',
            'end_date.*'                                        => 'end date',
            'start_time.*'                                      => 'start time',
            'end_time.*'                                        => 'end time',
            'poster'                                            => 'poster image',
            'banner'                                            => 'banner image',
            'exclusive_image'                                   => 'exclusive image',
            'is_active'                                         => 'status',
            'cancellation_policy'                               => 'cancellation policy',
            'link_meeting'                                      => 'meeting link',
            'facility'                                          => 'facilities',
            'ticket_type.*'                                     => 'ticket type',
            'price.*'                                           => 'ticket price',
            'quantity.*'                                        => 'ticket quantity',
        ];
    }
}