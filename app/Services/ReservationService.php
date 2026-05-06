<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;

class ReservationService
{


    public function makeReservation(array $data): Reservation
    {

        $reservation = Reservation::create([
            'user_id'      => $data['user_id'],
            'equipment_id' => $data['equipment_id'],
            'start_time'   => $data['start_time'],
            'end_time'     => $data['end_time'],
            'status'       => 'Pending',
        ]);

        return $reservation;
    }


    public function calculateCost(Reservation $reservation): float
    {
        $start = Carbon::parse($reservation->start_time);
        $end = Carbon::parse($reservation->end_time);

        $hours = $start->diffInMinutes($end) / 60;

        return $hours * $reservation->equipment->hourly_rate;
    }
}