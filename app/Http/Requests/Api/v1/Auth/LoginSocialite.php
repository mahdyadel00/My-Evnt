<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class LoginSocialite extends FormRequest
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
            'social_type'       =>  ['required', 'string', 'in:google,facebook'],
            'social_id'         =>  ['nullable', 'string'],
            'name'              =>  ['nullable', 'string'],
            'email'             =>  ['nullable', 'email'],
            'photo'             =>  [new Media],
            'access_token'      =>  ['nullable', 'string'],
        ];
    }



    public function messages(): array
    {
        return [
            'social_type.required'       => 'Social type is required.',
            'social_type.in'             => 'Social type must be either google or facebook.',
            'social_id.required'         => 'Social ID is required.',
            'name.required'              => 'Name is required.',
            'email.required'             => 'Email is required.',
            'email.email'                => 'Email is not valid.',
            'email.unique'               => 'Email already exists.',
            'photo.required'             => 'Photo is required.',
            'access_token.required'      => 'Access token is required.',
        ];
    }
}
