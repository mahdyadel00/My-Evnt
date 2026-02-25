<?php

namespace App\Http\Requests\Backend\TermsCondition;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTermsConditionRequest extends FormRequest
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
                'terms_condition'   => ['sometimes', 'string'],
                'privacy_policy'    => ['sometimes', 'string'],
                'about_us'   => ['sometimes', 'string'],
                'shipping_payment'  => ['sometimes', 'string'],
        ];
    }

    /**
     * messages
     *
     */
    public function messages(): array
    {
        return [
            'terms_condition.string'    => 'The terms condition must be a string.',
            'privacy_policy.string'     => 'The privacy policy must be a string.',
            'about_us.string'           => 'The about us must be a string.',
            'shipping_payment.string'   => 'The shipping payment must be a string.',
        ];
    }
}
