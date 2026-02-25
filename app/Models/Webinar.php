<?php

namespace App\Models;

use App\Models\Aboutwebinar;
use App\Models\WebinarSpeaker;
use App\Models\WebinarCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Webinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_name',
        'slug',
        'company_name',
        'description',
        'title',
        'date',
        'time',
        'link',
        'status',
        'facebook',
        'linkedin',
        'instagram',
        'youtube',
    ];

    protected $casts = [
        'date'      => 'datetime',
        'time'      => 'datetime',
        'status'    => 'boolean',
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function aboutwebinars()
    {
        return $this->hasMany(Aboutwebinar::class);
    }

    public function speakers()
    {
        return $this->hasMany(WebinarSpeaker::class);
    }

    public function cards()
    {
        return $this->hasMany(WebinarCard::class);
    }

    /**
     * Get route key name for route model binding
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
