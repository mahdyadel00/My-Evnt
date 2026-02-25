<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CheckResetCode extends FormRequest
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
            'phone'     => ['required', 'string', 'max:255' , 'exists:users,phone'],
            'code'      => ['required', 'string', 'max:255'],
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
            'phone.required'    => 'Phone is required',
            'phone.string'      => 'Phone must be string',
            'phone.max'         => 'Phone max is 255',
            'phone.exists'      => 'Phone not found',
            'code.required'     => 'Code is required',
            'code.string'       => 'Code must be string',
            'code.max'          => 'Code max is 255',
        ];
    }
}
