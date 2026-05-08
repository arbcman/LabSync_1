<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentSession;
use App\Models\Grant;
use App\Models\Reservation;
use App\Services\GrantService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EquipmentSessionController extends Controller
{
    public function storeSessionForReservation(Reservation $reservation)
    {

        $request = [
            'user_id' => $reservation->user_id,
            'equipment_id' => $reservation->equipment_id,
            'start_time' => $reservation->start_time,
            'end_time'   => $reservation->end_time,
        ];

        return EquipmentSession::create([
            'user_id' => $request['user_id'],
            'equipment_id' => $request['equipment_id'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],

        ]);
    }

    public function storeSessionForStartNow(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        EquipmentSession::create([
            'equipment_id' => $equipment->id,
            'user_id' => auth()->id(),
            'start_time' => now(),
        ]);

        $equipment->update([
            'status' => 'Active',
            'quantity' => $equipment->quantity - 1,
        ]);
        return redirect()->route('Researcher.dashboard')->with('success', 'Session started!');
    }

    public function endSessionForCheckout($id)
    {
        $session = EquipmentSession::findOrFail($id);

        $session->update([
            'id' => $session->id,
            'end_time' => now(),
        ]);

        $equipment = $session->equipment;

        $equipment->update([
            'status' => 'Idle',
            'quantity' => $equipment->quantity + 1,
        ]);

        $durationInHours = $session->start_time->diffInMinutes($session->end_time) / 60;
        $Cost = $durationInHours * $session->equipment->hourly_rate;

        $session->update(['total_cost' => $durationInHours * $session->equipment->hourly_rate]);
        return redirect()->route('Researcher.dashboard')->with('success', 'Session Ended!');
    }
}