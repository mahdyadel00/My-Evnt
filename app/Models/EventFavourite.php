<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class EventFavourite extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = ['event_id', 'user_id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
