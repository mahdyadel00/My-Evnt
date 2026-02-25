<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code',
        'phone',
        'extension',
        'holder_name',
        'phoneable_id',
        'phoneable_type',
    ];

    public function phoneable()
    {
        return $this->morphTo();
    }
}
