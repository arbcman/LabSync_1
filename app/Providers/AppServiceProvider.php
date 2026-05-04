<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Models\AuditTrails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Event::listen('eloquent.saved: *', function ($event, $data) {
            $model = $data[0];

            if ($model instanceof \App\Models\AuditTrails) {
                return;
            }

            AuditTrails::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action'  => ($model->wasRecentlyCreated ? 'Created ' : 'Updated ') . get_class($model) . ' ID: ' . $model->id,
                'user_ip' => request()->ip(),
            ]);
        });

        Event::listen('eloquent.deleted: *', function ($event, $data) {
            $model = $data[0];

            if ($model instanceof \App\Models\AuditTrails) {
                return;
            }

            \App\Models\AuditTrails::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action'  => 'Deleted ' . get_class($model) . ' ID: ' . $model->id,
                'user_ip' => request()->ip(),
            ]);
        });
    }
}