<?php

namespace App\Models;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aboutwebinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'title',
        'description',
    ];
    

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
    
    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }
    
    
}
