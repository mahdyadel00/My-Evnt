<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Webinar;
use App\Models\Media;

class WebinarCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'webinar_id',
        'link',
        'status',
    ];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
