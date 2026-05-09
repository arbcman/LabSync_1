<?php

namespace App\Http\Controllers;

use App\Models\EquipmentSession;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResearcherController extends Controller
{
    public function dashboard()
    {
        $id = Auth::id();
        $reservations = Reservation::where('user_id', $id)->get();
        $activeSessions = EquipmentSession::where('user_id', $id)->whereNull('end_time')->get();
        return view('dashboards.researcher', compact('reservations', 'activeSessions'));
    }

}