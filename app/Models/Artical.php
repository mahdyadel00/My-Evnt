<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artical extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'title',
        'description',
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
