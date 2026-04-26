<?php

declare(strict_types=1);

namespace App\Http\Requests\Backend\Setting;

use App\Enums\OutboundMessageChannel;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendUserOutboundMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize multi-select payload (Select2 placeholder option, etc.).
     */
    protected function prepareForValidation(): void
    {
        $ids = $this->input('user_ids', []);
        if (!is_array($ids)) {
            $ids = [];
        }

        $ids = array_values(array_unique(array_filter(
            array_map(static fn ($v) => is_numeric($v) ? (int) $v : null, $ids),
            static fn ($v) => $v !== null && $v > 0
        )));

        $this->merge(['user_ids' => $ids]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'distinct', 'exists:users,id'],
            'message' => ['required', 'string', 'max:50000'],
            'channel' => ['required', 'string', Rule::in(OutboundMessageChannel::values())],
        ];
    }

    /**
     * Reject tampered IDs for users who are not allowed in the messaging UI (no real name / phone).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $ids = $this->input('user_ids', []);
            if (!is_array($ids) || $ids === []) {
                return;
            }

            $users = User::query()->whereIn('id', $ids)->get()->keyBy('id');

            foreach ($ids as $index => $id) {
                $user = $users->get($id);
                if (!$user instanceof User || !$user->isEligibleForOutboundMessaging()) {
                    $validator->errors()->add(
                        'user_ids.' . $index,
                        __('One or more selected users are not valid for messaging.')
                    );
                }
            }
        });
    }
}
