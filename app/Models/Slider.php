<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderByLatest;
use App\Observers\AuditObserver;
class Slider extends Model
{
    use HasFactory , OrderByLatest;

    protected $observables = ['logging'];

    protected $fillable = [
        'title',
        'description',
        'url',
        'event_id',
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
