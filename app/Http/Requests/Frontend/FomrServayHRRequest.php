<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class FomrServayHRRequest extends FormRequest
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
            'event_id'                  => ['required', 'exists:events,id'],
            'first_name'                => ['required', 'string', 'max:255'],
            'last_name'                 => ['required', 'string', 'max:255'],
            'email'                     => ['required', 'email', 'max:255'],
            'phone'                     => ['required', 'string', 'max:20', 'regex:/^(\+2|002)?01[0125678][0-9]{8}$/'],
            'job_title'                 => ['nullable', 'string', 'max:255'],
            'organization'              => ['nullable', 'string', 'max:255'],
            'ticket_type'               => ['required', 'in:attendee,startups'],
            'attendee_type'             => ['required_if:ticket_type,attendee', 'nullable', 'in:attendee,mentorship'],
            'mentorship_track'          => ['required_if:attendee_type,mentorship', 'nullable', 'in:agricultural_biotech,food_packaging_processing,medicinal_aromatic_plants'],
            'startup_file'              => ['required_if:ticket_type,startups', 'nullable', 'file', 'mimes:pdf,ppt,pptx,doc,docx', 'max:10240'],
        ];
    }



    public function messages(): array
    {
        return [
            'event_id.required'         => 'The event field is required.',
            'event_id.exists'           => 'The selected event is invalid.',
            'first_name.required'       => 'The first name field is required.',
            'last_name.required'        => 'The last name field is required.',
            'email.required'            => 'The email field is required.',
            'email.email'               => 'The email must be a valid email address.',
            'phone.required'            => 'The phone field is required.',
            'phone.regex'               => 'Please enter a valid Egyptian mobile number (e.g., 01012345678 or +201012345678).',
            'job_title.max'             => 'The job title may not be greater than 255 characters.',
            'organization.max'          => 'The organization may not be greater than 255 characters.',
            'ticket_type.required'      => 'Please select a ticket type.',
            'ticket_type.in'            => 'The selected ticket type is invalid.',
            'attendee_type.required_if' => 'Please select an attendee type.',
            'attendee_type.in'          => 'The selected attendee type is invalid.',
            'mentorship_track.required_if' => 'Please select a mentorship track.',
            'mentorship_track.in'       => 'The selected mentorship track is invalid.',
            'startup_file.required_if'  => 'Please upload your pitch deck file.',
            'startup_file.file'         => 'The uploaded file must be a valid file.',
            'startup_file.mimes'        => 'The file must be a PDF, PPT, PPTX, DOC, or DOCX file.',
            'startup_file.max'          => 'The file size must not exceed 10MB.',
        ];
    }
}
