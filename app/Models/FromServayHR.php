<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FromServayHR extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'job_title',
        'organization',
        'ticket_type',
        'attendee_type',
        'mentorship_track',
        'startup_file',
        'company_name',
        'position',
        'notes',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
