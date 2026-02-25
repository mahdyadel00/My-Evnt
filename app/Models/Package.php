<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description' , 'price_monthly', 'price_yearly', 'discount'];

    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
