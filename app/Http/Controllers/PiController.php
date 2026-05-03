<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PiService;
use Illuminate\Http\Request;

class PiController extends Controller
{
    protected $piService;

    public function __construct(PiService $piService)
    {
        $this->piService = $piService;
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
}