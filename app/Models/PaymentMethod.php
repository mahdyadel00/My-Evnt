<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected  $fillable = [
        'type',
        'name',
        'phone',
        'account_number',
        'account_name',
        'bank_name',
        'branch',
        'iban',
        'card_number',
        'card_name',
        'card_expiry',
        'card_cvc',
        'address',
        'email',
        'note',
        'company_id',
        'event_id',
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
