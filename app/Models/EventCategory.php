<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_parent',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(EventCategory::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(EventCategory::class, 'parent_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'category_users');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
