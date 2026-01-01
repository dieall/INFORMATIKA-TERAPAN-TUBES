<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\HealthData;
use App\Observers\HealthDataObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observers
        HealthData::observe(HealthDataObserver::class);
    }
}
