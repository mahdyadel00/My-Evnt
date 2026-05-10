<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Homepage "Are you organizer" carousel slide: title, description (subtitle), hero image only.
 *
 * hero_image holds either storage path (disk public) or full https URL.
 */
class OrganizerFeatureSlide extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sort_order',
        'is_active',
        'title',
        'subtitle',
        'hero_image',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function heroImageSrc(): string
    {
        $value = trim((string) $this->hero_image);
        if ($value === '') {
            return '';
        }
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        if (Str::startsWith($value, '//')) {
            return 'https:'.$value;
        }

        // Root-relative: works on http://127.0.0.1 even when APP_URL is another domain (Storage::url breaks that).
        $normalized = ltrim(str_replace('\\', '/', $value), '/');

        return '/storage/'.$normalized;
    }
}
