<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model // AKA BillingRecord Class
{

    protected $fillable = [
        'session_id',
        'amount',
        'normalized_amount',
    ];


    public function equipmentSession()
    {
        return $this->belongsTo(EquipmentSession::class);
    }
}