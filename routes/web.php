<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EquipmentController;
use App\Livewire\Actions\Logout;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('dashboards.admin'))->middleware('role:Admin')->name('admin.dashboard');

    Route::get('/labmanager/dashboard', fn() => view('dashboards.labmanager'))->middleware('role:Lab_Manager')->name('labmanager.dashboard');
    Route::get('/researcher/dashboard', fn() => view('dashboards.researcher'))->middleware('role:Researcher')->name('researcher.dashboard');

    Route::get('/pi/dashboard', fn() => view('dashboards.pi'))->middleware('role:PI')->name('pi.dashboard');

    Route::get('/auditor/dashboard', fn() => view('dashboards.auditor'))->middleware('role:Auditor')->name('auditor.dashboard');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::post('/adminAddUser', [AdminController::class, 'store'])->name('admin.users.store');
    Route::delete('/adminDeleteUser', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/logout', [Logout::class, '__invoke'])->name('logout');
});

Route::middleware(['auth', 'role:PI']);