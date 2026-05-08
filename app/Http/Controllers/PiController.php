<?php

namespace App\Http\Controllers;

use App\Models\PiProfile;
use App\Models\Reservation;
use App\Models\User;
use App\Services\PiService;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class PiController extends Controller
{
    protected $piService;
    protected $reservationService;
    public function __construct(PiService $piService, ReservationService $reservationService)
    {
        $this->piService = $piService;
        $this->reservationService = $reservationService;
    }

    public function store(Request $request)
    {
        $rules = [
            'user_name'  => 'required|string',
            'user_email' => 'required|email',
            'user_pass'  => User::where('email', $request->user_email)->exists()
                ? 'nullable|min:6'
                : 'required|min:6',
            'expiry_date' => 'required|date',
            'academic_level'  => 'required|string',
            'clearance_level' => 'required|integer',
        ];
        $validated = $request->validate($rules);

        $user = $this->piService->StoreOrUpdateResearcher($validated);
        $status = $user->wasRecentlyCreated ? 'created' : 'updated';

        return redirect()->back()->with('success', "Researcher {$user->name} was successfully {$status}.");
    }

    public function dashboard()
    {

        $pendingReservations = Reservation::where('status', 'Pending')
            ->with(['user', 'equipment'])   // add these relations to your models (see section 4)
            ->orderBy('created_at', 'asc')  // oldest first — fairest queue order
            ->get();
        return view('dashboards.pi', compact('pendingReservations'));
    }

    public function approve(Reservation $reservation)
    {
        if ($reservation->status !== 'Pending') {
            return redirect()->route('PI.dashboard', ['tab' => 'pending'])
                ->with('error', 'Reservation is no longer pending.');
        }

        $cost = $this->reservationService->calculateCost($reservation);
        if ($this->piService->approve($reservation, $cost)) {
            return redirect()->route('PI.dashboard', ['tab' => 'pending'])->with('success', "Reservation #{$reservation->id} approved.");
        } else {
            return redirect()->route('PI.dashboard', ['tab' => 'pending'])->with('fail', "Approval of Reservation #{$reservation->id} Failed.");
        }
    }


    public function reject(Reservation $reservation)
    {
        if ($reservation->status !== 'Pending') {
            return redirect()->route('PI.dashboard', ['tab' => 'pending'])
                ->with('error', 'Reservation is no longer pending.');
        }

        $this->piService->reject($reservation);

        return redirect()->route('PI.dashboard', ['tab' => 'pending'])->with('success', "Reservation #{$reservation->id} rejected.");
    }
}