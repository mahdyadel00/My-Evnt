<?php

namespace App\Http\Requests\Backend\Customer;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'cover'         => [new Media],
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
            'title.required'        => 'Title is required',
            'title.string'          => 'Title must be a string',
            'title.max'             => 'Title must not be greater than 255 characters',
            'description.required'  => 'Description is required',
            'description.string'    => 'Description must be a string',
        ];
    }
}
