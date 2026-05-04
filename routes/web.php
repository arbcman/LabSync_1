<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LabManagerController;
use App\Http\Controllers\PiController;
use App\Livewire\Actions\Logout;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;



Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('dashboards.admin'))->middleware('role:Admin')->name('Admin.dashboard');

    Route::get('/labmanager/dashboard', fn() => view('dashboards.labmanager'))->middleware('role:Lab_Manager')->name('Lab_Manager.dashboard');
    Route::get('/researcher', fn() => view('welcome'))->middleware('role:Researcher')->name('Researcher.dashboard');

    Route::get('/pi/dashboard', fn() => view('dashboards.pi'))->middleware('role:PI')->name('PI.dashboard');

    Route::get('/auditor/dashboard', fn() => view('dashboards.auditor'))->middleware('role:Auditor')->name('Auditor.dashboard');
    Route::post('/logout', [Logout::class, '__invoke'])->name('logout');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::post('/adminAddUser', [AdminController::class, 'store'])->name('admin.users.store');
    Route::delete('/adminDeleteUser', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware(['auth', 'role:PI'])->group(function () {
    Route::post('/piAddResearcher', [PiController::class, 'store'])->name('pi.researcher.store');
});

route::middleware(['auth', 'role:Lab_Manager'])->group(function () {
    Route::post('/LabmStoreEquipment', [LabManagerController::class, 'store'])->name('LabM.equipment.store');
    Route::delete('/LabmDeleteEquipment', [LabManagerController::class, 'destroy'])->name('LabM.equipment.destroy');
});

Route::get('/equipment/{id}', [EquipmentController::class, 'show'])->name('equipment.show');

Route::get('/auditor/dashboard', [AuditorController::class, 'dashboard'])->name('Auditor.dashboard')->middleware(['auth', 'role:Auditor']);