<?php
declare(strict_types=1);

namespace App\Http\Requests\Backend\Company;

    use App\Rules\Media;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    /**
     * Company form validation request.
     */
    class CompanyRequest extends FormRequest
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
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
         */
        public function rules(): array
        {
            $companyId = $this->route('id') ?? $this->route('company') ?? null;
            $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);
            $requiredOnCreateOrSometimes = $isUpdate ? 'sometimes' : 'required';

            return [
                'first_name'                                => ['nullable', 'string', 'max:255'],
                'last_name'                                 => ['nullable', 'string', 'max:255'],
                'user_name'                                 => ['nullable', 'string', 'max:255', Rule::unique('companies', 'user_name')->ignore($companyId)],
                'company_name'                              => [$requiredOnCreateOrSometimes, 'string', 'max:255', Rule::unique('companies', 'company_name')->ignore($companyId)],
                'email'                                     => ['nullable', 'string', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($companyId)],
                'phone'                                     => ['nullable', 'string', 'max:255', Rule::unique('companies', 'phone')->ignore($companyId)],
                'whats_app'                                 => ['nullable', 'string', 'max:255', Rule::unique('companies', 'whats_app')->ignore($companyId)],
                'website'                                   => ['nullable', 'string', 'max:255'],
                'facebook'                                  => ['nullable', 'string', 'max:255'],
                'twitter'                                   => ['nullable', 'string', 'max:255'],
                'instagram'                                 => ['nullable', 'string', 'max:255'],
                'linkedin'                                  => ['nullable', 'string', 'max:255'],
                'youtube'                                   => ['nullable', 'string', 'max:255'],
                'snapchat'                                  => ['nullable', 'string', 'max:255'],
                'tiktok'                                    => ['nullable', 'string', 'max:255'],
                'description'                               => ['nullable', 'string'],
                'address'                                   => ['nullable', 'string', 'max:255'],
                'commercial_register_image'                 => ['nullable', new Media()],
                'tax_card'                                  => ['nullable', new Media()],
                'commercial_record'                         => ['nullable', new Media()],
                'logo'                                      => ['nullable', new Media()],
                'status'                                    => ['nullable', 'boolean'],
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
                'first_name'                                => 'First Name',
                'last_name'                                 => 'Last Name',
                'user_name'                                 => 'User Name',
                'company_name'                              => 'Company Name',
                'email'                                     => 'Company Email',
                'phone'                                     => 'Company Phone', // Add this line to the file
                'whats_app'                                 => 'Company WhatsApp',
                'website'                                   => 'Company Website',
                'facebook'                                  => 'Facebook',
                'twitter'                                   => 'Twitter',
                'instagram'                                 => 'Instagram',
                'linkedin'                                  => 'Linkedin',
                'youtube'                                   => 'Youtube',
                'snapchat'                                  => 'Snapchat',
                'tiktok'                                    => 'Tiktok',
                'status'                                    => 'Status',
                'description'                               => 'Company Description',
                'address'                                   => 'Company Address',
                'commercial_register_image'                 => 'Commercial Register Image',
                'tax_card'                                  => 'Tax Card',
                'commercial_record'                         => 'Commercial Record',
                'logo'                                      => 'Logo',
            ];
        }

        /**
         * Get the validation messages that apply to the request.
         *
         * @return array<string, string>
         */
        /**
         * Get custom validation messages for the request.
         *
         * @return array<string, string>
         */
        public function messages(): array
        {
            return [
                'company_name.required'                     => 'The :attribute field is required.',
                'company_name.unique'                       => 'The :attribute field must be unique.',
                'email.required'                            => 'The :attribute field is required.',
                'email.unique'                              => 'The :attribute field must be unique.',
                'phone.required'                            => 'The :attribute field is required.',
                'phone.unique'                              => 'The :attribute field must be unique.',
                'whats_app.required'                        => 'The :attribute field is required.',
                'whats_app.unique'                          => 'The :attribute field must be unique.',
            ];
        }
    }
