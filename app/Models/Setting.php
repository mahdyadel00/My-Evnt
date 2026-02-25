<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class Setting extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'email',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'phone',
        'phone_2',
        'whats_app',
        'name',
        'description',
        'site_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
