<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\OrderDelivered;
use App\Listeners\AwardPointsForOrder;

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
        // Suppress PHP 8.5+ deprecation warnings display
        if (function_exists('error_reporting')) {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            @ini_set('display_errors', '0');
        }
        
        // Event listeners are auto-discovered by Laravel's EventServiceProvider
        // No need for manual registration
    }
}
