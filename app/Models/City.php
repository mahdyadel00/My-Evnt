<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderByLatest;
use App\Observers\AuditObserver;
class City extends Model
{
    use HasFactory , OrderByLatest;

    protected $observables = ['logging'];

    protected $fillable = [
        'country_id',
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
