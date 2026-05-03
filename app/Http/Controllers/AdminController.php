<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function store(Request $request)
    {
        $rules = [
            'user_name'  => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'user_role'  => 'required|exists:roles,name',
            'user_pass'  =>  User::where('email', $request->user_email)->exists()
                ? 'nullable|min:6'
                : 'required|min:6',
            'expiry_date' => 'required|date',
        ];
        
        if ($request->user_role === 'PI')
            $rules['budget_limit'] = 'required|numeric';
        else
            $rules['lab_locations'] = 'required|string';

        $validated = $request->validate($rules);

        $user = $this->adminService->storeOrUpdateUser($validated);
        
        $status = $user->wasRecentlyCreated ? 'created' : 'updated';
        return redirect()->back()->with('success', "User {$user->name} was successfully {{$status}}");
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string|exists:users,user_id',
        ]);

        $id = $request->input('user_id');
        if ($id == Auth::id()) {
            return back()->withErrors([
                'user_id' => 'You cannot delete your own account.'
            ]);
        }

        $this->adminService->deleteUser($request->user_id);
        return "Deleted user with ID: " . $id;
    }
}