<?php

namespace App\Filament\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class FilamentRouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(app_path('Filament/Routes/web.php'));
        });
    }
}
