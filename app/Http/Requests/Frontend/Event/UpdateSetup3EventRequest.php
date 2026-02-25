<?php

namespace App\Http\Requests\Frontend\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSetup3EventRequest extends FormRequest
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
            'tickets'                   => 'sometimes|array',
            'tickets.*'                 => 'required_with:eventDates|array',
            'tickets.*.ticket_type'     => 'required_with:eventDates|string|max:255',
            'tickets.*.price'           => 'required_with:eventDates|numeric',
            'tickets.*.quantity'        => 'required_with:eventDates|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'tickets.required'                  => 'Tickets are required',
            'tickets.array'                     => 'Tickets must be an array',
            'tickets.*.ticket_type.required'    => 'Ticket type is required',
            'tickets.*.ticket_type.string'      => 'Ticket type must be a string',
            'tickets.*.ticket_type.max'         => 'Ticket type must not be more than 255 characters',
            'tickets.*.price.required'          => 'Ticket price is required',
            'tickets.*.price.numeric'           => 'Ticket price must be a number',
            'tickets.*.quantity.required'       => 'Ticket quantity is required',
            'tickets.*.quantity.numeric'        => 'Ticket quantity must be a number',
        ];
    }
}
