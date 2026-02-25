<?php

namespace App\Http\Requests\Front\Sponsor;

use Illuminate\Foundation\Http\FormRequest;

class StoreSponsorRequest extends FormRequest
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
            'ad_fee_id' => ['required', 'integer', 'exists:ad_fees,id'],
        ];
    }


    /**
     * Custom message for validation
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ad_fee_id.required'    => __('validation.required', ['attribute' => __('attributes.ad_fee_id')]),
            'ad_fee_id.integer'     => __('validation.integer', ['attribute' => __('attributes.ad_fee_id')]),
            'ad_fee_id.exists'      => __('validation.exists', ['attribute' => __('attributes.ad_fee_id')]),
        ];
    }
}
