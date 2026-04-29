<?php

declare(strict_types=1);

namespace App\Http\Requests\Backend\HomePopupFeature;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomePopupFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $rawActive = $this->input('is_active', '0');
        $isActive = false;
        if (is_bool($rawActive)) {
            $isActive = $rawActive;
        } elseif (is_array($rawActive)) {
            $isActive = in_array(1, $rawActive, true) || in_array('1', array_map('strval', $rawActive), true);
        } else {
            $isActive = (string) $rawActive === '1';
        }

        $rawShowButtons = $this->input('show_action_buttons', '0');
        $showActionButtons = false;
        if (is_bool($rawShowButtons)) {
            $showActionButtons = $rawShowButtons;
        } elseif (is_array($rawShowButtons)) {
            $showActionButtons = in_array(1, $rawShowButtons, true) || in_array('1', array_map('strval', $rawShowButtons), true);
        } else {
            $showActionButtons = (string) $rawShowButtons === '1';
        }

        $this->merge([
            'event_id'                      => $this->filled('event_id') ? (int) $this->input('event_id') : null,
            'is_active'                     => $isActive,
            'show_action_buttons'           => $showActionButtons,
            'sort_order'                    => (int) $this->input('sort_order', 0),
            'cta_label'                     => $this->filled('cta_label') ? $this->input('cta_label') : 'Get Ticket',
            'dismiss_label'                 => $this->filled('dismiss_label') ? $this->input('dismiss_label') : 'Maybe Later',
        ]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id'                      => ['nullable', 'integer', 'exists:events,id'],
            'title'                         => ['nullable', 'string', 'max:255'],
            'description'                   => ['nullable', 'string', 'max:20000'],
            'image'                         => ['required', 'image', 'max:4096'],
            'manual_location'               => ['nullable', 'string', 'max:255'],
            'manual_datetime_label'         => ['nullable', 'string', 'max:255'],
            'cta_label'                     => ['required', 'string', 'max:80'],
            'dismiss_label'                 => ['required', 'string', 'max:80'],
            'show_action_buttons'           => ['required', 'boolean'],
            'is_active'                     => ['required', 'boolean'],
            'sort_order'                    => ['nullable', 'integer', 'min:0', 'max:999999'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $eventId = $this->input('event_id');
            $title = trim((string) $this->input('title', ''));
            $description = trim((string) $this->input('description', ''));

            if (empty($eventId) && ($title === '' || $description === '')) {
                $validator->errors()->add(
                    'event_id',
                    __('Link an event, or leave event empty and fill both title and description manually.')
                );
            }
        });
    }
}
