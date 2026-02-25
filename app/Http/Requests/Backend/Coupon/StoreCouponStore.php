<?php

namespace App\Http\Requests\Backend\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponStore extends FormRequest
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
            'event_id'      => ['required', 'exists:events,id'],
            'code'          => ['required', 'string', 'unique:coupons,code'],
            'type'          => ['required', 'string'],
            'value'         => ['required', 'numeric'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date'],
            'description'   => ['nullable', 'string'],
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
            'code.required'         => 'Code is required',
            'code.unique'           => 'Code already exists',
            'type.required'         => 'Type is required',
            'value.required'        => 'Value is required',
            'start_date.required'   => 'Start date is required',
            'end_date.required'     => 'End date is required',
        ];
    }
}
