<?php

namespace App\Http\Requests\Frontend\Event;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
//        dd($this->all());
        return [
            'name'                      => ['required', 'string' ,'max:255'],
            'currency_id'               => ['required', 'integer' , 'exists:currencies,id'],
            'category_id'               => ['required', 'integer' , 'exists:event_categories,id'],
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
            'days'                      => ['sometimes', 'integer'],
            'poster'                    => [new Media],
            'banner'                    => [new Media],


        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                 => 'Event name is required',
            'name.string'                   => 'Event name must be a string',
            'name.max'                      => 'Event name must not be more than 255 characters',

            'currency_id.required'          => 'Event currency is required',
            'currency_id.integer'           => 'Event currency must be an integer',
            'currency_id.exists'            => 'Event currency does not exist',

            'category_id.required'          => 'Event category is required',
            'category_id.integer'           => 'Event category must be an integer',
            'category_id.exists'            => 'Event category does not exist',

            'sub_category_id.integer'       => 'Event sub category must be an integer',
            'sub_category_id.exists'        => 'Event sub category does not exist',
            'sub_category_id.required'      => 'Event sub category is required',

            'start_date.required'           => 'Event start date is required',
            'start_date.date'               => 'Event start date must be a date',

            'end_date.required'             => 'Event end date is required',
            'end_date.date'                 => 'Event end date must be a date',

            'start_time.required'           => 'Event start time is required',
            'start_time.time'               => 'Event start time must be a time',

            'end_time.required'             => 'Event end time is required',
            'end_time.time'                 => 'Event end time must be a time',

            'description.required'          => 'Event description is required',
            'description.string'            => 'Event description must be a string',

            'cancellation_policy.required'  => 'Event cancellation policy is required',
            'cancellation_policy.string'    => 'Event cancellation policy must be a string',


        ];
    }
}
