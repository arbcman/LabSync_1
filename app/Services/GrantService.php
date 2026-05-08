<?php

namespace App\Services;

use App\Http\Controllers\EquipmentSessionController;
use App\Models\EquipmentSession;
use App\Models\Reservation;

class GrantService
{
    public function checkBalance(float $cost): bool
    {

        $grant = auth()->user()->piProfile->grants()->first();

        if ($grant && $cost > $grant->balance) {
            return false;
        }
        $newBalance = $grant->balance - $cost;
        $grant->update(['balance' => $newBalance]);
        
        return true;
    }
}