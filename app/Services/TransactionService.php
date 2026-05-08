<?php

namespace App\Services;

use App\Models\EquipmentSession;
use App\Models\Transaction;

class TransactionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function makeNew(EquipmentSession $session, float $cost)
    {
        $normalized_amount = $cost * config('app.normalization_factor');
        return Transaction::create([
            'session_id' => $session->id,
            'amount' => $cost,
            'normalized_amount' => $normalized_amount,
        ]);
    }
}