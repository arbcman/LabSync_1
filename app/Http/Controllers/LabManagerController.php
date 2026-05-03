<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LabMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabManagerController extends Controller
{
    protected $labService;

    public function __construct(LabMService $labService)
    {
        $this->labService = $labService;
    }

    public function store(Request $request)
    {
        $rules = [
            'equipment_name'  => 'required|string',
            'equipment_status' => 'required|string',
            'hourly_rate'  => 'required|numeric',
            'required_clearance' => 'required|integer',
        ];
        $validated = $request->validate($rules);

        $equip = $this->labService->addNewEquipment($validated);
        $status = $equip->wasRecentlyCreated ? 'created' : 'updated';

        return redirect()->back()->with('success', "Equipment {$equip->name} was successfully {$status}.");
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|integer|exists:equipment,id',
        ]);
        $id = $request->input('equipment_id');
        $this->labService->deleteEquipment($request->equipment_id);
        return "Deleted Equipment with ID: " . $id;
        
    }
}