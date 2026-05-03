<?php

namespace App\Services;

use App\Models\Equipment;

class LabMService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}


    public function addNewEquipment(array $data)
    {
        $values = [
            'status' => $data['equipment_status'],
            'hourly_rate' => $data['hourly_rate'],
            'required_clearance'     => $data['required_clearance'],
        ];

        return  Equipment::updateOrCreate(['name' => $data['equipment_name']], $values);
    }

    public function deleteEquipment($id)
    {
        return  Equipment::where('id', $id)->firstOrFail()->delete();
    }
}