<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class CategoryUser extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = ['user_id', 'category_id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class);
    }

}
