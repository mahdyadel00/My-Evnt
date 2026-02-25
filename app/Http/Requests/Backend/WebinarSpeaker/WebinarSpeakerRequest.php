<?php

namespace App\Http\Requests\Backend\WebinarSpeaker;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Media;
class WebinarSpeakerRequest extends FormRequest
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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);
        $requiredOnCreateOrSometimes = $isUpdate ? 'sometimes' : 'required';
        return [
            'name'                  => [$requiredOnCreateOrSometimes, 'string', 'max:255'],
            'job_title'             => [$requiredOnCreateOrSometimes, 'string', 'max:255'],
            'description'           => [$requiredOnCreateOrSometimes, 'string'],
            'facebook'              => ['nullable', 'string', 'max:255'],
            'twitter'               => ['nullable', 'string', 'max:255'],
            'linkedin'              => ['nullable', 'string', 'max:255'],
            'instagram'             => ['nullable', 'string', 'max:255'],
            'youtube'               => ['nullable', 'string', 'max:255'],
            'image'                 => [new Media],
            'webinar_id'            => [$requiredOnCreateOrSometimes, 'exists:webinars,id'],
            'aboutwebinar_id'       => [$requiredOnCreateOrSometimes, 'exists:aboutwebinars,id'],
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
            'name'                  => 'Name',
            'job_title'             => 'Job Title',
            'description'           => 'Description',
            'facebook'              => 'Facebook',
            'twitter'               => 'Twitter',
            'linkedin'              => 'Linkedin',
            'instagram'             => 'Instagram',
            'youtube'               => 'Youtube',
            'image'                 => 'Image',
            'webinar_id'            => 'Webinar',
            'aboutwebinar_id'       => 'About Webinar',
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
            'name.string'                  => 'Name must be a string',
            'name.max'                     => 'Name must not be greater than 255 characters',
            'job_title.string'             => 'Job Title must be a string',
            'job_title.max'                => 'Job Title must not be greater than 255 characters',
            'description.string'           => 'Description must be a string',
            'facebook.string'              => 'Facebook must be a string',
            'facebook.max'                 => 'Facebook must not be greater than 255 characters',
            'twitter.string'               => 'Twitter must be a string',
            'twitter.max'                  => 'Twitter must not be greater than 255 characters',
            'linkedin.string'              => 'Linkedin must be a string',
            'linkedin.max'                 => 'Linkedin must not be greater than 255 characters',
            'instagram.string'             => 'Instagram must be a string',
            'instagram.max'                => 'Instagram must not be greater than 255 characters',
            'youtube.string'               => 'Youtube must be a string',
            'youtube.max'                  => 'Youtube must not be greater than 255 characters',
            'image.mimes'                  => 'Image must be a file of type: jpg,jpeg,png,gif,svg,webp',
            'webinar_id.exists'            => 'Webinar must exist',
            'aboutwebinar_id.exists'       => 'About Webinar must exist',
        ];
    }
}
