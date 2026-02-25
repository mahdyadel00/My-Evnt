<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Phone implements Rule
{
    private array $regex = [
        'phone' => '/^([0-9\s\-\+\(\)]*)$/',
    ];
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

        $rules = !is_array($value)
            ? ['phone' => $this->regex['phone']]
            : $value;

        $validator = Validator::make($rules, [
            'phone' => $this->regex['phone'],
        ]);

        if ($validator->fails()) {
            $this->messages = $validator->errors()->messages();
            return false;
        }
        return true;

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
