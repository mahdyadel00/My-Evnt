<?php

namespace App\Http\Requests\Backend\Webinar;

use App\Rules\Media;
use Illuminate\Foundation\Http\FormRequest;

class WebinarRequest extends FormRequest
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
        // dd($this->all());
        $webinarId = $this->route('id') ?? $this->route('webinar') ?? null;
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);
        $requiredOnCreateOrSometimes = $isUpdate ? 'sometimes' : 'required';
        return [
            'webinar_name'                  => [$requiredOnCreateOrSometimes, 'string', 'max:255'],
            'company_name'                  => ['nullable', 'string', 'max:255'],
            'description'                   => ['nullable', 'string'],
            'title'                         => ['nullable', 'string', 'max:255'],
            'date'                          => ['nullable'],
            'time'                          => ['nullable'],
            'link'                          => ['nullable', 'string', 'max:255'],
            'status'                        => ['nullable', 'boolean'],
            'facebook'                      => ['nullable', 'string', 'max:255'],
            'linkedin'                      => ['nullable', 'string', 'max:255'],
            'instagram'                     => ['nullable', 'string', 'max:255'],
            'youtube'                       => ['nullable', 'string', 'max:255'],
            'image'                         => [new Media],
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
            'webinar_name'                  => 'Webinar Name',
            'company_name'                  => 'Company Name',
            'description'                   => 'Description',
            'title'                         => 'Title',
            'date'                          => 'Date',
            'time'                          => 'Time',
            'link'                          => 'Link',
            'status'                        => 'Status',
            'facebook'                      => 'Facebook',
            'linkedin'                      => 'Linkedin',
            'instagram'                     => 'Instagram',
            'youtube'                       => 'Youtube',
            'image'                         => 'Image',
            'image.required'                 => 'Image is required',
            'image.image'                    => 'Image must be an image',
            'image.mimes'                    => 'Image must be a jpeg, png, jpg, gif, svg, webp, mp3, mp4, video, avi, video, mpeg, doc, docx, pdf, xlsx, pptx, txt',
            'image.max'                      => 'Image must be less than 2048kb',
            'image.dimensions'               => 'Image must be less than 1024x1024',
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
            'webinar_name.required'                  => 'Webinar Name is required',
            'company_name.required'                  => 'Company Name is required',
            'description.required'                   => 'Description is required',
            'title.required'                         => 'Title is required',
            'date.required'                          => 'Date is required',
            'time.required'                          => 'Time is required',
            'link.required'                          => 'Link is required',
            'status.required'                        => 'Status is required',
            'facebook.required'                      => 'Facebook is required',
            'linkedin.required'                      => 'Linkedin is required',
            'instagram.required'                     => 'Instagram is required',
            'youtube.required'                       => 'Youtube is required',
            'image.required'                         => 'Image is required',
            'image.image'                            => 'Image must be an image',
            'image.mimes'                            => 'Image must be a jpeg, png, jpg, gif, svg, webp, mp3, mp4, video, avi, video, mpeg, doc, docx, pdf, xlsx, pptx, txt',
            'image.max'                              => 'Image must be less than 2048kb',
            'image.dimensions'                       => 'Image must be less than 1024x1024',
        ];
    }

}
