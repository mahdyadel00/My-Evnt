<?php

namespace App\Http\Requests\Frontend\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'event_id'   =>  ['nullable'],
            'user_id'    =>  ['nullable'],
//            'total'      =>  ['required', 'numeric'],
//            'quantity'   =>  ['required', 'numeric'],
            'first_name' => ['sometimes' , 'string' , 'max:255'],
            'last_name'  => ['sometimes' , 'string' , 'max:255'],
            'email'      => ['sometimes' , 'string' , 'email' , 'max:255' , 'email'],
            'phone'      => ['sometimes' , 'string' , 'max:255'],

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
            'event_id.required' => 'Event is required',
            'event_id.exists'   => 'Event not found',
            'user_id.required'  => 'User is required',
            'user_id.exists'    => 'User not found',
            'total.required'    => 'Total is required',
            'total.numeric'     => 'Total must be a number',

            'quantity.required' => 'Quantity is required',
            'quantity.numeric'  => 'Quantity must be a number',

            'first_name.required' => 'First name is required',
            'first_name.string'   => 'First name must be a string',
            'first_name.max'      => 'First name must not be greater than 255 characters',

            'last_name.required' => 'Last name is required',
            'last_name.string'   => 'Last name must be a string',
            'last_name.max'      => 'Last name must not be greater than 255 characters',

            'email.required' => 'Email is required',
            'email.string'   => 'Email must be a string',
            'email.email'    => 'Email must be a valid email',
            'email.max'      => 'Email must not be greater than 255 characters',

            'phone.required' => 'Phone is required',
            'phone.string'   => 'Phone must be a string',
            'phone.max'      => 'Phone must not be greater than 255 characters',


        ];
    }
}
