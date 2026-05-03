<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}


    public function storeOrUpdateUser(array $data)
    {
        $role = Role::where('name', $data['user_role'])->firstOrFail();

        $values = [
            'name'       => $data['user_name'],
            'role_id'    => $role->id,
            'password' => Hash::make($data['user_pass']),
            'budget_limit' => $data['budget_limit'] ?? null,
            'managed_Lab_Locations' => $data['lab_locations'] ?? null,
            'is_active'     => true,
            'expiry_date'   => $data['expiry_date'],
        ];

        if (!empty($data['user_pass'])) {
            $values['password'] = Hash::make($data['user_pass']);
        }

        return  User::updateOrCreate(['email' => $data['user_email']], $values);
    }

    public function deleteUser($id)
    {
        return  User::where('user_id', $id)->firstOrFail()->delete();
    }
}