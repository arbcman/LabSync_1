<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function StoreorUpdateResearcher(array $data)
    {
        $role = Role::where('name', 'Researcher')->firstOrFail();
        $user = auth()->user();

        $values = [
            'name'  => $data['user_name'],
            'role_id' => $role->id,
            'password' => Hash::make($data['user_pass']),
            'expiry_date' => $data['expiry_date'],
            'academicLevel' => $data['academic_level'],
            'is_active' => true,
            'pis_id' => $user->id,
            'clearance_level' => $data['clearance_level'],
        ];
        if (!empty($data['user_pass'])) {
            $values['password'] = Hash::make($data['user_pass']);
        }
        return User::updateOrCreate(
            ['email' => $data['user_email']],
            $values
        );
    }
}