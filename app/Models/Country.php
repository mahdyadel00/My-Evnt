<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderByLatest;
use App\Observers\AuditObserver;
class Country extends Model
{
    use HasFactory, OrderByLatest;

    protected $observables = ['logging'];

    protected $fillable = [
        'code',
        'is_available',
        'name',
    ];

    protected $casts = [
        'is_available' => 'boolean',
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

    public function cities()
    {
        return $this->hasMany(City::class);
    }

}
