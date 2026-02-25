<?php

namespace App\Http\Requests\Backend\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponStore extends FormRequest
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
            'event_id'      => ['sometimes','exists:events,id'],
            'type'          => ['sometimes','string'],
            'value'         => ['sometimes','numeric'],
            'start_date'    => ['sometimes','date'],
            'end_date'      => ['sometimes','date'],
            'description'   => ['sometimes','string'],
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
            'event_id.required'     => 'Event is required',
            'event_id.exists'       => 'Event not found',
            'type.required'         => 'Type is required',
            'value.required'        => 'Value is required',
            'start_date.required'   => 'Start date is required',
            'end_date.required'     => 'End date is required',
        ];
    }
}
