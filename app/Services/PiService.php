<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Equipment;
use App\Models\PiProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function approve(Reservation $reservation, float $cost): void
    {
        $pi = auth()->user()->piProfile;

        if ($cost > $pi->budget_limit) {
            throw new \Exception("Budget exceeded");
        }
        $budgetNew = $pi->budget_limit - $cost;
        $pi->update(['budget_limit' => $budgetNew]);

        $reservation->update(['status' => 'Approved']);
    }

    public function reject(Reservation $reservation): void
    {
        $reservation->update(['status' => 'Rejected']);
    }
}