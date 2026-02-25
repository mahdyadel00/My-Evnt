<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media;   
use App\Models\Webinar;
use App\Models\Aboutwebinar;

class WebinarSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'description',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'youtube',
        'webinar_id',
        'aboutwebinar_id',
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    public function aboutwebinar()
    {
        return $this->belongsTo(Aboutwebinar::class);
    }
}
