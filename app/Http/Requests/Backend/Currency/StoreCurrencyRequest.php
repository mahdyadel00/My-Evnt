<?php

namespace App\Http\Requests\Backend\Currency;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
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

            'name'      => ['required', 'string', 'max:255'],
            'code'      => ['required', 'string', 'max:255'],
            'status'    => ['required', 'boolean'],
            'image'     => [new Media],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'      => 'Currency Name',
            'code'      => 'Currency Code',
            'status'    => 'Status',
            'image'     => 'Currency Image',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'The :attribute field is required.',
            'code.required'     => 'The :attribute field is required.',
            'status.required'   => 'The :attribute field is required.',
            'image.required'    => 'The :attribute field is required.',
        ];
    }
}
