<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPhone extends FormRequest
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
            'phone'    =>  ['required', 'string', 'max:11', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'code'     =>  ['required', 'string', 'min:4', 'max:4']
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
            'phone.required'    => __("auth.phone_required" , ["attribute" => __("auth.phone")]),
            'phone.string'      => __("auth.phone_string" , ["attribute" => __("auth.phone")]),
            'phone.max'         => __("auth.phone_max" , ["attribute" => __("auth.phone")]),
            'phone.regex'       => __("auth.phone_regex" , ["attribute" => __("auth.phone")]),
            'phone.exists'      => __("auth.phone_not_found"),

            'code.required'     => __("auth.code_required" , ["attribute" => __("auth.code")]),
            'code.string'       => __("auth.code_string" , ["attribute" => __("auth.code")]),
            'code.min'          => __("auth.code_min" , ["attribute" => __("auth.code")]),
            'code.max'          => __("auth.code_max" , ["attribute" => __("auth.code")]),
        ];
    }
}
