<?php

use App\Mail\NotifyUserForCertificateExpiration;
use App\Models\Certification;
use App\Models\EquipmentSession;
use App\Models\User;
use App\Services\ConsumableService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    //Check Equipment_Consumables 
    app(ConsumableService::class)->checkStock();

    // Check If user is inactive and session is active !
    $cutoff = now()->subMinutes(15);
    $inactiveUserIds = User::where('is_active', true)->where('updated_at', '<', $cutoff)->pluck('id');

    if ($inactiveUserIds->isNotEmpty()) {
        EquipmentSession::whereIn('user_id', $inactiveUserIds)
            ->whereNull('end_time')
            ->update([
                'end_time' => now(),
            ]);

        User::whereIn('id', $inactiveUserIds)->update(['is_active' => false]);
    }
})->everyMinute();

// check if certificaion is almost expired, then send user an email.
Schedule::call(function () {
    $certifications = Certification::all();

    foreach ($certifications as $cert) {
        if ($cert->almostExpired()) {
            Mail::to($cert->user->email)->send(new NotifyUserForCertificateExpiration($cert));
        }
    }
})->dailyAt('08:00');