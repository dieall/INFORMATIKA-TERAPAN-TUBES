<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\HealthData;
use App\Observers\HealthDataObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Listeners\LogLogin;
use App\Listeners\LogLogout;
use App\Listeners\LogFailedLogin;

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

        // Register Event Listeners
        $this->app['events']->listen(Login::class, LogLogin::class);
        $this->app['events']->listen(Logout::class, LogLogout::class);
        $this->app['events']->listen(Failed::class, LogFailedLogin::class);
    }
}
