<?php

namespace App\Http\Requests\Frontend\Event;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'name'                      => ['sometimes', 'string' ,'max:255'],
            'currency_id'               => ['sometimes', 'integer' , 'exists:currencies,id'],
            'category_id'               => ['sometimes', 'integer' , 'exists:event_categories,id'],
            'sub_category_id'           => ['sometimes', 'integer' , 'exists:event_categories,id'],
            'description'               => ['sometimes', 'string'],
            'cancellation_policy'       => ['sometimes', 'string'],
            'eventDates'                => 'sometimes|array',
            'eventDates.*'              => 'required_with:eventDates|array',
            'eventDates.*.start_date'   => 'required_with:eventDates|date',
            'eventDates.*.end_date'     => 'required_with:eventDates|date',
            'eventDates.*.start_time'   => 'required_with:eventDates|date_format:H:i',
            'eventDates.*.end_time'     => 'required_with:eventDates|date_format:H:i',
            'format'                    => ['sometimes', 'boolean'],
            'poster'                    => ['sometimes' , new Media],
            'banner'                    => ['sometimes' , new Media],
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
            'name.string'                           => 'Name must be a string',
            'name.max'                              => 'Name must not be greater than 255 characters',

            'currency_id.integer'                   => 'Currency must be an integer',
            'currency_id.exists'                    => 'Currency does not exist',

            'category_id.integer'                   => 'Category must be an integer',
            'category_id.exists'                    => 'Category does not exist',

            'sub_category_id.integer'               => 'Sub category must be an integer',
            'sub_category_id.exists'                => 'Sub category does not exist',

            'description.string'                    => 'Description must be a string',

            'cancellation_policy.string'            => 'Cancellation policy must be a string',

            'eventDates.array'                      => 'Event dates must be an array',
            'eventDates.*.start_date.date'          => 'Start date must be a date',
            'eventDates.*.end_date.date'            => 'End date must be a date',
            'eventDates.*.start_time.date_format'   => 'Start time must be in H:i format',
            'eventDates.*.end_time.date_format'     => 'End time must be in H:i format',

            'format.boolean'                        => 'Format must be a boolean',

        ];
    }
}
