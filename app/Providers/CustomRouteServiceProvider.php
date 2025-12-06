<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CustomRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    // app/Providers/CustomRouteServiceProvider.php (di dalam method boot)
public function boot(): void
{
    Route::middleware('api')
        ->prefix('api')
        ->group(base_path('routes/api.php'));

    // (Opsional) Jika Anda ingin memuat web.php juga:
    Route::middleware('web')
        ->group(base_path('routes/web.php'));
}
}
