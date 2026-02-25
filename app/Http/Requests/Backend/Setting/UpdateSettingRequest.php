<?php

namespace App\Http\Requests\Backend\Setting;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'name'              =>   ['nullable', 'string' , 'max:255'],
            'email'             =>   ['nullable', 'string' , 'max:255'],
            'phone'             =>   ['nullable', 'string' , 'max:255'],
            'phone_2'           =>   ['nullable', 'string' , 'max:255'],
            'description'       =>   ['nullable', 'string' , 'max:255'],
            'facebook'          =>   ['nullable', 'string' , 'max:255'],
            'twitter'           =>   ['nullable', 'string' , 'max:255'],
            'instagram'         =>   ['nullable', 'string' , 'max:255'],
            'linkedin'          =>   ['nullable', 'string' , 'max:255'],
            'youtube'           =>   ['nullable', 'string' , 'max:255'],
            'header_logo'       =>   ['nullable', new Media],
            'footer_logo'       =>   ['nullable', new Media],
            'favicon'           =>   ['nullable', new Media],
            'site_name'         =>   ['nullable', 'string', 'max:255'],
            'meta_title'        =>   ['nullable', 'string', 'max:60'],
            'meta_description'  =>   ['nullable', 'string', 'max:160'],
            'meta_keywords'     =>   ['nullable', 'string'],
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
            'name.string'       => 'Name must be a string',
            'name.max'          => 'Name must not be greater than 255 characters',
            'email.string'      => 'Email must be a string',
            'email.max'         => 'Email must not be greater than 255 characters',
            'phone.string'      => 'Phone must be a string',
            'phone.max'         => 'Phone must not be greater than 255 characters',
            'phone2.string'     => 'Phone2 must be a string',
            'phone2.max'        => 'Phone2 must not be greater than 255 characters',
            'description.string'    => 'Address must be a string',
            'description.max'       => 'Address must not be greater than 255 characters',
            'facebook.string'   => 'Facebook must be a string',
            'facebook.max'      => 'Facebook must not be greater than 255 characters',
            'twitter.string'    => 'Twitter must be a string',
            'twitter.max'       => 'Twitter must not be greater than 255 characters',
            'instagram.string'  => 'Instagram must be a string',
            'instagram.max'     => 'Instagram must not be greater than 255 characters',
            'linkedin.string'   => 'Linkedin must be a string',
            'linkedin.max'      => 'Linkedin must not be greater than 255 characters',
            'youtube.string'    => 'Youtube must be a string',
            'youtube.max'       => 'Youtube must not be greater than 255 characters',
            'logo'              => 'Logo must be an image',
            'favicon'           => 'Favicon must be an image',
            'site_name.max'     => 'Site name must not be greater than 255 characters',
            'meta_title.max'    => 'Meta title must not be greater than 60 characters',
            'meta_description.max' => 'Meta description must not be greater than 160 characters',
        ];
    }
}
