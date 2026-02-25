<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Media implements Rule
{
    private array $mimes = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp', 'mp3', 'mp4', "video", "avi", "video", "mpeg", "doc", "docx", "pdf", "xlsx", "pptx", "txt"];
    private array $messages;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
//        if (empty($value)) {
//            $this->messages = [__('validation.required', ['attribute' => __("attributes.$attribute")])];
//            return Validator::make([$attribute => $value], [$attribute => ['required']])->passes();
//        }

        $rules = !is_array($value)
            ? [$attribute => ['file', 'mimes:' . implode(',', $this->mimes)]]
            : [
                $attribute                 => ['array'],
                "$attribute.*.file"        => ['mimes:' . implode(',', $this->mimes)],
                "$attribute.*.name"        => ["sometimes", 'string'],
                "$attribute.*.order"       => ["sometimes", 'integer'],
                "$attribute.*.is_main"     => ["sometimes", 'boolean'],
                "$attribute.*.description" => ["sometimes", 'string'],
            ];
        $this->messages = array_map(static fn ($message) => $message[0], Validator::make([$attribute => $value], $rules)->messages()->toArray());
        return Validator::make([$attribute => $value], $rules)->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return array<string, string>
     */
    public function message(): array
    {
        return $this->messages;
    }
}
