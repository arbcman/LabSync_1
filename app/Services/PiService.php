<?php

namespace App\Services;

use App\Http\Controllers\EquipmentSessionController;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Boolean;

class PiService
{

    public function StoreorUpdateResearcher(array $data)
    {
        return DB::transaction(function () use ($data) {


            $role = Role::where('name', 'Researcher')->firstOrFail();

            $userValues = [
                'name'  => $data['user_name'],
                'password' => Hash::make($data['user_pass']),
                'role_id' => $role->id,
                'expiry_date' => $data['expiry_date'],
                'is_active' => true,
                'clearance_level' => $data['clearance_level'],
            ];
            if (!empty($data['user_pass'])) {
                $userValues['password'] = Hash::make($data['user_pass']);
            }

            $user = User::updateOrCreate(['email' => $data['user_email']], $userValues);

            $user->researcherProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'academic_leve' => $data['academic_level'],
                    'pis_id' => Auth::id(),
                ]
            );
            return $user;
        });
    }




    public function notifyPI(Reservation $reservation): void {}

    public function approve(Reservation $reservation, float $cost): bool
    {
        $pi = auth()->user()->piProfile;
        if ($cost > $pi->budget_limit) {
            throw new \Exception("Budget exceeded");
        }
        $grantService = app(GrantService::class);
        $sessionController = app(EquipmentSessionController::class);
        if ($grantService->checkBalance($cost)) {
            $eqpSession = $sessionController->storeSessionForReservation($reservation);
            $reservation->update(['status' => 'Approved']);
            $transaction = app(TransactionService::class);
            $transaction->makeNew($eqpSession, $cost);
            return true;
        }
        else{
            return false;
        }
    }

    public function reject(Reservation $reservation): void
    {
        $reservation->update(['status' => 'Rejected']);
        $equipment = Equipment::findOrFail($reservation->equipment_id);
        $quantity = $equipment->quantity + $reservation->quantity;
        $equipment->update([
            'quantity' => $quantity,
        ]);
    }
}