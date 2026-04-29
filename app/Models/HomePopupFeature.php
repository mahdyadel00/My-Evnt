<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Promotional popup on the public homepage: optional linked event or fully manual copy.
 */
class HomePopupFeature extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'image_path',
        'manual_location',
        'manual_datetime_label',
        'cta_label',
        'dismiss_label',
        'show_action_buttons',
        'is_active',
        'sort_order',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'show_action_buttons' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    public function resolveTitle(): string
    {
        if ($this->title) {
            return $this->title;
        }
        if ($this->event) {
            return (string) $this->event->name;
        }

        return '';
    }

    public function resolveDescription(): string
    {
        if ($this->description) {
            return $this->description;
        }
        if ($this->event) {
            if (! empty($this->event->summary)) {
                return (string) $this->event->summary;
            }
            if (! empty($this->event->description)) {
                return Str::limit(strip_tags((string) $this->event->description), 400);
            }
        }

        return '';
    }

    /**
     * Plain-text description snippet for the homepage popup (no HTML; keeps layout stable).
     */
    public function resolveDescriptionPreview(int $maxChars = 180): string
    {
        $raw = '';
        if (filled($this->description)) {
            $raw = (string) $this->description;
        } elseif ($this->event) {
            if (! empty($this->event->summary)) {
                $raw = (string) $this->event->summary;
            } elseif (! empty($this->event->description)) {
                $raw = (string) $this->event->description;
            }
        }
        $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($raw)));
        $plain = self::decodeHtmlEntities($plain);

        return Str::limit($plain, $maxChars, '…');
    }

    /**
     * Title for popup display: normalises multiply-encoded HTML entities from DB.
     */
    public function resolveTitleDisplay(): string
    {
        return self::decodeHtmlEntities(trim($this->resolveTitle()));
    }

    /**
     * Badge / category label for popup display.
     */
    public function resolveBadgeDisplay(): string
    {
        return self::decodeHtmlEntities($this->resolveBadge());
    }

    /**
     * Location line for popup display.
     */
    public function resolveLocationDisplay(): string
    {
        return self::decodeHtmlEntities($this->resolveLocation());
    }

    /**
     * Date/time line for popup display (manual copy may contain entities).
     */
    public function resolveDatetimeDisplay(): string
    {
        return self::decodeHtmlEntities($this->resolveDatetimeLabel());
    }

    public function resolveCtaLabelDisplay(): string
    {
        return self::decodeHtmlEntities((string) $this->cta_label);
    }

    public function resolveDismissLabelDisplay(): string
    {
        return self::decodeHtmlEntities((string) $this->dismiss_label);
    }

    /**
     * Decode HTML entities until stable (handles e.g. &amp;quot; stored in the database).
     */
    private static function decodeHtmlEntities(string $value): string
    {
        $decoded = $value;
        for ($i = 0; $i < 10; $i++) {
            $next = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($next === $decoded) {
                return $decoded;
            }
            $decoded = $next;
        }

        return $decoded;
    }

    public function resolveBadge(): string
    {
        if ($this->event?->category) {
            return (string) $this->event->category->name;
        }

        return __('Event');
    }

    public function resolveLocation(): string
    {
        if ($this->manual_location) {
            return $this->manual_location;
        }
        if ($this->event) {
            if ($this->event->location) {
                return (string) $this->event->location;
            }
            if ($this->event->city) {
                return (string) $this->event->city->name;
            }
        }

        return '';
    }

    public function resolveDatetimeLabel(): string
    {
        if ($this->manual_datetime_label) {
            return $this->manual_datetime_label;
        }
        if (! $this->event) {
            return '';
        }
        $firstDate = $this->event->eventDates->sortBy('start_date')->first();
        if (! $firstDate || ! $firstDate->start_date) {
            return '';
        }
        $date = Carbon::parse($firstDate->start_date)->locale(app()->getLocale());
        $label = $date->translatedFormat('D, M j');
        if ($firstDate->start_time) {
            $label .= ', '.Carbon::parse($firstDate->start_time)->format('g:i A');
        }

        return $label;
    }

    /**
     * Public URL for an admin-uploaded popup image (relative to current host, avoids APP_URL / host mismatches).
     */
    public function resolveManualUploadPublicUrl(): ?string
    {
        if (filled($this->image_path)) {
            return self::publicStorageUrlFromRelativePath((string) $this->image_path);
        }
        if (array_key_exists('image_url', $this->attributes) && filled($this->attributes['image_url'])) {
            return self::normalizeStoredMediaUrl((string) $this->attributes['image_url']);
        }

        return null;
    }

    public function resolveImageUrl(): string
    {
        $manual = $this->resolveManualUploadPublicUrl();
        if ($manual !== null) {
            return $manual;
        }

        return 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=500&fit=crop&auto=format&q=80';
    }

    /**
     * Build a root-relative /storage/... URL so the browser always requests the same host as the page.
     */
    private static function publicStorageUrlFromRelativePath(string $path): string
    {
        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        if (str_starts_with($path, 'storage/')) {
            return '/'.$path;
        }

        return '/storage/'.$path;
    }

    /**
     * Legacy DB may store a full URL or a path under public storage.
     */
    private static function normalizeStoredMediaUrl(string $value): string
    {
        $value = str_replace('\\', '/', trim($value));
        if (preg_match('#^https?://#i', $value)) {
            return $value;
        }

        return self::publicStorageUrlFromRelativePath($value);
    }

    public function resolveCtaUrl(): string
    {
        if ($this->event) {
            $eventDateId = null;
            if ($this->event->relationLoaded('eventDates')) {
                $eventDateId = $this->event->eventDates->sortBy('start_date')->value('id');
            } else {
                $eventDateId = $this->event->eventDates()->orderBy('start_date')->value('id');
            }

            if ($eventDateId) {
                return route('checkout_user', ['event_date_id' => $eventDateId]);
            }

            return route('event', $this->event->uuid);
        }

        return route('events');
    }

    public function resolveBannerLinkUrl(): string
    {
        if ($this->event) {
            return route('event', $this->event->uuid);
        }

        return route('events');
    }
}
