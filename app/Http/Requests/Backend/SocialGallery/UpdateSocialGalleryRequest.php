<?php

declare(strict_types=1);

namespace App\Http\Requests\Backend\SocialGallery;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateSocialGalleryRequest
 *
 * Validates data for updating social gallery
 *
 * @package App\Http\Requests\Backend\SocialGallery
 */
class UpdateSocialGalleryRequest extends FormRequest
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
            'title'                                 => ['required', 'string', 'max:255'],
            'instagram_handle'                      => ['required', 'string', 'max:255'],
            'instagram_link'                        => ['required', 'url', 'max:255'],
            'status'                                => ['sometimes', 'boolean'],
            'images.*'                              => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'post_urls.*'                           => ['nullable', 'url', 'max:500'],
            'existing_post_urls.*'                  => ['nullable', 'url', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title'                                 => 'Title',
            'instagram_handle'                      => 'Instagram Handle',
            'instagram_link'                        => 'Instagram Link',
            'status'                                => 'Status',
            'images.*'                              => 'Images',
            'post_urls.*'                           => 'Post URLs',
            'existing_post_urls.*'                  => 'Existing Post URLs',
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
            'title.required'                        => 'The title field is required.',
            'title.max'                             => 'The title may not be greater than :max characters.',
            'instagram_handle.required'             => 'The Instagram handle field is required.',
            'instagram_handle.max'                  => 'The Instagram handle may not be greater than :max characters.',
            'instagram_link.required'               => 'The Instagram link field is required.',
            'instagram_link.url'                    => 'The Instagram link must be a valid URL.',
            'instagram_link.max'                    => 'The Instagram link may not be greater than :max characters.',
            'images.*.image'                        => 'Each file must be an image.',
            'images.*.mimes'                        => 'Each image must be a file of type: jpeg, jpg, png, gif, webp.',
            'images.*.max'                          => 'Each image may not be greater than 2MB.',
            'post_urls.*.url'                       => 'Each post URL must be a valid URL.',
            'post_urls.*.max'                       => 'Each post URL may not be greater than :max characters.',
            'existing_post_urls.*.url'              => 'Each existing post URL must be a valid URL.',
            'existing_post_urls.*.max'              => 'Each existing post URL may not be greater than :max characters.',
        ];
    }
}
